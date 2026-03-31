<!DOCTYPE html>
<html lang="pt-ao">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinicaly AI | Inteligência Clínica Avançada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
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
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Dosis', sans-serif;
            scroll-behavior: smooth;

        }

        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, #f8fafc 0%, #eff6ff 100%);
        }

        .medical-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 30px;
        }

        .medical-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        }

        .perspective-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            height: 120px;
            margin: 40px auto;
        }

        .layer {
            position: absolute;
            width: 100%;
            height: 100px;
            left: 0;
            transform: perspective(1000px) rotateX(45deg);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.5s ease;
        }

        .layer-1 {
            background: #6d55b1;
            top: 0;
            z-index: 3;
            color: white;
            font-weight: 800;
            font-size: 2rem;
            box-shadow: 0 10px 30px rgba(109, 85, 177, 0.3);
        }

        .layer-2 {
            background: rgba(109, 85, 177, 0.4);
            top: -20px;
            z-index: 2;
        }

        .layer-3 {
            background: rgba(109, 85, 177, 0.1);
            top: -40px;
            z-index: 1;
        }

        .step-line {
            position: absolute;
            left: 28px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
            z-index: 0;
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
        }

        .footer-wave {
            line-height: 0;
            margin-top: -1px;
        }
 
nav a {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-link-active {
    background-color: #735ab8 !important;
    color: white !important;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(115, 90, 184, 0.4);
    transition: all 0.3s ease;
}

 
.dark body {
    background-color: #020617 !important;
    color: #f1f5f9 !important;
}

 
.dark .text-slate-500, 
.dark .text-slate-400,
.dark p {
    color: #cbd5e1 !important;  
}

.dark section, 
.dark .bg-white, 
.dark .bg-slate-50,
.dark #sobre, .dark #tecnologia, .dark #estudo, .dark #jornada {
    background-color: #0f172a !important;
    border-color: #1e293b !important;
}

.dark .medical-card {
    background-color: #1e293b !important;
    border: 1px solid #334155 !important;
}

.dark h1, .dark h2, .dark h3, .dark h4, .dark strong {
    color: #ffffff !important;
}

 
.dark nav.glass {
    background: rgba(2, 6, 23, 0.85) !important;
    border-bottom: 1px solid #1e293b !important;
}

[x-cloak] { display: none !important; }

    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased hero-gradient" x-data="{ loginModal: false }">

    <div x-show="loginModal" 
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-500/20 backdrop-blur-sm">
    
    <div @click.away="loginModal = false" 
         class="bg-white w-full max-w-sm rounded-[32px] shadow-2xl border border-slate-100 overflow-hidden">
        
        <div class="p-10 text-center">
            <div class="mb-6 flex justify-center">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center rotate-3">
                    <i class="fa-solid fa-hand-sparkles text-2xl"></i>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-slate-800 mb-3 tracking-tight">Seja bem vindo!</h3>
            <p class="text-slate-500 font-medium mb-8 leading-relaxed">
                Deseja acessar nossos serviços? <br>
                <span class="text-indigo-600/80">Faça login ou cadastre-se para continuar.</span>
            </p>

            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="block w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                    Entrar agora
                </a>
                
                <a href="{{ route('register') }}" 
                   class="block w-full bg-white border border-slate-200 text-slate-600 py-4 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                    Criar conta gratuita
                </a>

                <button @click="loginModal = false" 
                        class="pt-4 text-slate-400 text-sm font-semibold hover:text-slate-600 transition-colors">
                    Talvez mais tarde
                </button>
            </div>
        </div>

        <div class="bg-slate-50/50 py-4 border-t border-slate-50 text-center">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Clinicaly• Angola</span>
        </div>
    </div>
