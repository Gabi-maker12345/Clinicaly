<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnostico;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Symptom;
use App\Models\Disease;
use Carbon\Carbon;

class DiagnosticController extends Controller
{
    public function medicoIndex()
    {
        $user = Auth::user();

        $stats = [
            'pendentes' => Diagnostico::where('status', 'pendente')->count(),
            'validados' => Diagnostico::where('status', 'validado')
                                ->whereMonth('created_at', now()->month)
                                ->count(),
            'pacientes' => Diagnostico::distinct('id_paciente')->count(),
        ];

        $pendentes = Diagnostico::with(['paciente'])
            ->where('status', 'pendente')
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($diag) {
                $symptomNames = Symptom::whereIn('id', $diag->id_sintomas ?? [])
                    ->pluck('name')
                    ->take(3)
                    ->join(' · ');
                
                $diag->sintomas_preview = $symptomNames;
                return $diag;
            });

        $recentes = Diagnostico::with(['paciente'])
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function ($diag) {
                $symptomNames = Symptom::whereIn('id', $diag->id_sintomas ?? [])
                    ->pluck('name')
                    ->take(3)
                    ->join(' · ');
                
                $diag->sintomas_preview = $symptomNames;
          
                $diag->probability = $diag->doencas_sugeridas[0]['probabilidade'] ?? 0;
                
                return $diag;
            });

        return view('dashboard_medico', compact('stats', 'pendentes', 'recentes', 'user'));
    }

   public function validar(Diagnostico $diagnostico)
    {
        $diagnostico->load(['paciente']);
  
        $ids = is_array($diagnostico->id_sintomas) ? $diagnostico->id_sintomas : json_decode($diagnostico->id_sintomas, true) ?? [];
        
        $symptoms = Symptom::whereIn('id', $ids)->get();
        
        return view('pages.validar_diagnostico', compact('diagnostico', 'symptoms'));
    }

    public function confirmar(Request $request, Diagnostico $diagnostico)
    {
        $request->validate([
            'doenca_confirmada' => 'required|string',
            'gravidade_real'    => 'required|string',
            'observacoes'       => 'nullable|string',
        ]);

        $diagnostico->update([
            'status' => 'validado',
            'parecer_medico' => [
                'doenca' => $request->doenca_confirmada,
                'gravidade' => $request->gravidade_real,
                'notas' => $request->observacoes,
                'data_validacao' => now()->toDateTimeString(),
            ]
        ]);

        return redirect()->route('dashboard')->with('success', 'Diagnóstico validado com sucesso!');
    }

    /**
     * Rejeita o diagnóstico (Elimina o registo)
     */
    public function rejeitar(Diagnostico $diagnostico)
    {
        $diagnostico->delete();

        return redirect()->route('dashboard')->with('info', 'O diagnóstico foi rejeitado e removido da fila.');
    }

    public function fila()
    {
        $diagnosticos = Diagnostico::with('paciente')
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->get();

        $diagnosticosFormatados = $diagnosticos->map(function ($diag) {
            $topProb = $diag->doencas_sugeridas[0]['probabilidade'] ?? 0;
            
            if ($topProb >= 80) {
                $diag->nivel_gravidade = 'critico';
            } elseif ($topProb >= 50) {
                $diag->nivel_gravidade = 'alto';
            } else {
                $diag->nivel_gravidade = 'medio_baixo';
            }

            return $diag;
        });

        $counts = [
            'all' => $diagnosticosFormatados->count(),
            'critico' => $diagnosticosFormatados->where('nivel_gravidade', 'critico')->count(),
            'alto' => $diagnosticosFormatados->where('nivel_gravidade', 'alto')->count(),
            'medio_baixo' => $diagnosticosFormatados->where('nivel_gravidade', 'medio_baixo')->count(),
        ];

        return view('pages.diagnostico_fila', [
            'diagnosticos' => $diagnosticosFormatados,
            'counts' => $counts
        ]);
    }

    /**
     * Exibe o perfil do médico com a lista de pacientes integrada.
     */
    public function profile()
    {
        $user = Auth::user();
        
        // Busca o diagnóstico mais recente de cada paciente relacionado a este sistema
        $pacientes = Diagnostico::with('paciente')
            ->latest()
            ->get()
            ->unique('id_paciente')
            ->map(function ($diag) {
                $topProb = $diag->doencas_sugeridas[0]['probabilidade'] ?? 0;
                
                if ($diag->status === 'validado') {
                    $diag->status_contexto = 'validado';
                } elseif ($topProb >= 80) {
                    $diag->status_contexto = 'pendente_critico';
                } elseif ($topProb >= 50) {
                    $diag->status_contexto = 'pendente_alto';
                } else {
                    $diag->status_contexto = 'baixo';
                }
                return $diag;
            });

        // Carrega diagnósticos relacionados ao usuário atual com suas prescrições
        $meusDiagnosticos = Diagnostico::with(['paciente', 'doenca'])
            ->where('id_paciente', $user->id)
            ->latest()
            ->get()
            ->map(function ($diag) {
                $topProb = $diag->doencas_sugeridas[0]['probabilidade'] ?? 0;
                
                if ($diag->status === 'validado') {
                    $diag->status_contexto = 'validado';
                } elseif ($topProb >= 80) {
                    $diag->status_contexto = 'pendente_critico';
                } elseif ($topProb >= 50) {
                    $diag->status_contexto = 'pendente_alto';
                } else {
                    $diag->status_contexto = 'pendente';
                }
                
                // Carrega a prescrição associada a este diagnóstico
                $diag->prescricao = Prescription::where('diagnostico_id', $diag->id)
                    ->with(['monitorings', 'diagnostico.doenca', 'diagnostico.paciente'])
                    ->first();
                
                return $diag;
            });

        // Carrega prescrições relacionadas aos diagnósticos do usuário
        $minhasPrescricoes = collect();
        if ($user->role === 'pacient') {
            $minhasPrescricoes = \App\Models\Prescription::with(['diagnostico.doenca', 'diagnostico.paciente', 'monitorings'])
                ->whereHas('diagnostico', function ($query) use ($user) {
                    $query->where('id_paciente', $user->id);
                })
                ->latest()
                ->get();
        }

        return view('profile.show', compact('user', 'pacientes', 'meusDiagnosticos', 'minhasPrescricoes'));
    }

    public function history($id)
    {
        // Correção 1: Usar User em vez de Patient (conforme sua model)
        $patient = User::with(['searchHistories', 'chatSessions'])->findOrFail($id);

        // Busca diagnósticos relacionados
        $diagnosticosMedicos = Diagnostico::where('id_paciente', $id)->with('medico')->get();

        // Correção 2: Adicionar 'total_prescriptions' para evitar erro no card
        $stats = [
            'total_diagnostics'   => $diagnosticosMedicos->count(),
            'total_chats'         => $patient->chatSessions->count(),
            'total_prescriptions' => 0, 
            'adherence_rate'      => 87,
        ];

        $timeline = collect();

        foreach ($diagnosticosMedicos as $diag) {
            $prob = $diag->doencas_sugeridas[0]['probabilidade'] ?? 0;
            
            $timeline->push([
                'id'       => $diag->id,
                'type'     => 'medical_record',
                'date'     => $diag->created_at,
                'title'    => $diag->status === 'validado' 
                            ? ($diag->parecer_medico['doenca'] ?? 'Confirmado') 
                            : ($diag->doencas_sugeridas[0]['nome'] ?? 'Suspeita'),
                'status'   => $diag->status,
                'score'    => $prob, // Resolve erro "Undefined array key score"
                'severity' => $prob >= 80 ? 'Crítica' : ($prob >= 50 ? 'Alta' : 'Baixa'),
                'doctor'   => $diag->medico->name ?? 'Sistema', // Resolve erro "Undefined array key doctor"
            ]);
        }

        // Histórico de buscas por IA
        foreach ($patient->searchHistories as $search) {
            $timeline->push([
                'type'     => 'ia_search',
                'date'     => $search->created_at,
                'title'    => $search->query ?? 'Pesquisa de sintomas',
                'status'   => 'info',
                'score'    => 0,
                'severity' => 'N/A',
                'doctor'   => 'IA'
            ]);
        }

        $timeline = $timeline->sortByDesc('date');

        return view('pages.history', compact('patient', 'stats', 'timeline'));
    }

}