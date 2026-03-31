<!DOCTYPE html>
<html lang="pt-ao">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinicaly | Resultado da Análise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Dosis', sans-serif; }
        .progress-ring { transition: stroke-dashoffset 0.35s; transform: rotate(-90deg); transform-origin: 50% 50%; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 p-4 md:p-8">

    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('discovery.index') }}" class="flex items-center gap-2 text-indigo-600 font-bold hover:gap-3 transition-all">
                <i class="fa-solid fa-arrow-left"></i> Nova Análise
            </a>
            <div class="text-right">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">ID da Sessão: #{{ rand(1000, 9999) }}</span>
                <p class="text-sm font-bold text-slate-600">Paciente: {{ $age }} anos | {{ $gender == 'm' ? 'Masculino' : 'Feminino' }}</p>
            </div>
        </div>

        @if(count($results) > 0)
            @php $topMatch = $results->first(); @endphp
            <div class="bg-white rounded-[45px] p-8 md:p-12 border border-slate-200 shadow-xl shadow-indigo-100/50 mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8">
                    <span class="bg-indigo-600 text-white px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest">Altamente Provável</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="relative w-48 h-48">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <circle class="text-slate-100 stroke-current" stroke-width="8" fill="transparent" r="40" cx="50" cy="50"/>
                                <circle class="text-indigo-600 stroke-current progress-ring" stroke-width="8" stroke-linecap="round" fill="transparent" r="40" cx="50" cy="50"
                                        style="stroke-dasharray: 251.2; stroke-dashoffset: {{ 251.2 - (251.2 * $topMatch->probability) / 100 }}"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-black text-slate-800">{{ $topMatch->probability }}%</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Match Clínico</span>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <h1 class="text-4xl font-black text-slate-900 mb-2 leading-tight">{{ $topMatch->name }}</h1>
                        <p class="text-slate-500 font-medium mb-6 text-lg leading-relaxed">{{ $topMatch->description }}</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100">
                                <span class="text-[10px] font-black text-indigo-500 uppercase block mb-1">Protocolo Sugerido</span>
                                <p class="text-sm font-bold text-slate-700">{{ $topMatch->target_treatment }}</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100">
                                <span class="text-[10px] font-black text-indigo-500 uppercase block mb-1">Gravidade</span>
                                <div class="flex gap-1 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="h-1.5 w-6 rounded-full {{ $i <= $topMatch->severity ? 'bg-indigo-600' : 'bg-slate-200' }}"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-6 px-4">Diagnósticos Diferenciais</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($results->skip(1) as $disease)
                <div class="bg-white p-6 rounded-[35px] border border-slate-200 hover:border-indigo-400 transition-all flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                            <i class="fa-solid fa-notes-medical text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $disease->name }}</h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $disease->match_count }} sintomas detectados</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black text-indigo-600">{{ $disease->probability }}%</span>
                        <p class="text-[8px] font-bold text-slate-300 uppercase">Match</p>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <div class="bg-white rounded-[45px] p-20 text-center border-2 border-dashed border-slate-200">
                <i class="fa-solid fa-microscope text-6xl text-slate-200 mb-6"></i>
                <h2 class="text-2xl font-black text-slate-400 uppercase">Inconclusivo</h2>
                <p class="text-slate-400 max-w-sm mx-auto mt-2">Os sintomas e dados biométricos informados não apresentam correlação segura com o banco de patologias.</p>
            </div>
        @endif

        <div class="mt-12 p-8 bg-slate-900 rounded-[35px] text-white flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-white/10 rounded-2xl"><i class="fa-solid fa-shield-halved text-indigo-400"></i></div>
                <div>
                    <h4 class="font-bold uppercase text-xs tracking-widest">Suporte à Decisão Clínica</h4>
                    <p class="text-[10px] text-slate-400">Clinicaly AI fornece probabilidades estatísticas, não diagnósticos definitivos.</p>
                </div>
            </div>
            <button onclick="window.print()" class="px-8 py-3 bg-white text-slate-900 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-indigo-500 hover:text-white transition-all">
                Exportar Relatório PDF
            </button>
        </div>
    </div>

</body>
</html>