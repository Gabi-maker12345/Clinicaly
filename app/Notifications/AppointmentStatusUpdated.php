<?php

namespace App\Notifications;

use App\Models\AppointmentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public AppointmentRequest $appointment)
    {
        $this->appointment->loadMissing(['patient', 'doctor']);
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $accepted = $this->appointment->status === 'accepted';
        $message = (new MailMessage)
            ->subject($accepted ? 'Consulta aceite' : 'Consulta recusada')
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Dr(a). ' . ($this->appointment->doctor?->name ?? 'seu médico') . ' respondeu à sua proposta de consulta.')
            ->line('Data solicitada: ' . $this->appointment->scheduled_for->format('d/m/Y H:i'))
            ->line('Estado: ' . ($accepted ? 'Aceite' : 'Recusada'));

        if ($this->appointment->doctor_response) {
            $message->line('Resposta: ' . $this->appointment->doctor_response);
        }

        return $message->action(
            $accepted ? 'Ver agenda' : 'Conversar com o médico',
            $accepted ? route('appointments.index') : route('appointments.chat', $this->appointment)
        );
    }

    public function toArray(object $notifiable): array
    {
        $accepted = $this->appointment->status === 'accepted';

        return [
            'type' => 'appt',
            'title' => $accepted ? 'Consulta aceite' : 'Consulta recusada',
            'message' => $accepted
                ? 'Sua consulta foi aceite para ' . $this->appointment->scheduled_for->format('d/m/Y H:i') . '.'
                : 'Sua consulta foi recusada. Você pode conversar com o médico no chat.',
            'appointment_id' => $this->appointment->id,
            'url' => $accepted
                ? route('appointments.index') . '#appointment-' . $this->appointment->id
                : route('appointments.chat', $this->appointment),
            'chat_url' => route('appointments.chat', $this->appointment),
        ];
    }
}
