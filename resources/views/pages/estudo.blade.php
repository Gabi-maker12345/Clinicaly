<x-guest-layout>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .py-0 > div { animation: fadeInUp 0.4s ease-out; }
    </style>
    
    <div class="py-0" style="background: var(--bg); color: var(--tx); min-height: 100vh;">
        @livewire('chat-ia')
    </div>
</x-guest-layout>