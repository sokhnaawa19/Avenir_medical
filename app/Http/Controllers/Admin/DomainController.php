<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DomainRequest;
use App\Models\Domain;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DomainController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.domains.index', [
            'domains' => Domain::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.domains.form', [
            'domain' => new Domain(['is_active' => true]),
        ]);
    }

    public function store(DomainRequest $request): RedirectResponse
    {
        $domain = new Domain($request->payload());
        $domain->image = $this->storeUpload($request->file('image'), 'domaines');
        $domain->video_url = $this->storeVideo($request, null);
        $domain->save();

        return redirect()->to(admin_back('admin.domains.index'))
            ->with('success', 'Le domaine a été ajouté.');
    }

    public function edit(Domain $domain): View
    {
        return view('admin.domains.form', ['domain' => $domain]);
    }

    public function update(DomainRequest $request, Domain $domain): RedirectResponse
    {
        $domain->fill($request->payload());
        $domain->image = $this->storeUpload($request->file('image'), 'domaines', $domain->image);
        $domain->video_url = $this->storeVideo($request, $domain->video_url);
        $domain->save();

        return redirect()->to(admin_back('admin.domains.index'))
            ->with('success', 'Le domaine a été mis à jour.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        $this->deleteMedia($domain->image);
        $this->deleteMedia($domain->video_url);
        $domain->delete();

        return redirect()->to(admin_back('admin.domains.index'))
            ->with('success', 'Le domaine a été supprimé.');
    }
}
