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
        .al { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: var(--rs); font-size: 0.84rem; font-weight: 500; border: 1px solid; }
        .al-rd { background: var(--rb); border-color: var(--rbd); color: var(--rd); }
        .al-wn { background: var(--wb); border-color: var(--wbd); color: var(--wn); }
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
            @if(Auth::user()->role === 'doctor')
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
                    id="btn-biblioteca">
                <i class="fa-solid fa-book-medical"></i> Lista de Sintomas
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($diseases as $disease)
                    <article id="disease-{{ $disease->id }}"
                             style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);"
                             class="p-6 rounded-[32px] hover:border-indigo-500 transition-all group shadow-sm">
                        <div class="flex justify-between items-start mb-6">
                            <div style="background: var(--is); color: var(--in);" class="p-4 rounded-2xl"><i class="fa-solid fa-virus"></i></div>
                            <span style="background: var(--sf2); border: 1px solid var(--bd); color: var(--mu);" class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $disease->category->name ?? 'Geral' }}</span>
                        </div>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition-colors">{{ $disease->name }}</h3>
                        <p style="color: var(--mu);" class="text-sm mb-6 line-clamp-2">{{ $disease->description }}</p>
                        <a href="{{ route('chat.index', ['prompt' => 'Forneça detalhes clínicos sobre: ' . $disease->name]) }}" 
                           class="block text-center w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm hover:shadow-lg hover:shadow-indigo-200 transition-all">Detalhes via IA</a>
                    </article>
                    @endforeach
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
                    <a id="symptom-{{ $symptom->id }}"
                       href="{{ route('chat.index', ['prompt' => 'Análise clínica do sintoma: ' . $symptom->name]) }}" 
                       style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);"
                       class="relative overflow-hidden p-6 rounded-[24px] hover:scale-[1.02] hover:shadow-xl transition-all group">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                            <span class="font-bold group-hover:text-indigo-600">{{ $symptom->name }}</span>
                        </div>
                        <div style="color: var(--mu);" class="text-[10px] font-black uppercase flex items-center gap-2">
                            <span>Consultar Protocolo</span>
                            <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform"></i>
                        </div>
                        <i style="color: var(--sf2);" class="fa-solid fa-notes-medical absolute -bottom-4 -right-4 text-6xl group-hover:opacity-50 transition-opacity"></i>
                    </a>
                    @empty
                    <div style="background: var(--sf); border: 1px solid var(--bd); color: var(--mu);" class="p-8 rounded-[24px] col-span-full text-center">
                        <i class="fa-solid fa-inbox text-4xl mb-4"></i>
                        <p class="font-bold">Nenhum sintoma disponível</p>
                    </div>
                    @endforelse
                </div>
            </section>

            {{-- 3. REALIZAR DIAGNÓSTICO --}}
            <section id="section-diagnostico" style="display: none;">
                <div class="max-w-3xl mx-auto text-center mb-10">
                    <h2 style="color: var(--tx);" class="text-3xl font-black uppercase tracking-tight">Realizar Diagnóstico</h2>
                    <p style="color: var(--mu);" class="mt-2 font-medium">Triagem automática baseada em perfis biométricos.</p>
                </div>

                <!-- Session error alert -->
                @if($errors->any() || session('error'))
                <div class="max-w-3xl mx-auto mb-6">
                    <div class="al al-rd" style="margin-bottom: 0;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>
                            <strong>Erro ao processar diagnóstico:</strong>
                            @if(session('error'))
                            <p>{{ session('error') }}</p>
                            @else
                            <p>{{ $errors->first() }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('processar.diagnostico') }}" method="POST" class="max-w-3xl mx-auto space-y-6">
                    @csrf
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

                        <!-- Error notification component -->
                        <div id="formErrorNotification" style="display: none; margin-bottom: 16px;" class="al al-rd">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div>
                                <strong id="formErrorMessage">Erro ao processar diagnóstico</strong>
                            </div>
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
                            <button type="button" onclick="addSymptom({{ $symptom->id }}, '{{ $symptom->name }}')"
                                    style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);"
                                    class="text-left px-3 py-2 rounded-lg text-xs font-bold transition-colors hover:border-indigo-500">
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

            {{-- 4. CATEGORIAS --}}
            <section id="section-categorias" style="display: none;">
                <h2 style="color: var(--tx);" class="text-3xl font-black mb-8 uppercase tracking-tight">Categorias & Especialidades</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($categories as $category)
                    <div id="category-{{ $category->id }}"
                         style="background: var(--sf); border: 1px solid var(--bd); color: var(--tx);"
                         class="p-6 rounded-[24px] flex items-center gap-4 group hover:border-indigo-500 transition-all">
                        <div style="background: var(--is); color: var(--in);" class="w-12 h-12 flex items-center justify-center rounded-xl"><i class="fa-solid fa-folder-tree"></i></div>
                        <div>
                            <h4 class="font-bold">{{ $category->name }}</h4>
                            <span style="color: var(--mu);" class="text-[10px] font-bold uppercase tracking-widest">{{ $category->diseases_count }} Registros</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

        </div>
    </main>

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
        allCategories: {!! $categories->toJson() !!}
    };
    
    // Set initial view for doctors
    @auth
    @if(Auth::user()->role === 'doctor')
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
        document.getElementById('searchSymptom').value = '';
        document.getElementById('symptomDropdown').style.display = 'none';
    }

    // Remove symptom
    function removeSymptom(id) {
        appState.selectedSintomas = appState.selectedSintomas.filter(s => s.id !== id);
        renderSelectedSymptoms();
    }

    // Render selected symptoms
    function renderSelectedSymptoms() {
        const container = document.getElementById('selectedSymptoms');
        container.innerHTML = appState.selectedSintomas.map(s => `
            <div class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                <span>${s.name}</span>
                <button type="button" onclick="removeSymptom(${s.id})"><i class="fa-solid fa-xmark"></i></button>
            </div>
        `).join('');
        
        // Update hidden JSON field with symptom IDs
        const ids = appState.selectedSintomas.map(s => s.id);
        document.getElementById('symptomIdsJson').value = JSON.stringify(ids);
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
            <button type="button" onclick="addSymptom(${s.id}, '${s.name}')" style="width: 100%; text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--bd); color: var(--tx); transition: background 0.2s; font-weight: 600; font-size: 0.9rem;"
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
            <button type="button" onclick="selectPatient(${p.id}, '${p.name}')" style="width: 100%; text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--bd); color: var(--tx); transition: background 0.2s;"
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
            <button type="button" onclick="goToResult('${r.type}', ${r.id}, '${r.name}')" style="width: 100%; text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--bd); color: var(--tx); transition: background 0.2s;"
                    onmouseover="this.style.background = 'var(--is)'" onmouseout="this.style.background = 'transparent'">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i style="color: var(--in); font-size: 0.8rem;" class="fa-solid ${r.type === 'disease' ? 'fa-virus' : r.type === 'symptom' ? 'fa-notes-medical' : r.type === 'patient' ? 'fa-user' : 'fa-folder'}"></i>
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
                }
            }, 100);
        } else if (type === 'patient') {
            switchView('diagnostico');
            selectPatient(id, name);
        } else if (type === 'category') {
            switchView('categorias');
            setTimeout(() => {
                const el = document.getElementById('category-' + id);
                if (el) {
                    el.scrollIntoView({behavior: 'smooth', block: 'center'});
                    el.classList.add('pulse-item');
                    setTimeout(() => el.classList.remove('pulse-item'), 4000);
                }
            }, 100);
        }
    }

    // Set form loading state
    // Validate form before submission
    function validateDiagnosticForm(event) {
        // Hide previous error if exists
        document.getElementById('formErrorNotification').style.display = 'none';

        // Get form data
        const gender = document.getElementById('gender')?.value;
        const age = document.getElementById('age')?.value;
        const weight = document.getElementById('weight')?.value;
        const height = document.getElementById('height')?.value;
        const symptoms = appState.selectedSintomas || [];

        // Helper function to show error
        function showError(message) {
            const notif = document.getElementById('formErrorNotification');
            const msg = document.getElementById('formErrorMessage');
            msg.textContent = message;
            notif.style.display = 'flex';
            notif.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

    // Initialize - show first section
    window.addEventListener('DOMContentLoaded', function() {
        switchView(appState.view);
    });
</script>
</body>
</html>
