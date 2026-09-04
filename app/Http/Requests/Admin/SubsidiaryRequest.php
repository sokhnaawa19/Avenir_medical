<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubsidiaryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'tagline' => ['nullable', 'string', 'max:190'],
            'activity' => ['nullable', 'string', 'max:190'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'website' => ['nullable', 'url', 'max:190'],
            'color' => ['nullable', 'string', 'max:20'],
            'founded_year' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:5000'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => "nom de l'entreprise", 'activity' => 'activité', 'website' => 'site internet'];
    }

    /**
     * Donnees pretes a enregistrer (les cases a cocher deviennent vrai/faux).
     *
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return array_merge(
            collect($this->validated())->except(['logo', 'image'])->all(),
            [
                'is_active' => $this->boolean('is_active'),
                'position' => (int) $this->input('position', 0),
            ]
        );
    }
}
