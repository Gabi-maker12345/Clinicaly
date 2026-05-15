<?php

namespace App\Http\Controllers;

use App\Models\ClinicStockItem;
use App\Models\ClinicStockMovement;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClinicStockController extends Controller
{
    public function store(Request $request)
    {
        $clinic = $this->clinic();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'unit' => ['required', 'string', 'max:30'],
            'quantity' => ['required', 'integer', 'min:0'],
            'minimum_quantity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $item = ClinicStockItem::create([
            ...$data,
            'clinic_id' => $clinic->id,
            'minimum_quantity' => $data['minimum_quantity'] ?? 0,
        ]);

        if ($item->quantity > 0) {
            ClinicStockMovement::create([
                'clinic_id' => $clinic->id,
                'clinic_stock_item_id' => $item->id,
                'user_id' => $clinic->id,
                'type' => 'entrada',
                'quantity' => $item->quantity,
                'balance_after' => $item->quantity,
                'notes' => 'Estoque inicial',
            ]);
        }

        return redirect()->route('clinic.index', ['tab' => 'stock'])->with('success', 'Item adicionado ao estoque.');
    }

    public function update(Request $request, ClinicStockItem $item)
    {
        $clinic = $this->clinic();
        $this->ensureClinicItem($item, $clinic);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'unit' => ['required', 'string', 'max:30'],
            'minimum_quantity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $item->update([
            ...$data,
            'minimum_quantity' => $data['minimum_quantity'] ?? 0,
        ]);

        return redirect()->route('clinic.index', ['tab' => 'stock'])->with('success', 'Item atualizado.');
    }

    public function destroy(ClinicStockItem $item)
    {
        $clinic = $this->clinic();
        $this->ensureClinicItem($item, $clinic);
        $item->delete();

        return redirect()->route('clinic.index', ['tab' => 'stock'])->with('info', 'Item removido do estoque.');
    }

    public function movement(Request $request)
    {
        $clinic = $this->clinic();

        $data = $request->validate([
            'clinic_stock_item_id' => ['required', 'exists:clinic_stock_items,id'],
            'type' => ['required', 'in:entrada,saida,ajuste'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $item = ClinicStockItem::where('clinic_id', $clinic->id)->findOrFail($data['clinic_stock_item_id']);

        DB::transaction(function () use ($item, $clinic, $data) {
            $quantity = (int) $data['quantity'];
            $newBalance = match ($data['type']) {
                'entrada' => $item->quantity + $quantity,
                'ajuste' => $quantity,
                default => max(0, $item->quantity - $quantity),
            };

            $item->update(['quantity' => $newBalance]);

            ClinicStockMovement::create([
                'clinic_id' => $clinic->id,
                'clinic_stock_item_id' => $item->id,
                'user_id' => Auth::id(),
                'type' => $data['type'],
                'quantity' => $quantity,
                'balance_after' => $newBalance,
                'notes' => $data['notes'] ?? null,
            ]);
        });

        return redirect()->route('clinic.index', ['tab' => 'stock'])->with('success', 'Movimentação registrada.');
    }

    public function movements()
    {
        $clinic = $this->clinic();

        $movements = ClinicStockMovement::with(['item', 'responsible', 'prescription.diagnostico.paciente'])
            ->where('clinic_id', $clinic->id)
            ->latest()
            ->paginate(30);

        return view('clinic.movements', compact('clinic', 'movements'));
    }

    public function checkout(Request $request, Prescription $prescription)
    {
        $doctor = Auth::user();

        if (! $doctor?->isDoctor() || ! $doctor->clinic_id) {
            abort(403);
        }

        $prescription->loadMissing('diagnostico');

        if ((int) $prescription->diagnostico?->id_medico !== (int) $doctor->id) {
            abort(403);
        }

        $data = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*.id' => ['nullable', 'exists:clinic_stock_items,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $doctor, $prescription) {
            foreach ($data['items'] ?? [] as $row) {
                $quantity = (int) ($row['quantity'] ?? 0);

                if ($quantity <= 0 || empty($row['id'])) {
                    continue;
                }

                $item = ClinicStockItem::where('clinic_id', $doctor->clinic_id)->findOrFail($row['id']);
                $newBalance = max(0, $item->quantity - $quantity);
                $item->update(['quantity' => $newBalance]);

                ClinicStockMovement::create([
                    'clinic_id' => $doctor->clinic_id,
                    'clinic_stock_item_id' => $item->id,
                    'user_id' => $doctor->id,
                    'prescription_id' => $prescription->id,
                    'type' => 'saida',
                    'quantity' => $quantity,
                    'balance_after' => $newBalance,
                    'notes' => 'Uso informado no checkout da prescrição.',
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Checkout de estoque registrado.');
    }

    private function clinic(): User
    {
        $clinic = Auth::user();

        if (! $clinic?->isClinic()) {
            abort(403);
        }

        return $clinic;
    }

    private function ensureClinicItem(ClinicStockItem $item, User $clinic): void
    {
        if ((int) $item->clinic_id !== (int) $clinic->id) {
            abort(403);
        }
    }
}
