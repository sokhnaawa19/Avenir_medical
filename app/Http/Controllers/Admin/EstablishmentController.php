<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EstablishmentRequest;
use App\Models\Establishment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EstablishmentController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.establishments.index', [
            'establishments' => Establishment::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.establishments.form', [
            'establishment' => new Establishment(['is_active' => true]),
        ]);
    }

    public function store(EstablishmentRequest $request): RedirectResponse
    {
        $establishment = new Establishment($request->payload());
        $establishment->logo = $this->storeUpload($request->file('logo'), 'references');
        $establishment->image = $this->storeUpload($request->file('image'), 'references');
        $establishment->save();

        return redirect()->to(admin_back('admin.establishments.index'))
            ->with('success', 'L\'établissement a été ajouté(e).');
    }

    public function edit(Establishment $establishment): View
    {
        return view('admin.establishments.form', ['establishment' => $establishment]);
    }

    public function update(EstablishmentRequest $request, Establishment $establishment): RedirectResponse
    {
        $establishment->fill($request->payload());
        $establishment->logo = $this->storeUpload($request->file('logo'), 'references', $establishment->logo);
        $establishment->image = $this->storeUpload($request->file('image'), 'references', $establishment->image);
        $establishment->save();

        return redirect()->to(admin_back('admin.establishments.index'))
            ->with('success', 'L\'établissement a été mis(e) à jour.');
    }

    public function destroy(Establishment $establishment): RedirectResponse
    {
        $this->deleteMedia($establishment->logo);
        $this->deleteMedia($establishment->image);
        $establishment->delete();

        return redirect()->to(admin_back('admin.establishments.index'))
            ->with('success', 'L\'établissement a été supprimé(e).');
    }
}
