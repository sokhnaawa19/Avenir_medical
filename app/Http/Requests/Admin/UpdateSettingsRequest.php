<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Les regles sont construites a partir du fichier config/settings.php :
     * chaque champ declare y definit son type et ses contraintes.
     */
    public function rules(): array
    {
        $group = (string) $this->route('group');
        $fields = config('settings.'.$group.'.fields', []);
        $rules = [];

        foreach ($fields as $key => $field) {
            $rules['settings.'.$key] = $this->rulesForField($field);

            if (($field['type'] ?? 'text') === 'image') {
                $rules['files.'.$key] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,ico', 'max:4096'];
                $rules['remove.'.$key] = ['nullable', 'boolean'];
            }

            if (($field['type'] ?? 'text') === 'video') {
                $rules['files.'.$key] = ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v', 'max:51200'];
                $rules['remove.'.$key] = ['nullable', 'boolean'];
            }
        }

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $field
     * @return array<int, string>
     */
    private function rulesForField(array $field): array
    {
        if (isset($field['rules'])) {
            return is_array($field['rules']) ? $field['rules'] : explode('|', (string) $field['rules']);
        }

        return match ($field['type'] ?? 'text') {
            'email' => ['nullable', 'email', 'max:190'],
            'url' => ['nullable', 'url', 'max:190'],
            'number' => ['nullable', 'numeric'],
            'boolean' => ['nullable', 'boolean'],
            'color' => ['nullable', 'string', 'max:20'],
            'textarea', 'richtext' => ['nullable', 'string', 'max:20000'],
            'image', 'video' => ['nullable', 'string', 'max:255'],
            default => ['nullable', 'string', 'max:500'],
        };
    }
}
