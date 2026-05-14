<x-app-layout>
<main class="page">
    <style>
    .page{max-width:900px;margin:0 auto;padding:28px 20px 80px;}
    @keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
    .fi{animation:fi .3s ease both;}.fi1{animation-delay:.05s;}.fi2{animation-delay:.1s;}.fi3{animation-delay:.15s;}
    .act-card{transition:all .2s;}.act-card:hover{border-color:var(--in);box-shadow:var(--sh2);transform:translateY(-2px);}
    </style>

    <section class="fi" aria-labelledby="pg-h">
        <h1 id="pg-h" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">Olá, <span style="background:linear-gradient(135deg,var(--in),var(--il));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ explode(' ', $user->name)[0] }}</span></h1>
        <p style="font-family:'Space Mono',monospace;font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);">O que quer fazer hoje?</p>
    </section>

    @if($stats['pendentes'] > 0)
    <section class="fi fi1" style="margin-top:14px;" aria-label="Resumo rápido">
        <div role="status" style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:var(--rs);font-size:.84rem;font-weight:500;border:1px solid;background:var(--rb);border-color:var(--rbd);color:var(--rd);">
            <i class="fa-solid fa-hourglass-half" aria-hidden="true" style="flex-shrink:0;"></i>
            <p style="flex:1;margin:0;text-align:left;line-height:1.35;"><strong>{{ $stats['pendentes'] }} diagnóstico{{ $stats['pendentes'] != 1 ? 's' : '' }}</strong> a aguardar validação médica</p>
            <a href="{{ route('discovery.index') }}" style="margin-left:auto;flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:30px;border:none;font-family:'Dosis',sans-serif;font-size:.84rem;font-weight:700;cursor:pointer;text-decoration:none;background:transparent;color:var(--rd);border:1.5px solid var(--rbd);">Ver</a>
        </div>
    </section>
    @endif

    <nav class="fi fi2" style="margin-top:20px;" aria-label="Actividades principais">
        <ul style="list-style:none;display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;">

            <li>
                <a href="{{ route('chat.index') }}" class="act-card" style="display:flex;align-items:center;gap:16px;padding:22px;background:var(--sf);border:1.5px solid var(--bd);border-radius:var(--r);box-shadow:var(--sh);text-decoration:none;" aria-label="Chat IA">
                    <span style="width:52px;height:52px;border-radius:14px;background:var(--bb);border:2px solid var(--bbd);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-robot" style="font-size:1.3rem;color:var(--bl);" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p style="font-weight:800;font-size:1rem;color:var(--tx);">Chat IA</p>
                        <p style="font-size:.78rem;color:var(--mu);margin-top:2px;">Conversa livre com o assistente médico</p>
                    </div>
                    <i class="fa-solid fa-arrow-right" style="margin-left:auto;color:var(--mu);font-size:.85rem;" aria-hidden="true"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('discovery.index') }}" class="act-card" style="display:flex;align-items:center;gap:16px;padding:22px;background:var(--sf);border:1.5px solid var(--bd);border-radius:var(--r);box-shadow:var(--sh);text-decoration:none;" aria-label="Dicionário de Saúde">
                    <span style="width:52px;height:52px;border-radius:14px;background:var(--gb);border:2px solid var(--gbd);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-book-medical" style="font-size:1.3rem;color:var(--gr);" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p style="font-weight:800;font-size:1rem;color:var(--tx);">Dicionário de Saúde</p>
                        <p style="font-size:.78rem;color:var(--mu);margin-top:2px;">Doenças, sintomas e fontes médicas</p>
                    </div>
                    <i class="fa-solid fa-arrow-right" style="margin-left:auto;color:var(--mu);font-size:.85rem;" aria-hidden="true"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.show') }}" class="act-card" style="display:flex;align-items:center;gap:16px;padding:22px;background:var(--sf);border:1.5px solid var(--bd);border-radius:var(--r);box-shadow:var(--sh);text-decoration:none;position:relative;" aria-label="Meus Diagnósticos">
                    <span style="width:52px;height:52px;border-radius:14px;background:var(--is);border:2px solid var(--bd);display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;">
                        <i class="fa-solid fa-stethoscope" style="font-size:1.3rem;color:var(--in);" aria-hidden="true"></i>
                        @if($stats['pendentes'] > 0)
                        <span style="position:absolute;top:-4px;right:-4px;width:18px;height:18px;border-radius:50%;background:var(--rd);color:#fff;font-size:.55rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid var(--sf);">{{ $stats['pendentes'] }}</span>
                        @endif
                    </span>
                    <div>
                        <p style="font-weight:800;font-size:1rem;color:var(--tx);">Meus Diagnósticos</p>
                        <p style="font-size:.78rem;color:var(--mu);margin-top:2px;">Histórico e resultados dos diagnósticos</p>
                    </div>
                    <i class="fa-solid fa-arrow-right" style="margin-left:auto;color:var(--mu);font-size:.85rem;" aria-hidden="true"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.show') }}" class="act-card" style="display:flex;align-items:center;gap:16px;padding:22px;background:var(--sf);border:1.5px solid var(--bd);border-radius:var(--r);box-shadow:var(--sh);text-decoration:none;" aria-label="Minhas Prescrições">
                    <span style="width:52px;height:52px;border-radius:14px;background:var(--wb);border:2px solid var(--wbd);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-file-medical" style="font-size:1.3rem;color:var(--wn);" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p style="font-weight:800;font-size:1rem;color:var(--tx);">Minhas Prescrições</p>
                        <p style="font-size:.78rem;color:var(--mu);margin-top:2px;">Medicamentos e histórico de receitas</p>
                    </div>
                    <i class="fa-solid fa-arrow-right" style="margin-left:auto;color:var(--mu);font-size:.85rem;" aria-hidden="true"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('messages.index') }}" class="act-card" style="display:flex;align-items:center;gap:16px;padding:22px;background:var(--sf);border:1.5px solid var(--bd);border-radius:var(--r);box-shadow:var(--sh);text-decoration:none;" aria-label="Minhas Mensagens">
                    <span style="width:52px;height:52px;border-radius:14px;background:var(--rb);border:2px solid var(--rbd);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-comment-dots" style="font-size:1.3rem;color:var(--rd);" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p style="font-weight:800;font-size:1rem;color:var(--tx);">Minhas Mensagens</p>
                        <p style="font-size:.78rem;color:var(--mu);margin-top:2px;">Conversas com seu médico</p>
                    </div>
                    <i class="fa-solid fa-arrow-right" style="margin-left:auto;color:var(--mu);font-size:.85rem;" aria-hidden="true"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.show') }}" class="act-card" style="display:flex;align-items:center;gap:16px;padding:22px;background:var(--sf);border:1.5px solid var(--bd);border-radius:var(--r);box-shadow:var(--sh);text-decoration:none;" aria-label="Meu Perfil">
                    <span style="width:52px;height:52px;border-radius:14px;background:var(--bb);border:2px solid var(--bbd);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-user-gear" style="font-size:1.3rem;color:var(--bl);" aria-hidden="true"></i>
                    </span>
                    <div>
                        <p style="font-weight:800;font-size:1rem;color:var(--tx);">Meu Perfil</p>
                        <p style="font-size:.78rem;color:var(--mu);margin-top:2px;">Dados pessoais e configurações</p>
                    </div>
                    <i class="fa-solid fa-arrow-right" style="margin-left:auto;color:var(--mu);font-size:.85rem;" aria-hidden="true"></i>
                </a>
            </li>

        </ul>
    </nav>

</main>

<script>
    document.querySelectorAll('.act-card').forEach(c => {
        c.addEventListener('mouseenter', () => {
            c.style.borderColor = 'var(--in)';
            c.style.boxShadow = 'var(--sh2)';
            c.style.transform = 'translateY(-2px)';
        });
        c.addEventListener('mouseleave', () => {
            c.style.borderColor = 'var(--bd)';
            c.style.boxShadow = 'var(--sh)';
            c.style.transform = '';
        });
    });
</script>
</x-app-layout>
