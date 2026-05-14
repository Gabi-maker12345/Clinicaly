<?php

namespace App\Notifications;

use App\Models\AppointmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRequested extends Notification
{
    use Queueable;

    public function __construct(public AppointmentRequest $appointment)
    {
        $this->appointment->loadMissing(['patient', 'doctor', 'diagnostico.doenca']);
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nova proposta de consulta')
            ->greeting('Olá, Dr(a). ' . $notifiable->name)
            ->line(($this->appointment->patient?->name ?? 'Um paciente') . ' enviou uma proposta de agendamento.')
            ->line('Data e hora: ' . $this->appointment->scheduled_for->format('d/m/Y H:i'))
            ->line('Tipo: ' . $this->typeLabel())
            ->action('Ver proposta', route('appointments.index'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'appt',
            'title' => 'Nova proposta de consulta',
            'message' => ($this->appointment->patient?->name ?? 'Paciente') . ' solicitou consulta para ' . $this->appointment->scheduled_for->format('d/m/Y H:i') . '.',
            'appointment_id' => $this->appointment->id,
            'url' => route('appointments.index') . '#appointment-' . $this->appointment->id,
        ];
    }

    private function typeLabel(): string
    {
        return $this->appointment->consultation_type === 'routine'
            ? 'Consulta de rotina'
            : 'Consulta de acompanhamento';
    }
}
