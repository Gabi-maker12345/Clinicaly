<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Support\Facades\Auth;
use App\Models\Diagnostico;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscoveryController extends Controller
{

    public function index(){
        return view('welcome');
    }
    public function diagnostico() 
    {
        return view('diagnostico', [
            'symptoms'   => \App\Models\Symptom::orderBy('name')->get(),
            'diseases'   => \App\Models\Disease::with('categories')->get(),
            'categories' => \App\Models\Category::withCount('diseases')->get(),
            'patients'   => \App\Models\User::where('role', 'pacient')->orderBy('name')->get(),
        ]);
    }
    
    public function process(Request $request)
    {
        // =========================================================
        // 1. COLETA E VALIDAÇÃO DE DADOS
        // =========================================================
        $perfil = [
            'genero' => $request->input('gender'),
            'idade'  => (int) $request->input('age'),
            'peso'   => (float) $request->input('weight'),
            'altura' => (float) $request->input('height'),
        ];

        // Get symptom IDs from either the hidden JSON field or the items array
        $symptomIds = [];
        
        // Try JSON field first (more reliable)
        if ($request->has('symptom_ids_json')) {
            $json = $request->input('symptom_ids_json', '[]');
            $symptomIds = json_decode($json, true) ?? [];
        }
        
        // Fallback to items array if JSON is empty
        if (empty($symptomIds)) {
            $symptomIds = $request->input('items', []);
        }
        
        // Validate symptom IDs are present
        if (empty($symptomIds) || count($symptomIds) === 0) {
            return back()->with('error', 'Selecione ao menos um sintoma.');
        }
        
        // Ensure all symptom IDs are integers
        $symptomIds = array_map('intval', $symptomIds);
        $symptomIds = array_filter($symptomIds);
        
        if (count($symptomIds) === 0) {
            return back()->with('error', 'Selecione ao menos um sintoma.');
        }
        
        // Get patient ID: use form value if filled, otherwise use Auth::id()
        $pacienteId = null;
        $patientIdFromForm = $request->input('id_paciente');
        
        if (!empty($patientIdFromForm) && $patientIdFromForm !== '') {
            $pacienteId = (int) $patientIdFromForm;
        } else {
            $pacienteId = Auth::id();
        }
        
        if (empty($pacienteId)) {
            return back()->with('error', 'Nenhum paciente identificado. Por favor, selecione um paciente ou faça login.');
        }

        // =========================================================
        // 2. CÁLCULO DE IMC E CLASSIFICAÇÃO CLÍNICA
        // IMC é um preditor independente para diversas patologias.
        // Usamos a classificação da OMS para mapear fatores de risco.
        // =========================================================
        $imc = $this->calcularIMC($perfil['peso'], $perfil['altura']);
        $perfil['imc']              = $imc;
        $perfil['imc_classificacao'] = $this->classificarIMC($imc);

        // =========================================================
        // 3. DETECÇÃO DE FLAGS DE ALTO RISCO (TRIAGEM CRÍTICA)
        // Antes de qualquer cálculo, verificamos combinações de
        // sintomas que configuram emergências médicas conhecidas.
        // Isso garante que casos graves nunca sejam subavaliados.
        // =========================================================
        $alertasCriticos = $this->detectarAlertas($symptomIds);

        // =========================================================
        // 4. ALGORITMO DE PROBABILIDADE APRIMORADO
        // Carregamos sintomas com pivot (peso/severidade por doença).
        // =========================================================
        $results = Disease::with(['symptoms', 'categories'])
            ->whereHas('symptoms', function ($q) use ($symptomIds) {
                $q->whereIn('symptoms.id', $symptomIds);
            })
            ->get()
            ->map(function ($disease) use ($symptomIds, $perfil) {

                // --- 4a. SCORE BASE PONDERADO ---
                // Em vez de contagem simples, usamos o peso clínico
                // de cada sintoma na pivot (campo 'weight', escala 1–10).
                // Sintomas mais específicos (peso alto) contribuem mais.
                $pesoTotalDoenca   = $disease->symptoms->sum('pivot.weight') ?: 1;
                $pesoCoincidente   = $disease->symptoms
                    ->whereIn('id', $symptomIds)
                    ->sum('pivot.weight');

                $scorePonderado = $pesoCoincidente / $pesoTotalDoenca;

                // --- 4b. BÔNUS DE COBERTURA ---
                // Apresentar MUITOS sintomas da mesma doença aumenta
                // a probabilidade além do peso individual.
                // Fórmula: bônus proporcional à % de sintomas cobertos.
                $totalSintomasDoenca = $disease->symptoms->count();
                $sintomasCoincidentes = $disease->symptoms
                    ->whereIn('id', $symptomIds)
                    ->count();

                $cobertura = $totalSintomasDoenca > 0
                    ? $sintomasCoincidentes / $totalSintomasDoenca
                    : 0;

                // Bônus de cobertura: até +15% quando cobre >80% dos sintomas
                $bonusCobertura = $cobertura >= 0.8 ? 0.15
                    : ($cobertura >= 0.5 ? 0.08
                    : ($cobertura >= 0.3 ? 0.03 : 0));

                // --- 4c. PENALIZAÇÃO POR SINTOMAS NEGATIVOS ---
                // Se o paciente NÃO apresenta sintomas que são muito
                // esperados para esta doença (peso >= 8), reduzimos
                // o score. Isso evita falso positivo por sobreposição
                // de sintomas inespecíficos (ex: cefaleia e febre).
                $sintomasEsperadosAusentes = $disease->symptoms
                    ->whereNotIn('id', $symptomIds)
                    ->where('pivot.weight', '>=', 8)
                    ->count();

                $penalizacaoNegativa = min($sintomasEsperadosAusentes * 0.05, 0.25);

                // Score base final (0.0 a 1.0)
                $scoreBase = max(0, ($scorePonderado + $bonusCobertura) - $penalizacaoNegativa);

                // --- 4d. MODIFICADOR DE GÊNERO (multiplicativo) ---
                // Multiplicativo em vez de aditivo: evita que a bonificação
                // de idade "ressuscite" uma doença incompatível por gênero.
                $modGenero = 1.0;
                if (!empty($disease->target_gender) && $disease->target_gender !== 'both') {
                    if ($disease->target_gender !== $perfil['genero']) {
                        // Doenças exclusivas do outro gênero (ex: câncer de próstata
                        // em mulher): redução drástica mas não zero —
                        // anomalias anatômicas existem.
                        $modGenero = 0.05;
                    } else {
                        // Preferência de gênero confirmada: leve bônus
                        $modGenero = 1.2;
                    }
                }

                // --- 4e. MODIFICADOR DE FAIXA ETÁRIA (multiplicativo) ---
                // Usa gradiente suavizado: quanto mais longe da faixa
                // típica, maior a penalização — em vez de binário (dentro/fora).
                $modIdade = $this->calcularModificadorIdade(
                    $perfil['idade'],
                    $disease->min_age,
                    $disease->max_age
                );

                // --- 4f. MODIFICADOR DE IMC (multiplicativo) ---
                // Mapeado por categoria da doença (ex: doenças metabólicas
                // têm risco aumentado com obesidade; doenças por má-nutrição
                // aumentam com baixo peso).
                $modImc = $this->calcularModificadorImc(
                    $perfil['imc_classificacao'],
                    $disease->categories->pluck('name')->toArray(),
                    $disease->severity
                );

                // --- 4g. MODIFICADOR DE SEVERIDADE ---
                // Doenças graves (severity: critical/high) recebem leve
                // bônus na triagem — preferimos falso positivo a falso
                // negativo em condições sérias.
                $modSeveridade = match($disease->severity ?? 'low') {
                    'critical' => 1.25,
                    'high'     => 1.10,
                    'medium'   => 1.00,
                    default    => 0.95,
                };

                // --- 4h. SCORE FINAL ---
                $scoreFinal = $scoreBase
                    * $modGenero
                    * $modIdade
                    * $modImc
                    * $modSeveridade;

                // Normaliza para 0–100 com cap em 99
                // (100% de certeza não existe na medicina)
                $probabilidade = min(round($scoreFinal * 100), 99);

                // Expõe metadados para ordenação e exibição
                $disease->match_count        = $sintomasCoincidentes;
                $disease->peso_coincidente   = $pesoCoincidente;
                $disease->cobertura          = round($cobertura * 100);
                $disease->probability        = $probabilidade;
                $disease->mod_genero         = $modGenero;
                $disease->mod_idade          = $modIdade;
                $disease->mod_imc            = $modImc;

                return $disease;
            })
            // Threshold clínico mínimo: 5% — abaixo disso é ruído
            ->filter(fn($d) => $d->probability >= 5)
            // Ordenação: peso_coincidente (especificidade) > probability > cobertura
            ->sortByDesc(fn($d) => [
                $d->peso_coincidente,
                $d->probability,
                $d->cobertura,
            ])
            ->values();

        // =========================================================
        // 5. PERSISTÊNCIA E INTEGRAÇÃO COM IA GEMINI
        // =========================================================
        $diagnostico = null;

        if ($results->isNotEmpty() && $pacienteId) {

            $top4 = $results->take(4)->map(fn($d) => [
                'id'           => $d->id,
                'nome'         => $d->name,
                'icd_code'     => $d->icd_code,
                'probabilidade'=> $d->probability,
                'cobertura'    => $d->cobertura,
                'severidade'   => $d->severity,
            ])->toArray();

            $nomesSintomas = Symptom::whereIn('id', $symptomIds)
                ->pluck('name')
                ->toArray();

            $linksIA = $this->gerarLinksViaGemini($top4[0]['nome'], $nomesSintomas);

            try {
                $diagnostico = Diagnostico::create([
                    'id_medico'         => (Auth::user()?->role === 'doctor') ? Auth::id() : null,
                    'id_paciente'       => $pacienteId,
                    'doencas_sugeridas' => $top4,
                    'id_sintomas'       => $symptomIds,
                    'links_referencia'  => $linksIA,
                    'dados_biometricos' => $perfil,
                    'alertas_criticos'  => $alertasCriticos,
                    'status'            => 'pendente',
                ]);
            } catch (\Exception $e) {
                \Log::error('Erro ao criar Diagnostico', [
                    'error' => $e->getMessage(),
                    'pacienteId' => $pacienteId,
                    'symptomIds' => $symptomIds,
                ]);
                return back()->with('error', 'Erro ao salvar o diagnóstico: ' . $e->getMessage());
            }
        }

        try {
            return view('results', [
                'results'          => $results,
                'perfil'           => $perfil,
                'alertas_criticos' => $alertasCriticos,
                'diagnostico'      => $diagnostico,
                'age'              => $perfil['idade'],
                'gender'           => $perfil['genero'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao renderizar view results', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }


    // =========================================================
    // MÉTODOS AUXILIARES
    // =========================================================

    /**
     * Calcula o IMC.
     * altura esperada em metros.
     */
    private function calcularIMC(float $peso, float $altura): float
    {
        if ($altura <= 0) return 0;
        // Aceita altura em cm ou metros
        $alturaM = $altura > 10 ? $altura / 100 : $altura;
        return round($peso / ($alturaM ** 2), 2);
    }

    /**
     * Classificação OMS de IMC.
     */
    private function classificarIMC(float $imc): string
    {
        return match(true) {
            $imc < 16.0 => 'magreza_grave',
            $imc < 17.0 => 'magreza_moderada',
            $imc < 18.5 => 'magreza_leve',
            $imc < 25.0 => 'normal',
            $imc < 30.0 => 'sobrepeso',
            $imc < 35.0 => 'obesidade_1',
            $imc < 40.0 => 'obesidade_2',
            default     => 'obesidade_3',
        };
    }

    /**
     * Modificador de idade com gradiente suavizado.
     *
     * Em vez de binário (dentro/fora), aplica:
     * - Dentro da faixa típica: ×1.0 a ×1.3 (bônus no pico epidemiológico)
     * - Fora da faixa: penalização proporcional à distância
     *
     * Isso reflete o fato de que doenças não "desaparecem" fora da faixa
     * típica — apenas se tornam menos prováveis.
     */
    private function calcularModificadorIdade(int $idade, ?int $minAge, ?int $maxAge): float
    {
        // Sem restrição definida: neutro
        if (is_null($minAge) || is_null($maxAge)) return 1.0;

        if ($idade >= $minAge && $idade <= $maxAge) {
            // Bônus no "pico" epidemiológico (centro da faixa)
            $centro = ($minAge + $maxAge) / 2;
            $amplitude = max(($maxAge - $minAge) / 2, 1);
            $distanciaDoCentro = abs($idade - $centro) / $amplitude;

            // Quanto mais próximo do centro, maior o bônus (até ×1.3)
            return 1.0 + (0.3 * (1 - $distanciaDoCentro));
        }

        // Fora da faixa: penalização suavizada por distância
        $distancia = $idade < $minAge
            ? ($minAge - $idade)
            : ($idade - $maxAge);

        // Decaimento: cada 5 anos fora da faixa reduz 20%, mínimo ×0.1
        $fator = max(0.1, 1.0 - ($distancia / 5) * 0.2);

        return $fator;
    }

    /**
     * Modificador de IMC baseado na categoria da doença.
     *
     * Mapeamento clínico:
     * - Doenças metabólicas/cardiovasculares/respiratórias:
     *   risco aumenta com obesidade.
     * - Doenças por desnutrição/hematológicas/imunológicas:
     *   risco aumenta com baixo peso.
     * - Doenças críticas: IMC extremo sempre aumenta risco.
     */
    private function calcularModificadorImc(
        string $imcClass,
        array $categorias,
        ?string $severidade
    ): float {
        $catNorm = array_map('strtolower', $categorias);

        $ehMetabolica     = $this->categoriaContem($catNorm, ['metabolic','cardiovascular','endocrin','diabetes','hypertension']);
        $ehRespiratoria   = $this->categoriaContem($catNorm, ['respiratory','pulmonary','sleep']);
        $ehHematologica   = $this->categoriaContem($catNorm, ['hematolog','anemia','nutritional','deficiency']);
        $ehImunologica    = $this->categoriaContem($catNorm, ['immunolog','autoimmune','infectious']);
        $ehOrtopedica     = $this->categoriaContem($catNorm, ['orthopedic','musculoskeletal','joint','arthr']);

        return match($imcClass) {
            'obesidade_3' => match(true) {
                $ehMetabolica, $ehRespiratoria, $ehOrtopedica => 1.5,
                $severidade === 'critical'                    => 1.4,
                default                                       => 1.2,
            },
            'obesidade_2' => match(true) {
                $ehMetabolica, $ehRespiratoria                => 1.35,
                $ehOrtopedica                                 => 1.3,
                default                                       => 1.1,
            },
            'obesidade_1' => match(true) {
                $ehMetabolica                                 => 1.2,
                default                                       => 1.05,
            },
            'sobrepeso' => match(true) {
                $ehMetabolica                                 => 1.1,
                default                                       => 1.0,
            },
            'normal'    => 1.0,
            'magreza_leve' => match(true) {
                $ehHematologica, $ehImunologica               => 1.15,
                default                                       => 0.95,
            },
            'magreza_moderada' => match(true) {
                $ehHematologica, $ehImunologica               => 1.35,
                default                                       => 0.9,
            },
            'magreza_grave' => match(true) {
                $ehHematologica, $ehImunologica               => 1.5,
                $severidade === 'critical'                    => 1.4,
                default                                       => 0.85,
            },
            default => 1.0,
        };
    }

    /**
     * Verifica se alguma categoria da doença contém
     * uma das palavras-chave clínicas.
     */
    private function categoriaContem(array $categorias, array $palavras): bool
    {
        foreach ($categorias as $cat) {
            foreach ($palavras as $palavra) {
                if (str_contains($cat, $palavra)) return true;
            }
        }
        return false;
    }

    /**
     * Detecção de alertas críticos.
     *
     * Conjuntos de sintomas que, em combinação, configuram
     * emergências médicas que precisam de triagem imediata.
     * IDs dos sintomas devem ser mapeados conforme seu banco.
     *
     * IMPORTANTE: substitua os IDs pelos reais do seu banco.
     */
    private function detectarAlertas(array $symptomIds): array
    {
        $alertas = [];

        // Mapa: [nome_alerta => [sintomas_necessarios, threshold_minimo]]
        // threshold = quantos sintomas do grupo devem estar presentes
        $protocolos = [
            'possivel_iam' => [
                'descricao' => 'Possível Infarto Agudo do Miocárdio — avaliação imediata',
                'cor'       => 'red',
                'sintomas'  => [1, 2, 3, 4, 5],  // ex: dor_peito, irradiacao_braco, sudorese, nausea, dispneia
                'threshold' => 3,
            ],
            'possivel_avc' => [
                'descricao' => 'Possível AVC — protocolo FAST',
                'cor'       => 'red',
                'sintomas'  => [6, 7, 8, 9],      // ex: fraqueza_facial, confusao, alteracao_fala, cefaleia_subita
                'threshold' => 2,
            ],
            'possivel_sepse' => [
                'descricao' => 'Possível Sepse — critérios SIRS',
                'cor'       => 'orange',
                'sintomas'  => [10, 11, 12, 13],  // ex: febre_alta, taquicardia, taquipneia, confusao
                'threshold' => 3,
            ],
            'possivel_embolia' => [
                'descricao' => 'Possível Embolia Pulmonar — score Wells',
                'cor'       => 'orange',
                'sintomas'  => [14, 15, 16],      // ex: dispneia_subita, dor_toracica_pleuritica, hemoptise
                'threshold' => 2,
            ],
        ];

        foreach ($protocolos as $chave => $protocolo) {
            $coincidencias = count(array_intersect($symptomIds, $protocolo['sintomas']));
            if ($coincidencias >= $protocolo['threshold']) {
                $alertas[] = [
                    'codigo'    => $chave,
                    'descricao' => $protocolo['descricao'],
                    'cor'       => $protocolo['cor'],
                ];
            }
        }

        return $alertas;
    }

    /**
     * Lógica para consultar o Gemini e obter links reais
     */
    private function gerarLinksViaGemini($doenca, $sintomas)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        $sintomasStr = implode(', ', $sintomas);
        
        // Prompt estruturado para a IA retornar apenas o JSON que precisamos
        $prompt = "Atue como um assistente médico. O paciente apresenta os sintomas: {$sintomasStr}. 
                   O diagnóstico provável é {$doenca}. Forneça exatamente 3 links de fontes médicas confiáveis 
                   (como PubMed, Scielo ou OMS) que falem sobre este diagnóstico. 
                   Responda APENAS no formato JSON seguindo este exemplo:
                   [{\"fonte\": \"Nome da Fonte\", \"url\": \"link\", \"resumo\": \"descrição curta\"}]";

        try {
            $response = Http::withoutVerifying()->post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                
                // Limpeza básica caso a IA coloque blocos de código ```json
                $json = preg_replace('/^```json\s+|```$/', '', trim($text));
                return json_decode($json, true) ?? [];
            }
        } catch (\Exception $e) {
            return []; // Retorna vazio em caso de falha na API
        }

        return [];
    }
}
    