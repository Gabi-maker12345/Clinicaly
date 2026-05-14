<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        section { animation: fadeInUp 0.4s ease-out; }
        article { animation: fadeInUp 0.45s ease-out; }
    </style>
    <style>
        .page { width:100%; max-width:none; margin:0; padding:32px 36px 80px; }
        .history-shell{width:min(1280px,100%);margin:0 auto}
        .history-grid{display:grid;grid-template-columns:minmax(280px,.34fr) minmax(0,1fr);gap:18px;align-items:start}
        .ph { display: flex; flex-direction:column; align-items:stretch; gap: 18px; padding: 22px; background: var(--sf); border: 1px solid var(--bd); border-radius: var(--r); box-shadow: var(--sh); margin-bottom: 14px; overflow:hidden; }
        .profile-row{display:flex;align-items:center;gap:14px;min-width:0}
        .profile-row .flex-1{min-width:0}
        .profile-row h2,.profile-row p{overflow-wrap:anywhere}
        .av-lg { width: 68px; height: 68px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.3rem; }
        .tg { display: inline-flex; align-items: center; gap: 4px; font-size: .7rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; border: 1px solid; }
        .sc { border-radius: var(--r); padding: 18px 20px; background: var(--sf); border: 1px solid var(--bd); }
        .sc .v { font-size: 2rem; font-weight: 800; line-height: 1; }
        .tli { display: flex; gap: 16px; padding-bottom: 22px; position: relative; }
        .tli::before { content:''; position: absolute; left: 11px; top: 26px; bottom: 0; width: 1.5px; background: var(--bd); }
        .tli:last-child::before { display: none; }
        .tld { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .6rem; z-index: 1; border: 2px solid; background: #fff; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 30px; font-weight: 700; font-size: .85rem; transition: 0.2s; border: none; cursor: pointer; text-decoration: none; }
        .b-pr { background: var(--in); color: #fff; }
        .b-gr { background: var(--gb); color: var(--gr); border: 1.5px solid var(--gbd); }
        .b-gh { background: transparent; border: 1px solid var(--bd); color: var(--mu); }
        .bsm { padding: 4px 12px; font-size: .75rem; }
        @media(max-width:980px){.page{padding:24px 16px 60px}.history-grid{grid-template-columns:1fr}}
    </style>

    <div class="page">
    <div class="history-shell">
        <section class="mb-6">
            <header class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold mt-2" style="color: var(--tx);">Histórico do Paciente</h1>
                </div>
                <a href="{{ route('dashboard') }}" class="btn b-gh"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
            </header>
        </section>

        <div class="history-grid">
        <aside>
            <article class="ph">
                <div class="profile-row">
                    <div class="av-lg bg-indigo-100 text-indigo-600 border-2 border-indigo-200">
                        {{ substr($patient->name, 0, 2) }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold" style="color: var(--tx);">{{ $patient->name }}</h2>
                        <p class="text-xs font-mono uppercase tracking-tighter" style="color: var(--mu);">ID: {{ $patient->id }} · {{ $patient->email }}</p>
                        <div class="flex gap-2 mt-3 flex-wrap">
                            <span class="tg border-blue-200 text-blue-600 bg-blue-50">{{ $stats['total_diagnostics'] }} Diagnósticos</span>
                            <span class="tg border-emerald-200 text-emerald-600 bg-emerald-50">Adesão {{ $stats['adherence_rate'] }}%</span>
                        </div>
                    </div>
                </div>
                <nav class="flex flex-col gap-2 items-stretch w-full">
                    <form action="{{ route('messages.start', $patient->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn b-gh bsm w-full">
                            <i class="fa-solid fa-message"></i> Chat
                        </button>
                    </form>
                </nav>
            </article>

            <div class="grid grid-cols-1 gap-3 mb-6">
                <article class="sc"><p class="v text-blue-600">{{ $stats['total_diagnostics'] }}</p><p class="text-xs text-gray-500 font-bold uppercase">Diagnósticos</p></article>
                <article class="sc"><p class="v text-emerald-600">{{ $stats['total_prescriptions'] }}</p><p class="text-xs text-gray-500 font-bold uppercase">Prescrições</p></article>
                <article class="sc"><p class="v text-purple-600">{{ $stats['adherence_rate'] }}%</p><p class="text-xs text-gray-500 font-bold uppercase">Adesão</p></article>
            </div>
        </aside>

        <article style="background: var(--sf); border: 1px solid var(--bd); min-height:620px;" class="rounded-2xl p-6 shadow-sm">
            <h2 class="flex items-center gap-2 text-xs font-mono font-bold uppercase tracking-widest pb-4 mb-6" style="color: var(--mu); border-bottom: 1px solid var(--bd);">
                <i class="fa-solid fa-timeline"></i> Linha do Tempo Clínica
            </h2>

            <ol>
                @forelse($timeline as $item)
                    <li class="tli">
                        @php
                            $isPending = $item['status'] === 'pending' || $item['status'] === null;
                        @endphp
                        
                        <span class="tld {{ $isPending ? 'border-red-500 text-red-500' : 'border-emerald-500 text-emerald-500' }}">
                            <i class="fa-solid {{ $isPending ? 'fa-triangle-exclamation' : 'fa-check' }}"></i>
                        </span>

                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-[10px] font-mono uppercase" style="color: var(--mu);">{{ $item['date']->translatedFormat('d M Y · H:i') }}</p>
                                    <p class="font-bold" style="color: var(--tx);">{{ $item['title'] }}</p>
                                    <p class="text-sm" style="color: var(--mu);">Score: {{ $item['score'] }}% · Gravidade: {{ $item['severity'] }}</p>
                                </div>
                                
                                @if(!$isPending)
                                    {{-- Passando o ID do diagnóstico corretamente para a rota --}}
                                    <a href="{{ route('prescriptions.create', $item['id']) }}" class="btn b-gh bsm">
                                        <i class="fa-solid fa-pills"></i> Prescrever
                                    </a>
                                @endif
                            </div>
                            
                            @if($isPending)
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ route('diagnostico.validar', $item['id']) }}" class="px-3 py-1 bg-red-600 text-white text-[10px] font-bold rounded-full uppercase">
                                        Validar Urgente
                                    </a>
                                </div>
                            @else
                                {{-- Aqui usamos o relacionamento 'medico' que definimos acima --}}
                                <p class="text-[10px] text-emerald-600 font-bold mt-1 uppercase">
                                    Validado por: {{ $item['doctor'] }}
                                </p>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="text-center py-12">
                        <p class="font-mono text-xs" style="color: var(--mu);">Nenhum registro clínico encontrado.</p>
                    </li>
                @endforelse
            </ol>
        </article>
        </div>
    </div>
    </div>
</x-app-layout>
