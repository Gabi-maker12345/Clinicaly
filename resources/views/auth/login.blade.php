<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">

    <x-auth-split
        title="Bem-vindo de volta"
        subtitle="Acesse sua área para continuar consultas, diagnósticos, prescrições e acompanhamento clínico."
        eyebrow="Acesso seguro"
    >
        @if (session('status'))
            <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-5 rounded-xl border border-red-100 bg-red-50 p-4" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-field">
                <label for="email">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="auth-input"
                    placeholder="seuemail@exemplo.com">
            </div>

            <div class="auth-field">
                <div class="flex items-center justify-between gap-3">
                    <label for="password" class="mb-0">Senha</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="auth-inline-link text-sm">Esqueceu?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="auth-input mt-2"
                    placeholder="••••••••">
            </div>

            <div class="auth-inline">
                <label for="remember" class="inline-flex cursor-pointer items-center gap-2 text-sm font-bold text-slate-600">
                    <input id="remember" type="checkbox" name="remember"
                        class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500"
                        @checked(old('remember'))>
                    Lembrar de mim
                </label>
            </div>

            <button type="submit" class="auth-action">
                Entrar no Clinicaly
            </button>
        </form>

        <div class="auth-link-row">
            Ainda não tem conta?
            <a href="{{ route('register') }}">Criar conta agora</a>
        </div>
    </x-auth-split>
</x-guest-layout>
