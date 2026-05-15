<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Diagnóstico - {{ config('app.name', 'Laravel') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|dosis:400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Dosis', 'ui-sans-serif', 'system-ui'],
                        dosis: ['Dosis', 'sans-serif'],
                        mono: ['Space Mono', 'monospace'],
                    },
                    colors: {
                        indigo: { 500: '#735ab8', 600: '#6d55b1', 700: '#58448f' },
                    }
                }
            }
        }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="/logo-clin.ico" type="image/x-icon">
    
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Dosis', sans-serif; margin: 0; padding: 0; }
        [x-cloak] { display: none !important; }
        :root{--in:#6d55b1;--il:#8b72cc;--is:#f5f3fd;--id:#ede9f8;--bg:#f4f2fb;--sf:#fff;--sf2:#f8f6fd;--sf3:#ede9f8;--bd:#ddd8f0;--tx:#1a1530;--mu:#7c72a0;--fa:#b0a8cc;--gr:#059669;--gb:#ecfdf5;--gbd:#a7f3d0;--bl:#3b82f6;--bb:#eff6ff;--bbd:#bfdbfe;--wn:#d97706;--wb:#fffbeb;--wbd:#fcd34d;--rd:#dc2626;--rb:#fef2f2;--rbd:#fca5a5;--sh:0 1px 3px rgba(109,85,177,.08);--sh2:0 4px 12px rgba(109,85,177,.10);--r:14px;--rs:8px;}
        [data-theme=dark]{--in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);}
        html[data-theme] { background-color: var(--bg); color: var(--tx); transition: background-color 0.3s ease, color 0.3s ease; }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(109, 85, 177, 0.5); }
            50% { box-shadow: 0 0 0 12px rgba(109, 85, 177, 0); }
        }
        .pulse-item { animation: pulse-glow 2s infinite; }
        .search-highlight { background: var(--is); border-left: 3px solid var(--in); }
        .symptom-option.selected{background:var(--in)!important;color:#fff!important;border-color:var(--il)!important;box-shadow:0 6px 16px rgba(109,85,177,.22);}
        .std-modal-overlay{display:none;position:fixed;inset:0;z-index:120;background:rgba(10,8,20,.55);backdrop-filter:blur(2px);align-items:center;justify-content:center;padding:20px}
        .std-modal-overlay.open{display:flex}.std-modal{width:min(430px,100%);background:var(--sf);border:1px solid var(--bd);border-radius:22px;box-shadow:var(--sh2);padding:24px}.std-modal h2{font-size:1.08rem;font-weight:800;color:var(--tx);margin-bottom:8px}.std-modal p{color:var(--mu);font-weight:600;line-height:1.5}.std-actions{display:flex;justify-content:flex-end;margin-top:18px}
        .info-modal-overlay{display:none;position:fixed;inset:0;z-index:130;background:rgba(10,8,20,.62);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px}
        .info-modal-overlay.open{display:flex}
        .info-modal{width:min(680px,100%);max-height:min(86vh,760px);overflow-y:auto;background:var(--sf);border:1px solid var(--bd);border-radius:28px;box-shadow:0 24px 70px rgba(0,0,0,.28);padding:28px;color:var(--tx)}
        .info-modal{scrollbar-width:none}
        .info-modal::-webkit-scrollbar{width:0;height:0}
        .info-modal-top{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;margin-bottom:18px}
        .info-modal-icon{width:52px;height:52px;border-radius:18px;display:flex;align-items:center;justify-content:center;background:var(--is);color:var(--in);font-size:1.1rem;flex:0 0 auto}
        .info-modal h2{font-size:1.55rem;font-weight:900;line-height:1.05;margin:0 0 6px}
        .info-modal-kind{font:800 .55rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.12em;color:var(--in)}
        .info-modal-desc{color:var(--mu);font-size:1rem;font-weight:650;line-height:1.65;margin:14px 0 18px}
        .info-close{width:38px;height:38px;border:none;border-radius:13px;background:var(--sf2);color:var(--mu);font-weight:900}
        .info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;margin:18px 0}
        .info-kv{background:var(--sf2);border:1px solid var(--bd);border-radius:16px;padding:12px}
        .info-kv span{display:block;color:var(--mu);font:800 .54rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px}
        .info-kv strong{font-size:.92rem;color:var(--tx)}
        .info-list{display:flex;flex-wrap:wrap;gap:8px;margin-top:8px}
        .info-pill{display:inline-flex;align-items:center;gap:6px;border:1px solid var(--bd);background:var(--sf2);color:var(--tx);border-radius:999px;padding:7px 11px;font-weight:800;font-size:.78rem}
        .info-small-btn{border:1px solid var(--bd);background:var(--sf2);color:var(--in);border-radius:999px;padding:8px 12px;font-size:.72rem;font-weight:900;text-transform:uppercase;letter-spacing:.06em}
        .al { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: var(--rs); font-size: 0.84rem; font-weight: 500; border: 1px solid; }
        .al-rd { background: var(--rb); border-color: var(--rbd); color: var(--rd); }
        .al-wn { background: var(--wb); border-color: var(--wbd); color: var(--wn); }
        #section-diagnostico form{max-width:820px!important}
        #section-diagnostico form>div{border-radius:22px!important;padding:24px!important;box-shadow:var(--sh)!important}
        #section-diagnostico label{font-family:'Space Mono',monospace!important;font-size:.58rem!important;letter-spacing:.1em!important;color:var(--mu)!important}
        #section-diagnostico input,
        #section-diagnostico select,
        #section-diagnostico textarea{
            min-height:46px!important;
            width:100%!important;
            border:1.5px solid var(--bd)!important;
            border-radius:12px!important;
            background:var(--sf2)!important;
            color:var(--tx)!important;
            padding:11px 14px!important;
            font:700 .92rem 'Dosis',sans-serif!important;
            outline:none!important;
            box-shadow:none!important;
            transition:border-color .18s,box-shadow .18s,background .18s!important;
        }
        #section-diagnostico input:focus,
        #section-diagnostico select:focus,
        #section-diagnostico textarea:focus{
            border-color:var(--in)!important;
            background:var(--sf)!important;
            box-shadow:0 0 0 3px rgba(109,85,177,.13)!important;
        }
        #section-diagnostico input::placeholder{color:var(--mu)!important;opacity:.75}
        #section-diagnostico input[type=number]{appearance:textfield!important;-moz-appearance:textfield!important}
        #section-diagnostico input[type=number]::-webkit-outer-spin-button,
        #section-diagnostico input[type=number]::-webkit-inner-spin-button{-webkit-appearance:none!important;margin:0!important}
        .clinical-prefill-card{min-width:0!important;overflow:hidden!important}
        .clinical-prefill-text{max-width:100%!important;white-space:pre-wrap!important;overflow-wrap:anywhere!important;word-break:break-word!important;line-height:1.55!important}
        #patientResults,#symptomDropdown,#searchResults{
            border:1px solid var(--bd)!important;
            background:var(--sf)!important;
            color:var(--tx)!important;
            border-radius:14px!important;
            box-shadow:var(--sh2)!important;
            scrollbar-width:none;
        }
        #patientResults::-webkit-scrollbar,#symptomDropdown::-webkit-scrollbar,#searchResults::-webkit-scrollbar,#symptomList::-webkit-scrollbar{width:0;height:0}
        #symptomList{border-radius:16px!important;scrollbar-width:none}
        #selectedSymptoms>div{border-radius:999px!important}
        #section-diagnostico button[type="submit"]{border-radius:16px!important;box-shadow:0 10px 28px rgba(109,85,177,.22)!important}
        @media(max-width:640px){#section-diagnostico form>div{padding:18px!important}#section-diagnostico .grid{grid-template-columns:1fr!important}}
        section { animation: fadeInUp 0.4s ease-out; }
        article { animation: fadeInUp 0.5s ease-out; }
        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(15px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
    </style>
</head>
<body style="background: var(--bg); color: var(--tx); transition: background-color 0.3s ease, color 0.3s ease;">
    
<div class="flex h-screen" style="background-color: var(--bg); color: var(--tx);">
    
    {{-- SIDEBAR ESQUERDA --}}
    <aside style="background-color: var(--sf); border-right: 1px solid var(--bd); color: var(--tx);" 
           class="w-72 flex flex-col p-6 overflow-y-auto sticky top-0 h-screen">
        <div class="flex items-center justify-between mb-10">
           <div class="flex items-center gap-2">
                <img src="{{ asset('Proosta-logo4.png') }}" alt="Logo" width="180px" class="w-32 md:w-44">
            </div>
        </div>

        <nav id="nav-main" class="flex-1 space-y-2">
            @if(in_array(Auth::user()->role, ['doctor', 'medico', 'médico'], true))
            <button onclick="switchView('diagnostico')" style="color: var(--tx);"
                    class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold hover:bg-slate-100 transition-colors"
                    id="btn-diagnostico">
                <i class="fa-solid fa-stethoscope"></i> Realizar Diagnóstico
            </button>
            @endif
            <button onclick="switchView('doencas')" style="color: var(--tx);"
                    class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold hover:bg-slate-100 transition-colors"
                    id="btn-doencas">
                <i class="fa-solid fa-virus-covid"></i> Base de Doenças
            </button>
            <button onclick="switchView('biblioteca_sintomas')" style="color: var(--tx);"
                    class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold hover:bg-slate-100 transition-colors"
                    id="btn-biblioteca_sintomas">
                <i class="fa-solid fa-book-medical"></i> Lista de Sintomas
            </button>
            <button onclick="switchView('remedios')" style="color: var(--tx);"
                    class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold hover:bg-slate-100 transition-colors"
                    id="btn-remedios">
                <i class="fa-solid fa-pills"></i> Remédios
            </button>
            <button onclick="switchView('categorias')" style="color: var(--tx);"
                    class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold hover:bg-slate-100 transition-colors"
                    id="btn-categorias">
                <i class="fa-solid fa-layer-group"></i> Categorias
            </button>
        </nav>

        <a href="{{ route('dashboard') }}" style="border-color: var(--bd); color: var(--in);" 
           class="w-full flex items-center justify-center gap-3 py-3 rounded-2xl border font-bold text-sm transition-colors hover:bg-indigo-50">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </aside>

    {{-- CONTEÚDO PRINCIPAL --}}
    <main class="flex-1 overflow-y-auto relative" style="background-color: var(--bg);">

        {{-- HEADER COM BUSCA UNIVERSAL --}}
        <header style="background-color: var(--sf); border-bottom: 1px solid var(--bd);"
                class="sticky top-0 z-40 backdrop-blur-md p-4 md:p-6">
            <div class="max-w-6xl mx-auto flex items-center gap-4">
                <button onclick="toggleMobileMenu()" class="md:hidden p-2" style="color: var(--in);"><i class="fa-solid fa-bars-staggered"></i></button>
                
                <div class="flex-1 relative">
                    <input type="text" id="searchQuery" placeholder="Buscar na base clínica..." 
                           oninput="performSearch()"
                           style="width: 100%; padding: 12px 18px; border: 1.5px solid var(--bd); border-radius: 30px; background: var(--sf2); color: var(--tx); font-family: 'Dosis', sans-serif; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: var(--mu); font-size: 0.85rem; pointer-events: none;"></i>
                    
                    {{-- Dropdown de resultados --}}
                    <div id="searchResults" style="position: absolute; top: 100%; left: 0; right: 0; margin-top: 8px; background: var(--sf); border: 1.5px solid var(--bd); border-radius: 14px; box-shadow: var(--sh2); z-index: 100; display: none; max-height: 400px; overflow-y: auto;">
                    </div>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-12 max-w-6xl mx-auto w-full">

            {{-- 1. BASE DE DOENÇAS --}}
            <section id="section-doencas">
                <h2 style="color: var(--tx);" class="text-3xl font-black mb-8 uppercase tracking-tight">Doenças Registradas</h2>
                <div class="space-y-10">
                    @foreach($categories as $category)
                        @php
                            $categoryDiseases = $diseases->filter(fn ($disease) => (int) ($disease->category_id ?? 0) === (int) $category->id || $disease->categories->contains('id', $category->id));
                        @endphp
                        @if($categoryDiseases->isNotEmpty())
                            <div id="disease-category-{{ $category->id }}" class="disease-category-block">
                                <div class="flex items-center justify-between gap-4 mb-4">
                                    <h3 style="color: var(--tx);" class="text-xl font-black uppercase tracking-tight">{{ $category->name }}</h3>
                                    <span style="background: var(--is); border: 1px solid var(--bd); color: var(--in);" class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $categoryDiseases->count() }} doenças</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($categoryDiseases as $disease)
                                    <button type="button" id="disease-{{ $disease->id }}"
                                             onclick="openInfoModal('disease', {{ $disease->id }})"
                                             style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx); text-align:left;"
                                             class="p-6 rounded-[32px] hover:border-indigo-500 transition-all group shadow-sm">
                                        <div class="flex justify-between items-start mb-6">
                                            <div style="background: var(--is); color: var(--in);" class="p-4 rounded-2xl"><i class="fa-solid fa-virus"></i></div>
                                            <span style="background: var(--sf2); border: 1px solid var(--bd); color: var(--mu);" class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $category->name }}</span>
                                        </div>
                                        <h4 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition-colors">{{ $disease->name }}</h4>
                                        <p style="color: var(--mu);" class="text-sm line-clamp-2">{{ $disease->brief_description }}</p>
                                        <span style="color: var(--in);" class="mt-5 inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest">Ver informações <i class="fa-solid fa-arrow-right-long group-hover:translate-x-1 transition-transform"></i></span>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @php $uncategorizedDiseases = $diseases->filter(fn ($disease) => empty($disease->category_id) && $disease->categories->isEmpty()); @endphp
                    @if($uncategorizedDiseases->isNotEmpty())
                        <div id="disease-category-geral" class="disease-category-block">
                            <h3 style="color: var(--tx);" class="text-xl font-black uppercase tracking-tight mb-4">Geral</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($uncategorizedDiseases as $disease)
                                <button type="button" id="disease-{{ $disease->id }}" onclick="openInfoModal('disease', {{ $disease->id }})" style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx); text-align:left;" class="p-6 rounded-[32px] hover:border-indigo-500 transition-all group shadow-sm">
                                    <div class="flex justify-between items-start mb-6">
                                        <div style="background: var(--is); color: var(--in);" class="p-4 rounded-2xl"><i class="fa-solid fa-virus"></i></div>
                                        <span style="background: var(--sf2); border: 1px solid var(--bd); color: var(--mu);" class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Geral</span>
                                    </div>
                                    <h4 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition-colors">{{ $disease->name }}</h4>
                                    <p style="color: var(--mu);" class="text-sm line-clamp-2">{{ $disease->brief_description }}</p>
                                    <span style="color: var(--in);" class="mt-5 inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest">Ver informações <i class="fa-solid fa-arrow-right-long group-hover:translate-x-1 transition-transform"></i></span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            {{-- 2. BIBLIOTECA DE SINTOMAS --}}
            <section id="section-biblioteca_sintomas" style="display: none;">
                <div class="mb-10">
                    <h2 style="color: var(--tx);" class="text-3xl font-black uppercase tracking-tight">Biblioteca de Sintomas</h2>
                    <p style="color: var(--mu);" class="font-bold text-xs uppercase tracking-widest mt-1">Clique para análise detalhada via Inteligência Clínica</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse($symptoms as $symptom)
                    <button type="button" id="symptom-{{ $symptom->id }}"
                       onclick="openInfoModal('symptom', {{ $symptom->id }})"
                       style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);"
                       class="relative overflow-hidden p-6 rounded-[24px] hover:scale-[1.02] hover:shadow-xl transition-all group text-left">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                            <span class="font-bold group-hover:text-indigo-600">{{ $symptom->name }}</span>
                        </div>
                        <div style="color: var(--mu);" class="text-[10px] font-black uppercase flex items-center gap-2">
                            <span>Ver informações</span>
                            <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform"></i>
                        </div>
                        <i style="color: var(--sf2);" class="fa-solid fa-notes-medical absolute -bottom-4 -right-4 text-6xl group-hover:opacity-50 transition-opacity"></i>
                    </button>
                    @empty
                    <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--mu);" class="p-8 rounded-[24px] col-span-full text-center">
                        <i class="fa-solid fa-inbox text-4xl mb-4"></i>
                        <p class="font-bold">Nenhum sintoma disponível</p>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- 3. REMÉDIOS --}}
            <section id="section-remedios" style="display: none;">
                <div class="mb-10">
                    <h2 style="color: var(--tx);" class="text-3xl font-black uppercase tracking-tight">Dicionário de Remédios</h2>
                    <p style="color: var(--mu);" class="font-bold text-xs uppercase tracking-widest mt-1">Medicamentos cadastrados e suas principais informações clínicas</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($medications as $medication)
                    <button type="button"
                            id="medication-{{ $medication->id }}"
                            onclick="openInfoModal('medication', {{ $medication->id }})"
                            style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx); text-align:left;"
                            class="p-6 rounded-[26px] hover:border-indigo-500 hover:shadow-xl transition-all group">
                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div style="background: var(--is); color: var(--in);" class="w-12 h-12 flex items-center justify-center rounded-2xl"><i class="fa-solid fa-capsules"></i></div>
                            <span style="background: var(--sf2); border: 1px solid var(--bd); color: var(--mu);" class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $medication->diseases->count() }} usos</span>
                        </div>
                        <h4 class="text-lg font-black mb-1 group-hover:text-indigo-600">{{ $medication->name }}</h4>
                        <p style="color: var(--mu);" class="text-xs font-bold uppercase tracking-widest mb-3">{{ $medication->active_principle ?? 'Princípio ativo não informado' }}</p>
                        <p style="color: var(--mu);" class="text-sm line-clamp-2">{{ $medication->brief_description }}</p>
                    </button>
                    @empty
                    <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--mu);" class="p-8 rounded-[24px] col-span-full text-center">
                        <i class="fa-solid fa-inbox text-4xl mb-4"></i>
                        <p class="font-bold">Nenhum remédio disponível</p>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- 4. REALIZAR DIAGNÓSTICO --}}
            <section id="section-diagnostico" style="display: none;">
                <div class="max-w-3xl mx-auto text-center mb-10">
                    <h2 style="color: var(--tx);" class="text-3xl font-black uppercase tracking-tight">Realizar Diagnóstico</h2>
                    <p style="color: var(--mu);" class="mt-2 font-medium">Triagem automática baseada em perfis biométricos.</p>
                </div>

                <form action="{{ route('processar.diagnostico') }}" method="POST" class="max-w-3xl mx-auto space-y-6">
                    @csrf
                    @if(request('as_role'))
                        <input type="hidden" name="as_role" value="{{ request('as_role') }}">
                    @endif
                    @if(!empty($diagnosticPrefill))
                        <input type="hidden" name="clinical_request_description" value="{{ $diagnosticPrefill['description'] ?? '' }}">
                        <input type="hidden" name="clinical_request_evolution" value="{{ $diagnosticPrefill['evolution'] ?? '' }}">
                        <input type="hidden" name="clinical_request_triggers" value="{{ $diagnosticPrefill['triggers'] ?? '' }}">
                        <input type="hidden" name="clinical_request_medical_history" value="{{ $diagnosticPrefill['medical_history'] ?? '' }}">
                        <input type="hidden" name="clinical_request_context" value="{{ $diagnosticPrefill['context'] ?? '' }}">
                        <input type="hidden" name="clinical_request_submitted_at" value="{{ $diagnosticPrefill['submitted_at'] ?? '' }}">

                        <div style="background: var(--sf); border: 1px solid var(--bd);" class="clinical-prefill-card p-8 rounded-[35px] shadow-sm">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <span style="color: var(--in);" class="text-[10px] font-black uppercase tracking-widest">Pedido recebido pelo chat</span>
                                    <h3 style="color: var(--tx);" class="text-xl font-black mt-1">Resumo clínico enviado por {{ $diagnosticPrefill['patient_name'] ?? 'paciente' }}</h3>
                                </div>
                                <i class="fa-solid fa-file-medical" style="color:var(--in);font-size:1.6rem;"></i>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <p style="color:var(--mu);" class="text-xs font-black uppercase tracking-widest mb-1">O que sente</p>
                                    <p style="color:var(--tx);" class="clinical-prefill-text font-semibold">{{ $diagnosticPrefill['description'] ?? 'Não informado' }}</p>
                                </div>
                                <div>
                                    <p style="color:var(--mu);" class="text-xs font-black uppercase tracking-widest mb-1">Quando começou e evolução</p>
                                    <p style="color:var(--tx);" class="clinical-prefill-text font-semibold">{{ $diagnosticPrefill['evolution'] ?? 'Não informado' }}</p>
                                </div>
                                <div>
                                    <p style="color:var(--mu);" class="text-xs font-black uppercase tracking-widest mb-1">Melhora ou piora</p>
                                    <p style="color:var(--tx);" class="clinical-prefill-text font-semibold">{{ $diagnosticPrefill['triggers'] ?? 'Não informado' }}</p>
                                </div>
                                <div>
                                    <p style="color:var(--mu);" class="text-xs font-black uppercase tracking-widest mb-1">Historial e medicamentos</p>
                                    <p style="color:var(--tx);" class="clinical-prefill-text font-semibold">{{ $diagnosticPrefill['medical_history'] ?? 'Não informado' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p style="color:var(--mu);" class="text-xs font-black uppercase tracking-widest mb-1">Contexto</p>
                                    <p style="color:var(--tx);" class="clinical-prefill-text font-semibold">{{ $diagnosticPrefill['context'] ?? 'Não informado' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div style="background: var(--sf); border: 1px solid var(--bd);" class="p-8 rounded-[35px] shadow-sm">
                        {{-- BUSCA DE PACIENTE --}}
                        <div class="mb-8 relative">
                            <label style="color: var(--mu);" class="block text-xs font-bold uppercase mb-2 ml-1">Paciente</label>
                            <div class="relative">
                                <input type="text" id="searchPatient" placeholder="Pesquisar paciente pelo nome..." 
                                       oninput="searchPatients()"
                                       style="width: 100%; padding: 12px 18px; background: var(--sf2); border: 1.5px solid var(--bd); border-radius: 18px; color: var(--tx); font-weight: 600; outline: none; transition: border-color 0.2s;"
                                       onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'">
                                <i class="fa-solid fa-user-tag" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: var(--mu); font-size: 0.85rem;"></i>
                                
                                <div id="patientResults" style="position: absolute; z-index: 50; width: 100%; margin-top: 8px; background: var(--sf); border: 1.5px solid var(--bd); border-radius: 14px; box-shadow: var(--sh2); max-height: 240px; overflow-y: auto; display: none;">
                                </div>
                            </div>
                            <input type="hidden" id="patientId" name="id_paciente">
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label style="color: var(--mu);" class="block text-xs font-bold uppercase mb-2">Idade</label>
                                <input type="number" id="age" name="age" required 
                                       style="width: 100%; padding: 12px; background: var(--sf2); border: 1.5px solid var(--bd); border-radius: 12px; color: var(--tx); outline: none;"
                                       onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'">
                            </div>
                            <div>
                                <label style="color: var(--mu);" class="block text-xs font-bold uppercase mb-2">Gênero</label>
                                <select id="gender" name="gender" required 
                                        style="width: 100%; padding: 12px; background: var(--sf2); border: 1.5px solid var(--bd); border-radius: 12px; color: var(--tx); outline: none;">
                                    <option value="m">Masculino</option>
                                    <option value="f">Feminino</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>
                            <div>
                                <label style="color: var(--mu);" class="block text-xs font-bold uppercase mb-2">Peso (kg)</label>
                                <input type="number" id="weight" name="weight" step="0.1" 
                                       style="width: 100%; padding: 12px; background: var(--sf2); border: 1.5px solid var(--bd); border-radius: 12px; color: var(--tx); outline: none;"
                                       onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'">
                            </div>
                            <div>
                                <label style="color: var(--mu);" class="block text-xs font-bold uppercase mb-2">Altura (m)</label>
                                <input type="number" id="height" name="height" step="0.01" placeholder="1.75" 
                                       style="width: 100%; padding: 12px; background: var(--sf2); border: 1.5px solid var(--bd); border-radius: 12px; color: var(--tx); outline: none;"
                                       onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'">
                            </div>
                        </div>
                    </div>

                    <div style="background: var(--sf); border: 1px solid var(--bd);" class="p-8 rounded-[35px] shadow-sm relative">
                        <div class="flex justify-between items-center mb-4">
                            <label style="color: var(--mu);" class="text-xs font-bold uppercase">Sintomas Detectados</label>
                            <button type="button" onclick="toggleSymptomList()" style="color: var(--in);" class="text-[10px] font-black uppercase tracking-widest">
                                <span id="symptomToggleText">+ Ver Lista</span>
                            </button>
                        </div>

                        <div style="position: relative; margin-bottom: 24px;">
                            <input type="text" id="searchSymptom" placeholder="Pesquisar sintoma..." 
                                   oninput="searchSymptoms()"
                                   style="width: 100%; padding: 12px 18px; border: 1.5px solid var(--bd); border-radius: 30px; background: var(--sf2); color: var(--tx); font-family: 'Dosis', sans-serif; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: var(--mu); font-size: 0.85rem;"></i>
                            
                            <div id="symptomDropdown" style="position: absolute; z-index: 50; width: 100%; margin-top: -2px; background: var(--sf); border: 1.5px solid var(--bd); border-top: none; border-radius: 0 0 14px 14px; max-height: 240px; overflow-y: auto; box-shadow: var(--sh2); display: none;">
                            </div>
                        </div>

                        <div id="symptomList" style="display: none; background: var(--is); border: 1px solid var(--bd);" class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-6 p-4 rounded-2xl max-h-48 overflow-y-auto">
                            @foreach($symptoms as $symptom)
                            <button type="button" data-symptom-option="{{ $symptom->id }}" onclick="addSymptom({{ $symptom->id }}, @js($symptom->name))"
                                    style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);"
                                    class="symptom-option text-left px-3 py-2 rounded-lg text-xs font-bold transition-colors hover:border-indigo-500">
                                + {{ $symptom->name }}
                            </button>
                            @endforeach
                        </div>

                        <div id="selectedSymptoms" class="flex flex-wrap gap-2">
                        </div>
                    </div>

                    <!-- Hidden field to track symptoms as JSON for validation -->
                    <input type="hidden" id="symptomIdsJson" name="symptom_ids_json" value="[]">

                    <button type="submit" onclick="return validateDiagnosticForm(event)"
                            class="w-full bg-indigo-600 text-white py-5 rounded-[25px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
                        Processar Diagnóstico
                    </button>
                </form>
            </section>

            {{-- 5. CATEGORIAS --}}
            <section id="section-categorias" style="display: none;">
                <h2 style="color: var(--tx);" class="text-3xl font-black mb-8 uppercase tracking-tight">Categorias & Especialidades</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($categories as $category)
                    @php
                        $categoryCount = $diseases->filter(fn ($disease) => (int) ($disease->category_id ?? 0) === (int) $category->id || $disease->categories->contains('id', $category->id))->count();
                    @endphp
                    <article id="category-{{ $category->id }}"
                         style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx); text-align:left; width:100%;"
                         class="p-6 rounded-[24px] flex flex-col gap-4 group hover:border-indigo-500 transition-all">
                        <div class="flex items-center gap-4">
                        <div style="background: var(--is); color: var(--in);" class="w-12 h-12 flex items-center justify-center rounded-xl"><i class="fa-solid fa-folder-tree"></i></div>
                        <div>
                            <h4 class="font-bold">{{ $category->name }}</h4>
                            <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">{{ $categoryCount }} Registros</span>
                        </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="openInfoModal('category', {{ $category->id }})" class="info-small-btn">Ver descrição</button>
                            <button type="button" onclick="goToCategory({{ $category->id }})" class="info-small-btn">Ver doenças</button>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>

        </div>
    </main>

</div>

<div id="infoModal" class="info-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="infoModalTitle">
    <div class="info-modal">
        <div class="info-modal-top">
            <div class="flex items-start gap-4">
                <div id="infoModalIcon" class="info-modal-icon"><i class="fa-solid fa-circle-info"></i></div>
                <div>
                    <div id="infoModalKind" class="info-modal-kind">Informação clínica</div>
                    <h2 id="infoModalTitle">Detalhes</h2>
                </div>
            </div>
            <button type="button" class="info-close" onclick="closeInfoModal()" aria-label="Fechar modal"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <p id="infoModalDescription" class="info-modal-desc"></p>
        <div id="infoModalFacts" class="info-grid"></div>
        <div id="infoModalRelated"></div>
    </div>
</div>

<div id="standardAlertModal" class="std-modal-overlay" role="dialog" aria-modal="true">
    <div class="std-modal">
        <h2 id="standardAlertTitle">Atenção</h2>
        <p id="standardAlertMessage"></p>
        <div class="std-actions">
            <button type="button" onclick="closeStandardAlert()" style="background:var(--in);color:#fff;border:none;border-radius:999px;padding:10px 20px;font-weight:800;">Entendi</button>
        </div>
    </div>
</div>

{{-- MODAL LOADING --}}
<div id="loadingModal" style="display: none;" class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm flex items-center justify-center">
    <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);" class="p-12 rounded-[40px] text-center shadow-2xl max-w-md w-full mx-4">
        <!-- Animated brain/analysis icon -->
        <div class="relative w-32 h-32 mx-auto mb-8">
            <svg class="w-full h-full" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                <!-- Outer rotating circle -->
                <circle cx="60" cy="60" r="50" fill="none" stroke="var(--is)" stroke-width="2" opacity="0.3"
                        style="animation: rotate 3s linear infinite;"/>
                <!-- Middle rotating circle -->
                <circle cx="60" cy="60" r="45" fill="none" stroke="var(--in)" stroke-width="2.5" opacity="0.6"
                        style="animation: rotate 2s linear infinite reverse;"/>
                <!-- Inner rotating circle -->
                <circle cx="60" cy="60" r="40" fill="none" stroke="var(--il)" stroke-width="2"
                        style="animation: rotate 4s linear infinite;"/>
                
                <!-- Center brain icon -->
                <g style="animation: pulse 1.5s ease-in-out infinite;">
                    <path d="M60 20 Q70 25 75 35 Q78 42 75 50 Q78 55 75 60 Q78 65 75 70 Q70 80 60 85" 
                          fill="none" stroke="var(--in)" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M60 20 Q50 25 45 35 Q42 42 45 50 Q42 55 45 60 Q42 65 45 70 Q50 80 60 85" 
                          fill="none" stroke="var(--in)" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="60" cy="52" r="4" fill="var(--in)"/>
                </g>
            </svg>
        </div>

        <!-- Text content -->
        <h3 class="text-2xl font-black uppercase tracking-tight mb-2">Analisando Dados</h3>
        <p style="color: var(--mu);" class="text-sm mb-6">Cruzando sintomas com algoritmo clínico avançado...</p>

        <!-- Progress indicators -->
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-check text-green-500"></i>
                <span class="text-sm">Coleta de dados</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-notch text-indigo-600 animate-spin"></i>
                <span class="text-sm">Processando algoritmo...</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-hourglass-end" style="color: var(--mu); opacity: 0.5;"></i>
                <span class="text-sm" style="color: var(--mu); opacity: 0.5;">Gerando relatório</span>
            </div>
        </div>
    </div>

    <style>
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
    </style>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    // Initialize theme BEFORE anything else
    (function(){
        const THEME_KEY = 'cl-theme';
        const savedTheme = localStorage.getItem(THEME_KEY) || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    })();

    // Global app state
    const appState = {
        view: 'doencas',
        selectedSintomas: [],
        selectedPatient: null,
        allSymptoms: {!! $symptoms->toJson() !!},
        allPatients: {!! $patients->toJson() !!},
        allDiseases: {!! $diseases->toJson() !!},
        allCategories: {!! $categories->toJson() !!},
        allMedications: {!! $medications->toJson() !!},
        diagnosticPrefill: @js($diagnosticPrefill ?? null)
    };
    
    // Set initial view for doctors
    @auth
    @if(in_array(Auth::user()->role, ['doctor', 'medico', 'médico'], true))
    appState.view = 'diagnostico';
    @endif
    @endauth

    // Switch view
    function switchView(viewName) {
        // Hide all sections
        document.querySelectorAll('section[id^="section-"]').forEach(s => s.style.display = 'none');
        
        // Show selected section
        const section = document.getElementById('section-' + viewName);
        if (section) section.style.display = 'block';
        
        // Update nav buttons
        document.querySelectorAll('.nav-link').forEach(btn => {
            btn.style.background = 'transparent';
            btn.style.color = 'var(--mu)';
        });
        
        const activeBtn = document.getElementById('btn-' + viewName);
        if (activeBtn) {
            activeBtn.style.background = '#6d55b1';
            activeBtn.style.color = 'white';
        }
        
        appState.view = viewName;
    }

    // Toggle symptom list
    function toggleSymptomList() {
        const list = document.getElementById('symptomList');
        const text = document.getElementById('symptomToggleText');
        if (list.style.display === 'none') {
            list.style.display = 'grid';
            text.textContent = '- Fechar';
        } else {
            list.style.display = 'none';
            text.textContent = '+ Ver Lista';
        }
    }

    // Add symptom
    function addSymptom(id, name) {
        if (!appState.selectedSintomas.find(s => s.id === id)) {
            appState.selectedSintomas.push({id, name});
            renderSelectedSymptoms();
        }
        updateSymptomOptionStyles();
        document.getElementById('searchSymptom').value = '';
        document.getElementById('symptomDropdown').style.display = 'none';
    }

    // Remove symptom
    function removeSymptom(id) {
        appState.selectedSintomas = appState.selectedSintomas.filter(s => s.id !== id);
        renderSelectedSymptoms();
        updateSymptomOptionStyles();
    }

    // Render selected symptoms
    function renderSelectedSymptoms() {
        const container = document.getElementById('selectedSymptoms');
        container.innerHTML = appState.selectedSintomas.map(s => `
            <div style="background: var(--in); border: 1.5px solid var(--il); color: #fff;" class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                <span>${s.name}</span>
                <button type="button" onclick="removeSymptom(${s.id})"><i class="fa-solid fa-xmark"></i></button>
            </div>
        `).join('');
        
        // Update hidden JSON field with symptom IDs
        const ids = appState.selectedSintomas.map(s => s.id);
        document.getElementById('symptomIdsJson').value = JSON.stringify(ids);
    }

    function updateSymptomOptionStyles() {
        const selectedIds = appState.selectedSintomas.map(s => String(s.id));
        document.querySelectorAll('[data-symptom-option]').forEach(btn => {
            const selected = selectedIds.includes(btn.dataset.symptomOption);
            btn.classList.toggle('selected', selected);
            btn.style.background = selected ? 'var(--in)' : 'var(--sf)';
            btn.style.color = selected ? '#fff' : 'var(--tx)';
            btn.style.borderColor = selected ? 'var(--il)' : 'var(--bd)';
        });
    }

    // Search symptoms
    function searchSymptoms() {
        const query = document.getElementById('searchSymptom').value.toLowerCase();
        const dropdown = document.getElementById('symptomDropdown');
        
        if (query.length < 2) {
            dropdown.style.display = 'none';
            return;
        }
        
        if (!appState.allSymptoms || appState.allSymptoms.length === 0) {
            dropdown.innerHTML = '<div style="padding: 12px 16px; color: var(--mu); font-size: 0.9rem;">Nenhum sintoma disponível</div>';
            dropdown.style.display = 'block';
            return;
        }
        
        const filtered = appState.allSymptoms.filter(s => s.name && s.name.toLowerCase().includes(query));
        
        if (filtered.length === 0) {
            dropdown.innerHTML = '<div style="padding: 12px 16px; color: var(--mu); font-size: 0.9rem;">Nenhum sintoma encontrado</div>';
            dropdown.style.display = 'block';
            return;
        }
        
        dropdown.innerHTML = filtered.map(s => `
            <button type="button" onclick='addSymptom(${s.id}, ${JSON.stringify(s.name)})' style="width: 100%; text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--bd); background: transparent; color: var(--tx); transition: background 0.2s; font-weight: 600; font-size: 0.9rem;"
                    onmouseover="this.style.background = 'var(--is)'" onmouseout="this.style.background = 'transparent'">
                + ${s.name}
            </button>
        `).join('');
        
        dropdown.style.display = 'block';
    }

    // Search patients
    function searchPatients() {
        const query = document.getElementById('searchPatient').value.toLowerCase();
        const results = document.getElementById('patientResults');
        
        if (query.length < 2) {
            results.style.display = 'none';
            return;
        }
        
        if (!appState.allPatients || appState.allPatients.length === 0) {
            results.innerHTML = '<div style="padding: 12px 16px; color: var(--mu); font-size: 0.9rem;">Nenhum paciente disponível</div>';
            results.style.display = 'block';
            return;
        }
        
        const filtered = appState.allPatients.filter(p => 
            (p.name && p.name.toLowerCase().includes(query)) || (p.email && p.email.toLowerCase().includes(query))
        );
        

        
        if (filtered.length === 0) {
            results.innerHTML = '<div style="padding: 12px 16px; color: var(--mu); font-size: 0.9rem;">Nenhum paciente encontrado</div>';
            results.style.display = 'block';
            return;
        }
        
        results.innerHTML = filtered.map(p => `
            <button type="button" onclick='selectPatient(${p.id}, ${JSON.stringify(p.name)})' style="width: 100%; text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--bd); background: transparent; color: var(--tx); transition: background 0.2s;"
                    onmouseover="this.style.background = 'var(--is)'" onmouseout="this.style.background = 'transparent'">
                <p class="font-bold">${p.name}</p>
                <p style="font-size: 0.75rem; color: var(--mu);" class="uppercase tracking-widest">${p.email}</p>
            </button>
        `).join('');
        
        results.style.display = 'block';
    }

    // Select patient
    function selectPatient(id, name) {
        appState.selectedPatient = {id, name};
        document.getElementById('searchPatient').value = name;
        document.getElementById('patientId').value = id;
        document.getElementById('patientResults').style.display = 'none';
    }

    function applyDiagnosticPrefill() {
        const prefill = appState.diagnosticPrefill;
        if (!prefill) return;

        switchView('diagnostico');

        if (prefill.patient_id && prefill.patient_name) {
            selectPatient(prefill.patient_id, prefill.patient_name);
        }

        const fieldMap = {
            age: prefill.age,
            weight: prefill.weight,
            height: prefill.height,
            gender: prefill.gender
        };

        Object.entries(fieldMap).forEach(([id, value]) => {
            const field = document.getElementById(id);
            if (field && value !== undefined && value !== null && value !== '') {
                field.value = value;
            }
        });

        const symptoms = Array.isArray(prefill.symptoms) && prefill.symptoms.length
            ? prefill.symptoms
            : (Array.isArray(prefill.symptom_ids) ? prefill.symptom_ids.map(id => appState.allSymptoms.find(s => Number(s.id) === Number(id))).filter(Boolean) : []);

        symptoms.forEach(symptom => addSymptom(Number(symptom.id), symptom.name));
    }

    // Perform universal search
    function performSearch() {
        const query = document.getElementById('searchQuery').value.toLowerCase();
        const results = document.getElementById('searchResults');
        
        if (query.length < 2) {
            results.style.display = 'none';
            return;
        }
        
        const searchResults = [];
        
        appState.allDiseases.forEach(d => {
            if (d.name.toLowerCase().includes(query)) {
                searchResults.push({type: 'disease', name: d.name, id: d.id, category: 'Doenças'});
            }
        });
        
        appState.allSymptoms.forEach(s => {
            if (s.name.toLowerCase().includes(query)) {
                searchResults.push({type: 'symptom', name: s.name, id: s.id, category: 'Sintomas'});
            }
        });

        appState.allMedications.forEach(m => {
            const activePrinciple = m.active_principle || '';
            if (m.name.toLowerCase().includes(query) || activePrinciple.toLowerCase().includes(query)) {
                searchResults.push({type: 'medication', name: m.name, id: m.id, category: 'Remédios'});
            }
        });
        
        appState.allPatients.forEach(p => {
            if (p.name.toLowerCase().includes(query) || p.email.toLowerCase().includes(query)) {
                searchResults.push({type: 'patient', name: p.name, id: p.id, email: p.email, category: 'Pacientes'});
            }
        });
        
        appState.allCategories.forEach(c => {
            if (c.name.toLowerCase().includes(query)) {
                searchResults.push({type: 'category', name: c.name, id: c.id, category: 'Categorias'});
            }
        });
        
        results.innerHTML = searchResults.slice(0, 8).map(r => `
            <button type="button" onclick='goToResult(${JSON.stringify(r.type)}, ${r.id}, ${JSON.stringify(r.name)})' style="width: 100%; text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--bd); color: var(--tx); transition: background 0.2s;"
                    onmouseover="this.style.background = 'var(--is)'" onmouseout="this.style.background = 'transparent'">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i style="color: var(--in); font-size: 0.8rem;" class="fa-solid ${r.type === 'disease' ? 'fa-virus' : r.type === 'symptom' ? 'fa-notes-medical' : r.type === 'medication' ? 'fa-pills' : r.type === 'patient' ? 'fa-user' : 'fa-folder'}"></i>
                    <div style="flex: 1;">
                        <p style="font-weight: 700; font-size: 0.9rem;">${r.name}</p>
                        <p style="font-size: 0.75rem; color: var(--mu);">${r.category}${r.email ? ' • ' + r.email : ''}</p>
                    </div>
                    <i class="fa-solid fa-chevron-right" style="color: var(--mu); font-size: 0.75rem;"></i>
                </div>
            </button>
        `).join('');
        
        if (searchResults.length > 8) {
            results.innerHTML += `<div style="padding: 12px 16px; text-align: center; font-size: 0.75rem; color: var(--mu);">+ ${searchResults.length - 8} resultados</div>`;
        }
        
        results.style.display = searchResults.length > 0 ? 'block' : 'none';
    }

    // Go to search result
    function goToResult(type, id, name) {
        document.getElementById('searchQuery').value = '';
        document.getElementById('searchResults').style.display = 'none';
        
        if (type === 'disease') {
            switchView('doencas');
            setTimeout(() => {
                const el = document.getElementById('disease-' + id);
                if (el) {
                    el.scrollIntoView({behavior: 'smooth', block: 'center'});
                    el.classList.add('pulse-item');
                    setTimeout(() => el.classList.remove('pulse-item'), 4000);
                    openInfoModal('disease', id);
                }
            }, 100);
        } else if (type === 'symptom') {
            switchView('biblioteca_sintomas');
            setTimeout(() => {
                const el = document.getElementById('symptom-' + id);
                if (el) {
                    el.scrollIntoView({behavior: 'smooth', block: 'center'});
                    el.classList.add('pulse-item');
                    setTimeout(() => el.classList.remove('pulse-item'), 4000);
                    openInfoModal('symptom', id);
                }
            }, 100);
        } else if (type === 'medication') {
            switchView('remedios');
            setTimeout(() => {
                const el = document.getElementById('medication-' + id);
                if (el) {
                    el.scrollIntoView({behavior: 'smooth', block: 'center'});
                    el.classList.add('pulse-item');
                    setTimeout(() => el.classList.remove('pulse-item'), 4000);
                    openInfoModal('medication', id);
                }
            }, 100);
        } else if (type === 'patient') {
            switchView('diagnostico');
            selectPatient(id, name);
        } else if (type === 'category') {
            goToCategory(id);
        }
    }

    function goToCategory(id) {
        switchView('doencas');
        window.location.hash = 'categoria-' + id;
        setTimeout(() => {
            const el = document.getElementById('disease-category-' + id);
            if (el) {
                el.scrollIntoView({behavior: 'smooth', block: 'start'});
                el.classList.add('pulse-item');
                setTimeout(() => el.classList.remove('pulse-item'), 4000);
            }
        }, 120);
    }

    function openStandardAlert(message, title = 'Atenção') {
        document.getElementById('standardAlertTitle').textContent = title;
        document.getElementById('standardAlertMessage').textContent = message;
        document.getElementById('standardAlertModal').classList.add('open');
    }

    function closeStandardAlert() {
        document.getElementById('standardAlertModal').classList.remove('open');
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function pickItem(type, id) {
        const dictionaries = {
            disease: appState.allDiseases,
            symptom: appState.allSymptoms,
            category: appState.allCategories,
            medication: appState.allMedications
        };

        return (dictionaries[type] || []).find(item => Number(item.id) === Number(id));
    }

    function openInfoModal(type, id) {
        const item = pickItem(type, id);
        if (!item) return;

        const config = {
            disease: { kind: 'Doença', icon: 'fa-virus' },
            symptom: { kind: 'Sintoma', icon: 'fa-notes-medical' },
            category: { kind: 'Categoria clínica', icon: 'fa-layer-group' },
            medication: { kind: 'Remédio', icon: 'fa-pills' }
        }[type] || { kind: 'Informação clínica', icon: 'fa-circle-info' };

        const description = item.brief_description
            || item.description
            || item.descriptions?.[0]?.content
            || 'Informação clínica em atualização.';

        document.getElementById('infoModalKind').textContent = config.kind;
        document.getElementById('infoModalTitle').textContent = item.name;
        document.getElementById('infoModalIcon').innerHTML = `<i class="fa-solid ${config.icon}"></i>`;
        document.getElementById('infoModalDescription').textContent = description;
        document.getElementById('infoModalFacts').innerHTML = renderInfoFacts(type, item);
        document.getElementById('infoModalRelated').innerHTML = renderInfoRelated(type, item);
        document.getElementById('infoModal').classList.add('open');
    }

    function closeInfoModal() {
        document.getElementById('infoModal').classList.remove('open');
    }

    function renderInfoFacts(type, item) {
        const facts = [];

        if (type === 'disease') {
            facts.push(['CID', item.icd_code || 'Não informado']);
            facts.push(['Severidade', formatSeverity(item.severity)]);
            facts.push(['Faixa etária', `${item.min_age ?? 0} a ${item.max_age ?? 120} anos`]);
            facts.push(['Gênero alvo', formatGender(item.target_gender)]);
        }

        if (type === 'symptom') {
            facts.push(['Severidade', formatSeverity(item.severity)]);
            facts.push(['Doenças relacionadas', `${item.diseases?.length || 0}`]);
        }

        if (type === 'medication') {
            facts.push(['Princípio ativo', item.active_principle || 'Não informado']);
            facts.push(['Condições associadas', `${item.diseases?.length || 0}`]);
        }

        if (type === 'category') {
            facts.push(['Registros', `${item.diseases_count || 0}`]);
            facts.push(['Tipo', 'Agrupamento clínico']);
        }

        return facts.map(([label, value]) => `
            <div class="info-kv">
                <span>${escapeHtml(label)}</span>
                <strong>${escapeHtml(value)}</strong>
            </div>
        `).join('');
    }

    function renderInfoRelated(type, item) {
        if (type === 'disease') {
            return renderPills('Sintomas relacionados', item.symptoms, 'fa-notes-medical')
                + renderPills('Medicamentos associados', item.medications, 'fa-pills')
                + renderPills('Categorias', item.categories, 'fa-layer-group');
        }

        if (type === 'symptom') {
            return renderPills('Doenças relacionadas', item.diseases, 'fa-virus');
        }

        if (type === 'medication') {
            return renderPills('Usado em condições como', item.diseases, 'fa-virus');
        }

        if (type === 'category') {
            const diseases = appState.allDiseases.filter(disease =>
                Number(disease.category_id || 0) === Number(item.id)
                || (disease.categories || []).some(category => Number(category.id) === Number(item.id))
            );

            return renderPills('Doenças nesta categoria', diseases, 'fa-virus');
        }

        return '';
    }

    function renderPills(title, items, icon) {
        if (!items || items.length === 0) return '';

        return `
            <div style="margin-top:18px;">
                <h3 style="font-size:.8rem;font-weight:900;text-transform:uppercase;letter-spacing:.08em;color:var(--in);margin-bottom:10px;">${escapeHtml(title)}</h3>
                <div class="info-list">
                    ${items.slice(0, 10).map(item => `<span class="info-pill"><i class="fa-solid ${icon}"></i>${escapeHtml(item.name)}</span>`).join('')}
                </div>
            </div>
        `;
    }

    function formatSeverity(severity) {
        const map = {
            low: 'Leve',
            medium: 'Moderada',
            high: 'Alta',
            critical: 'Crítica',
            1: 'Leve',
            2: 'Moderada',
            3: 'Alta',
            4: 'Crítica'
        };

        return map[severity] || severity || 'Não informada';
    }

    function formatGender(gender) {
        return {
            both: 'Todos',
            m: 'Masculino',
            f: 'Feminino',
            male: 'Masculino',
            female: 'Feminino'
        }[gender] || 'Todos';
    }

    // Set form loading state
    // Validate form before submission
    function validateDiagnosticForm(event) {
        // Get form data
        const gender = document.getElementById('gender')?.value;
        const age = document.getElementById('age')?.value;
        const weight = document.getElementById('weight')?.value;
        const height = document.getElementById('height')?.value;
        const symptoms = appState.selectedSintomas || [];

        // Helper function to show error
        function showError(message) {
            openStandardAlert(message, 'Dados incompletos');
        }

        // Validate all required fields
        if (!gender || !age || !weight || !height) {
            showError('⚠️ Por favor, preencha todos os dados biométricos (Género, Idade, Peso, Altura).');
            event.preventDefault();
            return false;
        }

        // Validate age range
        if (age < 1 || age > 120) {
            showError('⚠️ Idade inválida. Digite um valor entre 1 e 120 anos.');
            event.preventDefault();
            return false;
        }

        // Validate weight and height
        if (weight <= 0 || weight > 500) {
            showError('⚠️ Peso inválido. Digite um valor entre 0 e 500 kg.');
            event.preventDefault();
            return false;
        }

        // Height can be in meters (1.0 to 3.0) or cm (100 to 300)
        if (height <= 0 || (height <= 10 && height < 0.5) || (height > 10 && height > 300)) {
            showError('⚠️ Altura inválida. Digite um valor entre 0,5m e 3,0m (ou 50 a 300 cm).');
            event.preventDefault();
            return false;
        }

        // Validate minimum symptoms
        if (symptoms.length < 8) {
            showError(`⚠️ Selecione pelo menos 8 sintomas. Atualmente tem ${symptoms.length}.`);
            event.preventDefault();
            return false;
        }

        // All validations passed - show loading modal
        setFormLoading(true);
        return true;
    }

    function setFormLoading(state) {
        document.getElementById('loadingModal').style.display = state ? 'flex' : 'none';
    }

    // Toggle mobile menu
    function toggleMobileMenu() {
        // Mobile menu toggled
    }

    // Listen for theme changes
    window.addEventListener('storage', function(e) {
        if (e.key === 'cl-theme') {
            document.documentElement.setAttribute('data-theme', e.newValue || 'light');
        }
    });

    document.getElementById('infoModal')?.addEventListener('click', function(event) {
        if (event.target === this) closeInfoModal();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeInfoModal();
            closeStandardAlert();
        }
    });

    // Initialize - show first section
    window.addEventListener('DOMContentLoaded', function() {
        switchView(appState.view);
        applyDiagnosticPrefill();
        @if($errors->any() || session('error'))
            openStandardAlert(@js(session('error') ?: $errors->first()), 'Erro ao processar diagnóstico');
        @endif
    });
</script>
</body>
</html>
