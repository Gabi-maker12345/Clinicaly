<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(109, 85, 177, 0.15);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(109, 85, 177, 0.2);
            border-color: #6d55b1;
        }
    </style>

    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-purple-50 px-4 py-10">
        <div class="w-full max-w-md">
            <div class="glass-card p-10 rounded-[35px]">
                
                <div class="flex flex-col items-center text-center mb-10">
                    <div class="mb-6">
                        <img src="{{ asset('Proosta-logo4.png') }}" alt="Clinicaly Logo" class="w-40 h-auto mx-auto">
                    </div>
                    <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Bem-vindo de volta!</h2>
                    <p class="text-gray-500 mt-2 font-medium">Sentimos sua falta no Clinicaly.</p>
                </div>

                <x-validation-errors class="mb-6 rounded-xl p-4 bg-red-50" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-1">
                        <label class="block text-sm font-bold text-gray-700 ml-1">E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 outline-none transition-all input-focus text-gray-700 font-medium" 
                            placeholder="seuemail@exemplo.com">
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between items-center ml-1">
                            <label class="text-sm font-bold text-gray-700">Senha</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#6d55b1] hover:underline">Esqueceu?</a>
                            @endif
                        </div>
                        <input type="password" name="password" required
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 outline-none transition-all input-focus text-gray-700 font-medium" 
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center ml-1">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-gray-300 text-[#6d55b1] focus:ring-[#6d55b1]">
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-600 cursor-pointer">Lembrar de mim</label>
                    </div>

                    <button type="submit" class="w-full bg-[#6d55b1] hover:bg-[#5a4496] text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-indigo-100 transform transition-all active:scale-[0.98] uppercase tracking-widest text-xs">
                        Entrar no Sistema
                    </button>
                </form>

                <div class="mt-10 text-center border-t border-gray-50 pt-8">
                    <p class="text-sm text-gray-500 font-medium">
                        Ainda não tem conta? 
                        <a href="{{ route('register') }}" class="text-[#6d55b1] font-bold hover:text-[#483875] transition-colors ml-1">Criar conta agora</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>