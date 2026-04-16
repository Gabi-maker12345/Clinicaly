<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        section { animation: fadeInUp 0.4s ease-out; }
        section:nth-child(n+2) { animation-delay: 0.1s; }
        section:nth-child(n+3) { animation-delay: 0.2s; }
        div[style*="background: var(--sf)"] { animation: fadeInUp 0.45s ease-out; }
    </style>
    
    <main class="mt-12 max-w-5xl mx-auto px-6 pb-20">
        <section class="mb-10">
            <h1 style="color: var(--tx);" class="text-3xl font-light">Bom dia, Dr. <span class="font-black" style="color: var(--in);">{{ explode(' ', $user->name)[0] }}</span></h1>
            <p style="color: var(--mu);" class="font-medium">Confira as atualizações da sua fila clínica hoje.</p>
        </section>

        {{-- OS 3 CARDS DE CIMA --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Card 1: Casos Pendentes --}}
            <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-8 shadow-sm flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4">
                    <span class="flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                    </span>
                </div>
                <p class="text-4xl font-black text-amber-500 mb-2">{{ $stats['pendentes'] }}</p>
                <p class="font-bold">Casos Pendentes</p>
                <p style="color: var(--mu);" class="text-xs mt-1">Aguardando validação</p>
            </div>

            {{-- Card 2: Validados --}}
            <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-8 shadow-sm flex flex-col items-center text-center">
                <p class="text-4xl font-black text-emerald-500 mb-2">{{ $stats['validados'] }}</p>
                <p class="font-bold">Validados este mês</p>
                <p style="color: var(--mu);" class="text-xs mt-1">Produtividade clínica</p>
            </div>

            {{-- Card 3: Pacientes Ativos --}}
            <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-8 shadow-sm flex flex-col items-center text-center">
                <p class="text-4xl font-black text-blue-500 mb-2">{{ $stats['pacientes'] }}</p>
                <p class="font-bold">Pacientes ativos</p>
                <p style="color: var(--mu);" class="text-xs mt-1">Base Clinicaly</p>
            </div>
        </section>

        {{-- ALERTA CRÍTICO (CONDICIONAL) --}}
        @if($stats['pendentes'] > 0)
        <section class="mb-8">
            <div style="background: var(--rb); border: 1px solid var(--rbd); color: var(--rd);" class="rounded-[30px] p-6 flex items-center gap-4">
                <div class="bg-red-500 text-white w-10 h-10 rounded-2xl flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <p class="font-medium text-sm">Atenção médico: Você possui diagnósticos que requerem revisão imediata.</p>
                <a href="{{ route('diagnostico.fila') }}" class="ml-auto bg-red-600 text-white px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest">Ver Fila</a>
            </div>
        </section>
        @endif

        {{-- LISTA DE MOVIMENTAÇÕES MAIS RECENTES --}}
        <section style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="rounded-[35px] p-10 shadow-sm mb-8">
            <h2 style="color: var(--mu);" class="text-[10px] font-black uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i> Movimentações Recentes
            </h2>
            
            <div style="border-color: var(--bd);" class="divide-y">
                @forelse($recentes as $diag)
                <div class="py-6 flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div style="background: var(--is); color: var(--in);" class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-lg">
                            {{ substr($diag->paciente->name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold">{{ $diag->paciente->name ?? 'Paciente' }}</p>
                            <p style="color: var(--mu);" class="text-sm">{{ $diag->sintomas_preview }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div class="text-right hidden md:block">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $diag->status == 'pendente' ? 'text-amber-500' : 'text-emerald-500' }}">
                                {{ $diag->status }}
                            </span>
                            <p style="color: var(--mu);" class="text-[10px] font-bold uppercase mt-1">{{ $diag->updated_at->diffForHumans() }}</p>
                        </div>

                        <a href="{{ $diag->status === 'pendente' ? route('diagnostico.validar', $diag->id) : route('patients.history', $diag->id_paciente) }}" class="bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg shadow-indigo-200">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
                @empty
                <p style="color: var(--mu);" class="text-center py-10 italic">Nenhuma atividade recente encontrada.</p>
                @endforelse
            </div>
        </section>

        {{-- BOTÕES DE ATALHO --}}
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('discovery.index') }}" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i class="fa-solid fa-plus-circle text-indigo-600 text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Novo Exame</span>
            </a>
            <a href="{{ route('chat.index') }}" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i style="color: var(--mu);" class="fa-solid fa-robot text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Chat IA</span>
            </a>
            <a href="{{ route('messages.index') }}" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i style="color: var(--mu);" class="fa-solid fa-comment-dots text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Mensagens</span>
            </a>
            <a href="{{ route('profile.show') }}" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-6 rounded-[30px] shadow-sm hover:border-indigo-500 transition-all flex flex-col items-center gap-3">
                <i style="color: var(--mu);" class="fa-solid fa-user-gear text-xl"></i>
                <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">Perfil</span>
            </a>
        </section>
    </main>
</x-app-layout>