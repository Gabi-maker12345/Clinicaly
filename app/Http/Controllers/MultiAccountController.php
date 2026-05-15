<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MultiAccountController extends Controller
{
    private const SESSION_KEY = 'clinicaly_role_sessions';

    private const ROLES = [
        User::ROLE_CLINIC => 'Clínica',
        User::ROLE_DOCTOR => 'Médico',
        User::ROLE_PATIENT => 'Paciente',
    ];

    public function index()
    {
        $slots = $this->slots();
        $users = User::whereIn('id', array_filter($slots))->get()->keyBy('id');

        return view('auth.multi-accounts', [
            'roles' => self::ROLES,
            'slots' => $slots,
            'users' => $users,
            'activeUser' => Auth::user(),
        ]);
    }

    public function connect(Request $request)
    {
        $data = $request->validate([
            'role' => ['required', 'in:'.implode(',', array_keys(self::ROLES))],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'switch_now' => ['nullable', 'boolean'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password) || ! $this->userMatchesRole($user, $data['role'])) {
            throw ValidationException::withMessages([
                'email' => 'As credenciais não correspondem ao perfil selecionado.',
            ]);
        }

        $slots = $this->slots();
        $slots[$data['role']] = $user->id;
        session([self::SESSION_KEY => $slots]);

        if ($request->boolean('switch_now')) {
            return redirect()->route($user->isClinic() ? 'clinic.index' : 'profile.show', ['as_role' => $data['role']])
                ->with('success', self::ROLES[$data['role']].' conectado.');
        }

        return back()->with('success', self::ROLES[$data['role']].' conectado.');
    }

    public function switch(Request $request, string $role)
    {
        if (! array_key_exists($role, self::ROLES)) {
            abort(404);
        }

        $slots = $this->slots();
        $userId = $slots[$role] ?? null;

        if (! $userId || ! $user = User::find($userId)) {
            return back()->with('error', 'Nenhuma conta conectada para este perfil.');
        }

        session([self::SESSION_KEY => $slots]);

        return redirect()->route($user->isClinic() ? 'clinic.index' : 'profile.show', ['as_role' => $role])
            ->with('success', 'Agora você está usando o perfil de '.self::ROLES[$role].'.');
    }

    public function open(string $role)
    {
        if (! array_key_exists($role, self::ROLES)) {
            abort(404);
        }

        $slots = $this->slots();
        $userId = $slots[$role] ?? null;

        if (! $userId || ! $user = User::find($userId)) {
            return redirect()->route('multi-accounts.index')->with('error', 'Nenhuma conta conectada para este perfil.');
        }

        return redirect()->route($user->isClinic() ? 'clinic.index' : 'profile.show', ['as_role' => $role]);
    }

    public function disconnect(string $role)
    {
        if (! array_key_exists($role, self::ROLES)) {
            abort(404);
        }

        $slots = $this->slots();
        unset($slots[$role]);
        session([self::SESSION_KEY => $slots]);

        return back()->with('info', self::ROLES[$role].' removido das contas conectadas.');
    }

    private function slots(): array
    {
        $slots = array_merge([
            User::ROLE_CLINIC => null,
            User::ROLE_DOCTOR => null,
            User::ROLE_PATIENT => null,
        ], session(self::SESSION_KEY, []));

        if ($user = Auth::user()) {
            foreach (array_keys(self::ROLES) as $role) {
                if ($this->userMatchesRole($user, $role)) {
                    $slots[$role] = $user->id;
                    session([self::SESSION_KEY => $slots]);
                    break;
                }
            }
        }

        return $slots;
    }

    private function userMatchesRole(User $user, string $role): bool
    {
        return match ($role) {
            User::ROLE_CLINIC => $user->isClinic(),
            User::ROLE_DOCTOR => $user->isDoctor(),
            User::ROLE_PATIENT => $user->isPatient(),
            default => false,
        };
    }

}
