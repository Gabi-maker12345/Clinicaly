<x-app-layout>
    <style>
        /* Dark mode theme mapping */
        :root { --tx:#1a1530; --mu:#7c72a0; --fa:#b0a8cc; --sf:#fff; --sf2:#f8f6fd; --in:#6d55b1; --bg:#f4f2fb; }
        [data-theme="dark"] { --tx:#e8e2f5; --mu:#8a7faa; --fa:#4a4268; --sf:#161222; --sf2:#1e1830; --in:#8b72cc; --bg:#0d0b14; }
        
        /* Global dark mode support */
        h1, h2, h3, p { color: var(--tx) !important; }
        .text-slate-900 { color: var(--tx) !important; }
        .text-slate-800 { color: var(--tx) !important; }
        .text-slate-600 { color: var(--mu) !important; }
        .text-slate-500 { color: var(--mu) !important; }
        .text-slate-400 { color: var(--mu) !important; }
        .bg-white, .bg-slate-100, .bg-slate-50 { background-color: var(--sf) !important; }
        .border-slate-100, .border-slate-200 { border-color: color-mix(in srgb, var(--tx) 10%, transparent) !important; }
        .focus:ring-slate-300 { --tw-ring-color: rgba(109, 85, 177, 0.3) !important; }
        
        /* Inputs and selects */
        input, textarea, select { 
            background-color: var(--sf2) !important; 
            color: var(--tx) !important; 
            border-color: color-mix(in srgb, var(--tx) 5%, transparent) !important; 
        }
        input::placeholder, textarea::placeholder { color: var(--mu) !important; opacity: 0.7; }
        
        .dark-mode-compatible { background-color: var(--sf); color: var(--tx); }
        .dark-mode-text { color: var(--tx); }
        .dark-mode-border { border-color: color-mix(in srgb, var(--tx) 10%, transparent); }
        .dark-mode-secondary { background-color: var(--sf2); color: var(--mu); }
    </style>
    <main class="mt-12 max-w-5xl mx-auto px-6 pb-20" x-data="{ showRejectModal: false }" style="background: var(--bg); color: var(--tx);">
        <section class="mb-8">
            <header class="mb-4 flex justify-between items-center">
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-600 border border-emerald-200">Revisão Clínica</span>
                <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-[#6d55b1] transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Voltar para fila
                </a>
            </header>
            <h1 class="text-3xl font-light text-slate-900">Validar <span class="font-black text-[#6d55b1]">Diagnóstico</span></h1>
            <p class="text-slate-400 font-medium uppercase text-[10px] tracking-widest mt-1">
                {{ $diagnostico->paciente->name }} · {{ $diagnostico->created_at->format('d M Y') }}
            </p>
        </section>

        @php
            $topProb = $diagnostico->doencas_sugeridas[0]['probabilidade'] ?? 0;
        @endphp

        @if($topProb > 80)
        <section class="mb-8">
            <div class="bg-red-50 border border-red-100 rounded-[30px] p-6 flex items-center gap-4" style="background: rgba(220,38,38,0.1); border-color: rgba(220,38,38,0.3);">
                <div class="bg-red-500 text-white w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-red-200">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <p class="text-red-900 font-medium text-sm">Gravidade classificada como <strong>CRÍTICA ({{ $topProb }}%)</strong> — reveja os dados antes de confirmar.</p>
            </div>
        </section>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- DADOS DO PACIENTE --}}
            <article class="rounded-[35px] p-8 border shadow-sm dark-mode-compatible" style="background: var(--sf); border-color: color-mix(in srgb, var(--tx) 10%, transparent);">
                <h2 class="text-[10px] font-black uppercase tracking-[0.2em] mb-6 flex items-center gap-2" style="color: var(--mu);">
                    <i class="fa-solid fa-user"></i> Perfil do Paciente
                </h2>
                <div class="flex items-center gap-4 mb-8">
                    @php
                        $words = explode(' ', $diagnostico->paciente->name);
                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <div class="w-16 h-16 rounded-[22px] bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-black border border-blue-100 uppercase">
                        {{ $initials }}
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-800">{{ $diagnostico->paciente->name }}</p>
                        <p class="text-xs text-slate-400 font-medium">
                            {{ $diagnostico->dados_biometricos['idade'] ?? '--' }} anos · 
                            {{ ($diagnostico->dados_biometricos['genero'] ?? '') == 'm' ? 'Masculino' : 'Feminino' }} · 
                            {{ $diagnostico->dados_biometricos['peso'] ?? '--' }} kg
                        </p>
                    </div>
                </div>

                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Sintomas Reportados</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($symptoms as $s)
                    <span class="px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-bold text-slate-600 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle text-[4px] text-blue-400"></i> {{ $s->name }}
                    </span>
                    @endforeach
                </div>
            </article>

            {{-- SCORES DA IA --}}
            <article class=\"rounded-[35px] p-8 border shadow-sm dark-mode-compatible\" style=\"background: var(--sf); border-color: color-mix(in srgb, var(--tx) 10%, transparent);\">
                <h2 class=\"text-[10px] font-black uppercase tracking-[0.2em] mb-6 flex items-center gap-2\" style=\"color: var(--mu);\">
                    <i class="fa-solid fa-robot"></i> Análise Probabilística
                </h2>
                <div class="space-y-6">
                    @foreach($diagnostico->doencas_sugeridas as $sugestao)
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <p class="text-sm font-bold text-slate-800">{{ $sugestao['nome'] }}</p>
                            <p class="text-xs font-black text-[#6d55b1]">{{ $sugestao['probabilidade'] }}%</p>
                        </div>
                        <div class="w-full h-2 bg-slate-50 rounded-full overflow-hidden">
                            <div class="h-full bg-[#6d55b1] transition-all duration-1000" 
                                 style="width: {{ $sugestao['probabilidade'] }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </article>
        </div>

        {{-- FONTES DO GEMINI --}}
        <section class=\"rounded-[35px] p-8 border shadow-sm mb-8 dark-mode-compatible\" style=\"background: var(--sf); border-color: color-mix(in srgb, var(--tx) 10%, transparent);\">
            <h2 class=\"text-[10px] font-black uppercase tracking-[0.2em] mb-6 flex items-center gap-2\" style=\"color: var(--mu);\">
                <i class="fa-solid fa-book-medical"></i> Referências Médicas (IA Gemini)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($diagnostico->links_referencia ?? [] as $link)
                <a href="{{ $link['url'] }}" target="_blank" class="p-5 rounded-[25px] bg-slate-50 border border-slate-100 hover:border-[#6d55b1] transition-all group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[10px] font-black uppercase text-[#6d55b1]">{{ $link['fonte'] }}</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-300 group-hover:text-[#6d55b1]"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-800 line-clamp-2 leading-relaxed">{{ $link['resumo'] }}</p>
                </a>
                @empty
                <p class="text-xs text-slate-400 italic">Nenhuma referência gerada para este caso.</p>
                @endforelse
            </div>
        </section>

        {{-- FORMULÁRIO DE DECISÃO --}}
        <form action="{{ route('diagnostico.confirmar', $diagnostico->id) }}" method="POST">
            @csrf
            <article class=\"rounded-[35px] p-8 border shadow-sm mb-8 dark-mode-compatible\" style=\"background: var(--sf); border-color: color-mix(in srgb, var(--tx) 10%, transparent);\">
                <h2 class=\"text-[10px] font-black uppercase tracking-[0.2em] mb-6 flex items-center gap-2\" style=\"color: var(--mu);\">
                    <i class="fa-solid fa-stethoscope"></i> Parecer Médico
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block tracking-widest">Confirmação de Doença</label>
                        <select name="doenca_confirmada" required class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-[#6d55b1]">
                            @foreach($diagnostico->doencas_sugeridas as $sug)
                                <option value="{{ $sug['nome'] }}">{{ $sug['nome'] }}</option>
                            @endforeach
                            <option value="Outra">Outra (Especificar em notas)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block tracking-widest">Gravidade Real</label>
                        <select name="gravidade_real" required class="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-[#6d55b1]">
                            <option value="Baixa">Baixa</option>
                            <option value="Média">Média</option>
                            <option value="Alta">Alta</option>
                            <option value="Crítica" selected>Crítica</option>
                        </select>
                    </div>
                </div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block tracking-widest">Observações Clínicas</label>
                <textarea name="observacoes" rows="4" class="w-full bg-slate-50 border-none rounded-[25px] p-5 text-sm font-medium focus:ring-2 focus:ring-[#6d55b1]" placeholder="Digite aqui o seu parecer técnico..."></textarea>
            </article>

            <div class="flex flex-wrap gap-4 justify-end">
                {{-- Botão que abre o modal de rejeição --}}
                <button type="button" @click="showRejectModal = true" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100 hover:bg-red-600 hover:text-white transition-all">
                    Rejeitar Caso
                </button>
                
                <form action="{{ route('messages.start', $diagnostico->id_paciente) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all dark-mode-secondary" style="background: var(--sf2); color: var(--mu);">
                        Contactar Paciente
                    </button>
                </form>

                <button type="submit" class="px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-[#6d55b1] text-white shadow-lg shadow-purple-100 hover:scale-105 transition-transform">
                    Validar e Emitir Prescrição
                </button>
            </div>
        </form>

        {{-- MODAL DE REJEIÇÃO (DELETE) --}}
        <template x-if="showRejectModal">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
                <div @click.away="showRejectModal = false" class="rounded-[40px] p-10 max-w-md w-full shadow-2xl dark-mode-compatible" style="background: var(--sf);">
                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Rejeitar Diagnóstico?</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8">
                        Você está prestes a excluir permanentemente este diagnóstico da fila. Esta ação não pode ser desfeita.
                    </p>
                    
                    <div class="flex gap-3">
                        <button @click="showRejectModal = false" class="flex-1 px-6 py-4 rounded-2xl bg-slate-100 text-slate-600 font-bold uppercase text-[10px] tracking-widest hover:bg-slate-200 transition-all">
                            Cancelar
                        </button>
                        
                        <form action="{{ route('diagnostico.rejeitar', $diagnostico->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-6 py-4 rounded-2xl bg-red-600 text-white font-bold uppercase text-[10px] tracking-widest shadow-lg shadow-red-200 hover:bg-red-700 transition-all">
                                Confirmar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </main>
</x-app-layout>