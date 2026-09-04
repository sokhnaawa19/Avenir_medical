<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EstablishmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'type' => ['nullable', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:80'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'description' => ['nullable', 'string', 'max:5000'],
            'equipments' => ['nullable', 'string', 'max:5000'],
            'year' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_flagship' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => "nom de l'établissement", 'city' => 'ville', 'year' => 'année'];
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
                'is_flagship' => $this->boolean('is_flagship'),
                'is_featured' => $this->boolean('is_featured'),
                'is_active' => $this->boolean('is_active'),
                'position' => (int) $this->input('position', 0),
            ]
        );
    }
}
