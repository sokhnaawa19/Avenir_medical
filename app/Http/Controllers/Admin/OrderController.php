<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', Rule::in(array_keys(Order::statuses()))],
        ]);

        return view('admin.orders.index', [
            'orders' => Order::query()
                ->withCount('items')
                ->when($filters['q'] ?? null, function ($query, $term): void {
                    $query->where(function ($builder) use ($term): void {
                        $builder->where('reference', 'like', '%'.$term.'%')
                            ->orWhere('customer_name', 'like', '%'.$term.'%')
                            ->orWhere('phone', 'like', '%'.$term.'%');
                    });
                })
                ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'filters' => $filters,
            'statuses' => Order::statuses(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items.product', 'user'),
            'statuses' => Order::statuses(),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::statuses()))],
        ]);

        $order->update($validated);

        return back()->with('success', 'La commande '.$order->reference.' est maintenant : '.$order->statusLabel().'.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->to(admin_back('admin.orders.index'))
            ->with('success', 'La commande a été supprimée.');
    }
}
