<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'orders' => Order::query()->count(),
                'pending' => Order::query()->where('status', Order::STATUS_PENDING)->count(),
                'revenue' => (int) Order::query()
                    ->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
                    ->sum('total'),
                'products' => Product::query()->count(),
                'posts' => Post::query()->count(),
                'clients' => User::query()->where('is_admin', false)->count(),
                'messages' => ContactMessage::query()->where('is_read', false)->count(),
            ],
            'latestOrders' => Order::query()->latest()->take(6)->get(),
            'latestMessages' => ContactMessage::query()->latest()->take(5)->get(),
            'lowStock' => Product::query()
                ->whereNotNull('stock')
                ->where('stock', '<=', 3)
                ->orderBy('stock')
                ->take(5)
                ->get(),
        ]);
    }
}
