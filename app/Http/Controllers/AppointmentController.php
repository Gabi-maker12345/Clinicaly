<?php

namespace App\Http\Controllers;

use App\Models\AppointmentRequest;
use App\Models\Conversation;
use App\Models\Prescription;
use App\Models\User;
use App\Notifications\AppointmentRequested;
use App\Notifications\AppointmentStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $data = $this->agendaData();

        return view('appointments.index', $data);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($this->isDoctor($user) || $user?->isClinic()) {
            abort(403);
        }

        $data = $request->validate([
            'doctor_id' => ['required', 'exists:users,id'],
            'clinic_id' => ['nullable', 'exists:users,id'],
            'prescription_id' => ['nullable', 'exists:prescriptions,id'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'consultation_type' => ['required', 'in:routine,follow_up'],
            'mode' => ['required', 'in:presencial,telemedicina'],
            'reason' => ['nullable', 'string', 'max:1200'],
        ]);

        $doctor = User::findOrFail($data['doctor_id']);
        $clinicId = $data['clinic_id'] ?? $doctor->clinic_id;

        if ($clinicId && (int) $doctor->clinic_id !== (int) $clinicId) {
            abort(422, 'O médico selecionado não pertence à clínica informada.');
        }

        $prescription = null;

        if (! empty($data['prescription_id'])) {
            $prescription = Prescription::with('diagnostico')
                ->where('id', $data['prescription_id'])
                ->whereHas('diagnostico', function ($query) use ($user, $data) {
                    $query->where('id_paciente', $user->id)
                        ->where('id_medico', $data['doctor_id']);
                })
                ->first();
        }

        if (! $prescription && ! $clinicId) {
            $prescription = Prescription::with('diagnostico')
                ->whereHas('diagnostico', function ($query) use ($user, $data) {
                    $query->where('id_paciente', $user->id)
                        ->where('id_medico', $data['doctor_id']);
                })
                ->latest()
                ->firstOrFail();
        }

        $appointment = AppointmentRequest::create([
            'patient_id' => $user->id,
            'doctor_id' => $data['doctor_id'],
            'clinic_id' => $clinicId,
            'prescription_id' => $prescription?->id,
            'diagnostico_id' => $prescription?->diagnostico_id,
            'scheduled_for' => $data['scheduled_date'] . ' ' . $data['scheduled_time'],
            'consultation_type' => $data['consultation_type'],
            'mode' => $data['mode'],
            'reason' => $data['reason'] ?? null,
        ]);

        $appointment->doctor?->notify(new AppointmentRequested($appointment));

        return redirect()->route('appointments.index')->with('success', 'Proposta enviada ao médico.');
    }

    public function accept(AppointmentRequest $appointment)
    {
        $this->authorizeDoctor($appointment);

        $appointment->update([
            'status' => 'accepted',
            'doctor_response' => request('doctor_response'),
            'responded_at' => now(),
        ]);

        $appointment->patient?->notify(new AppointmentStatusUpdated($appointment));

        return back()->with('success', 'Agendamento aceite e paciente notificado.');
    }

    public function reject(Request $request, AppointmentRequest $appointment)
    {
        $this->authorizeDoctor($appointment);

        $data = $request->validate([
            'doctor_response' => ['nullable', 'string', 'max:1200'],
        ]);

        $appointment->update([
            'status' => 'rejected',
            'doctor_response' => $data['doctor_response'] ?? null,
            'responded_at' => now(),
        ]);

        $appointment->patient?->notify(new AppointmentStatusUpdated($appointment));

        return back()->with('info', 'Agendamento recusado. O paciente recebeu o atalho para o chat.');
    }

    public function chat(AppointmentRequest $appointment)
    {
        $userId = Auth::id();

        if (! in_array($userId, [$appointment->patient_id, $appointment->doctor_id], true)) {
            abort(403);
        }

        $conversation = Conversation::where(function ($query) use ($appointment) {
            $query->where('sender_id', $appointment->patient_id)
                ->where('receiver_id', $appointment->doctor_id);
        })->orWhere(function ($query) use ($appointment) {
            $query->where('sender_id', $appointment->doctor_id)
                ->where('receiver_id', $appointment->patient_id);
        })->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'sender_id' => $appointment->patient_id,
                'receiver_id' => $appointment->doctor_id,
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function agendaData(): array
    {
        $user = Auth::user();
        $isDoctor = $this->isDoctor($user);

        $appointments = AppointmentRequest::with(['patient', 'doctor', 'clinic', 'prescription.monitorings', 'diagnostico.doenca'])
            ->when($user?->isClinic(), fn ($query) => $query->where('clinic_id', $user->id))
            ->when($isDoctor, fn ($query) => $query->where('doctor_id', $user->id))
            ->when(! $isDoctor && ! $user?->isClinic(), fn ($query) => $query->where('patient_id', $user->id))
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'accepted' THEN 1 WHEN 'rejected' THEN 2 ELSE 3 END")
            ->orderBy('scheduled_for')
            ->get();

        $prescriptions = Prescription::with(['diagnostico.medico', 'diagnostico.doenca', 'monitorings'])
            ->whereHas('diagnostico', function ($query) use ($user, $isDoctor) {
                $isDoctor
                    ? $query->where('id_medico', $user->id)
                    : $query->where('id_paciente', $user->id)->whereNotNull('id_medico');
            })
            ->latest()
            ->get();

        $doctorOptions = $isDoctor
            ? collect()
            : $prescriptions
                ->filter(fn ($prescription) => $prescription->diagnostico?->medico)
                ->groupBy(fn ($prescription) => $prescription->diagnostico->id_medico)
                ->map(function ($items) {
                    $latest = $items->first();

                    return [
                        'doctor' => $latest->diagnostico->medico,
                        'prescription' => $latest,
                        'prescriptions_count' => $items->count(),
                        'diagnosis' => $latest->diagnostico?->confirmed_disease_name,
                        'last_date' => $latest->created_at,
                    ];
                })
                ->values();

        return [
            'user' => $user,
            'isDoctor' => $isDoctor,
            'appointments' => $appointments,
            'doctorOptions' => $doctorOptions,
            'agendaPrescriptions' => $prescriptions,
            'agendaStats' => [
                'pending' => $appointments->where('status', 'pending')->count(),
                'accepted' => $appointments->where('status', 'accepted')->count(),
                'rejected' => $appointments->where('status', 'rejected')->count(),
                'available_doctors' => $doctorOptions->count(),
            ],
        ];
    }

    private function authorizeDoctor(AppointmentRequest $appointment): void
    {
        if (! $this->isDoctor(Auth::user()) || $appointment->doctor_id !== Auth::id()) {
            abort(403);
        }
    }

    private function isDoctor($user): bool
    {
        return $user?->isDoctor() ?? false;
    }
}
