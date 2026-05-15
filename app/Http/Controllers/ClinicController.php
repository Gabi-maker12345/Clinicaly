<?php

namespace App\Http\Controllers;

use App\Models\AppointmentRequest;
use App\Models\ClinicStockItem;
use App\Models\ClinicStockMovement;
use App\Models\Diagnostico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClinicController extends Controller
{
    public function index(Request $request)
    {
        $clinic = $this->clinic();
        $employees = $clinic->employees()
            ->where(fn ($query) => $query->where('role', User::ROLE_DOCTOR)->orWhere('role', 'doctor')->orWhere('role', 'médico'))
            ->orderBy('name')
            ->get();

        $employeeIds = $employees->pluck('id');
        $diagnostics = Diagnostico::with(['paciente', 'medico', 'doenca'])
            ->where(function ($query) use ($clinic, $employeeIds) {
                $query->where('clinic_id', $clinic->id)
                    ->orWhereIn('id_medico', $employeeIds);
            })
            ->latest()
            ->get();

        $patients = $diagnostics
            ->filter(fn ($diagnostic) => $diagnostic->paciente)
            ->groupBy('id_paciente')
            ->map(function ($items) {
                $latest = $items->first();

                return [
                    'patient' => $latest->paciente,
                    'last_seen' => $latest->created_at,
                    'consultations' => $items->sortByDesc('created_at')->values(),
                ];
            })
            ->sortBy(fn ($item) => mb_strtolower($item['patient']->name))
            ->values();

        $stockItems = ClinicStockItem::where('clinic_id', $clinic->id)->orderBy('name')->get();
        $appointments = AppointmentRequest::with(['patient', 'doctor', 'clinic', 'diagnostico.doenca'])
            ->where('clinic_id', $clinic->id)
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'accepted' THEN 1 WHEN 'rejected' THEN 2 ELSE 3 END")
            ->orderBy('scheduled_for')
            ->get();

        $movements = ClinicStockMovement::with(['item', 'responsible', 'prescription'])
            ->where('clinic_id', $clinic->id)
            ->latest()
            ->take(8)
            ->get();

        return view('clinic.index', [
            'clinic' => $clinic,
            'employees' => $employees,
            'patients' => $patients,
            'stockItems' => $stockItems,
            'appointments' => $appointments,
            'recentMovements' => $movements,
            'activeTab' => $request->query('tab', 'employees'),
        ]);
    }

    public function storeEmployee(Request $request)
    {
        $clinic = $this->clinic();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => User::ROLE_DOCTOR,
            'clinic_id' => $clinic->id,
            'specialty' => $data['specialty'] ?? null,
            'phone' => $data['phone'] ?? null,
            'is_available' => true,
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('clinic.index', ['tab' => 'employees'])->with('success', 'Funcionário cadastrado com sucesso.');
    }

    public function updateEmployee(Request $request, User $employee)
    {
        $clinic = $this->clinic();
        $this->ensureClinicEmployee($employee, $clinic);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->id)],
            'specialty' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $employee->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'specialty' => $data['specialty'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        if (! empty($data['password'])) {
            $employee->password = Hash::make($data['password']);
        }

        $employee->save();

        return redirect()->route('clinic.index', ['tab' => 'employees'])->with('success', 'Funcionário atualizado.');
    }

    public function destroyEmployee(User $employee)
    {
        $clinic = $this->clinic();
        $this->ensureClinicEmployee($employee, $clinic);
        $employee->delete();

        return redirect()->route('clinic.index', ['tab' => 'employees'])->with('info', 'Funcionário removido.');
    }

    public function employeeActivities(Request $request, User $employee)
    {
        $clinic = $this->clinic();
        $this->ensureClinicEmployee($employee, $clinic);

        $period = $request->query('period', 'month');
        $start = match ($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            default => now()->startOfMonth(),
        };

        $diagnostics = Diagnostico::with(['paciente', 'doenca'])
            ->where('id_medico', $employee->id)
            ->where('created_at', '>=', $start)
            ->latest()
            ->get();

        return view('clinic.employee-activities', [
            'clinic' => $clinic,
            'employee' => $employee,
            'diagnostics' => $diagnostics,
            'period' => $period,
        ]);
    }

    public function updateActivityHours(Request $request)
    {
        $clinic = $this->clinic();

        $data = $request->validate([
            'activity_hours' => ['required', 'string', 'max:80', 'regex:/^\s*\d{1,2}(?::\d{2})?\s*h?\s*[-–]\s*\d{1,2}(?::\d{2})?\s*h?\s*$/i'],
        ]);

        $clinic->update(['activity_hours' => $data['activity_hours']]);

        return back()->with('success', 'Horário de atividade atualizado.');
    }

    public function acceptAppointment(AppointmentRequest $appointment)
    {
        $clinic = $this->clinic();

        if ((int) $appointment->clinic_id !== (int) $clinic->id) {
            abort(403);
        }

        $appointment->update([
            'status' => 'accepted',
            'responded_at' => now(),
            'doctor_response' => request('doctor_response'),
        ]);

        return redirect()->route('clinic.index', ['tab' => 'appointments'])->with('success', 'Agendamento aceite pela clínica.');
    }

    private function clinic(): User
    {
        $clinic = Auth::user();

        if (! $clinic?->isClinic()) {
            abort(403);
        }

        return $clinic;
    }

    private function ensureClinicEmployee(User $employee, User $clinic): void
    {
        if ((int) $employee->clinic_id !== (int) $clinic->id) {
            abort(403);
        }
    }
}