</div>

    <nav class="fixed w-full z-50 glass border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="{{ asset('Proosta-logo4.png') }}" alt="Logo" width="180px" class="w-32 md:w-44">
            </div>
            
            <div class="hidden md:flex gap-8 text-sm font-bold text-slate-500">
                <a href="#sobre" class="hover:text-indigo-600 transition-colors px-3 py-2">Sobre Nós</a>
                <a href="#tecnologia" class="hover:text-indigo-600 transition-colors px-3 py-2">Tecnologia</a>
                <a href="#estudo" class="hover:text-indigo-600 transition-colors px-3 py-2">Educação</a>
                <a href="#jornada" class="hover:text-indigo-600 transition-colors px-3 py-2">Jornada</a>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-indigo-100 dark:hover:bg-slate-800 text-slate-500 dark:text-indigo-300 transition-all">
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-lg"></i>
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-lg hidden"></i>
                </button>

                @auth
                    <a href="{{ url('/chat') }}" class="hidden md:block bg-indigo-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-indigo-700 transition-all shadow-md">
                        Ir para o Chat
                    </a>
                @else
                    <button @click="loginModal = true" class="hidden md:block bg-indigo-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-indigo-700 transition-all shadow-md">
                        Aceder ao Chat
                    </button>
                @endauth

                <button id="menu-btn" class="md:hidden text-slate-600 dark:text-slate-300 p-2">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>
        </div>


        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 px-6 py-6 transition-all duration-300">
            <div class="flex flex-col gap-4 text-center font-bold text-slate-600 dark:text-slate-300">
                <a href="#sobre" class="mobile-link py-2 border-b border-slate-50 dark:border-slate-800">Sobre Nós</a>
                <a href="#tecnologia" class="mobile-link py-2 border-b border-slate-50 dark:border-slate-800">Tecnologia</a>
                <a href="#estudo" class="mobile-link py-2 border-b border-slate-50 dark:border-slate-800">Educação</a>
                <a href="#jornada" class="mobile-link py-2 border-b border-slate-50 dark:border-slate-800">Jornada</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/chat') }}" @click="loginModal = true" class="hidden md:block bg-indigo-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-indigo-700 transition-all shadow-md">
                            Ir para o Chat
                        </a>
                    @else
                        <a href="{{ route('login') }}" @click="loginModal = true" class="hidden md:block bg-indigo-600 text-white px-6 py-2.5 rounded-full text-sm font-bold hover:bg-indigo-700 transition-all shadow-md">
                            Aceder ao Chat
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="pt-40 pb-24 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <span
                class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-widest rounded-full mb-8">
                O Próximo Nível da Medicina em Angola
            </span>
            <h1 class="text-4xl md:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-8 tracking-tight">
                Sua mente clínica <br>
                <span class="text-indigo-600">potencializada por dados.</span>
            </h1>
            <p class="text-slate-500 text-xl max-w-2xl mx-auto mb-12 leading-relaxed font-medium">
                Uma plataforma intuitiva que organiza prontuários, sugere diagnósticos baseados no CID-11 e transforma
                dados em decisões seguras.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">

                <button
                    @click="loginModal = true" class="bg-slate-900 text-white px-10 py-5 rounded-2xl font-bold hover:bg-slate-800 transition-all shadow-xl flex items-center justify-center gap-3">
                    Começar Agora <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
                <button
                    class="bg-white border border-slate-200 text-slate-600 px-10 py-5 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                    Ver Demonstração
                </button>
            </div>
        </div>
    </section>

    <section id="sobre" class="py-24 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row gap-16 items-start mb-20">
                <div class="md:w-1/3">
                    <div class="w-16 h-1.5 bg-indigo-600 mb-6 rounded-full"></div>
                    <h2 class="text-4xl font-extrabold text-slate-900 leading-none uppercase">Quem <br> Somos?</h2>
                </div>
                <div class="md:w-2/3">
                    <p class="text-slate-500 text-xl leading-relaxed font-medium">
                        A <strong class="text-slate-800">ClinicalyAI</strong> nasceu para suprir a necessidade de
                        agilidade no setor de saúde angolano. Unimos o poder do processamento de linguagem natural (IA)
                        com bases de dados oficiais para oferecer um ecossistema onde o médico foca no paciente,
                        enquanto nós cuidamos da estruturação dos dados e da precisão farmacológica.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="medical-card bg-slate-50 p-10 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 text-2xl">
                        <i class="fa-solid fa-stethoscope"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 uppercase text-sm tracking-widest">Apoio ao
                        Diagnóstico</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">Algoritmos avançados que analisam
                        sintomas e sugerem códigos CID-11 atualizados em tempo real.</p>
                </div>
                <div class="medical-card bg-indigo-600 p-10 text-white shadow-2xl shadow-indigo-200">
                    <div
                        class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center mb-6 text-2xl">
                        <i class="fa-solid fa-pills"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 uppercase text-sm tracking-widest">Cérebro Farmacêutico</h3>
                    <p class="text-indigo-100 text-sm leading-relaxed font-medium">Consulta imediata à base ANVISA para
                        evitar interações medicamentosas e erros de dosagem.</p>
                </div>
                <div class="medical-card bg-slate-50 p-10 border border-slate-100">
                    <div
                        class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 text-2xl">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 uppercase text-sm tracking-widest">Fluxo
                        Educacional</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">O Clinicaly organiza suas consultas em
                        notas de estudo, facilitando a revisão de casos clínicos complexos.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="tecnologia" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div>
                    <h2 class="text-4xl font-extrabold text-slate-900 mb-6 uppercase">Base de Conhecimento <br>
                        Instantânea.</h2>
                    <p class="text-slate-500 text-lg mb-8 leading-relaxed font-medium">
                        Esqueça a busca manual em PDFs e livros pesados. Nossa IA cruza dados da <strong
                            class="text-slate-800">ANVISA</strong> e <strong class="text-slate-800">CID-11</strong> em
                        milissegundos, sugerindo posologias e identificando patologias com precisão técnica.
                    </p>
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <div class="flex gap-4 items-center mb-4 text-indigo-600">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center"><i
                                    class="fa-solid fa-search"></i></div>
                            <span class="font-bold text-sm uppercase tracking-widest">Simulação de Busca</span>
                        </div>
                        <div
                            class="h-12 bg-slate-50 rounded-xl border border-slate-100 flex items-center px-4 text-slate-400 text-sm font-medium">
                            Digite um sintoma ou fármaco...
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="medical-card bg-indigo-50 p-8">
                        <h4 class="font-extrabold text-indigo-900 mb-2 uppercase text-sm tracking-tight">CID-11
                            Atualizado</h4>
                        <p class="text-sm text-indigo-700/80 font-medium">Mapeamento completo de doenças para
                            faturamento e preenchimento automático de prontuário.</p>
                    </div>
                    <div class="medical-card bg-emerald-50 p-8">
                        <h4 class="font-extrabold text-emerald-900 mb-2 uppercase text-sm tracking-tight">Base ANVISA
                        </h4>
                        <p class="text-sm text-emerald-700/80 font-medium">Acesso direto a bulas, interações perigosas e
                            dosagens recomendadas pelo órgão regulador.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="estudo" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-extrabold text-slate-900 mb-16 uppercase">Evolução Profissional em <span
                    class="text-indigo-600">Flashcards</span></h2>
            <div class="flex flex-wrap justify-center gap-8">
                <div
                    class="w-72 h-96 bg-white border border-slate-200 rounded-[40px] p-8 flex flex-col justify-between hover:shadow-2xl transition-all group cursor-pointer">
                    <div class="text-xs font-black text-indigo-500 tracking-widest uppercase">Cardiologia</div>
                    <div class="text-xl font-bold text-slate-800 leading-snug">Quais são os Critérios de Duke para
                        Endocardite Infecciosa?</div>
                    <div
                        class="text-[10px] font-bold text-slate-400 border-t border-slate-100 pt-4 uppercase tracking-widest">
                        Clique para revelar resposta</div>
                </div>
                <div
                    class="w-72 h-96 bg-indigo-600 rounded-[40px] p-8 flex flex-col justify-between text-white shadow-xl shadow-indigo-200">
                    <div class="text-xs font-black opacity-60 tracking-widest uppercase">Resposta</div>
                    <div class="text-base leading-relaxed font-medium">Presença de 2 critérios maiores, ou 1 maior e 3
                        menores, ou 5 critérios menores para diagnóstico definitivo.</div>
                    <div class="bg-white/20 py-3 rounded-2xl text-xs font-bold text-center uppercase tracking-widest">
                        Dominado ✓</div>
                </div>
            </div>
        </div>
    </section>

    <section id="jornada" class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl font-extrabold text-center text-slate-900 mb-20 uppercase tracking-tight">Acompanhamento
                Inteligente</h2>

            <div class="relative ml-4 md:ml-0 md:pl-20">
                <div class="step-line"></div>

                <div class="flex items-start gap-10 mb-16">
                    <div class="step-dot"><i class="fa-solid fa-comment-medical text-indigo-600 text-xl"></i></div>
                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 w-full">
                        <h4 class="text-xl font-bold text-slate-900 mb-2 uppercase text-sm tracking-widest">1. Consulta
                            AI</h4>
                        <p class="text-slate-500 font-medium">A IA transcreve e estrutura os sintomas em tempo real
                            durante a conversa com o paciente, extraindo pontos chave do histórico clínico.</p>
                    </div>
                </div>

                <div class="flex items-start gap-10 mb-16">
                    <div class="step-dot"><i class="fa-solid fa-file-prescription text-indigo-600 text-xl"></i></div>
                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 w-full">
                        <h4 class="text-xl font-bold text-slate-900 mb-2 uppercase text-sm tracking-widest">2. Geração
                            de Receita</h4>
                        <p class="text-slate-500 font-medium">Emissão de prescrições automáticas com base no inventário
                            ANVISA, cruzando alergias e possíveis interações com tratamentos atuais.</p>
                    </div>
                </div>

                <div class="flex items-start gap-10 mb-16">
                    <div class="step-dot "><i
                            class="fa-solid fa-bell text-indigo-600 text-xl"></i></div>
                    <div class="bg-indigo-50 p-6 rounded-3xl border border-slate-100 w-full">
                        <h4 class="text-xl font-bold text-slate-900 mb-2 uppercase text-sm tracking-widest">3. Lembrete
                            & Evolução</h4>
                        <p class="text-slate-500 font-medium">O sistema notifica o paciente sobre os horários da
                            medicação e gera um resumo organizado da evolução clínica para o médico na próxima consulta.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white border-y border-slate-100">
        <div class="max-w-4xl mx-auto text-center px-6">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2 uppercase tracking-tight">Por que Clinicaly?</h2>
            <div class="w-12 h-1 bg-indigo-600 mx-auto mb-10 rounded-full"></div>
            <p class="text-slate-500 font-medium mb-16 text-lg">Tecnologia desenhada para a realidade do profissional de
                saúde moderno.</p>

            <div class="perspective-container">
                <div class="layer layer-3"></div>
                <div class="layer layer-2"></div>
                <div class="layer layer-1 tracking-tighter font-black">CLINICALY AI</div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white text-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 text-center">
                <div class="stat-item">
                    <span class="text-5xl text-slate-700 font-extrabold">100%</span>
                    <div class="h-0.5 bg-indigo-500 w-full my-4 opacity-30"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Segurança de
                        Dados</span>
                </div>
                <div class="stat-item">
                    <span class="text-5xl text-slate-700 font-extrabold">40k+</span>
                    <div class="h-0.5 bg-indigo-500 w-full my-4 opacity-30"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Medicamentos na
                        Base</span>
                </div>
                <div class="stat-item">
                    <span class="text-5xl text-slate-700 font-extrabold">0.5s</span>
                    <div class="h-0.5 bg-indigo-500 w-full my-4 opacity-30"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tempo de
                        Resposta</span>
                </div>
                <div class="stat-item">
                    <span class="text-5xl text-slate-700 font-extrabold">2026</span>
                    <div class="h-0.5 bg-indigo-500 w-full my-4 opacity-30"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Padrão
                        Tecnológico</span>
                </div>
            </div>
        </div>
    </section>

    <div class="footer-wave">
        <svg viewBox="0 0 1440 120" preserveAspectRatio="none" class="w-full h-24 text-slate-950 fill-current">
            <path d="M0 120L1440 120L1440 0C1165 60 275 60 0 0L0 120Z" />
        </svg>
    </div>

    <footer class="bg-slate-950 text-white pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 mb-16">
                <div>
                    <div class="text-3xl font-extrabold mb-6 uppercase tracking-tight">Clinicaly<span
                            class="text-indigo-500">AI</span></div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm font-medium">
                        Elevando o padrão da medicina digital em Angola com tecnologia AI de ponta. Desenhado para
                        profissionais de saúde que exigem precisão e agilidade.
                    </p>
                </div>

                <div class="border-l-2 border-indigo-600 pl-8">
                    <p class="text-slate-400 text-sm leading-relaxed font-medium">
                        <strong class="text-white uppercase text-xs tracking-widest">Aviso Legal:</strong> As
                        ferramentas de Inteligência Artificial da Clinicaly são assistentes de produtividade e não
                        substituem o diagnóstico final emitido por um médico devidamente registrado. Use como suporte
                        educacional e profissional.
                    </p>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex justify-between items-center flex-wrap gap-4">
                <div class="flex gap-4 items-center">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sistema Online:
                        Operacional</span>
                </div>
                <div class="flex gap-6 text-slate-500 text-xs font-bold uppercase tracking-widest">
                    <a href="#" class="hover:text-white transition-colors">Política de Privacidade</a>
                    <a href="#" class="hover:text-white transition-colors">Termos de Uso</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
     
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileLinks = document.querySelectorAll('.mobile-link');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        
        const icon = menuBtn.querySelector('i');
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-xmark');
    });
 
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            menuBtn.querySelector('i').classList.add('fa-bars');
            menuBtn.querySelector('i').classList.remove('fa-xmark');
        });
    });

   
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    themeToggleBtn.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');
        darkIcon.classList.toggle('hidden');
        lightIcon.classList.toggle('hidden');
    });

     
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll("nav .hidden.md\\:flex a, .mobile-link");

    window.addEventListener("scroll", () => {
        let current = "";
        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            if (pageYOffset >= sectionTop - 150) {
                current = section.getAttribute("id");
            }
        });

        navLinks.forEach((link) => {
            link.classList.remove("nav-link-active");
            if (link.getAttribute("href").includes(current) && current !== "") {
                link.classList.add("nav-link-active");
            }
        });
    });
</script>
</body>

</html>