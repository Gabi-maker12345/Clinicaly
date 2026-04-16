<?php
namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Monitoring;
use App\Models\Diagnostico;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    public function create(Diagnostico $diagnostico)
    {
        $diagnostico->load(['paciente', 'medico']); 
        
        return view('pages.Emitir-prescricao', compact('diagnostico'));
    }

    public function store(Request $request, Diagnostico $diagnostico)
    {
        $request->validate([
            'start_date' => 'required|date',
            'medications' => 'required|array|min:1',
            'medications.*.name' => 'required|string',
            'medications.*.interval' => 'required|integer|in:4,6,8,12,24',
            'medications.*.duration' => 'required|integer|min:1',
        ]);

        // 1. Cálculo da Data Final baseado no remédio com maior duração
        $startDate = Carbon::parse($request->start_date);
        $maxDurationDays = 0;

        foreach ($request->medications as $med) {
            if ($med['duration'] > $maxDurationDays) {
                $maxDurationDays = $med['duration'];
            }
        }

        $finishDate = $startDate->copy()->addDays((int) $maxDurationDays);

        // 2. Criar a Prescrição (Capa)
        $prescription = Prescription::create([
            'diagnostico_id' => $diagnostico->id,
            'start_date' => $startDate,
            'finish_date' => $finishDate,
            'recommendations' => $request->recommendations,
        ]);

        // 3. Salvar os Medicamentos (Monitoramentos Individuais)
        foreach ($request->medications as $med) {
            Monitoring::create([
                'prescription_id' => $prescription->id,
                'medication_name' => $med['name'],
                'interval_hours' => $med['interval'],
                'status' => 'pending'
            ]);
        }

        // 4. Validar o Diagnóstico
        $diagnostico->update(['status' => 'validado']);

        // Retorna para a dashboard ou lista de pacientes
        return redirect()->route('dashboard')->with('success', 'Prescrição emitida e diagnóstico validado!');
    }

    // Método para simular o banco de remédios na barra flutuante
    public function searchMedications(Request $request)
    {
        $query = strtolower($request->get('q', ''));
        
        // Exemplo de banco de dados mockado. Você pode trocar por um DB real: Medication::where('name', 'like', "%$query%")->get()
        $db = [
            'Amoxicilina 500mg', 'Ibuprofeno 400mg', 'Dipirona 1g', 'Paracetamol 750mg',
            'Ceftriaxona 2g IV', 'Dexametasona 0.15 mg/kg IV', 'Azitromicina 500mg', 'Omeprazol 20mg'
        ];

        $results = array_filter($db, function($med) use ($query) {
            return str_contains(strtolower($med), $query);
        });

        return response()->json(array_values($results));
    }
}