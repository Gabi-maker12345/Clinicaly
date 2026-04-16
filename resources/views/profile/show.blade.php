<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        section { animation: fadeInUp 0.4s ease-out; }
        .space-y-6 > * { animation: fadeInUp 0.45s ease-out; }
    </style>
    <style>
        body { font-family: 'Dosis', sans-serif; }

        aside { background-color: var(--sf); border-right: 1px solid var(--bd); }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px;
            border-radius: 10px; color: var(--mu); font-weight: 500; transition: all 0.3s ease;
        }
        .sidebar-link:hover, .sidebar-link.active { background-color: var(--is); color: var(--in); }

        .topbar { display: flex; align-items: center; padding: 0 24px; height: 64px; background: var(--sf); border-bottom: 1px solid var(--bd); gap: 12px; }
        .logo { font-weight: 800; color: var(--in); text-decoration: none; letter-spacing: -0.5px; }
        .vid { font-weight: 600; color: var(--mu); border-left: 1.5px solid var(--bd); padding-left: 12px; font-size: 0.9rem; }

        .page { padding: 32px 40px; width: 100%; box-sizing: border-box; }
        .card { background: var(--sf); border-radius: 16px; padding: 28px 32px; border: 1px solid var(--bd); margin-bottom: 20px; }

        .av { display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--sf); flex-shrink: 0; }
        .av-gr { background: linear-gradient(135deg, var(--in), var(--il)); }
        .av-sm { width: 40px; height: 40px; font-size: 0.8rem; border-radius: 10px; }
        .av-lg { width: 80px; height: 80px; font-size: 1.5rem; border-radius: 50%; }

        .tg { padding: 5px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; white-space: nowrap; }
        .tg-gr  { background: var(--gb); color: var(--gr); }
        .tg-df  { background: var(--sf2); color: var(--tx); }
        .tg-pu  { background: var(--id); color: var(--in); }
        .tg-rd  { background: var(--rb); color: var(--rd); }
        .tg-am  { background: var(--wb); color: var(--wn); }

        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.88rem; cursor: pointer; border: none; transition: 0.2s; text-decoration: none; }
        .b-pr { background: var(--in); color: var(--sf); }
        .b-pr:hover { background: var(--il); }
        .b-gh { background: var(--sf2); color: var(--tx); border: 1px solid var(--bd); }
        .b-gh:hover { background: var(--bg); }
        .b-rd { background: var(--rb); color: var(--rd); }
        .btn-icon { padding: 10px 12px; }

        .fl { display: flex; flex-direction: column; gap: 6px; }
        .fl label { font-size: 0.75rem; font-weight: 700; color: var(--mu); text-transform: uppercase; letter-spacing: 0.05em; }
        .fl input, .fl select {
            padding: 11px 14px; border-radius: 10px; border: 1.5px solid var(--bd);
            outline: none; font-family: inherit; font-size: 0.95rem; background: var(--sf);
            transition: border-color 0.2s; color: var(--tx);
        }
        .fl input:focus, .fl select:focus { border-color: var(--in); }

        .tgsw { width: 42px; height: 24px; background: var(--in); border-radius: 20px; position: relative; border: none; cursor: pointer; flex-shrink: 0; }
        .tgsw::after { content: ''; position: absolute; width: 18px; height: 18px; background: var(--sf); border-radius: 50%; top: 3px; right: 3px; transition: 0.2s; }
        .tgsw.off { background: var(--bd); }
        .tgsw.off::after { right: auto; left: 3px; }

        .tgrow { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; border-bottom: 1px solid var(--bd); }
        .nm { font-weight: 700; font-size: 0.95rem; color: var(--tx); }
        .ds { font-size: 0.82rem; color: var(--mu); margin-top: 2px; }

        .patient-row {
            display: flex; align-items: center; justify-content: space-between;
            gap: 20px; padding: 18px 0;
            border-bottom: 1px solid var(--bd);
        }
        .patient-row:last-child { border-bottom: none; }

        .toast { position: fixed; bottom: 20px; right: 20px; background: var(--tx); color: var(--sf); padding: 12px 20px; border-radius: 12px; display: flex; align-items: center; gap: 10px; opacity: 0; transition: 0.3s; z-index: 100; }
        [x-cloak] { display: none !important; }
    </style>

    <?php
    // Preparar dados de prescrições com sintomas carregados
    $prescricaoDataArray = [];
    foreach ($minhasPrescricoes as $p) {
        $p->loadMissing(['diagnostico.doenca', 'diagnostico.paciente', 'monitorings']);
        $arr = $p->toArray();
        if ($p->diagnostico) {
            $arr['diagnostico']['sintomas'] = $p->diagnostico->sintomas()->toArray();
        }
        $prescricaoDataArray[$p->id] = $arr;
    }
    ?>

    <script>
        // Armazenar dados de prescrições em variável global para Alpine.js acessar
        window.prescricaoData = {!! json_encode($prescricaoDataArray) !!};
        
        function openPharmacyModal(medName) {
            document.getElementById('pharmacy-modal').style.display = 'flex';
            document.getElementById('pharmacy-med-name').textContent = medName;
            document.body.style.overflow = 'hidden';
        }
        
        function closePharmacyModal() {
            document.getElementById('pharmacy-modal').style.display = 'none';
            document.body.style.overflow = '';
        }
    </script>

    <div class="flex h-screen overflow-hidden" x-data="{ 
        tab: 'profile', 
        search: '', 
        previousTab: 'profile',
        selectedPrescription: null
    }">

        {{-- SIDEBAR --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 flex flex-col">
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <span class="block px-4 text-[10px] text-gray-400 uppercase font-bold mb-2">Menu Principal</span>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link">
                            <i class="fa-solid fa-chart-line"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <button @click="tab = 'profile'" :class="tab === 'profile' ? 'active' : ''" class="sidebar-link w-full text-left">
                            <i class="fa-solid fa-circle-user"></i> <span>Meu Perfil</span>
                        </button>
                    </li>
                    @if(Auth::user()->role !== 'pacient')
                    <li>
                        <button @click="tab = 'patients'" :class="tab === 'patients' ? 'active' : ''" class="sidebar-link w-full text-left">
                            <i class="fa-solid fa-notes-medical"></i> <span>Lista de Pacientes</span>
                        </button>
                    </li>
                    @else
                    <li>
                        <button @click="tab = 'diagnosticos'" :class="tab === 'diagnosticos' ? 'active' : ''" class="sidebar-link w-full text-left">
                            <i class="fa-solid fa-stethoscope"></i> <span>Meus Diagnósticos</span>
                        </button>
                    </li>
                    <li>
                        <button @click="tab = 'prescricoes'" :class="tab === 'prescricoes' ? 'active' : ''" class="sidebar-link w-full text-left">
                            <i class="fa-solid fa-file-medical"></i> <span>Minhas Prescrições</span>
                        </button>
                    </li>
                    @endif
                </ul>
            </nav>
            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn b-rd w-full justify-center">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                    </button>
                </form>
            </div>
        </aside>

        {{-- CONTEÚDO PRINCIPAL --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <main class="page overflow-y-auto">

                {{-- CABEÇALHO DINÂMICO --}}
                <header class="mb-8">
                    <div x-show="tab === 'profile'">
                        <h1 style="font-size:1.65rem;font-weight:800;margin-bottom:4px;">Perfil / Configurações</h1>
                        <p style="font-family:'Space Mono',monospace;font-size:.6rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);">
                            ID: #{{ Auth::user()->id }} — {{ Auth::user()->role ?? 'Médico' }}
                        </p>
                    </div>
                    <div x-show="tab === 'patients'" x-cloak>
                        <h1 style="font-size:1.65rem;font-weight:800;margin-bottom:4px;">Lista de Pacientes</h1>
                        <p style="font-family:'Space Mono',monospace;font-size:.6rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);">
                            · {{ $pacientes->count() }} pacientes ativos no sistema
                        </p>
                    </div>
                    <div x-show="tab === 'diagnosticos'" x-cloak>
                        <h1 style="font-size:1.65rem;font-weight:800;margin-bottom:4px;">Meus Diagnósticos</h1>
                        <p style="font-family:'Space Mono',monospace;font-size:.6rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);">
                            · {{ $meusDiagnosticos->count() }} diagnósticos no histórico
                        </p>
                    </div>
                    <div x-show="tab === 'prescricoes'" x-cloak>
                        <h1 style="font-size:1.65rem;font-weight:800;margin-bottom:4px;">Minhas Prescrições</h1>
                        <p style="font-family:'Space Mono',monospace;font-size:.6rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);">
                            · {{ $minhasPrescricoes->count() }} prescrições ativas
                        </p>
                    </div>
                    <div x-show="tab === 'prescription_detail'" x-cloak>
                        <h1 style="font-size:1.65rem;font-weight:800;margin-bottom:4px;">Detalhe da Prescrição</h1>
                        <p style="font-family:'Space Mono',monospace;font-size:.6rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);">
                            <span x-text="selectedPrescription?.diagnostico?.doenca?.name ? '· Diagnóstico: ' + selectedPrescription.diagnostico.doenca.name : '· Prescrição'"></span>
                        </p>
                    </div>
                </header>

                {{-- ===== PERFIL ===== --}}
                <div x-show="tab === 'profile'" x-transition>

                    {{-- Foto + Nome --}}
                    <article class="card">
                        <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data" class="flex items-center gap-6">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                            <div class="av av-gr" style="width:64px;height:64px;font-size:1.1rem;border-radius:50%;overflow:hidden;flex-shrink:0;">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_path)
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                                @else
                                    {{ collect(explode(' ', Auth::user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('') }}
                                @endif
                            </div>

                            <div>
                                <h2 style="font-size:1.15rem;font-weight:800;color:var(--tx);">{{ Auth::user()->name }}</h2>
                                <p style="color:var(--mu);font-size:0.9rem;margin-top:2px;">{{ Auth::user()->email }}</p>
                                <div class="flex gap-2 mt-3">
                                    <span class="tg tg-gr"><i class="fa-solid fa-check"></i> Conta Verificada</span>
                                    <span class="tg tg-df">Luanda, Angola</span>
                                </div>
                                @if ($errors->updateProfileInformation->has('photo'))
                                    <p class="text-red-500 text-[10px] mt-2 font-bold uppercase">{{ $errors->updateProfileInformation->first('photo') }}</p>
                                @endif
                            </div>

                            <label class="btn b-gh ml-auto self-start cursor-pointer">
                                <i class="fa-solid fa-camera"></i> Alterar foto
                                <input type="file" name="photo" class="hidden" onchange="this.form.submit()">
                            </label>
                        </form>
                    </article>

                    {{-- Dados Pessoais --}}
                    <article class="card">
                        <h2 class="font-bold mb-5 flex items-center gap-2" style="font-size:1rem;">
                            <i class="fa-solid fa-user" style="color:var(--pr);"></i> Dados pessoais
                        </h2>
                        <form method="POST" action="{{ route('user-profile-information.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="fl">
                                    <label>Nome completo</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}">
                                </div>
                                <div class="fl">
                                    <label>E-mail</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}">
                                </div>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button type="submit" class="btn b-pr">
                                    <i class="fa-solid fa-floppy-disk"></i> Guardar alterações
                                </button>
                            </div>
                        </form>
                    </article>

                    {{-- Notificações --}}
                    <article class="card">
                        <h2 class="font-bold mb-2 flex items-center gap-2" style="font-size:1rem;">
                            <i class="fa-solid fa-bell" style="color:var(--pr);"></i> Notificações
                        </h2>
                        <ul>
                            <li class="tgrow" style="border-bottom:none;">
                                <div>
                                    <p class="nm">Alertas de Saúde</p>
                                    <p class="ds">Receber avisos sobre diagnósticos críticos</p>
                                </div>
                                <button class="tgsw" onclick="this.classList.toggle('off')"></button>
                            </li>
                        </ul>
                    </article>

                    {{-- Segurança --}}
                    <article class="card">
                        <h2 class="font-bold mb-5 flex items-center gap-2" style="font-size:1rem;">
                            <i class="fa-solid fa-lock" style="color:var(--pr);"></i> Segurança
                        </h2>
                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                            @livewire('profile.update-password-form')
                        @endif
                    </article>
                </div>

                {{-- ===== LISTA DE PACIENTES ===== --}}
                <div x-show="tab === 'patients'" x-transition x-cloak>

                    {{-- Pesquisa --}}
                    <article class="card">
                        <div class="fl">
                            <label>Procurar por nome</label>
                            <div class="relative flex items-center">
                                <i class="fa-solid fa-magnifying-glass absolute" style="left:14px;font-size:14px;color:var(--mu);pointer-events:none;"></i>
                                <input type="text" x-model="search"
                                       placeholder="Digite o nome do paciente..."
                                       style="padding-left:42px;width:100%;box-sizing:border-box;">
                            </div>
                        </div>
                    </article>

                    {{-- Tabela de Pacientes --}}
                    <article class="card">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="font-bold flex items-center gap-2" style="font-size:1rem;">
                                <i class="fa-solid fa-users" style="color:var(--pr);"></i> Pacientes Registrados
                            </h2>
                            <span class="tg tg-pu">{{ $pacientes->count() }} total</span>
                        </div>

                        <div>
                            @forelse($pacientes as $diag)
                                @php
                                    $paciente    = $diag->paciente;
                                    $initials    = collect(explode(' ', $paciente->name ?? 'P'))
                                                    ->map(fn($n) => mb_substr($n, 0, 1))
                                                    ->take(2)->join('');
                                    $statusClass = match($diag->status_contexto) {
                                        'pendente_critico' => 'tg tg-rd',
                                        'pendente_alto'    => 'tg tg-am',
                                        'validado'         => 'tg tg-gr',
                                        default            => 'tg tg-df',
                                    };
                                    $statusLabel = str_replace('_', ' ', $diag->status_contexto);
                                @endphp

                                <div class="patient-row"
                                     x-show="search === '' || '{{ strtolower($paciente->name ?? '') }}'.includes(search.toLowerCase())">

                                    {{-- Avatar + Info --}}
                                    <div class="flex items-center gap-5" style="min-width:0;flex:1;">
                                        <div class="av av-gr" style="width:52px;height:52px;font-size:0.9rem;border-radius:12px;">
                                            {{ $initials }}
                                        </div>
                                        <div style="min-width:0;">
                                            <p style="font-weight:700;font-size:1rem;color:var(--tx);line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $paciente->name ?? 'Paciente Externo' }}
                                            </p>
                                            <p style="font-size:0.74rem;color:var(--mu);font-weight:600;text-transform:uppercase;letter-spacing:0.07em;margin-top:3px;">
                                                ID: #{{ $paciente->id ?? '?' }} &middot; Último: {{ $diag->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Ações --}}
                                    <div class="flex items-center gap-3" style="flex-shrink:0;">
                                        <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                                    <form action="{{ route('messages.start', $paciente->id) ?? 0 }}" method="POST" style="display: inline;">                                    
                                        @csrf
                                        <button type="submit" class="btn b-gh bsm w-full">
                                            <i class="fa-solid fa-message"></i> Chat
                                        </button>
                                    </form>

                                        @if(str_contains($diag->status_contexto, 'pendente'))
                                            <a href="{{ route('diagnostico.validar', $diag->id) }}" class="btn b-pr">
                                                <i class="fa-solid fa-circle-check" style="font-size:13px;"></i> Validar
                                            </a>
                                        @else
                                            <a href="{{ route('patients.history', $paciente->id ?? 0) }}" class="btn b-gh">
                                                <i class="fa-solid fa-clock-rotate-left" style="font-size:13px;"></i> Histórico
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            @empty
                                <div class="text-center py-16">
                                    <i class="fa-solid fa-user-slash" style="font-size:2.5rem;color:var(--bd);display:block;margin-bottom:12px;"></i>
                                    <p style="color:var(--mu);font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">
                                        Nenhum paciente encontrado
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </article>
                </div>

                {{-- ===== MEUS DIAGNÓSTICOS ===== --}}
                <div x-show="tab === 'diagnosticos'" x-transition x-cloak>
                    {{-- Resumo --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <article class="card" style="background: linear-gradient(135deg, var(--in), var(--il)); padding: 24px; border: none;">
                            <p style="font-size: 0.75rem; font-weight: 700; color: #dbeafe; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Total</p>
                            <p style="font-size: 2rem; font-weight: 800; color: var(--sf); line-height: 1;">{{ $meusDiagnosticos->count() }}</p>
                            <p style="font-size: 0.82rem; color: #dbeafe; margin-top: 4px;">Diagnósticos</p>
                        </article>
                        <article class="card" style="background: linear-gradient(135deg, var(--gr), var(--gb)); padding: 24px; border: none;">
                            <p style="font-size: 0.75rem; font-weight: 700; color: #166534; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Validados</p>
                            <p style="font-size: 2rem; font-weight: 800; color: #166534; line-height: 1;">{{ $meusDiagnosticos->where('status_contexto', 'validado')->count() }}</p>
                            <p style="font-size: 0.82rem; color: #166534; margin-top: 4px;">Confirmados</p>
                        </article>
                        <article class="card" style="background: linear-gradient(135deg, var(--rd), #fca5a5); padding: 24px; border: none;">
                            <p style="font-size: 0.75rem; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Pendentes</p>
                            <p style="font-size: 2rem; font-weight: 800; color: #991b1b; line-height: 1;">{{ $meusDiagnosticos->where('status_contexto', '!=', 'validado')->count() }}</p>
                            <p style="font-size: 0.82rem; color: #991b1b; margin-top: 4px;">Aguardando validação</p>
                        </article>
                    </div>

                    {{-- Lista de Diagnósticos --}}
                    <article class="card">
                        <h2 class="font-bold mb-6 flex items-center gap-2" style="font-size:1rem;">
                            <i class="fa-solid fa-timeline" style="color:var(--pr);"></i> Histórico Clínico
                        </h2>
                        @forelse($meusDiagnosticos as $diag)
                            @php
                                $statusClass = match($diag->status_contexto) {
                                    'pendente_critico' => 'tg tg-rd',
                                    'pendente_alto'    => 'tg tg-am',
                                    'validado'         => 'tg tg-gr',
                                    default            => 'tg tg-df',
                                };
                                $statusIcon = match($diag->status_contexto) {
                                    'pendente_critico' => 'fa-triangle-exclamation',
                                    'pendente_alto'    => 'fa-hourglass-half',
                                    'validado'         => 'fa-check-circle',
                                    default            => 'fa-clock',
                                };
                                $statusLabel = match($diag->status_contexto) {
                                    'pendente_critico' => 'Crítico',
                                    'pendente_alto'    => 'Alto',
                                    'validado'         => 'Validado',
                                    default            => 'Pendente',
                                };
                            @endphp

                            <div style="display: flex; gap: 20px; padding: 20px 0; border-bottom: 1px solid var(--sf); align-items: flex-start;">
                                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--in), var(--il)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--sf); flex-shrink: 0;">
                                    <i class="fa-solid {{ $statusIcon }}"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <p style="font-weight: 800; font-size: 1rem; color: var(--tx); margin-bottom: 4px;">
                                        {{ $diag->doenca->name ?? 'Diagnóstico #' . $diag->id }}
                                    </p>
                                    <p style="font-size: 0.82rem; color: var(--mu); margin-bottom: 8px;">
                                        <i class="fa-solid fa-calendar-days" style="margin-right: 4px;"></i>
                                        {{ $diag->created_at->format('d \d\e M \d\e Y \à\s H:i') }}
                                    </p>
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                                        @if(isset($diag->doencas_sugeridas[0]['probabilidade']))
                                            <span class="tg tg-df">Score: {{ $diag->doencas_sugeridas[0]['probabilidade'] }}%</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 2px; flex-shrink: 0;">
                                    @if($diag->status_contexto === 'validado' && $diag->prescricao)
                                        <button @click="previousTab = tab; selectedPrescription = window.prescricaoData[{{ $diag->prescricao->id }}]; tab = 'prescription_detail'" class="btn b-pr" style="padding: 8px 14px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-file-medical"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 40px 20px;">
                                <i class="fa-solid fa-inbox" style="font-size: 3rem; color: var(--sf); margin-bottom: 12px; display: block;"></i>
                                <p style="color: var(--mu); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">
                                    Nenhum diagnóstico registrado
                                </p>
                            </div>
                        @endforelse
                    </article>
                </div>

                {{-- ===== MINHAS PRESCRIÇÕES ===== --}}
                <div x-show="tab === 'prescricoes'" x-transition x-cloak>
                    <article class="card">
                        <h2 class="font-bold mb-6 flex items-center gap-2" style="font-size:1rem;">
                            <i class="fa-solid fa-pills" style="color:var(--pr);"></i> Prescrições Ativas
                        </h2>
                        @forelse($minhasPrescricoes as $prescricao)
                            <div style="display: flex; gap: 16px; padding: 16px; background: var(--bg); border: 1.5px solid var(--sf); border-radius: 12px; margin-bottom: 12px;">
                                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--in), var(--il)); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--sf); flex-shrink: 0;">
                                    <i class="fa-solid fa-prescription-bottle"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <p style="font-weight: 800; font-size: 0.95rem; color: var(--tx); margin-bottom: 2px;">
                                        {{ $prescricao->diagnostico->doenca->name ?? 'Prescrição #' . $prescricao->id }}
                                    </p>
                                    <p style="font-size: 0.75rem; color: var(--mu); margin-bottom: 6px;">
                                        Emitida em {{ $prescricao->created_at->format('d/m/Y') }}
                                    </p>
                                    <p style="font-size: 0.72rem; color: var(--mu);">
                                        Válida até {{ $prescricao->finish_date->format('d/m/Y') }}
                                        @if(\Carbon\Carbon::now()->lte($prescricao->finish_date))
                                            <span class="tg tg-gr" style="margin-left: 8px;"><i class="fa-solid fa-check-circle"></i> Ativa</span>
                                        @else
                                            <span class="tg tg-df" style="margin-left: 8px;"><i class="fa-solid fa-calendar-days"></i> Expirada</span>
                                        @endif
                                    </p>
                                </div>
                                <button @click="previousTab = 'prescricoes'; selectedPrescription = window.prescricaoData[{{ $prescricao->id }}]; tab = 'prescription_detail'" style="padding: 8px 14px; background: var(--in); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.75rem; font-weight: 600; white-space: nowrap; height: fit-content;">
                                    <i class="fa-solid fa-arrow-right" style="margin-right: 4px;"></i>Ver detalhes
                                </button>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 40px 20px;">
                                <i class="fa-solid fa-file-medical" style="font-size: 3rem; color: var(--sf); margin-bottom: 12px; display: block;"></i>
                                <p style="color: var(--mu); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">
                                    Nenhuma prescrição registrada
                                </p>
                            </div>
                        @endforelse
                    </article>
                </div>

                {{-- ===== PRESCRIPTION DETAIL TAB ===== --}}
                <div x-show="tab === 'prescription_detail'" x-transition x-cloak>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <button @click="tab = previousTab" style="background: var(--sf); border: none; cursor: pointer; color: var(--mu); font-size: 1.2rem; padding: 8px 10px; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.background='var(--sf)';" onmouseout="this.style.background='var(--sf)';">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </div>

                    <article style="background: var(--sf); border: 1px solid var(--bd); border-radius: 20px; padding: 20px; margin-bottom: 20px;">
                        {{-- Health Status Alert --}}
                        <div x-show="selectedPrescription" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; font-size: 0.84rem; font-weight: 500; border: 1px solid var(--gbd); background: var(--gb); color: var(--gr); margin-bottom: 20px;">
                            <i class="fa-solid fa-stethoscope" style="flex-shrink: 0;"></i>
                            <p>
                                Diagnóstico confirmado: <strong x-text="selectedPrescription?.diagnostico?.doenca?.name || 'N/A'"></strong> · 
                                <span x-text="(selectedPrescription?.diagnostico?.doencas_sugeridas?.[0]?.probabilidade || 0) + '%'"></span> · 
                                Validado em <span x-text="selectedPrescription?.diagnostico?.created_at ? new Date(selectedPrescription.diagnostico.created_at).toLocaleDateString('pt-BR') : '-'"></span>
                            </p>
                        </div>

                        {{-- Diagnostic Data --}}
                        <article style="background: var(--sf); border: 1px solid var(--bd); border-radius: 20px; padding: 20px; margin-bottom: 20px;">
                            <h3 style="font-family:'Space Mono', monospace; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--mu); display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid var(--bd); margin-bottom: 16px;">
                                <i class="fa-solid fa-microscope"></i> Dados do diagnóstico
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div style="background: var(--sf2); border: 1px solid var(--bd); border-radius: 12px; padding: 14px;">
                                    <p style="font-family:'Space Mono', monospace; font-size: 0.58rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--mu); margin-bottom: 6px;">Doença confirmada</p>
                                    <p style="font-weight: 800; font-size: 0.95rem; color: var(--tx);" x-text="selectedPrescription?.diagnostico?.doenca?.name || 'N/A'"></p>
                                </div>
                                <div style="background: var(--sf2); border: 1px solid var(--bd); border-radius: 12px; padding: 14px;">
                                    <p style="font-family:'Space Mono', monospace; font-size: 0.58rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--mu); margin-bottom: 6px;">Paciente</p>
                                    <p style="font-weight: 800; font-size: 0.95rem; color: var(--tx);" x-text="selectedPrescription?.diagnostico?.paciente?.name || 'N/A'"></p>
                                </div>
                                <div style="grid-column: 1 / -1; background: var(--sf2); border: 1px solid var(--bd); border-radius: 12px; padding: 14px;">
                                    <p style="font-family:'Space Mono', monospace; font-size: 0.58rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--mu); margin-bottom: 8px;">Sintomas reportados</p>
                                    <ul style="list-style: none; display: flex; flex-wrap: wrap; gap: 6px;">
                                        <template x-if="selectedPrescription?.diagnostico?.sintomas?.length">
                                            <template x-for="sintoma in selectedPrescription.diagnostico.sintomas.slice(0, 5)" :key="sintoma.id">
                                                <li style="padding: 4px 12px; background: var(--sf2); border: 1px solid var(--bd); border-radius: 20px; font-size: 0.75rem; font-weight: 700; color: var(--mu);" x-text="sintoma.name"></li>
                                            </template>
                                        </template>
                                        <template x-if="!selectedPrescription?.diagnostico?.sintomas?.length">
                                            <li style="color: var(--mu); font-size: 0.75rem;">Sem sintomas registrados</li>
                                        </template>
                                    </ul>
                                </div>
                                <div style="grid-column: 1 / -1; background: var(--sf2); border: 1px solid var(--bd); border-radius: 12px; padding: 14px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span style="font-family:'Space Mono', monospace; font-size: 0.58rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--mu);">Gravidade & Score</span>
                                        <span style="font-family:'Space Mono', monospace; font-size: 0.62rem; color: var(--mu);" x-text="(selectedPrescription?.diagnostico?.doencas_sugeridas?.[0]?.probabilidade || 0) + '%'"></span>
                                    </div>
                                    <div style="height: 6px; background: var(--id); border-radius: 4px; overflow: hidden;">
                                        <div :style="{ width: (selectedPrescription?.diagnostico?.doencas_sugeridas?.[0]?.probabilidade || 0) + '%' }" style="height: 100%; border-radius: 4px; background: linear-gradient(90deg, var(--in), var(--il));"></div>
                                    </div>
                                </div>
                            </div>
                        </article>

                        {{-- Medications --}}
                        <article style="background: var(--sf); border: 1px solid var(--bd); border-radius: 20px; padding: 20px; margin-bottom: 20px;">
                            <h3 style="font-family:'Space Mono', monospace; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--mu); display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid var(--bd); margin-bottom: 16px;">
                                <i class="fa-solid fa-pills"></i> Medicamentos prescritos
                            </h3>
                            <template x-if="selectedPrescription?.monitorings?.length">
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    <template x-for="med in selectedPrescription.monitorings" :key="med.id">
                                        <div style="display: flex; align-items: center; gap: 14px; padding: 16px; background: var(--sf2); border: 1.5px solid var(--bd); border-radius: 20px;">
                                            <div style="width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; background: var(--is); color: var(--in);">
                                                <i class="fa-solid fa-capsules"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <p style="font-size: 0.95rem; font-weight: 800; color: var(--tx); margin-bottom: 4px;" x-text="med.medication_name"></p>
                                                <p style="font-size: 0.75rem; color: var(--mu);">
                                                    <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                                                    A cada <span x-text="med.interval_hours"></span> horas · 
                                                    <i class="fa-solid fa-calendar" style="margin: 0 4px;"></i>Até <span x-text="selectedPrescription?.finish_date ? new Date(selectedPrescription.finish_date).toLocaleDateString('pt-BR') : '-'"></span>
                                                </p>
                                            </div>
                                            <div style="display: flex; gap: 8px; align-items: center; flex-shrink: 0;">
                                                <span style="padding: 4px 10px; background: var(--gb); color: var(--gr); border-radius: 20px; font-size: 0.7rem; font-weight: 700; border: 1px solid var(--gbd);" x-text="med.status === 'completed' ? 'Concluído' : med.status === 'active' ? 'Ativo' : 'Pendente'"></span>
                                                <button @click="openPharmacyModal(med.medication_name)" style="padding: 6px 12px; background: #fef3c7; color: #92400e; border: 1.5px solid #fcd34d; border-radius: 8px; cursor: pointer; font-size: 0.7rem; font-weight: 600; white-space: nowrap;">
                                                    <i class="fa-solid fa-location-dot" style="margin-right: 4px;"></i>Farmácias
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!selectedPrescription?.monitorings?.length">
                                <p style="color: var(--mu); font-size: 0.8rem; text-align: center; padding: 20px;">Nenhum medicamento registrado</p>
                            </template>
                        </article>

                        {{-- Validity Period --}}
                        <article style="background: var(--sf); border: 1px solid var(--bd); border-radius: 20px; padding: 20px; margin-bottom: 20px;">
                            <h3 style="font-family:'Space Mono', monospace; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--mu); display: flex; align-items: center; gap: 8px; padding-bottom: 12px; border-bottom: 1px solid var(--bd); margin-bottom: 16px;">
                                <i class="fa-solid fa-calendar-days"></i> Período de validade
                            </h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                                <div style="background: var(--sf2); border: 1px solid var(--bd); border-radius: 12px; padding: 14px;">
                                    <p style="font-family:'Space Mono', monospace; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--mu); margin-bottom: 6px;">Início</p>
                                    <p style="font-weight: 800; color: var(--tx); font-size: 0.95rem;" x-text="selectedPrescription?.diagnostico?.created_at ? new Date(selectedPrescription.diagnostico.created_at).toLocaleDateString('pt-BR') : '-'"></p>
                                </div>
                                <div style="background: var(--sf2); border: 1px solid var(--bd); border-radius: 12px; padding: 14px;">
                                    <p style="font-family:'Space Mono', monospace; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--mu); margin-bottom: 6px;">Término</p>
                                    <p style="font-weight: 800; color: var(--tx); font-size: 0.95rem;" x-text="selectedPrescription?.finish_date ? new Date(selectedPrescription.finish_date).toLocaleDateString('pt-BR') : '-'"></p>
                                </div>
                            </div>
                        </article>
                    </article>
                </div>

                {{-- PHARMACY MODAL --}}
                <div id="pharmacy-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.55); z-index: 200; align-items: center; justify-content: center; padding: 20px;" onclick="if(event.target === this) closePharmacyModal()">
                    <div style="background: var(--sf); border: 1px solid var(--bd); border-radius: 24px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25); width: 100%; max-width: 760px; overflow: hidden; display: flex; flex-direction: column;" onclick="event.stopPropagation()">
                        
                        {{-- Modal Header --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid var(--bd); flex-shrink: 0;">
                            <div>
                                <h2 style="font-size: 0.95rem; font-weight: 800; color: var(--tx); margin-bottom: 2px;">Farmácias próximas</h2>
                                <p style="font-family:'Space Mono', monospace; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--mu);"><span id="pharmacy-med-name">Medicamento</span></p>
                            </div>
                            <button onclick="closePharmacyModal()" style="background: none; border: none; cursor: pointer; color: var(--mu); font-size: 1.1rem; padding: 4px; border-radius: 50%; transition: all 0.2s;" onmouseover="this.style.background='var(--sf)'; this.style.color='var(--in)';" onmouseout="this.style.background='none'; this.style.color='var(--mu)';">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        {{-- Map Container --}}
                        <div style="padding: 22px; flex: 1; display: flex; align-items: center; justify-content: center; min-height: 320px; background: var(--sf2);">
                            <div style="text-align: center;">
                                <i class="fa-solid fa-map-location-dot" style="font-size: 3rem; color: var(--in); margin-bottom: 12px; opacity: 0.4;"></i>
                                <p style="font-family:'Space Mono', monospace; font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--mu);">Farmácias próximas...</p>
                                <p style="font-size: 0.8rem; color: var(--mu); margin-top: 8px; max-width: 300px;">Integre aqui um mapa de farmácias (Google Maps Embed API)</p>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 22px; border-top: 1px solid var(--bd); flex-shrink: 0; background: var(--sf2);">
                            <p style="font-size: 0.75rem; color: var(--mu);"><i class="fa-solid fa-circle-info" style="margin-right: 6px;"></i>Localização baseada no seu GPS</p>
                            <button onclick="closePharmacyModal()" style="padding: 8px 14px; background: transparent; color: var(--mu); border: 1.5px solid var(--bd); border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Fechar</button>
                        </div>
                    </div>
                </div>

                {{-- PHARMACY MODAL --}}
                <div id="pharmacy-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.55); z-index: 200; align-items: center; justify-content: center; padding: 20px;" onclick="if(event.target === this) closePharmacyModal()">
                    <div style="background: var(--sf); border: 1px solid var(--bd); border-radius: 24px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25); width: 100%; max-width: 760px; overflow: hidden; display: flex; flex-direction: column;" onclick="event.stopPropagation()">
                        
                        {{-- Modal Header --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid var(--bd); flex-shrink: 0;">
                            <div>
                                <h2 style="font-size: 0.95rem; font-weight: 800; color: var(--tx); margin-bottom: 2px;">Farmácias próximas</h2>
                                <p style="font-family:'Space Mono', monospace; font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--mu);"><span id="pharmacy-med-name">Medicamento</span></p>
                            </div>
                            <button onclick="closePharmacyModal()" style="background: none; border: none; cursor: pointer; color: var(--mu); font-size: 1.1rem; padding: 4px; border-radius: 50%; transition: all 0.2s;" onmouseover="this.style.background='var(--sf)'; this.style.color='var(--in)';" onmouseout="this.style.background='none'; this.style.color='var(--mu)';">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        {{-- Map Container --}}
                        <div style="padding: 22px; flex: 1; display: flex; align-items: center; justify-content: center; min-height: 320px; background: var(--sf2);">
                            <div style="text-align: center;">
                                <i class="fa-solid fa-map-location-dot" style="font-size: 3rem; color: var(--in); margin-bottom: 12px; opacity: 0.4;"></i>
                                <p style="font-family:'Space Mono', monospace; font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.12em; color: var(--mu);">Farmácias próximas...</p>
                                <p style="font-size: 0.8rem; color: var(--mu); margin-top: 8px; max-width: 300px;">Integre aqui um mapa de farmácias (Google Maps Embed API)</p>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 22px; border-top: 1px solid var(--bd); flex-shrink: 0; background: var(--sf2);">
                            <p style="font-size: 0.75rem; color: var(--mu);"><i class="fa-solid fa-circle-info" style="margin-right: 6px;"></i>Localização baseada no seu GPS</p>
                            <button onclick="closePharmacyModal()" style="padding: 8px 14px; background: transparent; color: var(--mu); border: 1.5px solid var(--bd); border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">Fechar</button>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <div class="toast" id="toast"><i class="fa-solid fa-check"></i> Alterações guardadas</div>

    <script>
        window.addEventListener('saved', () => {
            const t = document.getElementById('toast');
            t.style.opacity = '1';
            setTimeout(() => t.style.opacity = '0', 2500);
        });
    </script>
</x-app-layout>

