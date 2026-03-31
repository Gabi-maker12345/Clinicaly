<div class="text-slate-700 h-screen flex overflow-hidden w-full" wire:poll.10s>
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">
    <style>
        .soft-card { background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 24px; }
        .custom-scroll { scroll-behavior: smooth; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        input:focus { outline: none !important; box-shadow: none !important; }
        [x-cloak] { display: none !important; }
    </style>

    {{-- Sidebar Esquerda (Aumentada levemente para w-72) --}}
    <aside class="w-72 border-r border-gray-100 flex flex-col p-6 bg-white shrink-0">
        <div class="flex items-center gap-2 mb-10">
                <img src="{{ asset('Proosta-logo4.png') }}" alt="Logo" width="180px" class="w-32 md:w-44">
            </div>

        <div class="flex flex-col items-center mb-6">
            <div class="w-20 h-20 rounded-full bg-slate-100 border-2 border-indigo-50 overflow-hidden flex items-center justify-center mb-3 shadow-sm">
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="object-cover h-full w-full">
                @else
                    <span class="text-xl font-bold text-indigo-300">{{ substr(Auth::user()->name, 0, 1) }}</span>
                @endif
            </div>
            <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto custom-scroll">
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-slate-50 transition-colors text-sm font-medium text-slate-500">Dashboard</a>
            <a href="#" class="block px-4 py-3 rounded-xl bg-indigo-50 text-indigo-600 text-sm font-bold border-l-4 border-indigo-500">Chat IA</a>
            
            <button wire:click="createNewChat" class="w-full mt-4 flex items-center justify-center gap-2 p-2 text-xs font-bold text-indigo-600 border border-dashed border-indigo-200 rounded-lg hover:bg-indigo-50 transition-all">
                + Novo Chat
            </button>

            <div class="mt-4 pt-4 border-t border-gray-50">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-2">Histórico</p>
                @foreach(\App\Models\ChatSession::where('user_id', auth()->id())->latest()->get() as $session)
                    <div class="group relative flex items-center mb-1" x-data="{ menuOpen: false, editModal: false, deleteModal: false }">
                        
                        {{-- Botão da Sessão --}}
                        <button wire:click="switchSession({{ $session->id }})" 
                            class="flex-1 text-left px-3 py-2 rounded-lg text-xs transition-all truncate pr-10 {{ $currentSessionId == $session->id ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50' }}">
                            {{ $session->title }}
                        </button>
                        
                        {{-- Ícone 3 Pontos --}}
                        <div class="absolute right-1">
                            <button @click="menuOpen = !menuOpen" class="p-1 text-slate-400 hover:text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </button>
                            
                            {{-- Dropdown de Opções --}}
                            <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak class="absolute right-0 mt-1 w-32 bg-white border border-gray-100 shadow-xl rounded-xl py-2 z-[100]">
                                <button @click="editModal = true; menuOpen = false" class="w-full text-left px-4 py-2 text-[10px] text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                    Editar nome
                                </button>
                                <button @click="deleteModal = true; menuOpen = false" class="w-full text-left px-4 py-2 text-[10px] text-red-500 hover:bg-red-50 flex items-center gap-2 font-bold">
                                    Excluir chat
                                </button>
                            </div>
                        </div>

                        {{-- MODAL EDITAR --}}
                        <div x-show="editModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
                            <div @click.away="editModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-xs p-6">
                                <h3 class="text-sm font-bold text-slate-800 mb-4">Renomear Conversa</h3>
                                <input type="text" x-ref="titleInput" value="{{ $session->title }}" 
                                    class="w-full border border-gray-200 rounded-xl px-4 py-2 text-xs mb-4 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <div class="flex gap-2">
                                    <button @click="editModal = false" class="flex-1 px-4 py-2 text-xs font-medium text-slate-500 hover:bg-slate-50 rounded-lg">Cancelar</button>
                                    <button @click="$wire.renameSession({{ $session->id }}, $refs.titleInput.value); editModal = false" class="flex-1 px-4 py-2 text-xs font-bold text-white bg-indigo-500 hover:bg-indigo-600 rounded-lg">Salvar</button>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL EXCLUIR --}}
                        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
                            <div @click.away="deleteModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-xs p-6 text-center">
                                <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800 mb-2">Eliminar Conversa?</h3>
                                <p class="text-[11px] text-slate-500 mb-6">Esta ação não pode ser desfeita. Todo o histórico será perdido.</p>
                                <div class="flex gap-2">
                                    <button @click="deleteModal = false" class="flex-1 px-4 py-2 text-xs font-medium text-slate-500 hover:bg-slate-50 rounded-lg">Manter</button>
                                    <button wire:click="deleteSession({{ $session->id }})" @click="deleteModal = false" class="flex-1 px-4 py-2 text-xs font-bold text-white bg-red-500 hover:bg-red-600 rounded-lg">Excluir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </nav>

        <a href="{{ route('dashboard') }}" class="mt-auto px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold hover:bg-gray-50 transition-colors text-center w-full">
            Voltar ao Início
        </a>
    </aside>

    {{-- Área Central --}}
    <main class="flex-1 flex flex-col relative p-8">
        <div id="chat-box" class="flex-1 flex flex-col overflow-y-auto custom-scroll mb-6 space-y-6 pr-2">
            
            @if(count($messages) > 0)
                @foreach($messages as $msg)
                    <div class="group flex {{ ($msg['role'] ?? '') == 'user' ? 'justify-end' : 'justify-start' }} relative mb-4">
                        
                        <div x-data="{ open: false }" class="absolute top-1/2 -translate-y-1/2 {{ ($msg['role'] ?? '') == 'user' ? '-left-8' : '-right-8' }} opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="open = !open" class="p-1 text-slate-300 hover:text-slate-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute z-10 mt-1 w-32 bg-white border border-gray-100 shadow-xl rounded-lg py-1 {{ ($msg['role'] ?? '') == 'user' ? 'left-0' : 'right-0' }}">
                                <button wire:click="deleteMessage({{ $msg['id'] }})" class="w-full text-left px-4 py-2 text-[11px] text-red-500 hover:bg-red-50 flex items-center gap-2">
                                    Deletar
                                </button>
                            </div>
                        </div>

                        <div class="max-w-[85%] p-4 rounded-3xl {{ ($msg['role'] ?? '') == 'user' ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white border border-gray-200 text-slate-700 rounded-tl-none shadow-sm' }}">
                            <div class="prose prose-sm max-w-none {{ ($msg['role'] ?? '') == 'user' ? 'prose-invert text-white' : 'text-slate-700' }} leading-relaxed">
                                {!! \Illuminate\Support\Str::markdown($msg['message'] ?? '') !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flex-1 flex flex-col justify-center items-center">
                    <div class="text-center">
                        <h1 class="text-3xl font-medium text-slate-400">Olá, <span class="text-slate-800 font-bold">{{ explode(' ', Auth::user()->name)[0] }}!</span></h1>
                        <p class="text-slate-400 mt-2 text-sm">Como posso ajudar com seus estudos médicos hoje?</p>
                    </div>
                </div>
            @endif

            <div wire:loading wire:target="sendMessage" class="flex justify-start">
                <div class="bg-white border border-gray-200 p-4 rounded-3xl rounded-tl-none shadow-sm flex gap-1">
                    <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                </div>
            </div>
        </div>

        <div class="max-w-3xl w-full mx-auto">
            <div class="bg-white border border-gray-200 rounded-full px-6 py-3 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow focus-within:border-indigo-300">
                <input type="text" wire:model="userInput" wire:keydown.enter="sendMessage" placeholder="Pergunte sobre CID-11, fármacos ou casos clínicos..." class="flex-1 bg-transparent border-none focus:ring-0 text-slate-600 text-sm py-2">
                <button type="button" wire:click="sendMessage" wire:loading.attr="disabled" class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center hover:bg-indigo-600 transition-all active:scale-95 disabled:opacity-50">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                </button>
            </div>
            <p class="text-[10px] text-center text-slate-400 mt-3">Clinicaly AI pode cometer erros. Verifique informações médicas importantes.</p>
        </div>
    </main>

    <script>
        document.addEventListener('livewire:init', () => {
        const container = document.getElementById('chat-box');
        const scrollToBottom = () => { container.scrollTop = container.scrollHeight; };
        
        scrollToBottom();

        Livewire.on('scroll-to-bottom', () => { 
            setTimeout(scrollToBottom, 50); 
        });

        const urlParams = new URLSearchParams(window.location.search);
        const prompt = urlParams.get('prompt');

        if (prompt) {
            setTimeout(() => {
                @this.sendMessage();
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 500);
        }
    });
    </script>
</div>