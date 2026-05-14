<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitoring;
use App\Models\MedicationIntakeLog;
use App\Models\Prescription;
use App\Notifications\MedicationReminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MonitoringController extends Controller
{
    public function startPrescription(Prescription $prescription)
    {
        $prescription->loadMissing('diagnostico.paciente', 'diagnostico.medico', 'diagnostico.doenca', 'monitorings.intakeLogs');

        if ($prescription->diagnostico?->id_paciente !== Auth::id()) {
            abort(403);
        }

        $now = now();

        foreach ($prescription->monitorings as $monitoring) {
            if ($monitoring->status === 'completed') {
                continue;
            }

            $latestLog = $monitoring->intakeLogs()
                ->latest('scheduled_at')
                ->first();

            $existingOpenLog = $monitoring->intakeLogs()
                ->where('status', 'pending')
                ->where('due_until', '>', $now)
                ->first();

            if (! $latestLog && ! $existingOpenLog) {
                $existingOpenLog = $monitoring->intakeLogs()->create([
                    'scheduled_at' => $now,
                    'notified_at' => $now,
                    'due_until' => $now->copy()->addMinutes(15),
                    'status' => 'pending',
                ]);

                $this->sendMedicationReminder($prescription, $existingOpenLog);
            }

            $nextNotificationAt = $latestLog
                ? $latestLog->scheduled_at->copy()->addHours($monitoring->interval_hours)
                : $now->copy()->addHours($monitoring->interval_hours);

            if ($nextNotificationAt->lessThanOrEqualTo($now)) {
                $nextNotificationAt = $now->copy()->addHours($monitoring->interval_hours);
            }

            $monitoring->update([
                'status' => 'active',
                'next_notification_at' => $nextNotificationAt,
            ]);
        }

        $prescription->refresh()->loadMissing('diagnostico.paciente', 'diagnostico.medico', 'diagnostico.doenca', 'monitorings.intakeLogs');

        return response()->json([
            'message' => 'Tratamento iniciado. A primeira dose foi enviada agora.',
            'started_at' => $now->toDateTimeString(),
            'prescription' => $prescription,
        ]);
    }

    private function sendMedicationReminder(Prescription $prescription, MedicationIntakeLog $log): void
    {
        $patient = $prescription->diagnostico?->paciente;

        if (! $patient) {
            return;
        }

        $notification = new MedicationReminder($log);

        $patient->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => MedicationReminder::class,
            'data' => $notification->toArray($patient),
        ]);

        try {
            $patient->notify($notification);
        } catch (\Throwable $exception) {
            Log::error('Falha ao enviar lembrete de medicação.', [
                'prescription_id' => $prescription->id,
                'log_id' => $log->id,
                'patient_id' => $prescription->diagnostico?->id_paciente,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function completeLog(MedicationIntakeLog $log)
    {
        $log->loadMissing('monitoring.prescription.diagnostico');

        if ($log->monitoring->prescription->diagnostico?->id_paciente !== Auth::id()) {
            abort(403);
        }

        if ($log->status !== 'pending' || !$log->due_until || now()->greaterThan($log->due_until)) {
            if (request()->expectsJson()) {
                if ($log->status === 'pending') {
                    $log->update(['status' => 'missed']);
                }

                return response()->json([
                    'message' => 'O prazo de confirmação desta toma expirou.',
                    'log' => $log->fresh(),
                ], 422);
            }

            return back()->with('error', 'O prazo de confirmação desta toma expirou.');
        }

        $log->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Toma confirmada com sucesso.',
                'log' => $log->fresh(),
            ]);
        }

        return back()->with('success', 'Toma confirmada com sucesso.');
    }

    public function completeLogFromMail(MedicationIntakeLog $log)
    {
        if ($log->status !== 'pending' || !$log->due_until || now()->greaterThan($log->due_until)) {
            if ($log->status === 'pending') {
                $log->update(['status' => 'missed']);
            }

            return redirect(route('profile.show') . '#prescricoes')->with('error', 'O prazo de confirmação desta toma expirou.');
        }

        $log->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect(route('profile.show') . '#prescricoes')->with('success', 'Toma confirmada com sucesso.');
    }
}
