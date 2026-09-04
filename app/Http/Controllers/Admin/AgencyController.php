<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgencyRequest;
use App\Models\Agency;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AgencyController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.agencies.index', [
            'agencies' => Agency::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.agencies.form', [
            'agency' => new Agency(['is_active' => true]),
        ]);
    }

    public function store(AgencyRequest $request): RedirectResponse
    {
        $agency = new Agency($request->payload());
        $agency->image = $this->storeUpload($request->file('image'), 'agences');
        $agency->save();

        return redirect()->to(admin_back('admin.agencies.index'))
            ->with('success', 'L\'agence a été ajouté(e).');
    }

    public function edit(Agency $agency): View
    {
        return view('admin.agencies.form', ['agency' => $agency]);
    }

    public function update(AgencyRequest $request, Agency $agency): RedirectResponse
    {
        $agency->fill($request->payload());
        $agency->image = $this->storeUpload($request->file('image'), 'agences', $agency->image);
        $agency->save();

        return redirect()->to(admin_back('admin.agencies.index'))
            ->with('success', 'L\'agence a été mis(e) à jour.');
    }

    public function destroy(Agency $agency): RedirectResponse
    {
        $this->deleteMedia($agency->image);
        $agency->delete();

        return redirect()->to(admin_back('admin.agencies.index'))
            ->with('success', 'L\'agence a été supprimé(e).');
    }
}
