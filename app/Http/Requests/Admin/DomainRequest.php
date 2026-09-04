<?php

namespace App\Http\Requests\Admin;

use App\Support\LineList;
use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'subtitle' => ['nullable', 'string', 'max:190'],
            'icon' => ['nullable', 'string', 'max:16'],
            'intro' => ['nullable', 'string', 'max:400'],
            'description' => ['nullable', 'string', 'max:5000'],

            // Champs « une ligne = un élément », convertis en listes plus bas.
            'equipments' => ['nullable', 'string', 'max:5000'],

            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v', 'max:102400'],
            'remove_video' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
            'in_gallery' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'titre',
            'subtitle' => 'sous-titre',
            'intro' => 'accroche',
            'equipments' => 'équipements',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $base = collect($this->validated())
            ->except(['image', 'video_file', 'remove_video', 'equipments'])
            ->all();

        return array_merge($base, [
            'equipments' => LineList::toPairs($this->input('equipments')),
            'in_gallery' => $this->boolean('in_gallery'),
            'is_active' => $this->boolean('is_active'),
            'position' => (int) $this->input('position', 0),
        ]);
    }
}
