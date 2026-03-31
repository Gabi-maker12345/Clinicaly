<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clinicaly.css') }}">
    <style>
        body { background-color: #f8fafc; color: #0f172a; }
        aside { background-color: #ffffff; border-right: 1px solid #e2e8f0; }
        aside::-webkit-scrollbar { width: 4px; }
        aside::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #475569;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background-color: #dbeafe;
            color: #4840A3;
        }

        .card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            transition: transform 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .card:hover { transform: translateY(-4px); }
        .text-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
        .text-value { font-weight: 700; color: #334155; font-size: 14px; }
        .btn-logout { color: #ef4444; font-weight: 500; transition: 0.2s; border: none; background: none; cursor: pointer; }
        .btn-logout:hover { background-color: #fef2f2; }
        
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .sidebar-transition { transition: transform 0.3s ease-in-out; }

        .input-profile {
            width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            outline: none;
        }
    </style>

    <div class="flex h-screen overflow-hidden">
        
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 sidebar-transition flex flex-col">
            
            <div class="p-6 border-b border-gray-100 text-center">
                <div class="mb-3 text-indigo-600">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img class="h-16 w-16 rounded-full object-cover mx-auto border-2 border-indigo-50 shadow-sm" 
                            src="{{ Auth::user()->profile_photo_url }}" 
                            alt="{{ Auth::user()->name }}" />
                    @else
                        <i class="fa-solid fa-circle-user text-4xl"></i>
                    @endif
                </div>
                <h3 class="font-bold text-gray-800 text-lg">{{ Auth::user()->name }}</h3>
                <span class="block text-xs text-indigo-600 font-medium mb-4">Paciente</span>
                
                <div class="grid grid-cols-3 gap-2 pt-4 border-t border-gray-50">
                    <div>
                        <span class="text-label block">Idade</span>
                        <span class="text-value">28</span>
                    </div>
                    <div class="border-x border-gray-100">
                        <span class="text-label block">Peso</span>
                        <span class="text-value">75kg</span>
                    </div>
                    <div>
                        <span class="text-label block">Altura</span>
                        <span class="text-value">1.80</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <span class="block px-4 text-label mb-2">Menu Principal</span>
                <ul class="nav-list space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link">
                            <i class="fa-solid fa-chart-line"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link active">
                            <i class="fa-solid fa-circle-user"></i> <span>Meu Perfil</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-notes-medical"></i> <span>Exames</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout flex items-center gap-3 w-full px-4 py-2 rounded-lg text-left">
                        <i class="fa-solid fa-right-from-bracket"></i> Sair
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
                <button id="menuBtn" class="p-2 text-gray-600 lg:hidden" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="relative w-full max-w-md hidden md:block">
                    <input type="text" class="w-full h-10 pl-10 pr-4 bg-gray-50 border-none rounded-xl outline-none" placeholder="Buscar no perfil...">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </span>
                </div>
                <div class="flex items-center">
                    <i class="fa-solid fa-bell text-gray-400"></i>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <div class="lg:col-span-2 space-y-6">
                            <div class="card">
                                <h2 class="font-bold text-slate-700 mb-6">Informações Pessoais</h2>
                                
                                <form method="POST" action="{{ route('user-profile-information.update') }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-label">Nome Completo</label>
                                            <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full mt-1 p-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-100">
                                        </div>
                                        
                                        <div>
                                            <label class="text-label">E-mail</label>
                                            <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full mt-1 p-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-100">
                                        </div>

                                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                            Atualizar Perfil
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="card">
                                <h2 class="font-bold text-slate-700 mb-6">Segurança</h2>
                                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                    @livewire('profile.update-password-form')
                                @endif
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="card">
                                <h3 class="font-bold mb-6 text-slate-700 text-center">Foto de Perfil</h3>
                                <div class="flex flex-col items-center">
                                    <img class="h-32 w-32 rounded-full object-cover border-4 border-indigo-50 mb-4 shadow-md" 
                                        src="{{ Auth::user()->profile_photo_url }}" 
                                        alt="{{ Auth::user()->name }}" />
                                    
                                    <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        
                                        {{-- Campos obrigatórios para validação do Jetstream --}}
                                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                                        <label class="cursor-pointer bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all inline-flex items-center">
                                            <i class="fa-solid fa-camera mr-2"></i> Alterar Foto
                                            <input type="file" name="photo" class="hidden" onchange="this.form.submit()">
                                        </label>
                                    </form>

                                    @if ($errors->updateProfileInformation->has('photo'))
                                        <span class="text-red-500 text-[10px] mt-2 font-bold uppercase">{{ $errors->updateProfileInformation->first('photo') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="card border-none" style="background-color: #4f46e5; color: white;">
                                <i class="fa-solid fa-circle-check mb-3 text-xl text-green-300"></i>
                                <h4 class="font-bold mb-2">Conta Verificada</h4>
                                <span class="block text-xs opacity-90 leading-relaxed">
                                    Seus dados de saúde estão sincronizados com a base da ANVISA e CID-11.
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>