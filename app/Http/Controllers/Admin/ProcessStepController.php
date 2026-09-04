<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcessStepRequest;
use App\Models\ProcessStep;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProcessStepController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.process.index', [
            'steps' => ProcessStep::query()->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.process.form', [
            'step' => new ProcessStep(['is_active' => true]),
        ]);
    }

    public function store(ProcessStepRequest $request): RedirectResponse
    {
        $step = new ProcessStep($request->payload());
        $step->image = $this->storeUpload($request->file('image'), 'parcours');
        $step->save();

        return redirect()->to(admin_back('admin.process.index'))
            ->with('success', 'L’étape a été ajoutée au parcours.');
    }

    public function edit(ProcessStep $step): View
    {
        return view('admin.process.form', ['step' => $step]);
    }

    public function update(ProcessStepRequest $request, ProcessStep $step): RedirectResponse
    {
        $step->fill($request->payload());
        $step->image = $this->storeUpload($request->file('image'), 'parcours', $step->image);
        $step->save();

        return redirect()->to(admin_back('admin.process.index'))
            ->with('success', 'L’étape a été mise à jour.');
    }

    public function destroy(ProcessStep $step): RedirectResponse
    {
        $this->deleteMedia($step->image);
        $step->delete();

        return redirect()->to(admin_back('admin.process.index'))
            ->with('success', 'L’étape a été supprimée.');
    }
}
