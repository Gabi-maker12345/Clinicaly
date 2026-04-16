<!DOCTYPE html>
<html lang="pt" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinicaly — Sessão Expirada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
:root{--in:#6d55b1;--il:#8b72cc;--is:#f5f3fd;--id:#ede9f8;--bg:#f4f2fb;--sf:#fff;--sf2:#f8f6fd;--sf3:#ede9f8;--bd:#ddd8f0;--tx:#1a1530;--mu:#7c72a0;--fa:#b0a8cc;--gr:#059669;--gb:#ecfdf5;--gbd:#a7f3d0;--bl:#3b82f6;--bb:#eff6ff;--bbd:#bfdbfe;--wn:#d97706;--wb:#fffbeb;--wbd:#fcd34d;--rd:#dc2626;--rb:#fef2f2;--rbd:#fca5a5;--sh:0 1px 3px rgba(109,85,177,.08);--sh2:0 4px 12px rgba(109,85,177,.10);--r:14px;--rs:8px;}
[data-theme=dark]{--in:#8b72cc;--il:#a892e0;--is:#1e1838;--id:#2a2050;--bg:#0d0b14;--sf:#161222;--sf2:#1e1830;--sf3:#251f3a;--bd:#2a2245;--tx:#e8e2f5;--mu:#8a7faa;--fa:#4a4268;--gr:#34c98a;--gb:#0d2e20;--gbd:#1a5c3c;--bl:#5b9cf6;--bb:#0d1f3c;--bbd:#1e3a6e;--wn:#f59e0b;--wb:#2e1d00;--wbd:#5c3a00;--rd:#ef4444;--rb:#2e0d0d;--rbd:#5c1a1a;--sh:0 1px 3px rgba(0,0,0,.4);--sh2:0 4px 12px rgba(0,0,0,.5);}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Dosis',sans-serif;background:var(--bg);color:var(--tx);min-height:100vh;transition:background .3s,color .3s;}
a{text-decoration:none;color:inherit;}
.topbar{position:sticky;top:0;z-index:50;background:var(--sf);border-bottom:1px solid var(--bd);box-shadow:var(--sh);display:flex;align-items:center;gap:12px;padding:0 24px;height:60px;}
.logo{font-size:1.05rem;font-weight:800;letter-spacing:.1em;background:linear-gradient(135deg,var(--in),var(--il));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
@keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.fi{animation:fi .3s ease both;}.fi2{animation-delay:.1s;}
.card{background:var(--sf);border:1px solid var(--bd);border-radius:var(--r);padding:20px;box-shadow:var(--sh);}
.av{display:flex;align-items:center;justify-content:center;font-weight:700;border-radius:50%;flex-shrink:0;}
.av-gr{background:var(--gb);color:var(--gr);border:1.5px solid var(--gbd);}
.av-sm{width:36px;height:36px;font-size:.78rem;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:30px;border:none;font-family:'Dosis',sans-serif;font-size:.88rem;font-weight:700;cursor:pointer;text-decoration:none;transition:all .2s;white-space:nowrap;justify-content:center;}
.bsm{padding:6px 14px;font-size:.8rem;}
.b-pr{background:var(--in);color:#fff;}
.b-pr:hover{background:var(--il);}
.b-gh{background:transparent;color:var(--mu);border:1.5px solid var(--bd);}
.b-gh:hover{border-color:var(--in);color:var(--in);background:var(--is);}
.ib{background:transparent;border:1.5px solid var(--bd);border-radius:50%;width:36px;height:36px;cursor:pointer;color:var(--mu);display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .2s;position:relative;}
.ib:hover{border-color:var(--in);color:var(--in);background:var(--is);}
.nd{position:absolute;top:-1px;right:-1px;width:8px;height:8px;border-radius:50%;background:var(--rd);border:2px solid var(--sf);}
.tb{background:var(--sf2);border:1.5px solid var(--bd);border-radius:50%;width:34px;height:34px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--mu);font-size:.85rem;transition:all .2s;}
.tb:hover{border-color:var(--in);color:var(--in);}
.er-c{display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:72px 32px;}
.er-cd{font-family:'Space Mono',monospace;font-size:5.5rem;font-weight:700;line-height:1;color:var(--wn);margin-bottom:12px;}
.er-ti{font-size:1.3rem;font-weight:800;margin-bottom:8px;}.er-su{font-size:.88rem;color:var(--mu);max-width:340px;line-height:1.6;margin-bottom:28px;}
    </style>
</head>
<body>
    <article class="card fi fi2" style="margin-top:0;" aria-live="polite" aria-labelledby="err-ti" id="err-card">
        <div class="er-c">
            <p id="err-ico" style="font-size:3.5rem;margin-bottom:8px;" role="img" aria-hidden="true">⏰</p>
            <p class="er-cd" id="err-cd">419</p>
            <p class="er-ti" id="err-ti">Sessão Expirada</p>
            <p class="er-su" id="err-su">A sua sessão expirou por inatividade. Por favor, faça login novamente para continuar.</p>
            <nav style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;" aria-label="Opções de recuperação">
                <a href="{{ route('login') }}" class="btn b-pr"><i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i> Fazer Login</a>
            </nav>
        </div>
    </article>
    
    <script>
        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        }
        
        function updateThemeIcon() {
            const theme = document.documentElement.getAttribute('data-theme') || 'light';
            const icon = document.getElementById('ti');
            if (theme === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        }
        
        // Initialize theme from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon();
        });
    </script>
</body>
</html>
