<!DOCTYPE html>
<html lang="pt-ao">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinicaly AI | Painel de Diagnóstico</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        indigo: { 500: '#735ab8', 600: '#6d55b1', 700: '#58448f' },
                        slate: { 950: '#020617' }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Dosis', sans-serif; }
        [x-cloak] { display: none !important; }
        .dark .bg-main { background-color: #020617 !important; }
        .nav-link { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-500" 
      x-data="{ 
        view: 'doencas', 
        darkMode: false, 
        mobileMenu: false, 
        modalLoading: false, 
        searchSymptom: '',
        showSymptomList: false,
        selectedSintomas: [],
        allSymptoms: {{ $symptoms->toJson() }},
        get filteredSymptoms() {
            if (this.searchSymptom.length < 2) return [];
            return this.allSymptoms.filter(i => i.name.toLowerCase().includes(this.searchSymptom.toLowerCase()))
        }
      }" 
      :class="{ 'dark': darkMode }">

    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        
        {{-- SIDEBAR --}}
        <aside :class="mobileMenu ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               class="fixed md:relative z-50 w-72 h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 p-6 flex flex-col transition-transform duration-300">
            
            <div class="flex items-center justify-between mb-10">
               <div class="flex items-center gap-2">
                    <img src="{{ asset('Proosta-logo4.png') }}" alt="Logo" width="180px" class="w-32 md:w-44">
                </div>
            </div>

            <nav class="flex-1 space-y-2">
                <button @click="view = 'doencas'" :class="view === 'doencas' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
                        class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold">
                    <i class="fa-solid fa-virus-covid"></i> Base de Doenças
                </button>
                <button @click="view = 'biblioteca_sintomas'" :class="view === 'biblioteca_sintomas' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
                        class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold">
                    <i class="fa-solid fa-book-medical"></i> Lista de Sintomas
                </button>
                <button @click="view = 'diagnostico'" :class="view === 'diagnostico' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
                        class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold">
                    <i class="fa-solid fa-stethoscope"></i> Realizar Diagnóstico
                </button>
                <button @click="view = 'categorias'" :class="view === 'categorias' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'"
                        class="nav-link w-full flex items-center gap-4 px-4 py-3 rounded-2xl font-bold">
                    <i class="fa-solid fa-layer-group"></i> Categorias
                </button>
            </nav>

            <button @click="darkMode = !darkMode" class="mt-auto w-full flex items-center justify-center gap-3 py-3 rounded-2xl border border-slate-200 dark:border-slate-700 font-bold text-sm">
                <i :class="darkMode ? 'fa-solid fa-sun text-yellow-500' : 'fa-solid fa-moon text-indigo-500'"></i>
                <span x-text="darkMode ? 'Modo Claro' : 'Modo Escuro'"></span>
            </button>
        </aside>

        <main class="flex-1 overflow-y-auto bg-main relative custom-scroll">
            {{-- HEADER DE PESQUISA --}}
            <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 p-4 md:p-6">
                <div class="max-w-6xl mx-auto flex items-center gap-4">
                    <button @click="mobileMenu = true" class="md:hidden p-2 text-indigo-600"><i class="fa-solid fa-bars-staggered"></i></button>
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" placeholder="Pesquisar na base clínica..." 
                               class="w-full bg-slate-100 dark:bg-slate-900 border-none px-12 py-3 rounded-2xl outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
            </header>

            <div class="p-6 md:p-12 max-w-6xl mx-auto">
                
                {{-- 1. BASE DE DOENÇAS --}}
                <section x-show="view === 'doencas'" x-transition>
                    <h2 class="text-3xl font-black mb-8 dark:text-white uppercase tracking-tight">Doenças Registradas</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($diseases as $disease)
                        <article class="bg-white dark:bg-slate-900 p-6 rounded-[32px] border border-slate-200 dark:border-slate-800 hover:border-indigo-500 transition-all group shadow-sm">
                            <div class="flex justify-between items-start mb-6">
                                <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-2xl"><i class="fa-solid fa-virus"></i></div>
                                <span class="bg-slate-100 dark:bg-slate-800 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest text-slate-500">{{ $disease->category->name ?? 'Geral' }}</span>
                            </div>
                            <h3 class="text-xl font-bold mb-2 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $disease->name }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 line-clamp-2">{{ $disease->description }}</p>
                            <a href="{{ route('chat.index', ['prompt' => 'Forneça detalhes clínicos sobre: ' . $disease->name]) }}" 
                               class="block text-center w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm hover:shadow-lg hover:shadow-indigo-200 transition-all">Detalhes via IA</a>
                        </article>
                        @endforeach
                    </div>
                </section>

                {{-- 2. LISTA DE SINTOMAS (ESTILIZADA) --}}
                <section x-show="view === 'biblioteca_sintomas'" x-cloak x-transition>
                    <div class="mb-10">
                        <h2 class="text-3xl font-black dark:text-white uppercase tracking-tight">Biblioteca de Sintomas</h2>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-1">Clique para análise detalhada via Inteligência Clínica</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($symptoms as $symptom)
                        <a href="{{ route('chat.index', ['prompt' => 'Análise clínica do sintoma: ' . $symptom->name]) }}" 
                           class="relative overflow-hidden p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[24px] hover:scale-[1.02] hover:shadow-xl transition-all group">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                                <span class="font-bold text-slate-700 dark:text-white group-hover:text-indigo-600">{{ $symptom->name }}</span>
                            </div>
                            <div class="text-[10px] font-black text-slate-400 uppercase flex items-center gap-2">
                                <span>Consultar Protocolo</span>
                                <i class="fa-solid fa-arrow-right-long group-hover:translate-x-2 transition-transform"></i>
                            </div>
                            <i class="fa-solid fa-notes-medical absolute -bottom-4 -right-4 text-slate-50 dark:text-slate-800 text-6xl group-hover:text-indigo-50/50 transition-colors"></i>
                        </a>
                        @endforeach
                    </div>
                </section>

                {{-- 3. REALIZAR DIAGNÓSTICO (AUTOCOMPLETE) --}}
                <section x-show="view === 'diagnostico'" x-cloak x-transition>
                    <div class="max-w-3xl mx-auto text-center mb-10">
                        <h2 class="text-3xl font-black dark:text-white uppercase tracking-tight">Realizar Diagnóstico</h2>
                        <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Triagem automática baseada em perfis biométricos.</p>
                    </div>

                    <form action="{{ route('processar.diagnostico') }}" method="POST" class="max-w-3xl mx-auto space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 bg-white dark:bg-slate-900 p-8 rounded-[35px] border border-slate-100 dark:border-slate-800 shadow-sm">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Idade</label>
                                <input type="number" name="age" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Gênero</label>
                                <select name="gender" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-indigo-500">
                                    <option value="m">Masculino</option>
                                    <option value="f">Feminino</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-400 mb-2">Peso (kg)</label>
                                <input type="number" name="weight" step="0.1" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-900 p-8 rounded-[35px] border border-slate-100 dark:border-slate-800 shadow-sm relative">
                            <div class="flex justify-between items-center mb-4">
                                <label class="text-xs font-bold uppercase text-slate-400 ml-1">Sintomas Detectados</label>
                                <button type="button" @click="showSymptomList = !showSymptomList" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                                    <span x-text="showSymptomList ? '- Fechar Lista' : '+ Ver Lista Completa'"></span>
                                </button>
                            </div>

                            <div class="relative mb-6">
                                <input type="text" x-model="searchSymptom" placeholder="Pesquisar sintoma para adicionar..." 
                                       class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-transparent focus:border-indigo-500 rounded-2xl py-4 px-6 transition-all">
                                
                                {{-- MENU FLUTUANTE --}}
                                <div x-show="filteredSymptoms.length > 0" x-cloak
                                     class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 max-h-60 overflow-y-auto">
                                    <template x-for="symptom in filteredSymptoms" :key="symptom.id">
                                        <button type="button" @click="if(!selectedSintomas.find(s => s.id === symptom.id)) { selectedSintomas.push(symptom); } searchSymptom = ''"
                                                class="w-full text-left px-6 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 font-bold text-sm border-b dark:border-slate-700 last:border-none">
                                            + <span x-text="symptom.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div x-show="showSymptomList" x-transition class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl max-h-48 overflow-y-auto custom-scroll">
                                @foreach($symptoms as $symptom)
                                <button type="button" @click="if(!selectedSintomas.find(s => s.id === {{ $symptom->id }})) { selectedSintomas.push({id: {{ $symptom->id }}, name: '{{ $symptom->name }}' }); }"
                                        class="text-left px-3 py-2 bg-white dark:bg-slate-800 rounded-lg text-xs font-bold hover:bg-indigo-50 transition-colors border border-slate-100 dark:border-slate-700">
                                    + {{ $symptom->name }}
                                </button>
                                @endforeach
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <template x-for="(s, index) in selectedSintomas" :key="s.id">
                                    <div class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                                        <span x-text="s.name"></span>
                                        <input type="hidden" name="items[]" :value="s.id">
                                        <button type="button" @click="selectedSintomas.splice(index, 1)"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <button type="submit" @click="modalLoading = true"
                                class="w-full bg-indigo-600 text-white py-5 rounded-[25px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all">
                            Processar Diagnóstico
                        </button>
                    </form>
                </section>

                {{-- 4. CATEGORIAS --}}
                <section x-show="view === 'categorias'" x-cloak x-transition>
                    <h2 class="text-3xl font-black mb-8 dark:text-white uppercase tracking-tight">Categorias & Especialidades</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($categories as $category)
                        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[24px] flex items-center gap-4 group hover:border-indigo-500 transition-all">
                            <div class="w-12 h-12 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-xl"><i class="fa-solid fa-folder-tree"></i></div>
                            <div>
                                <h4 class="font-bold dark:text-white">{{ $category->name }}</h4>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $category->diseases_count }} Registros</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>

            </div>
        </main>
    </div>

    {{-- MODAL LOADING --}}
    <div x-show="modalLoading" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-900 p-10 rounded-[40px] text-center shadow-2xl">
            <div class="animate-pulse">
                <i class="fa-solid fa-microscope text-7xl text-indigo-600 mb-6 animate-bounce"></i>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Cruzando Dados...</h3>
            </div>
        </div>
    </div>
    <script>
        function handleDiagnosis(event) {
            const form = event.target;
            const formData = new FormData(form);

            this.modalLoading = true;

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                this.modalLoading = false;
                this.results = data.results;
                this.showResultsModal = true;
            })
            .catch(error => {
                this.modalLoading = false;
                alert('Erro ao processar diagnóstico. Tente novamente.');
            });
        }
    </script>
</body>

 
</html>