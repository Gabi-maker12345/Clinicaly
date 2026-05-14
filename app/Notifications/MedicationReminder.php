<?php

namespace App\Notifications;

use App\Models\MedicationIntakeLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class MedicationReminder extends Notification
{
    use Queueable;

    public function __construct(public MedicationIntakeLog $log)
    {
        $this->log->loadMissing('monitoring.prescription.diagnostico.paciente');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $medication = $this->log->monitoring->medication_name;

        return (new MailMessage)
            ->subject('Hora de tomar ' . $medication)
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Está na hora de tomar o medicamento prescrito.')
            ->line('Medicamento: ' . $medication)
            ->line('Você tem 15 minutos para confirmar esta toma.')
            ->action('Concluir toma', $this->completionUrl())
            ->line('Se você não confirmar dentro do prazo, esta toma será marcada como falha.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Hora do medicamento',
            'message' => 'Tomar ' . $this->log->monitoring->medication_name,
            'log_id' => $this->log->id,
            'medication_name' => $this->log->monitoring->medication_name,
            'due_until' => optional($this->log->due_until)->toDateTimeString(),
            'complete_url' => $this->completionUrl(),
        ];
    }

    private function completionUrl(): string
    {
        return URL::temporarySignedRoute(
            'monitoring.logs.complete-mail',
            $this->log->due_until ?? now()->addMinutes(15),
            ['log' => $this->log->id]
        );
    }
}
