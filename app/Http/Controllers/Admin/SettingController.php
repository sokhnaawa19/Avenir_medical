<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Services\SettingsRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Reglages du site : tous les champs proviennent de config/settings.php.
 */
class SettingController extends Controller
{
    use HandlesMediaUploads;

    public function __construct(private readonly SettingsRepository $settings)
    {
    }

    public function index(): RedirectResponse
    {
        $first = array_key_first($this->settings->schema());

        return redirect()->route('admin.settings.edit', $first);
    }

    public function edit(string $group): View
    {
        $definition = $this->settings->schema()[$group] ?? abort(404);

        return view('admin.settings.edit', [
            'group' => $group,
            'definition' => $definition,
            'groups' => $this->settings->schema(),
        ]);
    }

    public function update(UpdateSettingsRequest $request, string $group): RedirectResponse
    {
        $fields = config('settings.'.$group.'.fields', []);
        $values = (array) $request->input('settings', []);

        foreach ($fields as $key => $field) {
            $type = $field['type'] ?? 'text';

            if (in_array($type, ['image', 'video'], true)) {
                $this->handleFileField($request, $key);

                continue;
            }

            if ($type === 'boolean') {
                $this->settings->set($key, $request->boolean('settings.'.$key) ? '1' : '0');

                continue;
            }

            $this->settings->set($key, $values[$key] ?? null);
        }

        return redirect()->route('admin.settings.edit', $group)
            ->with('success', 'Les réglages ont été enregistrés.');
    }

    /**
     * Envoi ou suppression d'un fichier de reglage (logo, photo, video...).
     */
    private function handleFileField(UpdateSettingsRequest $request, string $key): void
    {
        $current = (string) $this->settings->get($key);

        if ($request->boolean('remove.'.$key)) {
            $this->deleteMedia($current);
            $this->settings->set($key, null);

            return;
        }

        $file = $request->file('files.'.$key);

        if ($file) {
            $this->settings->set($key, $this->storeUpload($file, 'site', $current));
        }
    }
}
