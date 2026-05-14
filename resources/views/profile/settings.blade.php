@extends('layouts.app')

@section('content')
<section style="margin-bottom:22px;">
    <span class="tag info"><i class="fa-solid fa-gear"></i> Perfil</span>
    <h1 style="font-size:1.8rem;font-weight:800;margin-top:12px;color:var(--tx);">Configurações</h1>
    <p style="color:var(--mu);font-weight:600;">Segurança, notificações e dados pessoais da sua conta.</p>
</section>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;">
    <article class="card">
        <h2 style="font-size:1.1rem;font-weight:800;margin-bottom:8px;color:var(--tx);"><i class="fa-solid fa-shield-halved" style="color:var(--in);margin-right:8px;"></i>Segurança</h2>
        <p style="color:var(--mu);margin-bottom:18px;">Senha, sessões ativas e autenticação de dois fatores.</p>
        <a class="btn btn-ghost" href="{{ route('profile.show') }}#security"><i class="fa-solid fa-arrow-right"></i>Abrir segurança</a>
    </article>
    <article class="card">
        <h2 style="font-size:1.1rem;font-weight:800;margin-bottom:8px;color:var(--tx);"><i class="fa-solid fa-bell" style="color:var(--in);margin-right:8px;"></i>Notificações</h2>
        <p style="color:var(--mu);margin-bottom:18px;">Preferências de alertas, mensagens e lembretes clínicos.</p>
        <span class="tag">Em breve</span>
    </article>
    <article class="card">
        <h2 style="font-size:1.1rem;font-weight:800;margin-bottom:8px;color:var(--tx);"><i class="fa-solid fa-id-card" style="color:var(--in);margin-right:8px;"></i>Dados Pessoais</h2>
        <p style="color:var(--mu);margin-bottom:18px;">Nome, email e foto de perfil permanecem no formulário principal do perfil.</p>
        <a class="btn btn-ghost" href="{{ route('profile.show') }}#profile"><i class="fa-solid fa-user"></i>Editar perfil</a>
    </article>
</div>
@endsection
