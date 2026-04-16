<x-app-layout>
    <style>
        /* Dark mode theme mapping */
        :root { --tx:#1a1530; --mu:#7c72a0; --sf:#fff; --sf2:#f8f6fd; --in:#6d55b1; --bg:#f4f2fb; }
        [data-theme="dark"] { --tx:#e8e2f5; --mu:#8a7faa; --sf:#161222; --sf2:#1e1830; --in:#8b72cc; --bg:#0d0b14; }
        h1, h2, h3, p { color: var(--tx) !important; }
        .text-slate-900, .text-slate-800 { color: var(--tx) !important; }
        .text-slate-700, .text-slate-600, .text-slate-500, .text-slate-400 { color: var(--mu) !important; }
        .bg-white, .bg-slate-50, .bg-slate-100 { background-color: var(--sf) !important; }
        .border-slate-100, .border-slate-200 { border-color: color-mix(in srgb, var(--tx) 10%, transparent) !important; }
        input, textarea, select { background-color: var(--sf2) !important; color: var(--tx) !important; }
    </style>
    {{-- x-data inicializa o filtro do Alpine.js --}}
    <main class="mt-12 max-w-5xl mx-auto px-6 pb-20" x-data="{ filter: 'all' }" style="background: var(--bg); color: var(--tx);".
        
        {{-- CABEÇALHO DA PÁGINA --}}
        <section class="mb-8">
            <header class="mb-4 flex justify-between items-center">
                <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-[#6d55b1] transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Dashboard
                </a>
            </header>
            <h1 class="text-3xl font-light text-slate-900">Fila de <span class="font-black text-[#6d55b1]">Validação</span></h1>
            <p class="text-slate-400 font-medium uppercase text-[10px] tracking-widest mt-1">
                Diagnósticos aguardando revisão médica
            </p>
        </section>

        {{-- ALERTA DE STATUS --}}
        <section class="mb-8">
            <div class="bg-amber-50 border border-amber-100 rounded-[30px] p-6 flex items-center gap-4">
                <div class="bg-amber-500 text-white w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-amber-200">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <p class="text-amber-900 font-medium text-sm">
                    <strong>{{ $counts['all'] }} diagnósticos</strong> aguardam revisão — ordenados por gravidade e data.
                </p>
            </div>
        </section>

        {{-- SEÇÃO DE FILTROS --}}
        <section class="bg-white rounded-[35px] p-8 border border-slate-100 shadow-sm mb-8">
            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filtrar
            </h2>
            <nav aria-label="Filtros de diagnóstico">
                <ul class="flex flex-wrap gap-3">
                    <li>
                        <button @click="filter = 'all'" 
                                :class="filter === 'all' ? 'bg-[#6d55b1] text-white shadow-lg shadow-purple-100' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-100'"
                                class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Todos ({{ $counts['all'] }})
                        </button>
                    </li>
                    <li>
                        <button @click="filter = 'critico'" 
                                :class="filter === 'critico' ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-100'"
                                class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Críticos ({{ $counts['critico'] }})
                        </button>
                    </li>
                    <li>
                        <button @click="filter = 'alto'" 
                                :class="filter === 'alto' ? 'bg-amber-500 text-white shadow-lg shadow-amber-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-100'"
                                class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Altos ({{ $counts['alto'] }})
                        </button>
                    </li>
                    <li>
                        <button @click="filter = 'medio_baixo'" 
                                :class="filter === 'medio_baixo' ? 'bg-blue-500 text-white shadow-lg shadow-blue-200' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-100'"
                                class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Médios/Baixos ({{ $counts['medio_baixo'] }})
                        </button>
                    </li>
                </ul>
            </nav>
        </section>

        {{-- LISTA DE DIAGNÓSTICOS --}}
        <section class="bg-white rounded-[35px] p-8 border border-slate-100 shadow-sm">
            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-list"></i> Por validar
            </h2>
            
            <div class="space-y-4">
                @forelse($diagnosticos as $diagnostico)
                    @php
                        // Configuração das Iniciais
                        $words = explode(' ', $diagnostico->paciente->name);
                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        
                        // Configuração de Estilo Baseado na Gravidade
                        $isCritico = $diagnostico->nivel_gravidade === 'critico';
                        
                        if ($diagnostico->nivel_gravidade === 'critico') {
                            $badgeStyle = 'bg-red-100 text-red-600';
                            $icon = 'fa-arrow-up';
                            $label = 'Crítico';
                            $rowStyle = 'bg-red-50/40 border-red-100';
                        } elseif ($diagnostico->nivel_gravidade === 'alto') {
                            $badgeStyle = 'bg-amber-100 text-amber-600';
                            $icon = 'fa-arrow-up';
                            $label = 'Alto';
                            $rowStyle = 'bg-slate-50 border-slate-100';
                        } else {
                            $badgeStyle = 'bg-blue-100 text-blue-600';
                            $icon = 'fa-minus';
                            $label = 'Médio/Baixo';
                            $rowStyle = 'bg-slate-50 border-slate-100';
                        }

                        // Pegar as duas principais doenças sugeridas
                        $doencasStr = collect($diagnostico->doencas_sugeridas)->take(2)->pluck('nome')->join(' · ');
                    @endphp

                    {{-- x-show e x-transition controlam a exibição baseada no filtro --}}
                    <article x-show="filter === 'all' || filter === '{{ $diagnostico->nivel_gravidade }}'" 
                             x-transition
                             class="p-5 border rounded-[25px] flex flex-col md:flex-row md:items-center justify-between gap-6 hover:border-[#6d55b1] transition-all {{ $rowStyle }}">
                        
                        <div class="flex items-center gap-4">
                            {{-- Avatar --}}
                            <div class="w-14 h-14 rounded-[20px] bg-white text-slate-700 flex items-center justify-center text-lg font-black border border-slate-200 uppercase shrink-0 shadow-sm">
                                {{ $initials }}
                            </div>
                            
                            {{-- Informações --}}
                            <div>
                                <p class="text-base font-bold text-slate-800">{{ $diagnostico->paciente->name }}</p>
                                <ul class="flex flex-wrap items-center gap-2 mt-2">
                                    <li>
                                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $badgeStyle }} flex items-center gap-1">
                                            <i class="fa-solid {{ $icon }}"></i> {{ $label }}
                                        </span>
                                    </li>
                                    <li>
                                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[9px] font-bold text-slate-600">
                                            {{ $doencasStr }}
                                        </span>
                                    </li>
                                    <li>
                                        <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1">
                                            <i class="fa-regular fa-clock"></i> {{ $diagnostico->created_at->diffForHumans() }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Ações --}}
                        <div class="flex items-center gap-3 shrink-0">
                            <form action="{{ route('messages.start', $diagnostico->id_paciente ?? $diagnostico->paciente->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:border-slate-300 transition-all" style="background: var(--sf); border-color: color-mix(in srgb, var(--tx) 10%, transparent); color: var(--mu);">
                                    <i class="fa-solid fa-message mr-1"></i> Chat
                                </button>
                            </form>
                            
                            @if($isCritico)
                                <a href="{{ route('diagnostico.validar', $diagnostico->id) }}" class="px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest bg-red-500 text-white shadow-md shadow-red-200 hover:bg-red-600 hover:scale-105 transition-all">
                                    Abrir Urgente
                                </a>
                            @else
                                <a href="{{ route('diagnostico.validar', $diagnostico->id) }}" class="px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest bg-[#6d55b1] text-white shadow-md shadow-purple-200 hover:bg-[#5b4694] hover:scale-105 transition-all">
                                    Abrir
                                </a>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center text-2xl mx-auto mb-4 border border-slate-100">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <p class="text-slate-500 font-bold text-sm">Fila zerada!</p>
                        <p class="text-slate-400 text-xs mt-1">Nenhum diagnóstico pendente no momento.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</x-app-layout>