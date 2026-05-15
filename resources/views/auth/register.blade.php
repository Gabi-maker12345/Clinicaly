<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">

    @php
        $accountRoles = [
            [
                'value' => \App\Models\User::ROLE_PATIENT,
                'label' => 'Paciente',
                'copy' => 'Acompanhar diagnósticos e prescrições.',
                'icon' => 'P',
            ],
            [
                'value' => \App\Models\User::ROLE_DOCTOR,
                'label' => 'Médico',
                'copy' => 'Validar diagnósticos e gerir pacientes.',
                'icon' => 'M',
            ],
            [
                'value' => \App\Models\User::ROLE_CLINIC,
                'label' => 'Clínica',
                'copy' => 'Preparar a gestão da sua unidade.',
                'icon' => 'C',
            ],
        ];

        $selectedRole = old('role', \App\Models\User::ROLE_PATIENT);
    @endphp

    <x-auth-split
        title="Crie sua conta"
        subtitle="Escolha o perfil certo para entrar no Clinicaly com uma experiência preparada para sua rotina."
        eyebrow="Novo acesso"
    >
        <x-validation-errors class="mb-5 rounded-xl border border-red-100 bg-red-50 p-4" />

        <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ $selectedRole }}' }">
            @csrf

            <div class="auth-field">
                <span class="auth-field-label">Tipo de conta</span>
                <div class="auth-role-grid">
                    @foreach ($accountRoles as $role)
                        <label class="auth-role-option" for="role_{{ $role['value'] }}">
                            <input
                                id="role_{{ $role['value'] }}"
                                type="radio"
                                name="role"
                                value="{{ $role['value'] }}"
                                x-model="role"
                                @checked($selectedRole === $role['value'])
                                required>
                            <span class="auth-role-card">
                                <span class="auth-role-icon">{{ $role['icon'] }}</span>
                                <span class="auth-role-name">{{ $role['label'] }}</span>
                                <span class="auth-role-copy">{{ $role['copy'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="auth-field" x-show="role === '{{ \App\Models\User::ROLE_CLINIC }}'" x-cloak>
                <label for="activity_hours">Horário de atividade da clínica</label>
                <input id="activity_hours" type="text" name="activity_hours" value="{{ old('activity_hours') }}"
                    class="auth-input"
                    placeholder="Ex.: 12-16h">
            </div>

            <div class="auth-field">
                <label for="name">Nome completo ou nome da clínica</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                    class="auth-input"
                    placeholder="Como quer ser identificado?">
            </div>

            <div class="auth-field">
                <label for="email">E-mail profissional ou pessoal</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="auth-input"
                    placeholder="exemplo@clinicaly.com">
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="auth-field">
                    <label for="password">Senha</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="auth-input"
                        placeholder="••••••••">
                </div>
                <div class="auth-field">
                    <label for="password_confirmation">Confirmar</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="auth-input"
                        placeholder="••••••••">
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="auth-inline">
                    <label for="terms" class="inline-flex cursor-pointer items-start gap-2 text-sm font-bold text-slate-600">
                        <input id="terms" type="checkbox" name="terms" required
                            class="mt-1 h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        <span>
                            Aceito os
                            <a target="_blank" href="{{ route('terms.show') }}" class="auth-inline-link">Termos</a>
                            e a
                            <a target="_blank" href="{{ route('policy.show') }}" class="auth-inline-link">Política de Privacidade</a>.
                        </span>
                    </label>
                </div>
            @endif

            <button type="submit" class="auth-action">
                Finalizar cadastro
            </button>
        </form>

        <div class="auth-link-row">
            Já tem uma conta no Clinicaly?
            <a href="{{ route('login') }}">Fazer login</a>
        </div>
    </x-auth-split>
</x-guest-layout>
