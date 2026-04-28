<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'estado' => ['nullable', 'in:pendiente,procesando,enviado,entregado,cancelado'],
        ]);

        $orders = Pedido::with('usuario', 'factura')
            ->when($filters['estado'] ?? null, fn ($query, string $estado) => $query->where('estado', $estado))
            ->latest('fecha')
            ->paginate(12)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'filters' => $filters,
            'statuses' => $this->statuses(),
        ]);
    }

    public function show(Pedido $pedido): View
    {
        return view('admin.orders.show', [
            'order' => $pedido->load('usuario', 'factura', 'lineas.producto'),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', 'in:pendiente,procesando,enviado,entregado,cancelado'],
        ]);

        $pedido->update($data);

        return back()->with('success', 'Estado del pedido actualizado.');
    }

    private function statuses(): array
    {
        return [
            'pendiente' => 'Pendiente',
            'procesando' => 'Procesando',
            'enviado' => 'Enviado',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
        ];
    }
}
