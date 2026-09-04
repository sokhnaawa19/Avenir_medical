<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SubsidiaryRequest;
use App\Models\Subsidiary;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SubsidiaryController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.subsidiaries.index', [
            'subsidiaries' => Subsidiary::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.subsidiaries.form', [
            'subsidiary' => new Subsidiary(['is_active' => true]),
        ]);
    }

    public function store(SubsidiaryRequest $request): RedirectResponse
    {
        $subsidiary = new Subsidiary($request->payload());
        $subsidiary->logo = $this->storeUpload($request->file('logo'), 'groupe');
        $subsidiary->image = $this->storeUpload($request->file('image'), 'groupe');
        $subsidiary->save();

        return redirect()->to(admin_back('admin.subsidiaries.index'))
            ->with('success', 'L\'entreprise a été ajouté(e).');
    }

    public function edit(Subsidiary $subsidiary): View
    {
        return view('admin.subsidiaries.form', ['subsidiary' => $subsidiary]);
    }

    public function update(SubsidiaryRequest $request, Subsidiary $subsidiary): RedirectResponse
    {
        $subsidiary->fill($request->payload());
        $subsidiary->logo = $this->storeUpload($request->file('logo'), 'groupe', $subsidiary->logo);
        $subsidiary->image = $this->storeUpload($request->file('image'), 'groupe', $subsidiary->image);
        $subsidiary->save();

        return redirect()->to(admin_back('admin.subsidiaries.index'))
            ->with('success', 'L\'entreprise a été mis(e) à jour.');
    }

    public function destroy(Subsidiary $subsidiary): RedirectResponse
    {
        $this->deleteMedia($subsidiary->logo);
        $this->deleteMedia($subsidiary->image);
        $subsidiary->delete();

        return redirect()->to(admin_back('admin.subsidiaries.index'))
            ->with('success', 'L\'entreprise a été supprimé(e).');
    }
}
