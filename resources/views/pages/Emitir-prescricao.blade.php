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
        min-height: calc(100vh - var(--topbar-h, 64px));
        color: var(--tx);
        font-family: 'Dosis', sans-serif;
    }

    [data-theme=dark] .clinicaly-scope, .dark .clinicaly-scope {
        --in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);
    }
    
    .page{max-width:1180px;margin:0 auto;padding:0 0 80px;}
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

    .med-search-wrap{position:relative;margin-bottom:14px}
    .med-search-wrap i{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--mu);pointer-events:none}
    .med-search-input{width:100%;border:1.5px solid var(--bd);border-radius:16px;background:var(--sf2);color:var(--tx);padding:12px 40px 12px 15px;font-weight:700;outline:0}
    .med-search-input:focus{border-color:var(--in);box-shadow:0 0 0 3px rgba(109,85,177,.12);background:var(--sf)}
    .search-results { position:absolute;z-index:50;left:0;right:0;top:calc(100% + 7px);max-height:260px;overflow-y:auto;border:1px solid var(--bd);background:var(--sf);border-radius:14px;display:none;box-shadow:var(--sh2);scrollbar-width:thin;scrollbar-color:var(--bd) transparent}
    .search-results::-webkit-scrollbar{width:4px}.search-results::-webkit-scrollbar-track{background:transparent}.search-results::-webkit-scrollbar-thumb{background:var(--bd);border-radius:999px}
    .search-result-item { width:100%;padding:12px 16px;border:0;border-bottom:1px solid var(--bd);background:transparent;color:var(--tx);cursor:pointer;font-weight:700;display:flex;justify-content:space-between;align-items:center;text-align:left; }
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
                        {{ $diagnostico->paciente->name }} · {{ $diagnostico->confirmed_disease_name ?? 'Diagnóstico a Confirmar' }}
                    </p>
                </section>

                <section class="fi fi1" style="margin-top:14px;">
                    <div class="al al-gr">
                        <i class="fa-solid fa-circle-check"></i>
                        <p>Diagnóstico confirmado: <strong>{{ $diagnostico->confirmed_disease_name ?? 'Condição identificada' }}</strong> — defina os medicamentos abaixo</p>
                    </div>
                </section>

                <article class="card fi fi1" style="margin-top:14px;">
                    <h2 class="ct"><i class="fa-solid fa-pills"></i> Medicamentos — ajustável</h2>
                    <div class="med-search-wrap">
                        <input type="text" id="searchInput" class="med-search-input" placeholder="Pesquisar e adicionar remédio..." autocomplete="off" oninput="searchMedication(this.value)" onfocus="searchMedication(this.value)">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <div class="search-results" id="searchResults"></div>
                    </div>
                    <div id="medications-container">
                    </div>
                </article>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 fi fi2" style="margin-top:14px;">
                    <article class="card">
                        <h2 class="ct"><i class="fa-solid fa-user"></i> Dados do paciente</h2>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                            <span class="av av-bl av-sm">{{ substr($diagnostico->paciente->name, 0, 2) }}</span>
                            <div>
                                <p style="font-weight:700;">{{ $diagnostico->paciente->name }}</p>
                                <p style="font-size:.75rem;color:var(--mu);">
                                    {{ $diagnostico->dados_biometricos['idade'] ?? 'N/A' }} anos ·
                                    {{ ($diagnostico->dados_biometricos['genero'] ?? '') === 'm' ? 'Masculino' : (($diagnostico->dados_biometricos['genero'] ?? '') === 'f' ? 'Feminino' : 'N/A') }}
                                </p>
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

    @push('scripts')
    <script>
        let medIndex = 0;

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
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'search-result-item';
                        button.innerHTML = `<span>${med}</span> <i class="fa-solid fa-plus"></i>`;
                        button.onclick = () => addMedicationBlock(med);
                        resultsDiv.appendChild(button);
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
            document.getElementById('searchInput').value = '';
            document.getElementById('searchResults').style.display = 'none';
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.med-search-wrap')) {
                document.getElementById('searchResults').style.display = 'none';
            }
        });
    </script>
    @endpush
</x-app-layout>
