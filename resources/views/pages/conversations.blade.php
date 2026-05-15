@extends('layouts.app')

@section('content')
<style>
    .chat-shell{display:grid;grid-template-columns:320px minmax(0,1fr);gap:18px;min-height:620px}
    .conversation-list{padding:0;overflow:hidden;display:flex;flex-direction:column}
    .conversation-row{position:relative;display:flex;align-items:stretch;border-bottom:1px solid var(--bd)}
    .conversation-item{display:flex;align-items:center;gap:12px;padding:14px 10px 14px 16px;text-decoration:none;color:var(--tx);transition:.15s;min-width:0;flex:1}
    .conversation-item:hover,.conversation-item.active{background:var(--is);color:var(--in)}
    .conversation-actions{display:flex;align-items:center;padding-right:10px}
    .chat-panel{display:flex;flex-direction:column;min-height:620px}
    .chat-messages{flex:1;overflow-y:auto;padding:12px 6px 18px;display:flex;flex-direction:column;gap:14px;scrollbar-width:none}
    .chat-messages::-webkit-scrollbar,.conversation-scroll::-webkit-scrollbar{width:0;height:0}
    .chat-bubble{display:inline-block;width:auto;height:auto;min-width:0;min-height:0;max-width:min(58ch,100%);margin:0;padding:10px 15px;border-radius:18px;font-weight:650;font-size:.96rem;line-height:1.45;white-space:pre-wrap;overflow-wrap:anywhere;word-break:break-word;hyphens:auto;flex:0 1 auto;text-align:left;vertical-align:top}
    .chat-bubble.bubble-received{background:var(--sf2);border:1px solid var(--bd);border-bottom-left-radius:5px;align-self:flex-start}
    .chat-bubble.bubble-sent{background:var(--in);color:#fff;border-bottom-right-radius:5px;align-self:flex-end}
    .chat-bubble.bubble-pack{display:block;width:min(520px,100%);max-width:min(520px,100%);padding:12px}
    .clinical-pack{margin-top:10px;padding:12px;border-radius:14px;border:1px solid var(--bd);background:var(--sf);color:var(--tx);white-space:normal;text-align:left;width:100%;min-width:0;max-width:100%;overflow:hidden}
    .chat-bubble.bubble-sent .clinical-pack{border-color:rgba(255,255,255,.26);background:rgba(255,255,255,.12);color:#fff}
    .clinical-pack-title{display:flex;align-items:center;gap:8px;font:800 .62rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.1em;margin-bottom:8px}
    .clinical-pack-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin:10px 0}
    .clinical-pack-kv{border:1px solid var(--bd);border-radius:10px;padding:8px;background:var(--sf2)}
    .chat-bubble.bubble-sent .clinical-pack-kv{border-color:rgba(255,255,255,.18);background:rgba(255,255,255,.1)}
    .clinical-pack-kv span{display:block;font:800 .54rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.08em;opacity:.7}
    .clinical-pack-kv strong{display:block;font-size:.82rem;margin-top:2px;overflow-wrap:anywhere;word-break:break-word}
    .clinical-pack-text{font-weight:700;font-size:.82rem;line-height:1.5;margin:0;display:-webkit-box;-webkit-line-clamp:5;-webkit-box-orient:vertical;overflow:hidden;overflow-wrap:anywhere;word-break:break-word}
    .clinical-pack-symptoms{margin:8px 0 0;font-size:.78rem;font-weight:800;line-height:1.35;overflow-wrap:anywhere;word-break:break-word}
    .clinical-pack-link{display:inline-flex;align-items:center;gap:8px;margin-top:8px;border-radius:999px;padding:8px 12px;background:var(--in);color:#fff;text-decoration:none;font-size:.78rem;font-weight:900}
    .chat-bubble.bubble-sent .clinical-pack-link{background:#fff;color:var(--in)}
    .msg-meta{font:700 .62rem 'Space Mono',monospace;color:var(--fa);margin:0 0 4px}
    .msg-wrap{display:flex;flex-direction:column;align-items:flex-start;position:relative;width:100%;max-width:100%}
    .msg-wrap.sent{align-items:flex-end;text-align:right}
    .msg-line{display:inline-flex;align-items:flex-start;gap:6px;width:auto;max-width:min(70ch,88%);min-width:0}
    .msg-wrap:not(.sent) .msg-line{align-self:flex-start}
    .msg-wrap.sent .msg-line{align-self:flex-end;flex-direction:row-reverse}
    .dots-btn{width:30px;height:30px;border-radius:999px;border:1px solid var(--bd);background:var(--sf2);color:var(--mu);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:.15s}
    .dots-btn:hover{background:var(--is);color:var(--in);border-color:var(--in)}
    .action-menu{position:absolute;right:8px;top:34px;z-index:30;min-width:132px;background:var(--sf);border:1px solid var(--bd);border-radius:12px;box-shadow:var(--sh2);padding:6px}
    .conversation-row .action-menu{right:12px;top:44px}
    .action-menu button{display:flex;align-items:center;gap:8px;width:100%;border:0;background:transparent;color:var(--tx);padding:9px 10px;border-radius:9px;font-weight:700;text-align:left}
    .action-menu button:hover{background:var(--is);color:var(--in)}
    .edit-message{display:flex;gap:8px;max-width:min(78ch,82%);width:min(78ch,82%)}
    .edit-message input{width:100%;border:1.5px solid var(--bd);border-radius:14px;background:var(--sf2);color:var(--tx);padding:10px 12px;font-weight:700;outline:none}
    .std-modal-overlay{--modal-sf:#fff;--modal-sf2:#f7f9fc;--modal-bd:#dbe2ee;--modal-tx:#192132;--modal-mu:#6e7a91;--modal-in:#6d55b1;position:fixed;inset:0;z-index:2147483000;background:rgba(10,8,20,.62);backdrop-filter:blur(4px);display:flex;align-items:center;justify-content:center;padding:20px;overflow:hidden;overscroll-behavior:contain}
    html[data-theme="dark"] .std-modal-overlay{--modal-sf:#131128;--modal-sf2:#1a1735;--modal-bd:#302a55;--modal-tx:#e8e4f8;--modal-mu:#8880aa;--modal-in:#9880d4}
    .std-modal{width:min(420px,calc(100vw - 32px));max-height:none;overflow:visible;background:var(--modal-sf);border:1px solid var(--modal-bd);border-radius:20px;box-shadow:0 24px 70px rgba(0,0,0,.28);padding:22px;color:var(--modal-tx)}
    .std-modal h2{font-size:1.05rem;font-weight:800;color:var(--modal-tx);margin:0 0 8px}.std-modal p{color:var(--modal-mu);font-weight:600;line-height:1.45;margin:0 0 14px}.std-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:18px;flex-wrap:wrap}
    .std-modal input{width:100%;border:1.5px solid var(--modal-bd);border-radius:12px;background:var(--modal-sf2);color:var(--modal-tx);padding:11px 13px;font-weight:700;outline:none}.std-modal input:focus{border-color:var(--modal-in);box-shadow:0 0 0 3px rgba(109,85,177,.13)}
    .chat-form{display:flex;gap:10px;padding-top:16px;border-top:1px solid var(--bd)}
    .chat-form input,.user-search input{width:100%;border:1.5px solid var(--bd);border-radius:999px;background:var(--sf2);color:var(--tx);padding:12px 16px;font-weight:700;outline:none}
    .search-result{display:flex;align-items:center;gap:12px;width:100%;padding:12px 14px;border:0;border-bottom:1px solid var(--bd);background:transparent;color:var(--tx);text-align:left;cursor:pointer}
    .search-result:hover{background:var(--is)}
    body.conversation-modal-open{overflow:hidden!important}
    @media(max-width:980px){.chat-shell{grid-template-columns:1fr}.chat-panel{min-height:560px}.msg-line{max-width:96%}.chat-bubble.bubble-pack{max-width:100%}}
    @media(max-width:560px){.clinical-pack-grid{grid-template-columns:1fr}.chat-bubble{max-width:100%}}
</style>

<div x-data="{
    modalOpen: false,
    modalTitle: '',
    modalMessage: '',
    modalConfirm: null,
    editOpen: false,
    editAction: '',
    editTitle: '',
    deleteOpen: false,
    deleteAction: '',
    openEdit(action, title){ this.editAction = action; this.editTitle = title || ''; this.editOpen = true; },
    openDelete(action){ this.deleteAction = action; this.deleteOpen = true; },
    openConfirm(title, message, onConfirm){ this.modalTitle = title; this.modalMessage = message; this.modalConfirm = onConfirm; this.modalOpen = true; },
    closeModals(){ this.editOpen = false; this.deleteOpen = false; this.modalOpen = false; this.modalConfirm = null; },
    runConfirm(){ const action = this.modalConfirm; this.modalOpen = false; this.modalConfirm = null; if(action) action(); }
}" @clinicaly-confirm.window="openConfirm($event.detail.title, $event.detail.message, $event.detail.onConfirm)"
   @conversation-edit.window="openEdit($event.detail.action, $event.detail.title)"
   @conversation-delete.window="openDelete($event.detail.action)"
   @keydown.escape.window="closeModals()"
   x-effect="document.body.classList.toggle('conversation-modal-open', editOpen || deleteOpen || modalOpen)"
   x-init="$watch('editOpen', value => { if (value) $nextTick(() => $refs.conversationTitleInput?.focus()) })">

<section style="margin-bottom:20px;display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div>
        <span class="tag info"><i class="fa-solid fa-comments"></i> Conversas</span>
        <h1 style="font-size:1.8rem;font-weight:800;margin-top:12px;color:var(--tx);">Minhas Conversas</h1>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-ghost"><i class="fa-solid fa-arrow-left"></i>Voltar</a>
</section>

<div class="user-search" x-data="{ search: '', results: [], loading: false, open: false }" @click.away="open = false" style="position:relative;margin-bottom:18px;">
    <input x-model="search" @focus="open = true" @input="
        if (search.length < 2) { results = []; loading = false; return; }
        open = true; loading = true;
        fetch('{{ route('messages.search') }}?q=' + encodeURIComponent(search))
            .then(r => r.json())
            .then(data => { results = data; loading = false; })
            .catch(() => { results = []; loading = false; });
    " type="search" placeholder="Pesquisar usuário para iniciar conversa">
    <div x-show="open && (loading || search.length >= 2)" x-cloak class="card" style="position:absolute;z-index:20;left:0;right:0;top:calc(100% + 8px);padding:0;overflow:hidden;">
        <template x-if="loading"><div style="padding:16px;color:var(--mu);"><i class="fa-solid fa-spinner fa-spin"></i> Pesquisando...</div></template>
        <template x-for="user in results" :key="user.id">
            <form method="POST" :action="'{{ route('messages.start', '__USER_ID__') }}'.replace('__USER_ID__', user.id)">
                @csrf
                <button type="submit" class="search-result">
                    <img :src="user.profile_photo_url" :alt="user.name" style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                    <span style="flex:1;"><strong x-text="user.name"></strong><small style="display:block;color:var(--mu);font:700 .62rem 'Space Mono',monospace;text-transform:uppercase;" x-text="['doctor','medico','médico'].includes(user.role) ? 'Médico' : 'Paciente'"></small></span>
                    <i class="fa-solid fa-message" style="color:var(--in);"></i>
                </button>
            </form>
        </template>
        <template x-if="!loading && search.length >= 2 && results.length === 0"><div style="padding:16px;color:var(--mu);">Nenhum usuário encontrado.</div></template>
    </div>
</div>

<div class="chat-shell">
    <aside class="card conversation-list">
        <h2 style="padding:16px;margin:0;border-bottom:1px solid var(--bd);font:700 .65rem 'Space Mono',monospace;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);">
            <i class="fa-solid fa-inbox"></i> Conversas
        </h2>
        <div class="conversation-scroll" style="overflow:auto;min-height:0;">
            @forelse($conversations ?? collect() as $conv)
                @php
                    $participant = $conv->sender_id == auth()->id() ? $conv->receiver : $conv->sender;
                    $lastMessage = $conv->messages->first();
                    $active = isset($conversation) && $conversation->id === $conv->id;
                    $displayTitle = $conv->title ?: ($participant?->name ?? 'Usuário');
                @endphp
                <div class="conversation-row" x-data="{ open: false }" @click.away="open = false">
                    <a href="{{ route('messages.show', $conv->id) }}" class="conversation-item {{ $active ? 'active' : '' }}">
                        <img src="{{ $participant?->profile_photo_url }}" alt="{{ $participant?->name }}" style="width:42px;height:42px;border-radius:14px;object-fit:cover;">
                        <span style="min-width:0;flex:1;">
                            <strong style="display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $displayTitle }}</strong>
                            <small style="display:block;color:var(--mu);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $lastMessage?->body ?? 'Sem mensagens ainda' }}</small>
                        </span>
                        <small style="color:var(--fa);font-family:'Space Mono',monospace;">{{ $conv->updated_at?->format('H:i') }}</small>
                    </a>
                    <div class="conversation-actions">
                        <button type="button" class="dots-btn" @click.stop="open = !open" aria-label="Ações da conversa"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                    <div x-show="open" x-cloak class="action-menu">
                        <button type="button" @click="open = false; window.dispatchEvent(new CustomEvent('conversation-edit', {detail: {action: '{{ route('messages.conversations.update', $conv->id) }}', title: @js($conv->title ?: '')}}))"><i class="fa-solid fa-pen"></i>Editar</button>
                        <button type="button" @click="open = false; window.dispatchEvent(new CustomEvent('conversation-delete', {detail: {action: '{{ route('messages.conversations.destroy', $conv->id) }}'}}))"><i class="fa-solid fa-trash"></i>Excluir</button>
                    </div>
                </div>
            @empty
                <p style="padding:22px;color:var(--mu);text-align:center;">Nenhuma conversa iniciada.</p>
            @endforelse
        </div>
    </aside>

    @if(isset($conversation, $messages))
        @php $participant = $conversation->sender_id == auth()->id() ? $conversation->receiver : $conversation->sender; @endphp
        <article class="card chat-panel" x-data="{
            body: '',
            sending: false,
            editingId: null,
            editingBody: '',
            openMenu: null,
            messages: @js($messages->load('user')),
            authId: {{ auth()->id() }},
            scopedRole: new URLSearchParams(window.location.search).get('as_role'),
            clinicalPackUrlTemplate: @js(route('clinical-requests.diagnose', '__MESSAGE_ID__')),
            scroll(){ this.$nextTick(() => { const el = this.$refs.messages; el.scrollTop = el.scrollHeight; }); },
            clinicalPackUrl(msg){
                const url = new URL(this.clinicalPackUrlTemplate.replace('__MESSAGE_ID__', msg.id), window.location.origin);
                if(this.scopedRole) url.searchParams.set('as_role', this.scopedRole);
                return url.toString();
            },
            bubbleStyle(msg){
                const isPack = msg.type === 'clinical_pack';
                return [
                    'display:inline-flex',
                    'align-items:center',
                    'justify-content:flex-start',
                    'width:' + (isPack ? 'min(520px,100%)' : 'max-content'),
                    'max-width:' + (isPack ? 'min(520px,100%)' : 'min(58ch,100%)'),
                    'height:auto',
                    'min-width:0',
                    'min-height:0',
                    'padding:' + (isPack ? '12px' : '10px 15px'),
                    'line-height:1.45',
                    'white-space:pre-wrap',
                    'overflow-wrap:anywhere',
                    'word-break:break-word',
                    'text-align:left'
                ].join(';');
            },
            edit(msg){ this.editingId = msg.id; this.editingBody = msg.body; this.openMenu = null; },
            cancelEdit(){ this.editingId = null; this.editingBody = ''; },
            async saveEdit(msg){
                if(!this.editingBody.trim()) return;
                const response = await fetch('{{ route('messages.update', '__MESSAGE_ID__') }}'.replace('__MESSAGE_ID__', msg.id), {
                    method: 'PATCH',
                    headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                    body: JSON.stringify({body: this.editingBody})
                });
                if(response.ok){
                    const updated = await response.json();
                    this.messages = this.messages.map(item => item.id === updated.id ? updated : item);
                    this.cancelEdit();
                }
            },
            async deleteMessage(msg){
                window.dispatchEvent(new CustomEvent('clinicaly-confirm', {detail: {
                    title: 'Excluir mensagem',
                    message: 'Esta mensagem será removida da conversa.',
                    onConfirm: async () => {
                        const response = await fetch('{{ route('messages.destroy', '__MESSAGE_ID__') }}'.replace('__MESSAGE_ID__', msg.id), {
                            method: 'DELETE',
                            headers: {'Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
                        });
                        if(response.ok){ this.messages = this.messages.filter(item => item.id !== msg.id); this.openMenu = null; }
                    }
                }}));
            },
            async send(){
                if(!this.body.trim() || this.sending) return;
                this.sending = true;
                const response = await fetch('{{ route('messages.store') }}', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                    body: JSON.stringify({conversation_id: {{ $conversation->id }}, body: this.body})
                });
                if(response.ok){ this.messages.push(await response.json()); this.body = ''; this.scroll(); }
                this.sending = false;
            }
        }" x-init="scroll()">
            <header style="display:flex;align-items:center;gap:12px;padding-bottom:16px;border-bottom:1px solid var(--bd);">
                <img src="{{ $participant?->profile_photo_url }}" alt="{{ $participant?->name }}" style="width:46px;height:46px;border-radius:16px;object-fit:cover;">
                <div>
                    <h2 style="font-weight:800;color:var(--tx);">{{ $participant?->name }}</h2>
                    <p style="color:var(--gr);font-weight:700;font-size:.8rem;">online</p>
                </div>
            </header>
            <div class="chat-messages" x-ref="messages">
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.user_id == authId ? 'msg-wrap sent' : 'msg-wrap'" @click.away="openMenu = null">
                        <p class="msg-meta" x-text="(msg.user?.name || 'Usuário') + ' · ' + new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})"></p>
                        <template x-if="editingId === msg.id">
                            <div class="edit-message">
                                <input x-model="editingBody" @keydown.enter.prevent="saveEdit(msg)" @keydown.escape.prevent="cancelEdit()" type="text">
                                <button type="button" class="btn btn-primary btn-sm" @click="saveEdit(msg)"><i class="fa-solid fa-check"></i></button>
                                <button type="button" class="btn btn-ghost btn-sm" @click="cancelEdit()"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </template>
                        <template x-if="editingId !== msg.id">
                            <div class="msg-line">
                                <div class="chat-bubble" :class="[(msg.user_id == authId ? 'bubble-sent' : 'bubble-received'), (msg.type === 'clinical_pack' ? 'bubble-pack' : '')]" :style="bubbleStyle(msg)">
                                    <span style="display:inline;width:auto;max-width:100%;height:auto;line-height:1.45;overflow-wrap:anywhere;word-break:break-word;" x-text="(msg.body || '').trim()"></span>
                                    <template x-if="msg.type === 'clinical_pack'">
                                        <div class="clinical-pack">
                                            <div class="clinical-pack-title"><i class="fa-solid fa-file-medical"></i> Pacote clínico</div>
                                            <div class="clinical-pack-grid">
                                                <div class="clinical-pack-kv"><span>Paciente</span><strong x-text="msg.payload?.patient_name || 'Paciente'"></strong></div>
                                                <div class="clinical-pack-kv"><span>Clínica</span><strong x-text="msg.payload?.clinic_name || 'Clínica'"></strong></div>
                                                <div class="clinical-pack-kv"><span>Idade</span><strong x-text="msg.payload?.age ? msg.payload.age + ' anos' : 'Não informado'"></strong></div>
                                                <div class="clinical-pack-kv"><span>Biometria</span><strong x-text="`${msg.payload?.weight || '-'} kg · ${msg.payload?.height || '-'} m`"></strong></div>
                                            </div>
                                            <p class="clinical-pack-text" x-text="msg.payload?.description || 'Sem descrição clínica.'"></p>
                                            <template x-if="Array.isArray(msg.payload?.symptoms) && msg.payload.symptoms.length">
                                                <p class="clinical-pack-symptoms" x-text="'Sintomas: ' + msg.payload.symptoms.map(symptom => symptom.name).join(', ')"></p>
                                            </template>
                                            <template x-if="msg.payload?.doctor_id == authId">
                                                <a class="clinical-pack-link" :href="clinicalPackUrl(msg)">
                                                    <i class="fa-solid fa-stethoscope"></i> Abrir diagnóstico
                                                </a>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                <template x-if="msg.user_id == authId">
                                    <div style="position:relative;">
                                        <button type="button" class="dots-btn" @click.stop="openMenu = openMenu === msg.id ? null : msg.id" aria-label="Ações da mensagem"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                        <div x-show="openMenu === msg.id" x-cloak class="action-menu">
                                            <button type="button" @click="edit(msg)"><i class="fa-solid fa-pen"></i>Editar</button>
                                            <button type="button" @click="deleteMessage(msg)"><i class="fa-solid fa-trash"></i>Excluir</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            <form class="chat-form" @submit.prevent="send">
                <input x-model="body" type="text" placeholder="Escreva uma mensagem..." autocomplete="off" :disabled="sending">
                <button class="btn btn-primary" type="submit" :disabled="sending || !body.trim()"><i class="fa-solid fa-paper-plane"></i>Enviar</button>
            </form>
        </article>
    @else
        <article class="card" style="display:flex;align-items:center;justify-content:center;text-align:center;min-height:620px;">
            <p style="color:var(--mu);font-weight:700;"><i class="fa-solid fa-message" style="display:block;font-size:2.4rem;margin-bottom:12px;color:var(--in);"></i>Selecione uma conversa para começar.</p>
        </article>
    @endif
</div>

<template x-teleport="body">
    <div x-show="editOpen" x-cloak class="std-modal-overlay" @click.self="editOpen=false">
        <form class="std-modal" method="POST" :action="editAction" role="dialog" aria-modal="true">
            @csrf
            @method('PATCH')
            <h2>Editar conversa</h2>
            <p>Defina um nome curto para reconhecer esta conversa na lista.</p>
            <input type="text" name="title" x-model="editTitle" x-ref="conversationTitleInput" maxlength="80" placeholder="Ex.: Retorno pós-consulta">
            <div class="std-actions">
                <button type="button" class="btn btn-ghost" @click="editOpen=false">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</template>

<template x-teleport="body">
    <div x-show="deleteOpen" x-cloak class="std-modal-overlay" @click.self="deleteOpen=false">
        <form class="std-modal" method="POST" :action="deleteAction" role="dialog" aria-modal="true">
            @csrf
            @method('DELETE')
            <h2>Excluir conversa</h2>
            <p>Esta conversa e todas as suas mensagens serão removidas permanentemente.</p>
            <div class="std-actions">
                <button type="button" class="btn btn-ghost" @click="deleteOpen=false">Cancelar</button>
                <button type="submit" class="btn btn-danger">Excluir</button>
            </div>
        </form>
    </div>
</template>

<template x-teleport="body">
    <div x-show="modalOpen" x-cloak class="std-modal-overlay" @click.self="modalOpen=false">
        <div class="std-modal" role="dialog" aria-modal="true">
            <h2 x-text="modalTitle"></h2>
            <p x-text="modalMessage"></p>
            <div class="std-actions">
                <button type="button" class="btn btn-ghost" @click="modalOpen=false">Cancelar</button>
                <button type="button" class="btn btn-danger" @click="runConfirm()">Confirmar</button>
            </div>
        </div>
    </div>
</template>
</div>
@endsection
