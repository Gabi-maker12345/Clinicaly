<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <link rel="icon" href="/logo-clin.ico" type="image/x-icon">
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="sticky top-0 z-40 flex items-center justify-between h-16 px-6 bg-white border-b border-gray-100">
    
                    <div class="flex items-center gap-4">
                        <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="p-2 text-gray-400 lg:hidden">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-notes-medical text-indigo-600 text-2xl"></i>
                            <span class="font-bold text-xl tracking-tight text-slate-800">
                                Clinicaly<span class="text-indigo-600">.</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        
                        <button class="p-3 text-gray-400 hover:text-indigo-600 transition-colors relative">
                            <i class="fa-solid fa-bell text-lg"></i>
                            <span class="absolute top-3 right-3 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>

                        <div class="h-6 w-px bg-gray-100 mx-2"></div>

                        <div class="relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-3 p-1 rounded-xl hover:bg-gray-50 transition-all focus:outline-none">
                                        <img class="h-9 w-9 rounded-lg object-cover border border-gray-100" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                                        <div class="text-left hidden sm:block">
                                            <p class="text-xs font-bold text-slate-700 leading-none">{{ Auth::user()->name }}</p>
                                            <p class="text-[10px] text-indigo-500 font-medium mt-1 uppercase">Paciente</p>
                                        </div>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="block px-4 py-2 text-xs text-gray-400 uppercase font-bold tracking-widest">
                                        Gerenciar Conta
                                    </div>

                                    <x-dropdown-link href="{{ route('profile.show') }}">
                                        <i class="fa-solid fa-user-gear mr-2"></i> {{ __('Meu Perfil') }}
                                    </x-dropdown-link>

                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                            {{ __('API Tokens') }}
                                        </x-dropdown-link>
                                    @endif

                                    <div class="border-t border-gray-100"></div>

                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}"
                                                @click.prevent="$root.submit();"
                                                class="text-red-600">
                                            <i class="fa-solid fa-right-from-bracket mr-2"></i> {{ __('Sair') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
