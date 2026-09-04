<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GalleryPhotoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:190'],
            'album' => ['nullable', 'string', 'max:120'],
            'image' => [$this->route('photo') ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'caption' => ['nullable', 'string', 'max:1000'],
            'taken_at' => ['nullable', 'string', 'max:40'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['image' => 'photo', 'album' => 'album'];
    }

    /**
     * Donnees pretes a enregistrer (les cases a cocher deviennent vrai/faux).
     *
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return array_merge(
            collect($this->validated())->except('image')->all(),
            [
                'is_active' => $this->boolean('is_active'),
                'position' => (int) $this->input('position', 0),
            ]
        );
    }
}
