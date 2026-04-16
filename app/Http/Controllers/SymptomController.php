<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Symptom $symptom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Symptom $symptom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Symptom $symptom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Symptom $symptom)
    {
        //
    }

    /**
     * Search symptoms with descriptions for the welcome page modal.
     */
    public function searchSymptoms(Request $request)
    {
        $query = $request->input('query', '');

        $symptoms = Symptom::with('descriptions')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(function ($symptom) {
                // Mapear severidade para português
                $severityMap = [
                    'low' => 'leve',
                    'medium' => 'moderado',
                    'high' => 'grave'
                ];
                
                $severityPt = $severityMap[$symptom->severity] ?? $symptom->severity;

                return [
                    'id' => $symptom->id,
                    'name' => $symptom->name,
                    'severity' => $severityPt,
                    'description' => $symptom->descriptions->first()?->content ?? 'Sem descrição disponível.',
                ];
            });

        return response()->json([
            'symptoms' => $symptoms
        ]);
    }
}
