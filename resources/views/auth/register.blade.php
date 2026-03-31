<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-tr from-purple-50 via-white to-blue-50 px-4 py-12">
        <div class="w-full max-w-lg">
            <div class="bg-white p-10 rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] border border-gray-100">
 
                <div class="flex flex-col items-center text-center mb-10">
                    <div class="mb-6">
                        <img src="{{ asset('Proosta-logo4.png') }}" alt="Clinicaly Logo" class="w-40 h-auto mx-auto">
                    </div>
                    <h2 class="text-3xl font-black text-gray-900">Junte-se à nós</h2>
                    <p class="text-gray-500 mt-2">Sua jornada para uma saúde monitorada começa aqui.</p>>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 gap-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1 ml-1">Nome Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-[#4840A3] transition-all" 
                            placeholder="Como quer ser chamado?">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1 ml-1">E-mail Profissional/Pessoal</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-[#4840A3] transition-all" 
                            placeholder="exemplo@clinicaly.com">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1 ml-1">Senha</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-[#4840A3] transition-all" 
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1 ml-1">Confirmar</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-[#4840A3] transition-all" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#4840A3] hover:bg-[#3730a3] text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-100 mt-4 transition-all hover:-translate-y-0.5 active:translate-y-0">
                        Finalizar Cadastro
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                    <p class="text-sm text-gray-500">
                        Já tem uma conta no Clinicaly? 
                        <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Fazer Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>