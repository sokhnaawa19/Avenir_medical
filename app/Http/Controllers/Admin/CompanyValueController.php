<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompanyValueRequest;
use App\Models\CompanyValue;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CompanyValueController extends Controller
{
    public function index(): View
    {
        return view('admin.values.index', [
            'values' => CompanyValue::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.values.form', [
            'value' => new CompanyValue(['is_active' => true, 'icon' => '💎']),
        ]);
    }

    public function store(CompanyValueRequest $request): RedirectResponse
    {
        CompanyValue::query()->create($request->payload());

        return redirect()->to(admin_back('admin.values.index'))
            ->with('success', 'La valeur a été ajoutée.');
    }

    public function edit(CompanyValue $value): View
    {
        return view('admin.values.form', ['value' => $value]);
    }

    public function update(CompanyValueRequest $request, CompanyValue $value): RedirectResponse
    {
        $value->update($request->payload());

        return redirect()->to(admin_back('admin.values.index'))
            ->with('success', 'La valeur a été mise à jour.');
    }

    public function destroy(CompanyValue $value): RedirectResponse
    {
        $value->delete();

        return redirect()->to(admin_back('admin.values.index'))
            ->with('success', 'La valeur a été supprimée.');
    }
}
