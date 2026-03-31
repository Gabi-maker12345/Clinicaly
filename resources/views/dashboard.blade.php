<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <div class="min-h-screen pb-20">
        
        <div class="sticky top-0 z-50 px-4 pt-4">
            <nav x-data="{ open: false }" class="floating-nav max-w-10xl mx-auto rounded-[24px] px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('Proosta-logo4.png') }}" alt="Logo" width="180px" class="w-32 md:w-44">
                </div>

                <div class="relative flex items-center gap-3">
                    <div class="hidden sm:block text-right">
                        <p class="text-[13px] font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] font-black text-[#6d55b1] uppercase tracking-[0.15em] mt-1">Conta Paciente</p>
                    </div>

                    <button @click="open = !open" @click.away="open = false" class="focus:outline-none group">
                        <div class="h-10 w-10 rounded-xl border-[3px] border-white shadow-sm overflow-hidden group-hover:border-indigo-50 transition-all">
                            <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover">
                        </div>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="absolute right-0 top-14 w-56 rounded-[22px] dropdown-glass py-2 overflow-hidden shadow-2xl">
                        
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-[#6d55b1]/5 hover:text-[#6d55b1] transition-all">
                            <i class="fa-regular fa-user-circle text-lg opacity-50"></i> Ver Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit" @click.prevent="$root.submit();" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-rose-500 hover:bg-rose-50 transition-all text-left">
                                <i class="fa-solid fa-arrow-right-from-bracket text-lg opacity-50"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

        <main class="mt-12 max-w-5xl mx-auto px-6">
            <header class="mb-10">
                <h1 class="text-3xl font-light text-slate-900">Olá, <span class="font-black text-[#6d55b1]">{{ Auth::user()->name }}</span></h1>
                <p class="text-slate-400 font-medium">O que deseja priorizar hoje?</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div class="md:col-span-2 lg:col-span-2 rounded-[35px] p-10 bg-[#6d55b1] text-white shadow-2xl shadow-indigo-200 card-hover card-clickable relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="bg-white/10 w-14 h-14 rounded-2xl flex items-center justify-center mb-8 backdrop-blur-md border border-white/20">
                            <i class="fa-solid fa-stethoscope text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold mb-3">Diagnóstico Rápido</h2>
                        <p class="text-indigo-100/90 text-sm leading-relaxed max-w-xs">Avalie seus sintomas em poucos minutos com nossa triagem inteligente.</p>
                    </div>
                    <div class="mt-10 inline-flex bg-white text-[#6d55b1] font-bold py-3 px-8 rounded-2xl text-xs uppercase tracking-widest self-start transition-transform group-hover:scale-105">
                        <a href="{{ route ('discovery.index') }}" class=" text-decoration-none">
                            Iniciar Avaliação
                        </a>
                    </div>
                    <i class="fa-solid fa-wave-square absolute -right-10 -bottom-10 text-[15rem] text-white/5 rotate-12"></i>
                </div>

                <a href="{{ route('chat.index') }}" class="bg-white rounded-[35px] p-8 text-center border border-slate-100 shadow-sm card-hover card-clickable flex flex-col items-center justify-center transition-all">
                    <div class="bg-blue-50 text-blue-500 w-16 h-16 rounded-[24px] flex items-center justify-center mb-4">
                        <i class="fa-solid fa-robot text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Chat Medicinal</h3>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Dúvidas rápidas com nossa IA especializada.</p>
                </a>

                <div class="bg-white rounded-[35px] p-8 flex flex-col items-center justify-center text-center border border-slate-100 shadow-sm card-hover card-clickable">
                    <div class="bg-purple-50 text-purple-600 w-16 h-16 rounded-[24px] flex items-center justify-center mb-4">
                        <i class="fa-solid fa-layer-group text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Flashcards</h3>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Revise termos e conceitos médicos.</p>
                </div>

                <div class="md:col-span-2 bg-white rounded-[35px] p-10 overflow-hidden relative group border border-slate-100 shadow-sm card-hover card-clickable">
                    <div class="relative z-10">
                        <h2 class="text-xl font-bold text-slate-800 mb-2 tracking-tight">Enciclopédia Médica</h2>
                        <p class="text-slate-500 text-sm max-w-xs leading-relaxed">Busque em milhares de doenças, sintomas e categorias (CID-11 & ANVISA).</p>
                        <div class="mt-8 flex gap-3">
                            <span class="px-5 py-2 bg-slate-50 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest border border-slate-100">CID-11</span>
                            <span class="px-5 py-2 bg-slate-50 rounded-xl text-[10px] font-bold text-slate-500 uppercase tracking-widest border border-slate-100">ANVISA</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-book-medical absolute -right-4 -bottom-4 text-[10rem] text-slate-50 group-hover:text-slate-100/80 transition-colors"></i>
                </div>

                <div class="md:col-span-1 lg:col-span-2 bg-white rounded-[35px] p-10 flex flex-col md:flex-row gap-8 items-center border border-slate-100 shadow-sm card-hover card-clickable">
                    <div class="bg-orange-50 text-orange-600 p-7 rounded-[28px] shadow-inner">
                        <i class="fa-solid fa-file-prescription text-4xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Minhas Receitas</h2>
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">Acompanhamento e nuances do seu tratamento atual.</p>
                        <div class="mt-4 text-orange-600 font-black text-xs uppercase tracking-widest">Ver detalhes →</div>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-[35px] p-8 text-white border-none flex flex-col justify-between shadow-2xl shadow-slate-200 card-hover card-clickable">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-bell text-xl text-emerald-400"></i>
                        </div>
                        <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-3 py-1.5 rounded-full font-black uppercase tracking-widest">2 ativos</span>
                    </div>
                    <div class="mt-8">
                        <h3 class="font-bold text-xl leading-none tracking-tight">Lembretes</h3>
                        <p class="text-xs text-slate-400 mt-2 font-medium">Próxima dose em 45min.</p>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>