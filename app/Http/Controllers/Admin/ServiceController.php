<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\ContentPhoto;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::query()->withCount('photos')->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', [
            'service' => new Service(['is_active' => true, 'icon' => '🛠️']),
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $service = new Service($request->payload());
        $service->image = $this->storeUpload($request->file('image'), 'services');
        $service->save();

        $this->savePhotos($request, $service);

        return redirect()->to(admin_back('admin.services.index'))
            ->with('success', 'Le service a été ajouté.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->fill($request->payload());
        $service->image = $this->storeUpload($request->file('image'), 'services', $service->image);
        $service->save();

        $this->savePhotos($request, $service);

        return redirect()->to(admin_back('admin.services.index'))
            ->with('success', 'Le service a été mis à jour.');
    }

    /** Supprime une photo du service. */
    public function destroyPhoto(Service $service, ContentPhoto $photo): RedirectResponse
    {
        abort_unless(
            $photo->photoable_id === $service->id && $photo->photoable_type === Service::class,
            404
        );

        $this->deleteMedia($photo->image);
        $photo->delete();

        return back()->with('success', 'La photo a été supprimée.');
    }

    /** Enregistre les photos envoyées avec le formulaire. */
    private function savePhotos(ServiceRequest $request, Service $service): void
    {
        $position = (int) $service->photos()->max('position');

        foreach ($request->file('photos', []) as $fichier) {
            $service->photos()->create([
                'image' => $this->storeUpload($fichier, 'projets'),
                'position' => ++$position,
            ]);
        }
    }

    public function destroy(Service $service): RedirectResponse
    {
        foreach ($service->photos as $photo) {
            $this->deleteMedia($photo->image);
        }

        $this->deleteMedia($service->image);
        $service->delete();

        return redirect()->to(admin_back('admin.services.index'))
            ->with('success', 'Le service a été supprimé.');
    }
}
