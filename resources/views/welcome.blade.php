<!DOCTYPE html>
<html lang="pt-ao" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinicaly | Gestão Clínica Avançada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#f6f4fa',
                            100: '#ebe6f4',
                            200: '#d3cbe7',
                            300: '#b1a1d5',
                            400: '#8c73c0',
                            500: '#735ab8',
                            600: '#6d55b1',
                            700: '#58448f',
                            800: '#483875',
                            900: '#3d3060',
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Dosis:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Dosis', sans-serif !important;
            scroll-behavior: smooth;
        }

        /* Efeitos de Revelação no Scroll (Multidirecional) */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.active, .reveal-left.active, .reveal-right.active {
            opacity: 1;
            transform: translate(0);
        }

        /* Glassmorphism Premium */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.75) !important;
        }
        
        /* Nav Scrolled State */
        .nav-scrolled {
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }
        .dark .nav-scrolled {
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, #f8fafc 0%, #eff6ff 100%);
        }
        .dark .hero-gradient {
            background: radial-gradient(circle at top right, #0f172a 0%, #020617 100%);
        }

        /* Cards Refinados */
        .medical-card {
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 30px;
        }
        .medical-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 30px 60px -15px rgba(109, 85, 177, 0.2);
            border-color: rgba(109, 85, 177, 0.3);
        }

        /* 3D Estética - Por que Clinicaly */
        .perspective-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            height: 120px;
            margin: 40px auto;
            transform-style: preserve-3d;
        }
        .layer {
            position: absolute;
            width: 100%;
            height: 100px;
            left: 0;
            transform: perspective(1200px) rotateX(45deg);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .layer-1 {
            background: linear-gradient(135deg, #6d55b1, #483875);
            top: 0;
            z-index: 3;
            color: white;
            font-weight: 800;
            font-size: 2.2rem;
            box-shadow: 0 20px 40px rgba(109, 85, 177, 0.4);
            backdrop-filter: blur(5px);
        }
        .layer-1:hover {
            transform: perspective(1200px) rotateX(25deg) translateY(-15px) translateZ(20px);
            box-shadow: 0 30px 50px rgba(109, 85, 177, 0.6);
        }
        .layer-2 {
            background: rgba(109, 85, 177, 0.4);
            top: -20px;
            z-index: 2;
            backdrop-filter: blur(4px);
        }
        .layer-3 {
            background: rgba(109, 85, 177, 0.15);
            top: -40px;
            z-index: 1;
        }

        /* Jornada Steps Refinada */
        .step-line {
            position: absolute;
            left: 28px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #e2e8f0 0%, #6d55b1 50%, #e2e8f0 100%);
            z-index: 0;
            opacity: 0.5;
        }
        .dark .step-line {
            background: linear-gradient(to bottom, #1e293b 0%, #6d55b1 50%, #1e293b 100%);
        }
        .step-dot {
            position: relative;
            z-index: 10;
            width: 56px;
            height: 56px;
            background: white;
            border: 2px solid #6d55b1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 0 0 4px rgba(109, 85, 177, 0.1);
        }
        .step-dot:hover {
            transform: scale(1.15) rotate(5deg);
            background: #6d55b1;
            box-shadow: 0 0 20px rgba(109, 85, 177, 0.4);
        }
        .step-dot:hover i {
            color: white !important;
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        .footer-wave {
            line-height: 0;
            margin-top: -1px;
        }

        /* Navigation Micro-interactions */
        nav a {
            position: relative;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #6d55b1;
            transition: all 0.3s ease-out;
            transform: translateX(-50%);
        }
        nav a:hover::after {
            width: 70%;
        }

        /* Dark Mode Ajustes Profundos */
        .dark body {
            background-color: #020617 !important;
            color: #f1f5f9 !important;
        }
        .dark .text-slate-500, .dark .text-slate-400, .dark p {
            color: #94a3b8 !important;
        }
        .dark section, .dark .bg-white, .dark .bg-slate-50, 
        .dark #sobre, .dark #tecnologia, .dark #estudo, .dark #jornada {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
        }
        .dark .medical-card, .dark .bg-slate-50 {
            background-color: #1e293b !important;
            border: 1px solid #334155 !important;
        }
        .dark .medical-card:hover {
            border-color: #4f46e5 !important;
            box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.25);
        }
        .dark h1, .dark h2, .dark h3, .dark h4, .dark strong {
            color: #f8fafc !important;
        }
        .dark .step-dot {
            background: #0f172a;
        }
        
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased hero-gradient transition-colors duration-500" x-data="{ 
    loginModal: false, 
    mobileMenu: false, 
    symptomModal: false, 
    symptomSearch: '', 
    symptomResults: [], 
    searchingSymptoms: false,
    searchSymptoms() {
        // Para a busca se o campo estiver vazio
        if (!this.symptomSearch || this.symptomSearch.trim().length === 0) {
            this.symptomResults = [];
            this.searchingSymptoms = false;
            return;
        }
        
        this.searchingSymptoms = true;
        
        fetch('{{ route('api.symptoms.search') }}?query=' + encodeURIComponent(this.symptomSearch))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta da API');
                }
                return response.json();
            })
            .then(data => {
                this.symptomResults = data.symptoms || [];
                this.searchingSymptoms = false;
            })
            .catch(error => {
                console.error('Erro na busca:', error);
                this.searchingSymptoms = false;
                this.symptomResults = [];
            });
    }
}" @click.window="if ($event.target.id === 'symptom-modal-backdrop') symptomModal = false">

    <div x-show="loginModal" x-cloak 
        x-transition:enter="transition ease-out duration-500 cubic-bezier(0.16, 1, 0.3, 1)"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md">

        <div @click.away="loginModal = false"
            class="bg-white dark:bg-slate-800 w-full max-w-sm rounded-[32px] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] border border-slate-100 dark:border-slate-700 overflow-hidden transform transition-all">

            <div class="p-10 text-center">
                <div class="mb-6 flex justify-center">
                    <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center rotate-3 hover:rotate-12 transition-transform duration-500 cursor-pointer">
                        <i class="fa-solid fa-hand-sparkles text-2xl animate-pulse-slow"></i>
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-3 tracking-tight">Seja bem vindo!</h3>
                <p class="text-slate-500 dark:text-slate-400 font-medium mb-8 leading-relaxed">
                    Deseja acessar nossos serviços? <br>
                    <span class="text-indigo-600 dark:text-indigo-400">Faça login ou cadastre-se para continuar.</span>
                </p>

                <div class="space-y-3">
                    <a href="{{ route('login') }}"
                        class="block w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 hover:shadow-[0_10px_25px_-5px_rgba(79,70,229,0.4)] transition-all duration-300 active:scale-95">
                        Entrar agora
                    </a>

                    <a href="{{ route('register') }}"
                        class="block w-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-white py-4 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-slate-600 hover:shadow-lg transition-all duration-300 active:scale-95">
                        Criar conta gratuita
                    </a>

                    <button @click="loginModal = false"
                        class="pt-4 text-slate-400 dark:text-slate-500 text-sm font-semibold hover:text-slate-600 dark:hover:text-slate-300 transition-colors duration-300">
                        Talvez mais tarde
                    </button>
                </div>
            </div>

            <div class="bg-slate-50/50 dark:bg-slate-800/50 py-4 border-t border-slate-50 dark:border-slate-700 text-center">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">Clinicaly • Angola</span>
            </div>
        </div>
    </div>

    <!-- Modal de Busca de Sintomas -->
    <div x-show="symptomModal" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        id="symptom-modal-backdrop"
        class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">

        <div @click.away="symptomModal = false; symptomSearch = ''; symptomResults = []; searchingSymptoms = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">

            <!-- Header do Modal -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-8 py-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold text-white mb-2 flex items-center gap-3">
                        <i class="fa-solid fa-stethoscope"></i>
                        Base de Sintomas
                    </h3>
                    <p class="text-indigo-100 text-sm font-medium">Pesquise sintomas e visualize descrições detalhadas</p>
                </div>
                <button @click="symptomModal = false; symptomSearch = ''; symptomResults = []; searchingSymptoms = false"
                    class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all duration-300 active:scale-90">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Barra de Pesquisa -->
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-5 top-1/2 -translate-y-1/2 text-indigo-500"></i>
                    <input x-ref="symptomInput"
                        x-model="symptomSearch"
                        @input.debounce.300ms="searchSymptoms()"
                        @keydown.escape.window="symptomModal = false; symptomSearch = ''; symptomResults = []; searchingSymptoms = false"
                        type="text"
                        placeholder="Digite um sintoma para pesquisar..."
                        class="w-full pl-12 pr-4 py-4 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 outline-none transition-all duration-300 font-medium">
                </div>
            </div>

            <!-- Resultados -->
            <div class="max-h-96 overflow-y-auto p-8">
                <!-- Loading -->
                <div x-show="searchingSymptoms" class="text-center py-12">
                    <div class="inline-block w-10 h-10 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                    <p class="mt-4 text-slate-500 dark:text-slate-400 font-medium">Buscando sintomas...</p>
                </div>

                <!-- Sem resultados -->
                <div x-show="!searchingSymptoms && symptomSearch.length > 0 && symptomResults.length === 0" 
                     class="text-center py-12">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-magnifying-glass text-3xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 font-medium">Nenhum sintoma encontrado para "<span x-text="symptomSearch" class="font-bold"></span>"</p>
                    <p class="text-slate-400 text-sm mt-2">Tente buscar por outro termo</p>
                </div>

                <!-- Lista de Sintomas -->
                <div x-show="!searchingSymptoms && symptomResults.length > 0" 
                     class="space-y-4">
                    <template x-for="symptom in symptomResults" :key="symptom.id">
                        <div class="group bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl p-6 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </div>
                                    <h4 x-text="symptom.name" class="text-lg font-bold text-slate-800 dark:text-white"></h4>
                                </div>
                                <span x-show="symptom.severity"
                                    class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
                                    :class="{
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': symptom.severity === 'leve',
                                        'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400': symptom.severity === 'moderado',
                                        'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': symptom.severity === 'grave'
                                    }"
                                    x-text="symptom.severity">
                                </span>
                            </div>
                            <p x-text="symptom.description" class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed font-medium"></p>
                        </div>
                    </template>
                </div>

                <!-- Estado Inicial -->
                <div x-show="!searchingSymptoms && symptomSearch.length === 0 && symptomResults.length === 0" 
                     class="text-center py-8">
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Digite acima para pesquisar sintomas na nossa base de dados</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-50 dark:bg-slate-900/50 px-8 py-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest">
                    <i class="fa-solid fa-database mr-1"></i> Base Clinicaly
                </span>
                <span class="text-xs text-slate-400 dark:text-slate-500 font-medium" x-show="symptomResults.length > 0">
                    <span x-text="symptomResults.length"></span> sintoma(s) encontrado(s)
                </span>
            </div>
        </div>
    </div>

    <nav id="navbar" class="fixed w-full z-50 glass transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2 hover:scale-105 transition-transform duration-300 cursor-pointer">
                <img src="{{ asset('Proosta-logo4.png') }}" alt="Logo" width="180px" class="w-32 md:w-44 drop-shadow-sm">
            </div>

            <div class="hidden md:flex gap-8 text-sm font-bold text-slate-500 dark:text-slate-300">
                <a href="#sobre" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-all px-3 py-2 rounded-lg">Sobre Nós</a>
                <a href="#tecnologia" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-all px-3 py-2 rounded-lg">Tecnologia</a>
                <a href="#estudo" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-all px-3 py-2 rounded-lg">Educação</a>
                <a href="#jornada" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-all px-3 py-2 rounded-lg">Jornada</a>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-indigo-100 dark:hover:bg-slate-800 text-slate-500 dark:text-indigo-300 transition-all duration-300 active:scale-90 focus:outline-none">
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-lg transition-transform hover:rotate-12"></i>
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-lg hidden transition-transform hover:rotate-90"></i>
                </button>

                @auth
                <a href="{{ url('/chat') }}"
                    class="hidden md:block bg-indigo-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-indigo-700 hover:shadow-[0_8px_20px_-5px_rgba(79,70,229,0.5)] transition-all duration-300 active:scale-95">
                    Ir para o Chat
                </a>
                @else
                <button @click="loginModal = true"
                    class="hidden md:block bg-indigo-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-indigo-700 hover:shadow-[0_8px_20px_-5px_rgba(79,70,229,0.5)] transition-all duration-300 active:scale-95">
                    Aceder ao Chat
                </button>
                @endauth

                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-slate-600 dark:text-slate-300 p-2 active:scale-90 transition-transform">
                    <i class="fa-solid fa-bars text-2xl" :class="{'hidden': mobileMenu}"></i>
                    <i class="fa-solid fa-xmark text-2xl hidden" :class="{'block': mobileMenu, 'hidden': !mobileMenu}"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileMenu" x-collapse 
            class="md:hidden bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 px-6 py-6 transition-all duration-300 shadow-xl absolute w-full">
            <div class="flex flex-col gap-4 text-center font-bold text-slate-600 dark:text-slate-300">
                <a @click="mobileMenu = false" href="#sobre" class="py-3 border-b border-slate-50 dark:border-slate-800 hover:text-indigo-600 transition-colors">Sobre Nós</a>
                <a @click="mobileMenu = false" href="#tecnologia" class="py-3 border-b border-slate-50 dark:border-slate-800 hover:text-indigo-600 transition-colors">Tecnologia</a>
                <a @click="mobileMenu = false" href="#estudo" class="py-3 border-b border-slate-50 dark:border-slate-800 hover:text-indigo-600 transition-colors">Educação</a>
                <a @click="mobileMenu = false" href="#jornada" class="py-3 border-b border-slate-50 dark:border-slate-800 hover:text-indigo-600 transition-colors">Jornada</a>
                
                <div class="pt-4">
                    @if (Route::has('login'))
                        @auth
                        <a href="{{ url('/chat') }}" class="inline-block w-full bg-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-md active:scale-95">
                            Ir para o Chat
                        </a>
                        @else
                        <button @click="loginModal = true; mobileMenu = false" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-md active:scale-95">
                            Aceder ao Chat
                        </button>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-40 pb-24 px-6 relative overflow-hidden">
        <div class="absolute top-20 right-10 w-72 h-72 bg-indigo-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-float dark:bg-indigo-900/20 dark:mix-blend-screen"></div>
        <div class="absolute top-40 left-10 w-72 h-72 bg-purple-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-float" style="animation-delay: 2s;"></div>

        <div class="max-w-6xl mx-auto text-center relative z-10 animate-fade-in-up">
            <span class="inline-block px-4 py-1.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-widest rounded-full mb-8 border border-indigo-100 dark:border-indigo-500/20 shadow-sm hover:shadow-md transition-shadow cursor-default">
                O Próximo Nível da Medicina em Angola
            </span>
            <h1 class="text-4xl md:text-7xl font-extrabold text-slate-900 dark:text-white leading-[1.1] mb-8 tracking-tight">
                Sua mente clínica <br>
                <span class="text-indigo-600 dark:text-indigo-400 bg-clip-text">potencializada por dados.</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-xl max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                Uma plataforma intuitiva que organiza prontuários, sugere diagnósticos baseados no CID-11 e transforma
                dados em decisões seguras.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button @click="loginModal = true"
                    class="w-full sm:w-auto bg-slate-900 dark:bg-indigo-600 text-white px-10 py-5 rounded-2xl font-bold hover:bg-slate-800 dark:hover:bg-indigo-500 hover:shadow-[0_15px_30px_-10px_rgba(0,0,0,0.3)] dark:hover:shadow-[0_15px_30px_-10px_rgba(79,70,229,0.4)] transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 group">
                    Começar Agora <i class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform duration-300"></i>
                </button>
                <button class="w-full sm:w-auto bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 px-10 py-5 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-slate-700 hover:shadow-lg transition-all duration-300 active:scale-95">
                    Ver Demonstração
                </button>
            </div>
        </div>
    </section>

    <section id="sobre" class="py-24 bg-white border-y border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row gap-16 items-start mb-20 reveal">
                <div class="md:w-1/3">
                    <div class="w-16 h-1.5 bg-indigo-600 rounded-full mb-6 transition-all duration-500 hover:w-24"></div>
                    <h2 class="text-4xl font-extrabold text-slate-900 leading-none uppercase">Quem <br> Somos?</h2>
                </div>
                <div class="md:w-2/3 reveal-right">
                    <p class="text-slate-500 text-xl leading-relaxed font-medium">
                        A <strong class="text-slate-800">Clinicaly</strong> nasceu para suprir a necessidade de
                        agilidade no setor de saúde angolano. Unimos o poder do processamento avançado de dados
                        com bases de dados oficiais para oferecer um ecossistema onde o médico foca no paciente,
                        enquanto nós cuidamos da estruturação dos dados e da precisão farmacológica.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="medical-card bg-slate-50 p-10 border border-slate-100 group reveal-left">
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:rotate-12 group-hover:scale-110 transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-stethoscope"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 uppercase text-sm tracking-widest">Apoio ao Diagnóstico</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Algoritmos avançados que analisam
                        sintomas e sugerem códigos CID-11 atualizados em tempo real.</p>
                </div>
                <div class="medical-card bg-indigo-600 dark:bg-indigo-700 p-10 text-white shadow-2xl shadow-indigo-200/50 dark:shadow-none group reveal" style="transition-delay: 100ms;">
                    <div class="w-14 h-14 bg-white/20 text-white rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:-rotate-12 group-hover:scale-110 transition-all duration-500 shadow-inner">
                        <i class="fa-solid fa-pills"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 uppercase text-sm tracking-widest">Cérebro Farmacêutico</h3>
                    <p class="text-indigo-100 text-sm leading-relaxed font-medium">Consulta imediata à base ANVISA para
                        evitar interações medicamentosas e erros de dosagem.</p>
                </div>
                <div class="medical-card bg-slate-50 p-10 border border-slate-100 group reveal-right" style="transition-delay: 200ms;">
                    <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:rotate-12 group-hover:scale-110 transition-all duration-500 shadow-sm">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 uppercase text-sm tracking-widest">Fluxo Educacional</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">A Clinicaly organiza suas consultas em
                        notas de estudo, facilitando a revisão de casos clínicos complexos.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="tecnologia" class="py-24 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="reveal-left">
                    <h2 class="text-4xl font-extrabold text-slate-900 mb-6 uppercase">Base de Conhecimento <br> Instantânea.</h2>
                    <p class="text-slate-500 text-lg mb-8 leading-relaxed font-medium">
                        Esqueça a busca manual em PDFs e livros pesados. Nosso sistema cruza dados da <strong class="text-slate-800">ANVISA</strong> e <strong class="text-slate-800">CID-11</strong> em
                        milissegundos, sugerindo posologias e identificando patologias com precisão técnica.
                    </p>
                    
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-[0_10px_30px_-15px_rgba(0,0,0,0.1)] hover:shadow-[0_10px_30px_-15px_rgba(79,70,229,0.2)] transition-all duration-500 transform hover:-translate-y-1">
                        <div class="flex gap-4 items-center mb-4 text-indigo-600 dark:text-indigo-400">
                            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center animate-pulse-slow">
                                <i class="fa-solid fa-search"></i>
                            </div>
                            <span class="font-bold text-sm uppercase tracking-widest">Simulação de Busca</span>
                        </div>
                        <div @click="symptomModal = true; $nextTick(() => $refs.symptomInput.focus())" class="h-12 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-700 flex items-center px-4 text-slate-400 text-sm font-medium cursor-text hover:border-indigo-300 transition-colors duration-300">
                            Digite um sintoma ou fármaco...
                            <span class="ml-auto w-1.5 h-5 bg-indigo-500 animate-pulse"></span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 reveal-right">
                    <div class="medical-card bg-indigo-50 dark:bg-indigo-900/20 p-8 border border-transparent hover:border-indigo-200 dark:hover:border-indigo-700 transition-all duration-500">
                        <div class="mb-4 text-indigo-500"><i class="fa-solid fa-book-medical text-2xl"></i></div>
                        <h4 class="font-extrabold text-indigo-900 dark:text-indigo-300 mb-2 uppercase text-sm tracking-tight">CID-11 Atualizado</h4>
                        <p class="text-sm text-indigo-700/80 dark:text-indigo-400 font-medium">Mapeamento completo de doenças para faturamento e preenchimento automático de prontuário.</p>
                    </div>
                    <div class="medical-card bg-emerald-50 dark:bg-emerald-900/20 p-8 border border-transparent hover:border-emerald-200 dark:hover:border-emerald-700 transition-all duration-500 md:translate-y-8">
                        <div class="mb-4 text-emerald-500"><i class="fa-solid fa-shield-halved text-2xl"></i></div>
                        <h4 class="font-extrabold text-emerald-900 dark:text-emerald-300 mb-2 uppercase text-sm tracking-tight">Base ANVISA</h4>
                        <p class="text-sm text-emerald-700/80 dark:text-emerald-400 font-medium">Acesso direto a bulas, interações perigosas e dosagens recomendadas pelo órgão regulador.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="estudo" class="py-24 bg-white dark:bg-slate-900 reveal">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-16 uppercase">Evolução Profissional em <span class="text-indigo-600 dark:text-indigo-400 relative inline-block">Flashcards
                <svg class="absolute w-full h-3 -bottom-1 left-0 text-indigo-200 dark:text-indigo-900/50" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="4" fill="transparent"/></svg>
            </span></h2>
            
            <div class="flex flex-wrap justify-center gap-8 perspective-container" style="height: auto;">
                <div class="w-72 h-96 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-[40px] p-8 flex flex-col justify-between shadow-sm hover:shadow-[0_25px_50px_-12px_rgba(0,0,0,0.15)] dark:hover:shadow-[0_25px_50px_-12px_rgba(79,70,229,0.15)] transition-all duration-500 group cursor-pointer hover:-translate-y-4 hover:-rotate-2">
                    <div class="text-xs font-black text-indigo-500 tracking-widest uppercase flex items-center justify-between">
                        Cardiologia <i class="fa-regular fa-heart opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    <div class="text-xl font-bold text-slate-800 dark:text-white leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300">Quais são os Critérios de Duke para Endocardite Infecciosa?</div>
                    <div class="text-[10px] font-bold text-slate-400 border-t border-slate-100 dark:border-slate-700 pt-4 uppercase tracking-widest group-hover:text-indigo-500 transition-colors">Clique para revelar resposta</div>
                </div>
                
                <div class="w-72 h-96 bg-indigo-600 dark:bg-indigo-700 rounded-[40px] p-8 flex flex-col justify-between text-white shadow-[0_20px_40px_-10px_rgba(79,70,229,0.5)] transition-all duration-500 hover:-translate-y-4 hover:rotate-2">
                    <div class="text-xs font-black opacity-80 tracking-widest uppercase flex items-center justify-between">
                        Resposta <i class="fa-solid fa-check text-emerald-300"></i>
                    </div>
                    <div class="text-base leading-relaxed font-medium">Presença de 2 critérios maiores, ou 1 maior e 3 menores, ou 5 critérios menores para diagnóstico definitivo.</div>
                    <div class="bg-white/20 hover:bg-white/30 transition-colors py-3 rounded-2xl text-xs font-bold text-center uppercase tracking-widest cursor-pointer active:scale-95 duration-200">
                        Dominado <i class="fa-solid fa-check ml-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="jornada" class="py-24 bg-slate-50 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl font-extrabold text-center text-slate-900 mb-20 uppercase tracking-tight reveal">Acompanhamento Inteligente</h2>

            <div class="relative ml-4 md:ml-0 md:pl-20">
                <div class="step-line"></div>

                <div class="flex items-start gap-10 mb-16 group reveal-left">
                    <div class="step-dot"><i class="fa-solid fa-laptop-medical text-indigo-600 text-xl"></i></div>
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 w-full group-hover:border-indigo-300 dark:group-hover:border-indigo-600 group-hover:shadow-[0_10px_30px_-15px_rgba(79,70,229,0.2)] transition-all duration-500 transform group-hover:translate-x-2">
                        <h4 class="text-xl font-bold text-slate-900 mb-2 uppercase text-sm tracking-widest flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 flex items-center justify-center text-xs">1</span> Consulta Digital
                        </h4>
                        <p class="text-slate-500 font-medium leading-relaxed">O sistema registra e estrutura os sintomas em tempo real durante a conversa com o paciente, extraindo pontos chave do histórico clínico com precisão.</p>
                    </div>
                </div>

                <div class="flex items-start gap-10 mb-16 group reveal-right" style="transition-delay: 100ms;">
                    <div class="step-dot"><i class="fa-solid fa-file-prescription text-indigo-600 text-xl"></i></div>
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 w-full group-hover:border-indigo-300 dark:group-hover:border-indigo-600 group-hover:shadow-[0_10px_30px_-15px_rgba(79,70,229,0.2)] transition-all duration-500 transform group-hover:translate-x-2">
                        <h4 class="text-xl font-bold text-slate-900 mb-2 uppercase text-sm tracking-widest flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 flex items-center justify-center text-xs">2</span> Geração de Receita
                        </h4>
                        <p class="text-slate-500 font-medium leading-relaxed">Emissão de prescrições automáticas com base no inventário ANVISA, cruzando alergias e possíveis interações com tratamentos atuais.</p>
                    </div>
                </div>

                <div class="flex items-start gap-10 mb-16 group reveal-left" style="transition-delay: 200ms;">
                    <div class="step-dot bg-indigo-50 dark:bg-indigo-900"><i class="fa-solid fa-bell text-indigo-600 text-xl group-hover:animate-pulse"></i></div>
                    <div class="bg-indigo-50 dark:bg-indigo-900/30 p-8 rounded-3xl border border-indigo-100 dark:border-indigo-800/50 w-full group-hover:bg-indigo-100 dark:group-hover:bg-indigo-800/40 group-hover:shadow-[0_15px_30px_-15px_rgba(79,70,229,0.3)] transition-all duration-500 transform group-hover:translate-x-2">
                        <h4 class="text-xl font-bold text-indigo-900 dark:text-indigo-300 mb-2 uppercase text-sm tracking-widest flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-indigo-200 dark:bg-indigo-700 text-indigo-800 dark:text-white flex items-center justify-center text-xs">3</span> Lembrete & Evolução
                        </h4>
                        <p class="text-indigo-700/80 dark:text-indigo-200 font-medium leading-relaxed">O sistema notifica o paciente sobre os horários da medicação e gera um resumo organizado da evolução clínica para o médico na próxima consulta.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white dark:bg-slate-900 border-y border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="max-w-4xl mx-auto text-center px-6 reveal">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2 uppercase tracking-tight">Por que Clinicaly?</h2>
            <div class="w-12 h-1 bg-indigo-600 mx-auto mb-10 rounded-full transition-all duration-500 hover:w-24"></div>
            <p class="text-slate-500 font-medium mb-20 text-lg">Tecnologia desenhada para a realidade do profissional de saúde moderno.</p>

            <div class="perspective-container group cursor-crosshair">
                <div class="layer layer-3 group-hover:bg-indigo-500/20"></div>
                <div class="layer layer-2 group-hover:bg-indigo-500/50"></div>
                <div class="layer layer-1 tracking-tighter font-black text-3xl md:text-5xl">CLINICALY</div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white dark:bg-slate-900 text-white reveal">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-center" id="stats-container">
                <div class="stat-item group p-6 rounded-3xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-300">
                    <span class="text-5xl text-slate-800 dark:text-white font-extrabold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300" data-target="100">0</span><span class="text-5xl text-slate-800 dark:text-white font-extrabold group-hover:text-indigo-600 dark:group-hover:text-indigo-400">%</span>
                    <div class="h-0.5 bg-indigo-500 w-12 mx-auto my-6 opacity-30 group-hover:opacity-100 group-hover:w-full transition-all duration-500"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Segurança de Dados</span>
                </div>
                <div class="stat-item group p-6 rounded-3xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-300">
                    <span class="text-5xl text-slate-800 dark:text-white font-extrabold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300" data-target="40">0</span><span class="text-5xl text-slate-800 dark:text-white font-extrabold group-hover:text-indigo-600 dark:group-hover:text-indigo-400">k+</span>
                    <div class="h-0.5 bg-indigo-500 w-12 mx-auto my-6 opacity-30 group-hover:opacity-100 group-hover:w-full transition-all duration-500"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Medicamentos na Base</span>
                </div>
                <div class="stat-item group p-6 rounded-3xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-300">
                    <span class="text-5xl text-slate-800 dark:text-white font-extrabold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300" data-target="0.5" data-float="true">0</span><span class="text-5xl text-slate-800 dark:text-white font-extrabold group-hover:text-indigo-600 dark:group-hover:text-indigo-400">s</span>
                    <div class="h-0.5 bg-indigo-500 w-12 mx-auto my-6 opacity-30 group-hover:opacity-100 group-hover:w-full transition-all duration-500"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tempo de Resposta</span>
                </div>
                <div class="stat-item group p-6 rounded-3xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-300">
                    <span class="text-5xl text-slate-800 dark:text-white font-extrabold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300" data-target="2026">0</span>
                    <div class="h-0.5 bg-indigo-500 w-12 mx-auto my-6 opacity-30 group-hover:opacity-100 group-hover:w-full transition-all duration-500"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Padrão Tecnológico</span>
                </div>
            </div>
        </div>
    </section>

    <div class="footer-wave relative bg-white dark:bg-slate-900">
        <svg viewBox="0 0 1440 120" preserveAspectRatio="none" class="w-full h-24 text-slate-950 fill-current">
            <path d="M0 120L1440 120L1440 0C1165 60 275 60 0 0L0 120Z" />
        </svg>
    </div>

    <footer class="bg-slate-950 text-white pt-16 pb-12 relative overflow-hidden">
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[800px] h-[300px] bg-indigo-600/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-16">
                <div>
                    <div class="text-3xl font-extrabold mb-6 uppercase tracking-tight flex items-center gap-2">
                        Clinicaly<span class="text-indigo-500 w-2 h-2 rounded-full bg-indigo-500 inline-block"></span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm font-medium">
                        Elevando o padrão da medicina digital em Angola com tecnologia de ponta. Desenhado para
                        profissionais de saúde que exigem precisão e agilidade.
                    </p>
                </div>

                <div class="border-l-2 border-indigo-600/50 pl-8 hover:border-indigo-500 transition-colors duration-300">
                    <p class="text-slate-400 text-sm leading-relaxed font-medium">
                        <strong class="text-white uppercase text-xs tracking-widest block mb-2"><i class="fa-solid fa-circle-info text-indigo-500 mr-2"></i>Aviso Legal:</strong> As
                        ferramentas tecnológicas avançadas da Clinicaly são assistentes de produtividade e não
                        substituem o diagnóstico final emitido por um médico devidamente registrado. Use como suporte
                        educacional e profissional.
                    </p>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800/50 flex justify-between items-center flex-wrap gap-4">
                <div class="flex gap-4 items-center bg-slate-900 px-4 py-2 rounded-full border border-slate-800">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.8)]"></span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistema Online: Operacional</span>
                </div>
                <div class="flex gap-6 text-slate-500 text-xs font-bold uppercase tracking-widest">
                    <a href="#" class="hover:text-indigo-400 transition-colors duration-300 relative group">
                        Política de Privacidade
                        <span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-400 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#" class="hover:text-indigo-400 transition-colors duration-300 relative group">
                        Termos de Uso
                        <span class="absolute -bottom-1 left-0 w-0 h-px bg-indigo-400 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Scroll Reveal Logic (Intersection Observer)
            const revealOptions = {
                threshold: 0.15,
                rootMargin: "0px 0px -50px 0px"
            };

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        // Opcional: parar de observar após revelar
                        // revealObserver.unobserve(entry.target); 
                    }
                });
            }, revealOptions);

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
                revealObserver.observe(el);
            });

            // 2. Navbar Scroll Effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    navbar.classList.add('nav-scrolled');
                    navbar.classList.remove('py-4');
                    navbar.classList.add('py-2');
                } else {
                    navbar.classList.remove('nav-scrolled');
                    navbar.classList.add('py-4');
                    navbar.classList.remove('py-2');
                }
            });

            // 3. Dark Mode Toggle Logic
            const themeToggleBtn = document.getElementById('theme-toggle');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            // Verifica preferência salva ou do sistema
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                lightIcon.classList.remove('hidden');
                darkIcon.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
            }

            themeToggleBtn.addEventListener('click', function() {
                darkIcon.classList.toggle('hidden');
                lightIcon.classList.toggle('hidden');

                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            });

            // 4. Animated Number Counters (Estatísticas)
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counters = entry.target.querySelectorAll('span[data-target]');
                        counters.forEach(counter => {
                            const target = parseFloat(counter.getAttribute('data-target'));
                            const isFloat = counter.getAttribute('data-float') === 'true';
                            const duration = 2000; // 2 segundos
                            const increment = target / (duration / 16); // 60fps
                            let current = 0;

                            const updateCounter = () => {
                                current += increment;
                                if (current < target) {
                                    counter.innerText = isFloat ? current.toFixed(1) : Math.ceil(current);
                                    requestAnimationFrame(updateCounter);
                                } else {
                                    counter.innerText = target;
                                }
                            };
                            updateCounter();
                        });
                        statsObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            const statsContainer = document.getElementById('stats-container');
            if(statsContainer) statsObserver.observe(statsContainer);
        });
    </script>
</body>
</html>