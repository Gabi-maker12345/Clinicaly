<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\Disease;
use App\Models\Symptom;

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
        ]);
    }
    
    public function process(Request $request)
    {
        // Dados Dinâmicos do Paciente
        $perfil = [
            'genero' => $request->input('gender'),
            'idade'  => (int)$request->input('age'),
            'peso'   => (float)$request->input('weight'),
        ];

        $symptomIds = $request->input('items', []); // Array de IDs de sintomas
        $countSelected = count($symptomIds);

        if ($countSelected === 0) {
            return back()->with('error', 'Selecione ao menos um sintoma.');
        }

        $results = Disease::with(['symptoms', 'categories'])
            ->whereHas('symptoms', function ($query) use ($symptomIds) {
                // Mantemos o whereIn: ele traz qualquer doença que tenha PELO MENOS UM desses IDs
                $query->whereIn('symptoms.id', $symptomIds);
            })
            ->get()
            ->map(function ($disease) use ($symptomIds, $perfil) {
                // 1. Contagem de matches
                $diseaseSymptomIds = $disease->symptoms->pluck('id')->toArray();
                $matches = array_intersect($symptomIds, $diseaseSymptomIds);
                $matchCount = count($matches);
                $totalSintomasDoenca = count($diseaseSymptomIds);

                // 2. Probabilidade Base
                // Se a doença tem 10 sintomas e o usuário marcou 1, a base é 10%
                $probability = ($totalSintomasDoenca > 0) ? ($matchCount / $totalSintomasDoenca) * 100 : 0;

                // 3. Ajuste de Gênero (Mais flexível)
                // Se o target_gender for 'both' ou NULL ou vazio, ele ignora o filtro e mantém a prob.
                if ($disease->target_gender && 
                    !in_array($disease->target_gender, ['both', '']) && 
                    $disease->target_gender !== $perfil['genero']) {
                    $probability = $probability * 0.1; // Reduz 90% mas não some da lista (ajuda no diagnóstico)
                }

                // 4. Bônus de Idade
                if ($perfil['idade'] >= $disease->min_age && $perfil['idade'] <= $disease->max_age) {
                    $probability += 10; // Adiciona 10 pontos percentuais de relevância
                }

                $disease->match_count = $matchCount;
                $disease->total_count = $totalSintomasDoenca; 
                $disease->probability = min(round($probability), 100);

                return $disease;
            })
            // Remove apenas o que for realmente 0 absolute
            ->filter(fn($d) => $d->probability > 0)
            ->sortByDesc(fn($d) => [$d->match_count, $d->probability])
            ->values();

        return view('results', [
            'results' => $results,
            'age'     => $perfil['idade'], 
            'gender'  => $perfil['genero']  
        ]);
    }
}