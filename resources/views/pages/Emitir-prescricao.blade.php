<x-app-layout>
    @push('styles')
    {{-- Mantendo exatamente o seu bloco de estilos original --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
    
    /* Forçando o fundo e o reset para não herdar o preto do layout original */
    .clinicaly-scope {
        background-color: var(--bg) !important;
        min-height: 100vh;
        color: var(--tx);
        font-family: 'Dosis', sans-serif;
    }

    [data-theme=dark] .clinicaly-scope, .dark .clinicaly-scope {
        --in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);
    }
    
    .page{max-width:900px;margin:0 auto;padding:28px 20px 80px;}
    @keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
    .fi{animation:fi .3s ease both;}.fi1{animation-delay:.05s;}.fi2{animation-delay:.1s;}.fi3{animation-delay:.15s;}
    
    .rb{font-family:'Space Mono',monospace;font-size:.55rem;padding:4px 12px;border-radius:20px;letter-spacing:.08em;text-transform:uppercase;font-weight:700;display:inline-block;}
    .rb-gr{background:var(--gb);color:var(--gr);border:1.5px solid var(--gbd);}
    .card{background:var(--sf);border:1px solid var(--bd);border-radius:var(--r);padding:20px;box-shadow:var(--sh);color:var(--tx);}
    .ct{font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);display:flex;align-items:center;gap:8px;padding-bottom:12px;border-bottom:1px solid var(--bd);margin-bottom:14px;}
    .al{display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:var(--rs);font-size:.84rem;font-weight:500;border:1px solid;}
    .al-gr{background:var(--gb);border-color:var(--gbd);color:var(--gr);}
    .av{display:flex;align-items:center;justify-content:center;font-weight:700;border-radius:50%;flex-shrink:0;}
    .av-gr{background:var(--gb);color:var(--gr);border:1.5px solid var(--gbd);}
    .av-bl{background:var(--bb);color:var(--bl);border:1.5px solid var(--bbd);}
    .av-sm{width:36px;height:36px;font-size:.78rem;}
    
    .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:30px;border:none;font-family:'Dosis',sans-serif;font-size:.88rem;font-weight:700;cursor:pointer;transition:all .2s;white-space:nowrap;justify-content:center;}
    .bsm{padding:6px 14px;font-size:.8rem;}
    .b-pr{background:var(--in);color:#fff;}
    .b-pr:hover{background:var(--il);}
    .b-gh{background:transparent;color:var(--mu);border:1.5px solid var(--bd);}
    .b-gh:hover{border-color:var(--in);color:var(--in);background:var(--is);}
    .b-rd{background:var(--rb);color:var(--rd);border:1.5px solid var(--rbd);}
    .b-rd:hover{background:var(--rd);color:#fff;}
    
    .fl{display:flex;flex-direction:column;gap:6px;}.fl+.fl{margin-top:12px;}
    .fl label{font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.1em;color:var(--mu);}
    .fl input,.fl select,.fl textarea{background:var(--sf2);border:1.5px solid var(--bd);border-radius:var(--rs);padding:10px 14px;color:var(--tx);font-family:'Dosis',sans-serif;font-size:.92rem;outline:none;transition:border-color .2s,box-shadow .2s;width:100%;}
    .fl input:focus,.fl select:focus,.fl textarea:focus{border-color:var(--in);box-shadow:0 0 0 3px rgba(109,85,177,.1);}
    
    .mb{background:var(--sf2);border:1.5px solid var(--bd);border-radius:var(--rs);padding:16px;margin-bottom:10px;}
    .mb-nm{font-size:.95rem;font-weight:700;margin-bottom:10px;}

    .search-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 100; display: none; align-items: center; justify-content: center; backdrop-filter: blur(3px); opacity: 0; transition: opacity 0.2s; }
    .search-overlay.active { display: flex; opacity: 1; }
    .search-modal { background: var(--sf); border: 1px solid var(--bd); border-radius: var(--r); width: 90%; max-width: 500px; padding: 20px; box-shadow: var(--sh2); transform: translateY(20px); transition: transform 0.2s; }
    .search-overlay.active .search-modal { transform: translateY(0); }
    .search-results { max-height: 250px; overflow-y: auto; margin-top: 12px; border: 1px solid var(--bd); border-radius: var(--rs); display: none; }
    .search-result-item { padding: 12px 16px; border-bottom: 1px solid var(--bd); cursor: pointer; font-weight: 600; display: flex; justify-content: space-between; align-items: center; }
    .search-result-item:hover { background: var(--sf2); color: var(--in); }
    
    .dur-wrapper { display: flex; align-items: center; gap: 8px; background: var(--sf2); border: 1.5px solid var(--bd); border-radius: var(--rs); padding: 0 14px; }
    .dur-wrapper input { border: none !important; padding: 10px 0; background: transparent; width: 100%; outline: none; }
    .dur-wrapper span { font-size: 0.85rem; font-weight: 700; color: var(--mu); }
    
    .tb{background:var(--sf2);border:1.5px solid var(--bd);border-radius:50%;width:34px;height:34px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--mu);transition:all .2s;}
    </style>
    @endpush

    <div class="clinicaly-scope">
        <form action="{{ route('prescriptions.store', $diagnostico->id) }}" method="POST">
            @csrf
            <main class="page">
                <section class="fi">
                    <h1 style="font-size:1.55rem;font-weight:800;margin-bottom:4px; color: var(--tx);">Emitir Prescrição</h1>
                    <p style="font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);"> 
                        {{-- Correção de Relacionamento: user para paciente --}}
                        {{ $diagnostico->paciente->name }} · {{ $diagnostico->titulo ?? 'Diagnóstico a Confirmar' }}
                    </p>
                </section>

                <section class="fi fi1" style="margin-top:14px;">
                    <div class="al al-gr">
                        <i class="fa-solid fa-circle-check"></i>
                        <p>Diagnóstico confirmado: <strong>{{ $diagnostico->titulo ?? 'Condição identificada' }}</strong> — defina os medicamentos abaixo</p>
                    </div>
                </section>

                <article class="card fi fi1" style="margin-top:14px;">
                    <h2 class="ct"><i class="fa-solid fa-pills"></i> Medicamentos — ajustável</h2>
                    <div id="medications-container">
                    </div>
                    <button type="button" class="btn b-gh bsm" onclick="openSearchModal()"><i class="fa-solid fa-plus"></i> Adicionar medicamento</button>
                </article>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 fi fi2" style="margin-top:14px;">
                    <article class="card">
                        <h2 class="ct"><i class="fa-solid fa-user"></i> Dados do paciente</h2>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                            <span class="av av-bl av-sm">{{ substr($diagnostico->paciente->name, 0, 2) }}</span>
                            <div>
                                <p style="font-weight:700;">{{ $diagnostico->paciente->name }}</p>
                                <p style="font-size:.75rem;color:var(--mu);">{{ $diagnostico->paciente->idade ?? 'N/A' }} anos · {{ $diagnostico->paciente->genero ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="fl">
                            <label for="dt-inicio">Data de início</label>
                            <input type="date" name="start_date" id="dt-inicio" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </article>

                    <article class="card">
                        <h2 class="ct"><i class="fa-solid fa-user-doctor"></i> Dados do médico</h2>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                            <span class="av av-gr av-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                            <div>
                                <p style="font-weight:700;">Dr. {{ auth()->user()->name }}</p>
                                <p style="font-size:.75rem;color:var(--mu);">{{ auth()->user()->especialidade ?? 'Clínico Geral' }}</p>
                            </div>
                        </div>
                        <div class="fl">
                            <label for="obs">Observações gerais</label>
                            <input type="text" name="recommendations" id="obs" placeholder="Repouso, hidratação...">
                        </div>
                    </article>
                </div>

                <nav class="fi fi3" style="margin-top:16px;display:flex;gap:10px;justify-content:flex-end;">
                    <a href="{{ route('dashboard') }}" class="btn b-gh">Cancelar</a>
                    <button type="submit" class="btn b-pr"><i class="fa-solid fa-file-medical"></i> Emitir prescrição</button>
                </nav>
            </main>
        </form>
    </div>

    {{-- Modal de Busca --}}
    <div class="search-overlay clinicaly-scope" id="searchOverlay" onclick="closeSearchModal(event)">
        <div class="search-modal" onclick="event.stopPropagation()">
            <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                <h3 style="font-weight:800; font-size:1.1rem; color: var(--tx);"><i class="fa-solid fa-magnifying-glass" style="color:var(--mu)"></i> Buscar Medicamento</h3>
                <button type="button" class="tb" style="width:28px;height:28px;" onclick="closeSearchModal(true)"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="fl">
                <input type="text" id="searchInput" placeholder="Digite o nome do remédio..." autocomplete="off" oninput="searchMedication(this.value)">
            </div>
            <div class="search-results" id="searchResults"></div>
        </div>
    </div>

    @push('scripts')
    <script>
        let medIndex = 0;

        function openSearchModal() {
            document.getElementById('searchOverlay').classList.add('active');
            document.getElementById('searchInput').focus();
        }

        function closeSearchModal(force = false) {
            if (force || (event && event.target.id === 'searchOverlay')) {
                document.getElementById('searchOverlay').classList.remove('active');
                document.getElementById('searchInput').value = '';
                document.getElementById('searchResults').style.display = 'none';
            }
        }

        async function searchMedication(query) {
            const resultsDiv = document.getElementById('searchResults');
            if (query.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }
            try {
                const response = await fetch(`/api/medicamentos/buscar?q=${query}`);
                const data = await response.json();
                resultsDiv.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(med => {
                        const div = document.createElement('div');
                        div.className = 'search-result-item';
                        div.innerHTML = `<span>${med}</span> <i class="fa-solid fa-plus"></i>`;
                        div.onclick = () => addMedicationBlock(med);
                        resultsDiv.appendChild(div);
                    });
                    resultsDiv.style.display = 'block';
                } else {
                    resultsDiv.style.display = 'none';
                }
            } catch (error) { console.error('Erro na busca:', error); }
        }

        function addMedicationBlock(medName) {
            const container = document.getElementById('medications-container');
            const html = `
            <div class="mb" id="med-${medIndex}">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <p class="mb-nm" style="color: var(--tx);">${medName}</p>
                    <button type="button" class="btn b-rd bsm" onclick="document.getElementById('med-${medIndex}').remove()">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <input type="hidden" name="medications[${medIndex}][name]" value="${medName}">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="fl">
                        <label>Frequência</label>
                        <select name="medications[${medIndex}][interval]" required>
                            <option value="4">A cada 4 horas</option>
                            <option value="6">A cada 6 horas</option>
                            <option value="8">A cada 8 horas</option>
                            <option value="12">A cada 12 horas</option>
                            <option value="24">A cada 24 horas</option>
                        </select>
                    </div>
                    <div class="fl">
                        <label>Duração</label>
                        <div class="dur-wrapper">
                            <input type="number" name="medications[${medIndex}][duration]" placeholder="Ex: 7" required min="1">
                            <span>dias</span>
                        </div>
                    </div>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            medIndex++;
            closeSearchModal(true);
        }
    </script>
    @endpush
</x-app-layout>