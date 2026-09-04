<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryPhotoRequest;
use App\Models\GalleryPhoto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class GalleryPhotoController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.photos.index', [
            'photos' => GalleryPhoto::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.photos.form', [
            'photo' => new GalleryPhoto(['is_active' => true]),
        ]);
    }

    public function store(GalleryPhotoRequest $request): RedirectResponse
    {
        $photo = new GalleryPhoto($request->payload());
        $photo->image = $this->storeUpload($request->file('image'), 'galerie');
        $photo->save();

        return redirect()->to(admin_back('admin.photos.index'))
            ->with('success', 'La photo a été ajouté(e).');
    }

    public function edit(GalleryPhoto $photo): View
    {
        return view('admin.photos.form', ['photo' => $photo]);
    }

    public function update(GalleryPhotoRequest $request, GalleryPhoto $photo): RedirectResponse
    {
        $photo->fill($request->payload());
        $photo->image = $this->storeUpload($request->file('image'), 'galerie', $photo->image);
        $photo->save();

        return redirect()->to(admin_back('admin.photos.index'))
            ->with('success', 'La photo a été mis(e) à jour.');
    }

    public function destroy(GalleryPhoto $photo): RedirectResponse
    {
        $this->deleteMedia($photo->image);
        $photo->delete();

        return redirect()->to(admin_back('admin.photos.index'))
            ->with('success', 'La photo a été supprimé(e).');
    }
}
