@extends('layouts.app')

@section('content')
<section style="margin-bottom:22px;display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div>
        <span class="tag info"><i class="fa-solid fa-file-prescription"></i> Prescrições</span>
        <h1 style="font-size:1.8rem;font-weight:800;margin-top:12px;color:var(--tx);">Prescrições Emitidas</h1>
        <p style="color:var(--mu);font-weight:600;">Receitas vinculadas aos diagnósticos assinados por você.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-ghost"><i class="fa-solid fa-arrow-left"></i>Voltar</a>
</section>

<article class="card" style="overflow-x:auto;" x-data="{ search: '' }">
    <input type="search" x-model="search" placeholder="Pesquisar por paciente ou status..." style="width:100%;margin-bottom:16px;padding:12px 16px;border:1px solid var(--bd);border-radius:30px;background:var(--sf2);color:var(--tx);">
    <table class="data-table">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prescriptions as $prescription)
                @php
                    $status = $prescription->monitorings->contains('status', 'active') ? 'Ativa' : ($prescription->monitorings->contains('status', 'completed') ? 'Concluída' : 'Pendente');
                @endphp
                <tr data-search="{{ strtolower(($prescription->diagnostico?->paciente?->name ?? 'Paciente') . ' ' . $status) }}" x-show="$el.dataset.search.includes(search.toLowerCase())">
                    <td>{{ $prescription->diagnostico?->paciente?->name ?? 'Paciente' }}</td>
                    <td>{{ optional($prescription->created_at)->format('d/m/Y') }}</td>
                    <td><span class="tag {{ $status === 'Ativa' ? 'success' : '' }}">{{ $status }}</span></td>
                    <td>
                        <a class="btn btn-ghost btn-sm" href="{{ route('profile.show') }}#prescricoes">
                            <i class="fa-solid fa-eye"></i>Ver detalhes
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:var(--mu);padding:34px;">Nenhuma prescrição emitida até agora.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</article>
@endsection
