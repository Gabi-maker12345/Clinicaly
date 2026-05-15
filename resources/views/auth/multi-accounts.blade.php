<x-app-layout>
<style>
    .multi-shell{width:100%;max-width:1180px;margin:0 auto}
    .multi-head{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:20px}
    .multi-title{font-size:1.8rem;font-weight:800;color:var(--tx);line-height:1.05}
    .multi-sub{color:var(--mu);font-weight:650;margin-top:6px}
    .multi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px}
    .multi-card{background:var(--sf);border:1px solid var(--bd);border-radius:16px;box-shadow:var(--sh);padding:20px}
    .multi-card.active{border-color:var(--gbd);box-shadow:0 0 0 3px color-mix(in srgb,var(--gr) 14%,transparent)}
    .multi-form{display:grid;gap:10px;margin-top:14px}
    .multi-field label{display:block;font:800 .55rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em;color:var(--mu);margin-bottom:6px}
    .multi-field input{width:100%;border:1px solid var(--bd);border-radius:12px;background:var(--sf2);color:var(--tx);padding:10px 12px;font-weight:700}
    .multi-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
    .multi-user{display:flex;align-items:center;gap:12px;margin-top:14px;padding:12px;border:1px solid var(--bd);border-radius:12px;background:var(--sf2)}
    .multi-avatar{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:var(--is);color:var(--in);font-weight:900}
</style>

<div class="multi-shell">
    @if(session('success') || session('info') || session('error'))
        <div class="alert {{ session('success') ? 'success' : (session('error') ? 'danger' : 'warning') }}" style="margin-bottom:16px;">
            <i class="fa-solid {{ session('success') ? 'fa-circle-check' : (session('error') ? 'fa-triangle-exclamation' : 'fa-circle-info') }}"></i>
            <span>{{ session('success') ?? session('info') ?? session('error') }}</span>
        </div>
    @endif

    <header class="multi-head">
        <div>
            <span class="tag tag-purple">Sessões paralelas</span>
            <h1 class="multi-title" style="margin-top:10px;">Contas conectadas</h1>
            <p class="multi-sub">Conecte uma clínica, um médico e um paciente, depois alterne o perfil ativo sem sair das outras contas.</p>
        </div>
        <span class="tag success">Ativo: {{ $activeUser?->roleLabel() }}</span>
    </header>

    @if($errors->any())
        <div class="alert danger" style="margin-bottom:16px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <section class="multi-grid">
        @foreach($roles as $role => $label)
            @php
                $connectedUser = ($slots[$role] ?? null) ? $users->get($slots[$role]) : null;
                $isActive = $connectedUser && $activeUser && $connectedUser->id === $activeUser->id;
            @endphp
            <article class="multi-card {{ $isActive ? 'active' : '' }}">
                <span class="tag {{ $isActive ? 'success' : 'info' }}">{{ $label }}</span>
                @if($connectedUser)
                    <div class="multi-user">
                        <span class="multi-avatar">{{ mb_substr($connectedUser->name, 0, 2) }}</span>
                        <div style="min-width:0;">
                            <strong>{{ $connectedUser->name }}</strong>
                            <p class="muted" style="font-size:.86rem;">{{ $connectedUser->email }}</p>
                        </div>
                    </div>
                    <div class="multi-actions">
                        <a class="btn btn-primary btn-sm" href="{{ route('multi-accounts.open', $role) }}" target="_blank" rel="noopener">
                            <i class="fa-solid fa-up-right-from-square"></i>Abrir em nova aba
                        </a>
                        <form method="POST" action="{{ route('multi-accounts.switch', $role) }}">
                            @csrf
                            <button class="btn btn-ghost btn-sm" type="submit" @disabled($isActive)>
                                <i class="fa-solid fa-repeat"></i>{{ $isActive ? 'Aberto nesta aba' : 'Usar nesta aba' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('multi-accounts.disconnect', $role) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-link-slash"></i>Desconectar</button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('multi-accounts.connect') }}" class="multi-form">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">
                        <div class="multi-field">
                            <label>Email</label>
                            <input name="email" type="email" required autocomplete="username">
                        </div>
                        <div class="multi-field">
                            <label>Senha</label>
                            <input name="password" type="password" required autocomplete="current-password">
                        </div>
                        <label style="display:flex;align-items:center;gap:8px;color:var(--mu);font-weight:800;">
                            <input type="checkbox" name="switch_now" value="1" style="width:16px;height:16px;">
                            Usar este perfil agora
                        </label>
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-link"></i>Conectar {{ strtolower($label) }}</button>
                    </form>
                @endif
            </article>
        @endforeach
    </section>
</div>
</x-app-layout>
