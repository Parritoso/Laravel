<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Services\MongoLog\AdminAuditService;
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

        $oldValues = ['estado' => $pedido->estado];

        $pedido->update($data);

        AdminAuditService::track('update', 'Pedido', $pedido->id, $oldValues, ['estado' => $pedido->estado]);

        return back()->with('success', __('messages.admin_order_updated'));
    }

    private function statuses(): array
    {
        return [
            'pendiente' => __('statuses.pendiente'),
            'procesando' => __('statuses.procesando'),
            'enviado' => __('statuses.enviado'),
            'entregado' => __('statuses.entregado'),
            'cancelado' => __('statuses.cancelado'),
        ];
    }
}
