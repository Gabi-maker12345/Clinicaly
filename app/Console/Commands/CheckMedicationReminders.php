<?php

namespace App\Console\Commands;

use App\Models\MedicationIntakeLog;
use App\Models\Monitoring;
use App\Notifications\MedicationReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckMedicationReminders extends Command
{
    protected $signature = 'app:check-medication-reminders';

    protected $description = 'Envia lembretes de medicação e marca tomas expiradas.';

    public function handle(): int
    {
        $now = now();

        MedicationIntakeLog::where('status', 'pending')
            ->whereNotNull('due_until')
            ->where('due_until', '<', $now)
            ->update(['status' => 'missed']);

        $monitorings = Monitoring::with('prescription.diagnostico.paciente')
            ->where('status', 'active')
            ->whereNotNull('next_notification_at')
            ->where('next_notification_at', '<=', $now)
            ->get();

        foreach ($monitorings as $monitoring) {
            $lock = Cache::lock('medication-reminder:' . $monitoring->id, 55);

            if (! $lock->get()) {
                continue;
            }

            try {
                $monitoring->refresh()->loadMissing('prescription.diagnostico.paciente');
                $prescription = $monitoring->prescription;

                if ($prescription?->finish_date && $now->greaterThan($prescription->finish_date->endOfDay())) {
                    $monitoring->update(['status' => 'completed', 'next_notification_at' => null]);
                    continue;
                }

                $hasOpenLog = $monitoring->intakeLogs()
                    ->where('status', 'pending')
                    ->where('due_until', '>=', $now)
                    ->exists();

                if ($hasOpenLog) {
                    continue;
                }

                $latestLog = $monitoring->intakeLogs()
                    ->latest('scheduled_at')
                    ->first();

                if ($latestLog?->notified_at && $latestLog->notified_at->greaterThan($now->copy()->subHours($monitoring->interval_hours))) {
                    $nextNotificationAt = $latestLog->scheduled_at->copy()->addHours($monitoring->interval_hours);

                    if ($nextNotificationAt->lessThanOrEqualTo($now)) {
                        $nextNotificationAt = $now->copy()->addHours($monitoring->interval_hours);
                    }

                    $monitoring->update(['next_notification_at' => $nextNotificationAt]);
                    continue;
                }

                $log = $monitoring->intakeLogs()->create([
                    'scheduled_at' => $monitoring->next_notification_at,
                    'notified_at' => $now,
                    'due_until' => $now->copy()->addMinutes(15),
                    'status' => 'pending',
                ]);

                $patient = $prescription?->diagnostico?->paciente;
                if ($patient) {
                    $notification = new MedicationReminder($log);

                    $patient->notifications()->create([
                        'id' => (string) Str::uuid(),
                        'type' => MedicationReminder::class,
                        'data' => $notification->toArray($patient),
                    ]);

                    try {
                        $patient->notify($notification);
                    } catch (\Throwable $exception) {
                        Log::error('Falha ao enviar lembrete de medicação pelo agendador.', [
                            'monitoring_id' => $monitoring->id,
                            'log_id' => $log->id,
                            'patient_id' => $patient->id,
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }

                $monitoring->update([
                    'next_notification_at' => $now->copy()->addHours($monitoring->interval_hours),
                ]);
            } finally {
                $lock->release();
            }
        }

        return self::SUCCESS;
    }
}
