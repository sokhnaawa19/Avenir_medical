<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'role' => ['nullable', 'in:admins,clients'],
        ]);

        return view('admin.users.index', [
            'users' => User::query()
                ->withCount('orders')
                ->when($filters['q'] ?? null, function ($query, $term): void {
                    $query->where(function ($builder) use ($term): void {
                        $builder->where('name', 'like', '%'.$term.'%')
                            ->orWhere('email', 'like', '%'.$term.'%')
                            ->orWhere('phone', 'like', '%'.$term.'%');
                    });
                })
                ->when(($filters['role'] ?? null) === 'admins', fn ($query) => $query->where('is_admin', true))
                ->when(($filters['role'] ?? null) === 'clients', fn ($query) => $query->where('is_admin', false))
                ->latest()
                ->paginate(20)
                ->withQueryString(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::query()->create($request->payload());

        return redirect()->to(admin_back('admin.users.index'))
            ->with('success', 'Le compte a été créé.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', ['user' => $user]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        // Un administrateur ne peut pas retirer ses propres droits par erreur.
        $payload = $request->payload();

        if ($user->is($request->user())) {
            $payload['is_admin'] = true;
        }

        $user->update($payload);

        return redirect()->to(admin_back('admin.users.index'))
            ->with('success', 'Le compte a été mis à jour.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->to(admin_back('admin.users.index'))
            ->with('success', 'Le compte a été supprimé.');
    }
}
