<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Symptom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicalRequestController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user?->isPatient()) {
            abort(403);
        }

        $data = $request->validate([
            'clinic_id' => ['required', 'exists:users,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'symptoms' => ['nullable', 'array'],
            'symptoms.*' => ['integer', 'exists:symptoms,id'],
            'description' => ['required', 'string', 'max:4000'],
            'evolution' => ['required', 'string', 'max:2500'],
            'triggers' => ['required', 'string', 'max:2500'],
            'medical_history' => ['required', 'string', 'max:2500'],
            'context' => ['required', 'string', 'max:2500'],
            'age' => ['required', 'integer', 'min:0', 'max:130'],
            'weight' => ['required', 'numeric', 'min:1', 'max:500'],
            'height' => ['required', 'numeric', 'min:0.3', 'max:2.8'],
            'gender' => ['required', 'in:m,f,outro'],
        ]);

        $clinic = User::findOrFail($data['clinic_id']);
        $doctor = User::findOrFail($data['doctor_id']);

        if (! $clinic->isClinic() || ! $clinic->isOpenNow()) {
            return back()->with('error', 'Esta clínica não está aberta neste momento.');
        }

        if (! $doctor->isDoctor() || (int) $doctor->clinic_id !== (int) $clinic->id || ! $doctor->is_available) {
            return back()->with('error', 'Este médico não está livre nesta clínica agora.');
        }

        $symptomIds = collect($data['symptoms'] ?? [])->map(fn ($id) => (int) $id)->filter()->unique()->values();
        $symptoms = $symptomIds->isNotEmpty()
            ? Symptom::whereIn('id', $symptomIds)->orderBy('name')->get(['id', 'name'])
            : collect();

        $conversation = Conversation::where(function ($query) use ($user, $doctor) {
                $query->where('sender_id', $user->id)->where('receiver_id', $doctor->id);
            })
            ->orWhere(function ($query) use ($user, $doctor) {
                $query->where('sender_id', $doctor->id)->where('receiver_id', $user->id);
            })
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'sender_id' => $user->id,
                'receiver_id' => $doctor->id,
                'title' => 'Análise clínica de ' . $user->name,
            ]);
        }

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'body' => 'Bom dia, senhor ' . $doctor->name . ".\n\nGostaria de solicitar uma análise clínica com base nas seguintes informações:",
            'type' => 'clinical_pack',
            'payload' => [
                'patient_id' => $user->id,
                'patient_name' => $user->name,
                'clinic_id' => $clinic->id,
                'clinic_name' => $clinic->name,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'symptom_ids' => $symptomIds->all(),
                'symptoms' => $symptoms->map(fn ($symptom) => [
                    'id' => $symptom->id,
                    'name' => $symptom->name,
                ])->values()->all(),
                'description' => trim($data['description']),
                'evolution' => trim($data['evolution']),
                'triggers' => trim($data['triggers']),
                'medical_history' => trim($data['medical_history']),
                'context' => trim($data['context']),
                'age' => (int) $data['age'],
                'weight' => (float) $data['weight'],
                'height' => (float) $data['height'],
                'gender' => $data['gender'],
                'submitted_at' => now()->toDateTimeString(),
            ],
        ]);

        $conversation->touch();

        return redirect()
            ->route('messages.show', $conversation)
            ->with('success', 'Pedido enviado ao médico.');
    }

    public function diagnose(Message $message)
    {
        $message->load('conversation');
        $conversation = $message->conversation;
        $user = Auth::user();

        if (
            ! $user?->isDoctor()
            || $message->type !== 'clinical_pack'
            || ! $conversation
            || ((int) $conversation->sender_id !== (int) $user->id && (int) $conversation->receiver_id !== (int) $user->id)
        ) {
            abort(403);
        }

        $payload = $message->payload ?? [];
        if ((int) ($payload['doctor_id'] ?? 0) !== (int) $user->id) {
            abort(403);
        }

        return redirect()
            ->route('discovery.index')
            ->with('diagnostic_prefill', $payload);
    }
}
