<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartnerRequest;
use App\Models\Domain;
use App\Models\Partner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PartnerController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.partners.index', [
            'partners' => Partner::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.partners.form', [
            'partner' => new Partner(['is_active' => true, 'is_featured' => true]),
            'domains' => $this->domains(),
        ]);
    }

    public function store(PartnerRequest $request): RedirectResponse
    {
        $partner = new Partner($request->payload());
        $partner->logo = $this->storeUpload($request->file('logo'), 'partenaires');
        $partner->save();
        $partner->domains()->sync($request->domainLinks());

        return redirect()->to(admin_back('admin.partners.index'))
            ->with('success', 'Le partenaire « '.$partner->name.' » a été ajouté.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.form', [
            'partner' => $partner->load('domains'),
            'domains' => $this->domains(),
        ]);
    }

    public function update(PartnerRequest $request, Partner $partner): RedirectResponse
    {
        $partner->fill($request->payload());
        $partner->logo = $this->storeUpload($request->file('logo'), 'partenaires', $partner->logo);
        $partner->save();
        $partner->domains()->sync($request->domainLinks());

        return redirect()->to(admin_back('admin.partners.index'))
            ->with('success', 'Le partenaire a été mis à jour.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $this->deleteMedia($partner->logo);
        $partner->delete();

        return redirect()->to(admin_back('admin.partners.index'))
            ->with('success', 'Le partenaire a été supprimé.');
    }

    /**
     * La liste des domaines proposés dans le formulaire.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Domain>
     */
    private function domains()
    {
        return Domain::query()->ordered()->get();
    }
}
