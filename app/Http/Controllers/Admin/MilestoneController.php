<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MilestoneRequest;
use App\Models\Milestone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MilestoneController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.milestones.index', [
            'milestones' => Milestone::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.milestones.form', [
            'milestone' => new Milestone(['is_active' => true]),
        ]);
    }

    public function store(MilestoneRequest $request): RedirectResponse
    {
        $milestone = new Milestone($request->payload());
        $milestone->image = $this->storeUpload($request->file('image'), 'historique');
        $milestone->save();

        return redirect()->to(admin_back('admin.milestones.index'))
            ->with('success', 'L’étape a été ajoutée à l’historique.');
    }

    public function edit(Milestone $milestone): View
    {
        return view('admin.milestones.form', ['milestone' => $milestone]);
    }

    public function update(MilestoneRequest $request, Milestone $milestone): RedirectResponse
    {
        $milestone->fill($request->payload());
        $milestone->image = $this->storeUpload($request->file('image'), 'historique', $milestone->image);
        $milestone->save();

        return redirect()->to(admin_back('admin.milestones.index'))
            ->with('success', 'L’étape a été mise à jour.');
    }

    public function destroy(Milestone $milestone): RedirectResponse
    {
        $this->deleteMedia($milestone->image);
        $milestone->delete();

        return redirect()->to(admin_back('admin.milestones.index'))
            ->with('success', 'L’étape a été supprimée.');
    }
}
