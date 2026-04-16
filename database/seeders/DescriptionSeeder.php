<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // A tabela descriptions usa morphs('describable'):
        //   describable_type = FQCN do Model  (ex: 'App\Models\Disease')
        //   describable_id   = PK do registo
        //   type             = 'technical' | 'patient' | 'general'
        //
        // Cada doença recebe duas descrições (técnica + paciente).
        // Cada sintoma e medicamento recebe uma descrição técnica.

        // ─── DOENÇAS ────────────────────────────────────────────────────────
        $diseaseDescriptions = [
            // 1 – Tuberculose Pulmonar
            1 => [
                ['type' => 'technical', 'content' => 'Doença infecciosa causada pelo Mycobacterium tuberculosis. Afeta predominantemente o parênquima pulmonar, com formação de granulomas caseosos, podendo disseminar-se para outros órgãos (tuberculose extrapulmonar). Diagnóstico por baciloscopia, cultura e teste de sensibilidade.'],
                ['type' => 'patient',   'content' => 'A tuberculose é uma doença pulmonar causada por uma bactéria. Tratável com antibióticos tomados durante pelo menos 6 meses. É importante não interromper o tratamento mesmo quando os sintomas melhoram.'],
            ],
            // 2 – Malária por P. falciparum
            2 => [
                ['type' => 'technical', 'content' => 'Doença parasitária causada pelo Plasmodium falciparum, transmitida pelo mosquito Anopheles fêmea. É a forma mais grave de malária, com risco de malária cerebral, anemia hemolítica grave e falência de múltiplos órgãos.'],
                ['type' => 'patient',   'content' => 'A malária é transmitida pela picada de mosquito. Causa febre alta, calafrios e dores. O tratamento com medicamentos antipalúdicos é eficaz quando iniciado precocemente.'],
            ],
            // 3 – Dengue Hemorrágica
            3 => [
                ['type' => 'technical', 'content' => 'Manifestação grave da infecção pelo vírus Dengue (DENV 1-4), caracterizada por trombocitopenia, aumento da permeabilidade vascular e fenómenos hemorrágicos. Monitorização rigorosa do hematócrito e plaquetas é essencial.'],
                ['type' => 'patient',   'content' => 'A dengue grave pode causar sangramentos e queda brusca da pressão. Requer internação hospitalar. Beber muito líquido, repouso e evitar AAS são medidas fundamentais.'],
            ],
            // 4 – HIV/SIDA
            4 => [
                ['type' => 'technical', 'content' => 'Infecção pelo vírus HIV que compromete progressivamente os linfócitos T CD4+, levando à imunodeficiência. A contagem de CD4 < 200 células/mm³ define SIDA. A terapia antirretroviral (TARV) suprime a carga viral e reconstitui a imunidade.'],
                ['type' => 'patient',   'content' => 'O HIV ataca o sistema de defesa do organismo. Com o tratamento antirretroviral tomado corretamente todos os dias, é possível ter uma vida longa e saudável e reduzir a transmissão a zero.'],
            ],
            // 5 – Hepatite B Crónica
            5 => [
                ['type' => 'technical', 'content' => 'Infecção persistente pelo vírus HBV (> 6 meses), com risco de cirrose e carcinoma hepatocelular. Monitorização regular de ALT, HBeAg/Anti-HBe, carga viral e ecografia hepática é mandatória.'],
                ['type' => 'patient',   'content' => 'A hepatite B crónica afeta o fígado ao longo do tempo. Medicamentos antivirais controlam o vírus e reduzem o risco de danos hepáticos graves. Consultas regulares ao médico são essenciais.'],
            ],
            // 6 – Meningite Bacteriana
            6 => [
                ['type' => 'technical', 'content' => 'Inflamação das meninges por agentes bacterianos (S. pneumoniae, N. meningitidis, L. monocytogenes). Emergência médica com mortalidade elevada sem tratamento imediato. Punção lombar, hemoculturas e antibioterapia empírica devem ser iniciados sem demora.'],
                ['type' => 'patient',   'content' => 'A meningite bacteriana é uma emergência gravíssima. Febre intensa, dor de cabeça muito forte e rigidez no pescoço são sinais de alerta. Procure urgência imediatamente.'],
            ],
            // 7 – Asma Brônquica
            7 => [
                ['type' => 'technical', 'content' => 'Doença inflamatória crónica das vias aéreas com obstrução reversível do fluxo aéreo, hiper-reactividade brônquica e remodelação. Classificada por nível de controlo (GINA). Tratamento inclui corticoide inalado e broncodilatador de curta duração.'],
                ['type' => 'patient',   'content' => 'A asma causa episódios de falta de ar, tosse e chiado no peito. Com os medicamentos certos e evitando os gatilhos (pó, fumo, animais), é possível controlar bem a doença e ter uma vida normal.'],
            ],
            // 8 – Pneumonia Bacteriana
            8 => [
                ['type' => 'technical', 'content' => 'Infecção do parênquima pulmonar por agentes bacterianos, predominantemente Streptococcus pneumoniae. Classificada como adquirida na comunidade (PAC) ou hospitalar (PAH). Escore de gravidade PSI/CURB-65 orienta decisão de internamento.'],
                ['type' => 'patient',   'content' => 'A pneumonia é uma infecção pulmonar que causa febre, tosse com secreção e dificuldade para respirar. O tratamento com antibióticos é eficaz. Repouso, hidratação e seguir o esquema de antibióticos completo são fundamentais.'],
            ],
            // 9 – DPOC
            9 => [
                ['type' => 'technical', 'content' => 'Doença crónica e progressiva caracterizada por limitação persistente do fluxo aéreo (VEF1/CVF < 0,70 pós-broncodilatador). Classificação GOLD orienta tratamento. Principal fator de risco: tabagismo.'],
                ['type' => 'patient',   'content' => 'A DPOC dificulta a respiração de forma permanente e piora com o tempo. Parar de fumar é a medida mais eficaz. Os medicamentos inalatórios ajudam a abrir os brônquios e a reduzir os ataques de falta de ar.'],
            ],
            // 10 – Hipertensão
            10 => [
                ['type' => 'technical', 'content' => 'Elevação persistente da pressão arterial ≥ 140/90 mmHg. Principal fator de risco modificável para doenças cardiovasculares, AVC e doença renal crónica. Tratamento combina mudanças de estilo de vida e fármacos anti-hipertensivos.'],
                ['type' => 'patient',   'content' => 'A pressão alta geralmente não dá sintomas, mas danifica o coração e os vasos com o tempo. Reduzir o sal, praticar exercício, manter o peso saudável e tomar a medicação diariamente são essenciais.'],
            ],
            // 11 – IAM
            11 => [
                ['type' => 'technical', 'content' => 'Necrose do miocárdio por oclusão aguda de artéria coronária, geralmente por ruptura de placa aterosclerótica. Classificado em IAMCSST e IAMSSST por ECG. Reperfusão urgente (angioplastia ou trombolítico) é o pilar do tratamento.'],
                ['type' => 'patient',   'content' => 'O infarte é uma emergência. Dor forte no peito que irradia para o braço esquerdo, suor frio e falta de ar são sinais de alarme. Ligue imediatamente para o SAMU/112 – cada minuto conta.'],
            ],
            // 12 – AVC Isquémico
            12 => [
                ['type' => 'technical', 'content' => 'Oclusão de artéria cerebral com isquemia focal. A janela terapêutica para trombólise intravenosa com alteplase é de 4,5 h do início dos sintomas. NIHSS quantifica défice. Prevenção secundária com antiplaquetários ou anticoagulantes.'],
                ['type' => 'patient',   'content' => 'O AVC bloqueia o fluxo de sangue ao cérebro. Reconheça os sintomas com o acrónimo SAMU: Sorriso torto, Abraço assimétrico (fraqueza num braço), Mudança na fala. Ligue 112 imediatamente.'],
            ],
            // 13 – ICC
            13 => [
                ['type' => 'technical', 'content' => 'Síndrome caracterizada pela incapacidade do coração de fornecer débito cardíaco adequado às necessidades metabólicas. Classificada pela fração de ejeção (FEIr, FEmr, FEp). Tratamento inclui IECA/ARA, betabloqueador, diurético e SGLT2i.'],
                ['type' => 'patient',   'content' => 'A insuficiência cardíaca causa cansaço, falta de ar e inchaço nas pernas. Tomar os medicamentos diariamente, pesar-se todas as manhãs e restringir o sal e líquidos ajuda a evitar internamentos.'],
            ],
            // 14 – Parkinson
            14 => [
                ['type' => 'technical', 'content' => 'Doença neurodegenerativa com perda de neurónios dopaminérgicos na substância negra e acúmulo de alfa-sinucleína (corpos de Lewy). Tríade clínica: tremor em repouso, rigidez e bradicinesia. Levodopa é o gold standard terapêutico.'],
                ['type' => 'patient',   'content' => 'O Parkinson causa tremores, lentidão dos movimentos e rigidez. Não tem cura mas os medicamentos melhoram muito a qualidade de vida. Fisioterapia e exercício físico regular são igualmente importantes.'],
            ],
            // 15 – Alzheimer
            15 => [
                ['type' => 'technical', 'content' => 'Demência progressiva com acúmulo de placas de amilóide-β e emaranhados neurofibrilares de proteína tau. Diagnóstico clínico apoiado por neuroimagem e biomarcadores no LCR/PET. Inibidores da acetilcolinesterase têm efeito sintomático modesto.'],
                ['type' => 'patient',   'content' => 'O Alzheimer afeta a memória e a capacidade de realizar tarefas do dia a dia. Os medicamentos podem atrasar a progressão. Rotinas estáveis, estimulação cognitiva e apoio familiar são fundamentais.'],
            ],
            // 16 – Epilepsia
            16 => [
                ['type' => 'technical', 'content' => 'Distúrbio neurológico crónico caracterizado por crises epiléticas recorrentes não provocadas. Classificação ILAE 2017 considera tipo de crise, tipo de epilepsia e síndrome epiléptica. Objetivo terapêutico: ausência de crises sem efeitos adversos.'],
                ['type' => 'patient',   'content' => 'A epilepsia causa crises que podem ser convulsivas ou não. Tomar a medicação rigorosamente, dormir bem, evitar álcool e informar pessoas próximas sobre primeiros socorros em caso de crise são medidas essenciais.'],
            ],
            // 17 – DM1
            17 => [
                ['type' => 'technical', 'content' => 'Doença autoimune com destruição das células beta pancreáticas, resultando em deficiência absoluta de insulina. Requer insulinoterapia intensiva (basal-bolus). Monitorização contínua da glicose (CGM) reduz HbA1c e hipoglicemias.'],
                ['type' => 'patient',   'content' => 'No diabetes tipo 1 o pâncreas não produz insulina. É necessário aplicar insulina diariamente e monitorizar a glicose frequentemente. Com disciplina e educação diabetológica é possível ter uma vida plena.'],
            ],
            // 18 – DM2
            18 => [
                ['type' => 'technical', 'content' => 'Distúrbio metabólico crónico com resistência periférica à insulina e deficiência secretória progressiva. HbA1c orienta alvo terapêutico (< 7% na maioria). Metformina é o agente de primeira linha; SGLT2i e GLP-1 têm benefício cardiovascular comprovado.'],
                ['type' => 'patient',   'content' => 'O diabetes tipo 2 pode ser controlado com alimentação saudável, exercício e medicação. Controlar o açúcar no sangue regularmente ajuda a evitar complicações nos olhos, rins, nervos e coração.'],
            ],
            // 19 – Hipotiroidismo
            19 => [
                ['type' => 'technical', 'content' => 'Deficiência de hormônios tireóideos (T3/T4) com TSH elevado. Causa mais comum: tireoidite de Hashimoto. Tratamento com levotiroxina ajustado pela TSH alvo (0,5–2,5 mUI/L na maioria dos doentes).'],
                ['type' => 'patient',   'content' => 'O hipotiroidismo é a lentidão da glândula tiroide. Provoca cansaço, ganho de peso e intolerância ao frio. Um comprimido de levotiroxina em jejum todas as manhãs normaliza os hormônios e melhora os sintomas.'],
            ],
            // 20 – Artrite Reumatoide
            20 => [
                ['type' => 'technical', 'content' => 'Doença autoimune sistémica crónica com sinovite erosiva bilateral e simétrica. Fator reumatoide e anti-CCP confirmam diagnóstico. Alvo terapêutico: remissão ou baixa atividade (DAS28 < 2,6). DMARDs convencionais (metotrexato) e biológicos (anti-TNF).'],
                ['type' => 'patient',   'content' => 'A artrite reumatoide inflama as articulações causando dor, inchaço e rigidez, especialmente de manhã. O tratamento precoce evita deformações. Fisioterapia e exercício de baixo impacto são excelentes complementos à medicação.'],
            ],
        ];

        foreach ($diseaseDescriptions as $diseaseId => $descs) {
            foreach ($descs as $desc) {
                DB::table('descriptions')->insert([
                    'content'          => $desc['content'],
                    'type'             => $desc['type'],
                    'describable_id'   => $diseaseId,
                    'describable_type' => 'App\\Models\\Disease',
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);
            }
        }

        // ─── SINTOMAS (descrição técnica de cada sintoma) ───────────────────
        $symptomDescriptions = [
            1  => 'Elevação da temperatura corporal acima de 37,8 °C, resultado da resposta inflamatória do hipotálamo a pirógenos endógenos e exógenos.',
            2  => 'Sensação subjetiva de esgotamento físico ou mental desproporcional ao esforço realizado, com impacto funcional significativo.',
            8  => 'Dor ou pressão na região cefálica, podendo ser tensional, migrânea ou secundária a patologia intracraniana.',
            11 => 'Sensação subjetiva de falta de ar ou esforço respiratório aumentado; classificada por escala de MRC ou mMRC.',
            13 => 'Dor ou desconforto na região torácica; exige exclusão de causa cardíaca ou pleuropulmonar grave.',
            18 => 'Dor precordial com irradiação típica para mandíbula, ombro ou membro superior esquerdo, fortemente sugestiva de isquemia miocárdica.',
            21 => 'Movimento involuntário rítmico de uma extremidade em repouso, com frequência de 4–6 Hz, característico da doença de Parkinson.',
            25 => 'Descarga neuronal anormal e excessiva resultando em manifestações motoras, sensitivas, autonómicas ou psíquicas transitórias.',
            28 => 'Contração involuntária dos músculos cervicais que limita a flexão do pescoço; sinal meníngeo de alta especificidade.',
            35 => 'Coloração amarelada de pele e escleróticas por acúmulo de bilirrubina; pode ser pré-hepática, hepática ou pós-hepática.',
            39 => 'Volume urinário superior a 2,5–3 L/dia; pode indicar diabetes mellitus, diabetes insípida ou insuficiência renal.',
            47 => 'Dor em articulação podendo ser inflamatória (artrite) ou mecânica (artrose); a caraterização da dor orienta o diagnóstico.',
        ];

        foreach ($symptomDescriptions as $symptomId => $content) {
            DB::table('descriptions')->insert([
                'content'          => $content,
                'type'             => 'technical',
                'describable_id'   => $symptomId,
                'describable_type' => 'App\\Models\\Symptom',
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        // ─── MEDICAMENTOS (descrição técnica de cada medicamento) ───────────
        $medicationDescriptions = [
            9  => 'Antibiótico da classe das hidrazidas do ácido isonicotínico. Inibe a síntese de ácidos micólicos da parede celular do M. tuberculosis. Pilar do esquema RIPE.',
            10 => 'Antibiótico rifamicínico com ação bactericida sobre M. tuberculosis. Indutor potente do citocromo P450; interações medicamentosas relevantes.',
            17 => 'Combinação de artemetér (derivado da artemisinina) e lumefantrina. Terapêutica combinada de primeira linha para malária não complicada por P. falciparum.',
            21 => 'Analgésico e antipirético com mecanismo central (inibição da COX-3). Seguro em gravidez e crianças; hepatotóxico em sobredosagem.',
            26 => 'Inibidor da enzima conversora de angiotensina (IECA). Reduz pós-carga e remodelação cardíaca; primeira linha em HAS e ICC.',
            30 => 'Antiagregante plaquetário irreversível por inibição da COX-1. Essencial na fase aguda e prevenção secundária do IAM e AVC isquémico.',
            35 => 'Precursor da dopamina que atravessa a barreira hematoencefálica; convertido a dopamina no estriado. Gold standard para os sintomas motores do Parkinson.',
            37 => 'Inibidor da acetilcolinesterase. Aumenta a disponibilidade de acetilcolina nas sinapses colinérgicas; efeito sintomático na demência de Alzheimer.',
            39 => 'Antiepilético que estabiliza canais de sódio e potencia o GABA. Utilizado como agente de primeira ou segunda linha no espectro de epilepsias.',
            43 => 'Insulina de ação intermédia com pico às 4–8 h e duração de 12–18 h. Usada como insulina basal em esquemas de insulinoterapia.',
            45 => 'Biguanida que reduz a produção hepática de glicose e aumenta a sensibilidade periférica à insulina. Primeira linha no DM2; benefício cardiovascular comprovado.',
            47 => 'Hormônio tireóideo sintético T4. Substituição hormonal no hipotiroidismo; dose ajustada pela TSH sérica. Tomado em jejum 30 min antes do café.',
            23 => 'DMARD convencional que inibe a di-hidrofolato-redutase. Primeira linha na artrite reumatoide; requer suplementação com ácido fólico.',
        ];

        foreach ($medicationDescriptions as $medicationId => $content) {
            DB::table('descriptions')->insert([
                'content'          => $content,
                'type'             => 'technical',
                'describable_id'   => $medicationId,
                'describable_type' => 'App\\Models\\Medication',
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }
    }
}
