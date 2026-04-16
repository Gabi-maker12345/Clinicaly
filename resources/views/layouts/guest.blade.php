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
        <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;700;800&family=Space+Mono&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <style>
            body { font-family: 'Dosis', sans-serif !important; }
            :root{--in:#6d55b1;--il:#8b72cc;--is:#f5f3fd;--id:#ede9f8;--bg:#f4f2fb;--sf:#fff;--sf2:#f8f6fd;--sf3:#ede9f8;--bd:#ddd8f0;--tx:#1a1530;--mu:#7c72a0;--fa:#b0a8cc;--gr:#059669;--gb:#ecfdf5;--gbd:#a7f3d0;--bl:#3b82f6;--bb:#eff6ff;--bbd:#bfdbfe;--wn:#d97706;--wb:#fffbeb;--wbd:#fcd34d;--rd:#dc2626;--rb:#fef2f2;--rbd:#fca5a5;--sh:0 1px 3px rgba(109,85,177,.08);--sh2:0 4px 12px rgba(109,85,177,.10);--r:14px;--rs:8px;}
            [data-theme=dark]{--in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);}
            html[data-theme] { background-color: var(--bg); color: var(--tx); }
        </style>
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
        
        <script>
            // Apply theme from localStorage (inherited from main app)
            (function(){
                const theme = localStorage.getItem('cl-theme') || 'light';
                document.documentElement.setAttribute('data-theme', theme);
            })();
        </script>
    </body>
</html>
