<x-app-layout>
<div style="width:100%;max-width:none;margin:0;">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;">
        <div>
            <span class="tag tag-purple">Atividades</span>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--tx);margin-top:10px;">{{ $employee->name }}</h1>
            <p class="muted">{{ $employee->specialty ?? 'Profissional da clínica' }} · pacientes atendidos no período.</p>
        </div>
        <a href="{{ route('clinic.index', ['tab' => 'employees']) }}" class="btn btn-ghost"><i class="fa-solid fa-arrow-left"></i>Voltar</a>
    </div>

    <nav style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;">
        @foreach(['day' => 'Hoje', 'week' => 'Semana', 'month' => 'Mês'] as $key => $label)
            <a href="{{ route('clinic.employees.activities', [$employee, 'period' => $key]) }}" class="btn {{ $period === $key ? 'btn-primary' : 'btn-ghost' }}">{{ $label }}</a>
        @endforeach
    </nav>

    <div class="card" style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Data</th>
                    <th>Diagnóstico</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diagnostics as $diagnostic)
                    <tr>
                        <td>{{ $diagnostic->paciente?->name ?? 'Paciente' }}</td>
                        <td>{{ $diagnostic->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $diagnostic->confirmed_disease_name }}</td>
                        <td><span class="tag {{ $diagnostic->status === 'validado' ? 'success' : 'warn' }}">{{ $diagnostic->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted" style="text-align:center;padding:28px;">Nenhum paciente atendido neste período.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
