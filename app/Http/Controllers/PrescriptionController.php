<?php
namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Monitoring;
use App\Models\Diagnostico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrescriptionController extends Controller
{
    public function indexMedico()
    {
        $prescriptions = Prescription::with(['diagnostico.paciente', 'monitorings'])
            ->whereHas('diagnostico', function ($query) {
                $query->where('id_medico', Auth::id());
            })
            ->latest()
            ->get();

        return view('prescricoes.index_medico', compact('prescriptions'));
    }

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

        $startDate = Carbon::parse($request->start_date);
        $maxDurationDays = 0;

        foreach ($request->medications as $med) {
            if ($med['duration'] > $maxDurationDays) {
                $maxDurationDays = $med['duration'];
            }
        }

        $finishDate = $startDate->copy()->addDays((int) $maxDurationDays);

        $prescription = Prescription::create([
            'diagnostico_id' => $diagnostico->id,
            'start_date' => $startDate,
            'finish_date' => $finishDate,
            'recommendations' => $request->recommendations,
        ]);

        foreach ($request->medications as $med) {
            Monitoring::create([
                'prescription_id' => $prescription->id,
                'medication_name' => $med['name'],
                'interval_hours' => $med['interval'],
                'duration_days' => $med['duration'],
                'status' => 'pending'
            ]);
        }

        $diagnostico->update(['status' => 'validado']);

        return redirect()->route('dashboard')->with('success', 'Prescrição emitida e diagnóstico validado!');
    }

    public function searchMedications(Request $request)
    {
        $query = strtolower($request->get('q', ''));

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
