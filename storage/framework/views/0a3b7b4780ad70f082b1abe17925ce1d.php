

<?php $__env->startSection('content'); ?>
<style>
.topbar{position:sticky;top:0;z-index:50;background:var(--sf);border-bottom:1px solid var(--bd);box-shadow:var(--sh);display:flex;align-items:center;gap:12px;padding:0 24px;height:60px;}
.logo{font-size:1.05rem;font-weight:800;letter-spacing:.1em;background:linear-gradient(135deg,var(--in),var(--il));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;text-decoration:none;}
.vid{font-family:'Space Mono',monospace;font-size:.55rem;color:var(--mu);letter-spacing:.1em;text-transform:uppercase;}
.page{max-width:100%;margin:0 auto;padding:24px 24px 0; height: 100%; display: flex; flex-direction: column; overflow: hidden;}
@keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
.fi{animation:fi .3s ease both;}.fi1{animation-delay:.05s;}.fi2{animation-delay:.1s;}.fi3{animation-delay:.15s;}
.rb{font-family:'Space Mono',monospace;font-size:.55rem;padding:4px 12px;border-radius:20px;letter-spacing:.08em;text-transform:uppercase;font-weight:700;display:inline-block;}
.rb-gr{background:var(--gb);color:var(--gr);border:1.5px solid var(--gbd);}
.card{background:var(--sf);border:1px solid var(--bd);border-radius:var(--r);padding:24px;box-shadow:var(--sh); height: 60vh; overflow-y: scroll;}
.ct{font-family:'Space Mono',monospace;font-size:.65rem;text-transform:uppercase;letter-spacing:.12em;color:var(--mu);display:flex;align-items:center;gap:8px;padding-bottom:14px;border-bottom:1px solid var(--bd);margin-bottom:16px;}
.av{display:flex;align-items:center;justify-content:center;font-weight:700;border-radius:50%;flex-shrink:0;}
.av-gr{background:var(--gb);color:var(--gr);border:1.5px solid var(--gbd);}
.av-bl{background:var(--bb);color:var(--bl);border:1.5px solid var(--bbd);}
.av-sm{width:40px;height:40px;font-size:.85rem;}
.ib{background:transparent;border:1.5px solid var(--bd);border-radius:50%;width:36px;height:36px;cursor:pointer;color:var(--mu);display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .2s;position:relative;}
.ib:hover{border-color:var(--in);color:var(--in);background:var(--is);}
.nd{position:absolute;top:-1px;right:-1px;width:8px;height:8px;border-radius:50%;background:var(--rd);border:2px solid var(--sf);}
.tb{background:var(--sf2);border:1.5px solid var(--bd);border-radius:50%;width:34px;height:34px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--mu);font-size:.85rem;transition:all .2s;}
.tb:hover{border-color:var(--in);color:var(--in);}
.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:30px;border:none;font-family:'Dosis',sans-serif;font-size:.95rem;font-weight:700;cursor:pointer;text-decoration:none;transition:all .2s;white-space:nowrap;justify-content:center;}
.bsm{padding:8px 16px;font-size:.9rem;}
.b-pr{background:var(--in);color:#fff;}
.b-pr:hover{background:var(--il);}
.b-gh{background:transparent;color:var(--mu);border:1.5px solid var(--bd);}
.b-gh:hover{background:var(--is);border-color:var(--in);color:var(--in);}
.bxs{padding:6px 12px;font-size:.85rem;}
.ch-win{display:flex;flex-direction:column;gap:8px;flex:1;overflow-y:auto;padding:16px 4px;}
.ch-mt{font-family:'Space Mono',monospace;font-size:.62rem;color:var(--fa);margin-bottom:4px;}.ch-mt.r{text-align:right;margin-top:14px;}
.bub{max-width:72%; width: fit-content; padding:10px 16px; border-radius:18px; font-size:.95rem; line-height:1.5; word-wrap: break-word;}
.bub-rc{background:var(--sf2);border:1.5px solid var(--bd);border-bottom-left-radius:4px; align-self: flex-start;}
.bub-sn{background:var(--in);color:#fff;border-bottom-right-radius:4px; align-self: flex-end;}
.ch-bar{display:flex;gap:10px;margin-top:16px;padding-top:16px;border-top:1px solid var(--bd); flex-shrink: 0;}
.ch-bar input{flex:1;background:var(--sf2);border:1.5px solid var(--bd);border-radius:30px;padding:12px 18px;color:var(--tx);font-family:'Dosis',sans-serif;font-size:.95rem;outline:none;}
.ch-bar input:focus{border-color:var(--in);}
.conv-item{display:flex;align-items:center;gap:12px;padding:14px 16px;cursor:pointer;border-bottom:1px solid var(--bd);text-decoration:none;color:inherit;transition:background .15s;}
.conv-item:hover{background:var(--sf2);}
.conv-item.active{background:var(--is);}
.sr-only{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0);}
[x-cloak] { display: none !important; }
/* Custom Scrollbar para combinar com o design */
.ch-win::-webkit-scrollbar, aside ul::-webkit-scrollbar { width: 5px; }
.ch-win::-webkit-scrollbar-track, aside ul::-webkit-scrollbar-track { background: transparent; }
.ch-win::-webkit-scrollbar-thumb, aside ul::-webkit-scrollbar-thumb { background: var(--bd); border-radius: 10px; }
</style>

<main class="page" style="max-width:1400px;margin:0 auto;">
  <section class="fi" aria-labelledby="pg-h">
    <header style="margin-bottom:6px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
      <div>
      </div>
      <a href="<?php echo e(route('dashboard')); ?>" style="display:flex;align-items:center;gap:6px;padding:6px 14px;border-radius:30px;background:transparent;border:1.5px solid var(--bd);color:var(--mu);text-decoration:none;font-size:.85rem;font-weight:700;transition:all .2s;" onmouseover="this.style.borderColor='var(--in)';this.style.color='var(--in)';this.style.background='var(--is)'" onmouseout="this.style.borderColor='var(--bd)';this.style.color='var(--mu)';this.style.background='transparent'" title="Voltar ao dashboard">
        <i class="fa-solid fa-arrow-left" style="font-size:.78rem;"></i> Dashboard
      </a>
    </header>
    <h1 id="pg-h" style="font-size:1.55rem;font-weight:800;margin-bottom:4px;">Chat Médico–Paciente</h1>
  </section>

  
  <div x-data="{ search: '', results: [], loading: false, open: false }" style="margin-top:16px;margin-bottom:16px;" @click.away="open = false">
    <div style="position:relative;">
      <input 
        x-model="search" 
        @input="
          if(search.length < 2) { results = []; loading = false; return; }
          open = true;
          loading = true;
          fetch('<?php echo e(route('messages.search')); ?>?q=' + encodeURIComponent(search))
            .then(r => r.json())
            .then(data => { results = data; loading = false; })
            .catch(() => { results = []; loading = false; });
        "
        type="text" 
        placeholder="Pesquisar usuário para iniciar conversa..." 
        style="width:100%;padding:12px 18px;border:1.5px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);font-family:'Dosis',sans-serif;font-size:.95rem;outline:none;transition:border-color .2s;"
        @focus="open = true"
      >
      <i class="fa-solid fa-magnifying-glass" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);color:var(--mu);font-size:.85rem;pointer-events:none;"></i>
      
      
      <div x-show="open && (results.length > 0 || loading)" @click.stop style="position:absolute;top:100%;left:0;right:0;background:var(--sf);border:1.5px solid var(--bd);border-top:none;border-radius:0 0 14px 14px;max-height:300px;overflow-y:auto;z-index:10;margin-top:-2px;box-shadow:var(--sh2);">
        <template x-if="loading">
          <div style="padding:16px;text-align:center;color:var(--mu);font-size:.9rem;">
            <i class="fa-solid fa-spinner fa-spin"></i> Pesquisando...
          </div>
        </template>

        <template x-for="user in results" :key="user.id">
          <form method="POST" :action="'<?php echo e(route('messages.start', '__USER_ID__')); ?>'.replace('__USER_ID__', user.id)" style="padding:12px 16px;border-bottom:1px solid var(--bd);display:flex;align-items:center;gap:12px;transition:background .2s;width:100%;" @mouseenter="this.style.background='var(--bg)'" @mouseleave="this.style.background='transparent'">
            <?php echo csrf_field(); ?>
            <img :src="user.profile_photo_url" :alt="user.name" style="width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            <div style="flex:1;min-width:0;">
              <p style="margin:0;font-weight:600;font-size:.92rem;color:var(--tx);">
                <span x-text="user.name"></span>
              </p>
              <p style="margin:4px 0 0 0;font-size:.78rem;color:var(--mu);text-transform:uppercase;">
                <span x-text="user.role === 'doctor' ? 'Médico' : 'Paciente'"></span>
              </p>
            </div>
            <button type="submit" class="btn b-gh bxs" style="margin-left:8px;padding:8px 12px;font-size:.85rem;flex-shrink:0;">
              <i class="fa-solid fa-message" aria-hidden="true"></i> Chat
            </button>
          </form>
        </template>

        <template x-if="!loading && results.length === 0 && search.length >= 2">
          <div style="padding:16px;text-align:center;color:var(--mu);font-size:.9rem;">
            Nenhum usuário encontrado
          </div>
        </template>
      </div>
    </div>
  </div>

  <div class="grid fi fi1" style="margin-top:8px;grid-template-columns:320px 1fr;gap:20px; flex: 1; overflow: hidden; padding-bottom: 24px;">
    
    <aside aria-labelledby="conv-h" style="height:90vh;overflow-y:auto;display:flex;flex-direction:column;">
      <div class="card" style="padding:0;overflow:hidden;display:flex;flex-direction:column;flex:1;">
        <h2 class="ct" id="conv-h" style="padding:14px 16px;margin-bottom:0;border-bottom:1px solid var(--bd);">
          <i class="fa-solid fa-comments" aria-hidden="true"></i> Conversas
        </h2>
        <ul style="list-style:none; overflow-y:auto; flex:1;">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $conversations ?? $conversation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $participant = $conv->sender_id == auth()->id() ? $conv->receiver : $conv->sender;
              $lastMessage = $conv->messages->first();
              $unreadCount = $conv->messages->where('read', false)->where('user_id', '!=', auth()->id())->count();
            ?>
            <li class="group relative">
              <a href="<?php echo e(route('messages.show', $conv->id)); ?>" class="conv-item <?php echo e(request()->route('conversation') && request()->route('conversation')->id === $conv->id ? 'active' : ''); ?>" aria-current="<?php echo e(request()->route('conversation') && request()->route('conversation')->id === $conv->id ? 'page' : 'false'); ?>">
                <span class="av <?php echo e($participant?->role === 'doctor' ? 'av-gr' : 'av-bl'); ?> av-sm" aria-hidden="true">
                  <?php echo e(substr($participant?->name ?? '', 0, 2)); ?>

                </span>
                <div style="flex:1;min-width:0;">
                  <p style="font-weight:700;font-size:.92rem;margin:0;"><?php echo e($participant?->name); ?></p>
                  <p style="font-size:.78rem;color:var(--mu);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:4px 0 0 0;">
                    <?php echo e($lastMessage?->body ?? 'Sem mensagens ainda'); ?>

                  </p>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:5px;flex-shrink:0;">
                  <span style="font-family:'Space Mono',monospace;font-size:.62rem;color:var(--mu);">
                    <?php echo e($conv->updated_at->format('H:i')); ?>

                  </span>
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                    <span style="width:7px;height:7px;border-radius:50%;background:var(--rd);display:inline-block;" aria-label="Não lida"></span>
                  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
              </a>

              
              <div class="absolute right-2 top-1/2 -translate-y-1/2" x-data="{ menuOpen: false }">
                <button @click.prevent.stop="menuOpen = !menuOpen" class="p-1 text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                </button>
                <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 shadow-xl rounded-xl py-2 z-[100]">
                    <button class="w-full text-left px-4 py-2 text-[10px] text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                        Marcar lida
                    </button>
                    <button class="w-full text-left px-4 py-2 text-[10px] text-red-500 hover:bg-red-50 flex items-center gap-2 font-bold">
                        Excluir chat
                    </button>
                </div>
              </div>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li style="padding:14px;text-align:center;">
              <p style="color:var(--mu);font-size:.86rem;">Nenhuma conversa iniciada</p>
            </li>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
      </div>
    </aside>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($messages)): ?>
      <?php
        $participant = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
      ?>
      <article 
        class="card" 
        style="display:flex;flex-direction:column;height:90vh;overflow-y:auto;" 
        aria-labelledby="chat-h"
        x-data="{ 
            body: '', 
            messages: <?php echo e($messages->toJson()); ?>,
            authId: <?php echo e(auth()->id()); ?>,
            sending: false,
            scrollToBottom() {
                this.$nextTick(() => {
                    const win = document.getElementById('chat-win');
                    win.scrollTop = win.scrollHeight;
                });
            },
            async sendMessage() {
                if(!this.body.trim() || this.sending) return;
                this.sending = true;
                const res = await fetch('<?php echo e(route('messages.store')); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
                    body: JSON.stringify({ conversation_id: <?php echo e($conversation->id); ?>, body: this.body })
                });
                const newMsg = await res.json();
                this.messages.push(newMsg);
                this.body = '';
                this.sending = false;
                this.scrollToBottom();
            }
        }"
        x-init="scrollToBottom()"
      >
        <header style="padding-bottom:14px;border-bottom:1px solid var(--bd);margin-bottom:16px;display:flex;align-items:center;gap:10px;">
          <span class="av <?php echo e($participant?->role === 'doctor' ? 'av-gr' : 'av-bl'); ?> av-sm" aria-hidden="true">
            <?php echo e(substr($participant?->name ?? '', 0, 2)); ?>

          </span>
          <div>
            <h2 id="chat-h" style="font-weight:700;font-size:1.05rem;margin:0;"><?php echo e($participant?->name); ?></h2>
            <p style="font-size:.75rem;color:var(--gr);display:flex;align-items:center;gap:5px;margin:4px 0 0 0;">
              <span style="width:7px;height:7px;border-radius:50%;background:var(--gr);display:inline-block;" aria-hidden="true"></span> online
            </p>
          </div>
        </header>

        
        <div class="ch-win" aria-live="polite" aria-label="Mensagens" id="chat-win">
          <template x-for="msg in messages" :key="msg.id">
            <div class="group relative">
                <p class="ch-mt" :class="msg.user_id == authId ? 'r' : ''" style="font-size:.65rem;" x-text="msg.user.name + ' · ' + new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></p>
                <div class="flex flex-col" :class="msg.user_id == authId ? 'items-end' : 'items-start'">
                  <div class="flex items-center gap-2" :class="msg.user_id == authId ? 'flex-row-reverse' : 'flex-row'">
                    <p class="bub" :class="msg.user_id == authId ? 'bub-sn' : 'bub-rc'" x-text="msg.body"></p>
                    
                    
                    <div x-data="{ msgMenu: false }" class="opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="msgMenu = !msgMenu" class="p-1 text-slate-300 hover:text-slate-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                        </button>
                        <div x-show="msgMenu" @click.away="msgMenu = false" x-cloak class="absolute z-10 bg-white border border-gray-100 shadow-xl rounded-lg py-1 w-24 text-[10px]">
                            <button class="w-full text-left px-3 py-1.5 hover:bg-slate-50 text-slate-600">Editar</button>
                            <button class="w-full text-left px-3 py-1.5 hover:bg-red-50 text-red-500">Deletar</button>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
          </template>
          <template x-if="messages.length === 0">
            <p style="text-align:center;color:var(--mu);padding:20px;font-size:.95rem;">Nenhuma mensagem ainda</p>
          </template>
        </div>

        
        <form @submit.prevent="sendMessage" class="ch-bar" style="gap:10px;margin-top:16px;padding-top:16px;border-top:1px solid var(--bd);display:flex;">
          <label for="msg-input" class="sr-only">Escrever mensagem</label>
          <input 
            x-model="body" 
            type="text" 
            id="msg-input" 
            placeholder="Escreva uma mensagem..." 
            required 
            autocomplete="off" 
            :disabled="sending"
            style="flex:1;background:var(--sf2);border:1.5px solid var(--bd);border-radius:30px;padding:12px 18px;color:var(--tx);font-family:'Dosis',sans-serif;font-size:.97rem;outline:none;"
          >
          <button type="submit" class="btn b-pr bsm" :disabled="sending || !body.trim()" style="padding:10px 18px;font-size:.94rem;">
            <i class="fa-solid fa-paper-plane" :class="sending ? 'fa-spinner fa-spin' : ''" aria-hidden="true"></i> Enviar
          </button>
        </form>
      </article>
    <?php else: ?>
      <article class="card" style="display:flex;align-items:center;justify-content:center;min-height:480px;">
        <p style="text-align:center;color:var(--mu);font-size:.98rem;">
          <i class="fa-solid fa-message" style="font-size:2.5rem;margin-bottom:10px;display:block;"></i>
          Selecione uma conversa para começar
        </p>
      </article>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</main>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($messages)): ?>
<script>
  // Auto-scroll para o fim do chat ao carregar
  const chatWin = document.getElementById('chat-win');
  if (chatWin) {
    chatWin.scrollTop = chatWin.scrollHeight;
  }
</script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<script>
  // Theme initialization
  (function(){
    const t = localStorage.getItem('cl-theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\gcris\OneDrive\Documentos\Escola\Projetos Pessoais\Clinicaly\resources\views/pages/conversations.blade.php ENDPATH**/ ?>