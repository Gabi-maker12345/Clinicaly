<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Diagnostico;
use App\Models\Symptom;
use App\Models\Prescription;
use App\Models\Monitoring;

class DashboardController extends Controller
{
    public function pacienteIndex()
    {
        $user = Auth::user();

        // Estatísticas para o dashboard do paciente
        $stats = [
            'pendentes' => Diagnostico::where('id_paciente', $user->id)
                ->where('status', 'pendente')
                ->count(),
            'validados' => Diagnostico::where('id_paciente', $user->id)
                ->where('status', 'validado')
                ->count(),
            'prescricoes' => Prescription::whereHas('diagnostico', function ($q) use ($user) {
                $q->where('id_paciente', $user->id);
            })->count(),
            'monitoramentos_ativos' => Monitoring::where('status', 'active')
                ->whereHas('prescription.diagnostico', function ($q) use ($user) {
                    $q->where('id_paciente', $user->id);
                })->count(),
        ];

        // Diagnósticos recentes do paciente com sintomas preview
        $diagnosticos = Diagnostico::where('id_paciente', $user->id)
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function ($diag) {
                $symptomNames = Symptom::whereIn('id', $diag->id_sintomas ?? [])
                    ->pluck('name')
                    ->take(2)
                    ->join(' · ');
                
                $diag->sintomas_preview = $symptomNames ?: 'Sem sintomas registrados';
                return $diag;
            });

        // Prescrições recentes
        $prescricoes = Prescription::whereHas('diagnostico', function ($q) use ($user) {
            $q->where('id_paciente', $user->id);
        })
            ->latest('created_at')
            ->take(3)
            ->get();

        return view('dashboard', compact('user', 'stats', 'diagnosticos', 'prescricoes'));
    }
}
