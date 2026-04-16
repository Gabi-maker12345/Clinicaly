<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|dosis:400,500,600,700,800|space-mono:400,700&display=swap" rel="stylesheet" />
        
        <!-- Tailwind via CDN com Configuração para a fonte Dosis -->
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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Styles -->
        @stack('styles')
        @livewireStyles
        <link rel="icon" href="/logo-clin.ico" type="image/x-icon">
        <style>
            * { box-sizing: border-box; }
            body { font-family: 'Dosis', sans-serif; }
            :root{--in:#6d55b1;--il:#8b72cc;--is:#f5f3fd;--id:#ede9f8;--bg:#f4f2fb;--sf:#fff;--sf2:#f8f6fd;--sf3:#ede9f8;--bd:#ddd8f0;--tx:#1a1530;--mu:#7c72a0;--fa:#b0a8cc;--gr:#059669;--gb:#ecfdf5;--gbd:#a7f3d0;--bl:#3b82f6;--bb:#eff6ff;--bbd:#bfdbfe;--wn:#d97706;--wb:#fffbeb;--wbd:#fcd34d;--rd:#dc2626;--rb:#fef2f2;--rbd:#fca5a5;--sh:0 1px 3px rgba(109,85,177,.08);--sh2:0 4px 12px rgba(109,85,177,.10);--r:14px;--rs:8px;}
            [data-theme=dark]{--in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);}
            html[data-theme] { background-color: var(--bg); color: var(--tx); }
            .topbar{position:sticky;top:0;z-index:50;background:var(--sf);border-bottom:1px solid var(--bd);box-shadow:var(--sh);display:flex;align-items:center;gap:12px;padding:0 24px;height:60px;}
            .logo{text-decoration:none;display:flex;align-items:center;height:100%;}
            .vid{font-family:'Space Mono',monospace;font-size:.55rem;color:var(--mu);letter-spacing:.1em;text-transform:uppercase;}
            .ib{background:transparent;border:1.5px solid var(--bd);border-radius:50%;width:36px;height:36px;cursor:pointer;color:var(--mu);display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .2s;position:relative;}
            .ib:hover{border-color:var(--in);color:var(--in);background:var(--is);}
            .nd{position:absolute;top:-1px;right:-1px;width:8px;height:8px;border-radius:50%;background:var(--rd);border:2px solid var(--sf);}
            .tb{background:var(--sf2);border:1.5px solid var(--bd);border-radius:50%;width:34px;height:34px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--mu);font-size:.85rem;transition:all .2s;}
            .tb:hover{border-color:var(--in);color:var(--in);}
            .av{display:flex;align-items:center;justify-content:center;font-weight:700;border-radius:50%;flex-shrink:0;width:36px;height:36px;font-size:.78rem;}
            .av-gr{background:var(--gb);color:var(--gr);border:1.5px solid var(--gbd);}
            .av-bl{background:var(--bb);color:var(--bl);border:1.5px solid var(--bbd);}
        </style>
    </head>
    <body class="font-sans antialiased" data-theme="light" style="transition: background-color 0.3s ease, color 0.3s ease;">
        <x-banner />

        <header class="topbar" role="banner">
            <a href="{{ route('dashboard') }}" class="logo" title="Voltar ao início">
                <img src="{{ asset('Proosta-logo4.png') }}" alt="Logo" width="180px" class="w-32 md:w-44">
            </a>

            <nav style="display:flex;align-items:center;gap:8px;margin-left:auto;" aria-label="Acções globais">
                <ul style="list-style:none;display:flex;align-items:center;gap:8px;">
                    <li>
                        <a href="{{ route('messages.index') }}" class="ib" aria-label="Mensagens" title="Conversas">
                            <i class="fa-solid fa-comment-dots" aria-hidden="true"></i>
                            <span class="nd"></span>
                        </a>
                    </li>

                    <li>
                        <button class="ib" aria-label="Notificações" title="Notificações">
                            <i class="fa-solid fa-bell" aria-hidden="true"></i>
                        </button>
                    </li>

                    <li>
                        <button class="tb" id="tb" onclick="toggleTheme()" aria-label="Alternar tema" title="Alternar tema">
                            <i class="fa-solid fa-moon" id="ti" aria-hidden="true"></i>
                        </button>
                    </li>

                    <li>
                        <div class="relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button style="display:flex;align-items:center;gap:6px;padding:4px;border-radius:8px;background:transparent;border:1px solid var(--bd);cursor:pointer;transition:all .2s;" class="hover:border-violet-400">
                                        <img class="w-8 h-8 rounded object-cover border border-gray-200" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" title="{{ Auth::user()->name }}">
                                        <div style="text-align:left;display:none;" class="sm:block">
                                            <p style="font-size:.75rem;font-weight:700;color:var(--tx);line-height:1;">{{ Auth::user()->name }}</p>
                                            <p style="font-size:.65rem;color:var(--in);font-weight:600;margin-top:2px;text-transform:uppercase;">{{ auth()->user()->role === 'doctor' ? 'Médico' : 'Paciente' }}</p>
                                        </div>
                                        <i class="fa-solid fa-chevron-down text-[10px]" style="color:var(--mu);"></i>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div style="display:block;padding:8px 16px;font-size:.7rem;color:var(--mu);text-transform:uppercase;font-weight:700;letter-spacing:.08em;">
                                        Gerenciar Conta
                                    </div>

                                    <x-dropdown-link href="{{ route('profile.show') }}">
                                        <i class="fa-solid fa-user-gear mr-2"></i> {{ __('Meu Perfil') }}
                                    </x-dropdown-link>

                                    <div style="border-top:1px solid var(--bd);"></div>

                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}"
                                                        @click.prevent="$root.submit();"
                                                        style="color:var(--rd);">
                                            <i class="fa-solid fa-right-from-bracket mr-2"></i> {{ __('Sair') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </li>
                </ul>
            </nav>
        </header>

        <div>
            <!-- Page Content -->

            <!-- Page Content -->
            <main>
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
        @stack('scripts')
        @stack('modals')

        @livewireScripts

        <!-- Alpine.js CDN - Garantir que está carregado -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            // ============================================
            // SISTEMA UNIVERSAL DE MODO ESCURO - CLINICALY
            // ============================================
            // Chave de armazenamento
            const THEME_KEY = 'cl-theme';
            
            // Inicialização do tema antes do DOM renderizar
            (function(){
                const savedTheme = localStorage.getItem(THEME_KEY) || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
                updateThemeIcon(savedTheme);
            })();

            // Função para atualizar o ícone do tema
            function updateThemeIcon(theme){
                const icon = document.getElementById('ti');
                if(icon) {
                    icon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
                }
            }

            // Função para alternar o tema (chamada pelo botão do topbar)
            function toggleTheme(){
                const current = document.documentElement.getAttribute('data-theme') || 'light';
                const next = current === 'light' ? 'dark' : 'light';
                
                document.documentElement.setAttribute('data-theme', next);
                localStorage.setItem(THEME_KEY, next);
                updateThemeIcon(next);
                
                // Dispara evento customizado para outras views
                window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: next } }));
            }

            // Garante que o tema seja aplicado quando a página volta do cache
            window.addEventListener('pageshow', function(){
                const savedTheme = localStorage.getItem(THEME_KEY) || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
                updateThemeIcon(savedTheme);
            });
        </script>
    </body>
</html>
