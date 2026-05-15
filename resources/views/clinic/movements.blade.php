<x-app-layout>
<div style="width:100%;max-width:none;margin:0;">
    <div class="view-head" style="display:flex;align-items:flex-end;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;">
        <div>
            <span class="tag tag-purple">Estoque</span>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--tx);margin-top:10px;">Movimentações</h1>
            <p class="muted">Entradas, saídas, ajustes e itens usados em prescrições.</p>
        </div>
        <a href="{{ route('clinic.index', ['tab' => 'stock']) }}" class="btn btn-ghost"><i class="fa-solid fa-arrow-left"></i>Voltar ao estoque</a>
    </div>

    <div class="card" style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Item</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Saldo</th>
                    <th>Responsável</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $movement->item?->name ?? 'Item removido' }}</td>
                        <td><span class="tag {{ $movement->type === 'entrada' ? 'success' : ($movement->type === 'saida' ? 'danger' : 'warn') }}">{{ $movement->type }}</span></td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->balance_after }}</td>
                        <td>{{ $movement->responsible?->name ?? 'Sistema' }}</td>
                        <td>{{ $movement->notes ?? 'Sem observação' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted" style="text-align:center;padding:28px;">Nenhuma movimentação registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top:16px;">{{ $movements->links() }}</div>
    </div>
</div>
</x-app-layout>
