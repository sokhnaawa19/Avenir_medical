<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateProfileRequest;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        return view('account.index', [
            'orders' => $request->user()->orders()->with('items')->paginate(10),
        ]);
    }

    public function profile(): View
    {
        return view('account.profile');
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $request->user()->update($data);

        return redirect()->route('account.profile')
            ->with('success', 'Vos informations ont bien été enregistrées.');
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('account.order', [
            'order' => $order->load('items'),
        ]);
    }
}
