<x-app-layout>
<style>
    .noti-page{max-width:1180px;margin:0 auto;padding-bottom:48px}
    @keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
    .fi{animation:fi .3s ease both}.fi1{animation-delay:.05s}.fi2{animation-delay:.1s}
    .noti-head{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:10px}
    .noti-title{font-size:1.55rem;font-weight:800;margin-bottom:4px;color:var(--tx)}
    .noti-sub{font-family:'Space Mono',monospace;font-size:.58rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu)}
    .filter-list{list-style:none;display:flex;gap:8px;flex-wrap:wrap;margin:0;padding:0}
    .noti-list{list-style:none;margin:0;padding:0}
    .noti{display:flex;align-items:flex-start;gap:12px;padding:14px 12px;border-radius:8px;border-bottom:1px solid var(--bd);transition:background .15s;text-decoration:none;color:inherit}
    .noti:last-child{border-bottom:none}
    .noti:hover{background:var(--sf2)}
    .noti.unread{background:var(--is)}
    .noti.unread:hover{background:var(--id)}
    .ndot{width:9px;height:9px;border-radius:50%;margin-top:5px;flex-shrink:0}
    .nb{flex:1;min-width:0}
    .n-ti{font-size:.9rem;font-weight:700;color:var(--tx)}
    .n-su{font-size:.76rem;color:var(--mu);margin-top:2px;line-height:1.4}
    .n-tm{font-family:'Space Mono',monospace;font-size:.58rem;color:var(--fa);white-space:nowrap}
    .bxs{padding:4px 10px;font-size:.72rem}
    .empty-state{text-align:center;color:var(--mu);padding:34px;font-weight:700}
</style>

<main class="noti-page" x-data="{filter:'all'}">
    <section class="fi" aria-labelledby="pg-h">
        <div class="noti-head">
            <div>
                <h1 id="pg-h" class="noti-title">Notificações</h1>
                <p class="noti-sub">{{ $unreadCount }} não lidas</p>
            </div>
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button class="btn btn-ghost btn-sm" type="submit">Marcar todas como lidas</button>
            </form>
        </div>
    </section>

    <section class="card fi fi1" style="margin-top:14px;" aria-label="Filtros">
        <nav aria-label="Filtros de notificações">
            <ul class="filter-list">
                <li><button type="button" class="btn bxs" :class="filter === 'all' ? 'btn-primary' : 'btn-ghost'" @click="filter='all'">Todas ({{ $counts['all'] }})</button></li>
                <li><button type="button" class="btn bxs" :class="filter === 'unread' ? 'btn-primary' : 'btn-ghost'" @click="filter='unread'">Não lidas ({{ $counts['unread'] }})</button></li>
                <li><button type="button" class="btn bxs" :class="filter === 'diag' ? 'btn-primary' : 'btn-ghost'" @click="filter='diag'">Diagnósticos</button></li>
                <li><button type="button" class="btn bxs" :class="filter === 'msg' ? 'btn-primary' : 'btn-ghost'" @click="filter='msg'">Mensagens</button></li>
                <li><button type="button" class="btn bxs" :class="filter === 'presc' ? 'btn-primary' : 'btn-ghost'" @click="filter='presc'">Prescrições</button></li>
                <li><button type="button" class="btn bxs" :class="filter === 'appt' ? 'btn-primary' : 'btn-ghost'" @click="filter='appt'">Consultas ({{ $counts['appt'] }})</button></li>
            </ul>
        </nav>
    </section>

    <section class="card fi fi2" style="margin-top:14px;" aria-label="Lista de notificações" aria-live="polite">
        <ul class="noti-list">
            @forelse($notifications as $notification)
                <li x-show="filter === 'all' || (filter === 'unread' && {{ $notification['read'] ? 'false' : 'true' }}) || filter === '{{ $notification['type'] }}'"
                    class="noti {{ $notification['read'] ? '' : 'unread' }}"
                    data-type="{{ $notification['type'] }}"
                    data-read="{{ $notification['read'] ? 'true' : 'false' }}">
                    <span class="ndot" style="background:{{ $notification['read'] ? 'var(--fa)' : $notification['color'] }};" aria-hidden="true"></span>
                    <a class="nb" href="{{ $notification['url'] }}"
                       @if($notification['read_url'])
                       onclick="event.preventDefault(); fetch('{{ $notification['read_url'] }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).finally(() => window.location.href = this.href);"
                       @endif>
                        <p class="n-ti">{{ $notification['title'] }}</p>
                        <p class="n-su">{{ $notification['message'] }}</p>
                    </a>
                    <span class="n-tm">{{ optional($notification['time'])->diffForHumans() }}</span>
                </li>
            @empty
                <li class="empty-state">Nenhuma notificação encontrada.</li>
            @endforelse
        </ul>
    </section>
</main>
</x-app-layout>
