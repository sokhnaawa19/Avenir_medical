<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TrainingRequest;
use App\Models\Training;
use App\Models\ContentPhoto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TrainingController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.trainings.index', [
            'trainings' => Training::query()->withCount('photos')->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.trainings.form', [
            'training' => new Training(['is_active' => true]),
        ]);
    }

    public function store(TrainingRequest $request): RedirectResponse
    {
        $training = new Training($request->payload());
        $training->image = $this->storeUpload($request->file('image'), 'formations');
        $training->save();

        $this->savePhotos($request, $training);

        return redirect()->to(admin_back('admin.trainings.index'))
            ->with('success', 'La formation a été ajouté(e).');
    }

    public function edit(Training $training): View
    {
        return view('admin.trainings.form', ['training' => $training]);
    }

    public function update(TrainingRequest $request, Training $training): RedirectResponse
    {
        $training->fill($request->payload());
        $training->image = $this->storeUpload($request->file('image'), 'formations', $training->image);
        $training->save();

        $this->savePhotos($request, $training);

        return redirect()->to(admin_back('admin.trainings.index'))
            ->with('success', 'La formation a été mis(e) à jour.');
    }

    /** Supprime une photo d'une formation. */
    public function destroyPhoto(Training $training, ContentPhoto $photo): RedirectResponse
    {
        abort_unless($photo->photoable_id === $training->id && $photo->photoable_type === Training::class, 404);

        $this->deleteMedia($photo->image);
        $photo->delete();

        return back()->with('success', 'La photo a été supprimée.');
    }

    /**
     * Enregistre les photos envoyées avec le formulaire.
     */
    private function savePhotos(TrainingRequest $request, Training $training): void
    {
        $position = (int) $training->photos()->max('position');

        foreach ($request->file('photos', []) as $fichier) {
            $training->photos()->create([
                'image' => $this->storeUpload($fichier, 'formations'),
                'position' => ++$position,
            ]);
        }
    }

    public function destroy(Training $training): RedirectResponse
    {
        // Les photos liées sont supprimées avec la formation.
        foreach ($training->photos as $photo) {
            $this->deleteMedia($photo->image);
        }

        $this->deleteMedia($training->image);
        $training->delete();

        return redirect()->to(admin_back('admin.trainings.index'))
            ->with('success', 'La formation a été supprimé(e).');
    }
}
