<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'icon' => ['nullable', 'string', 'max:8'],
            'description' => ['nullable', 'string', 'max:5000'],
            'photos' => ['nullable', 'array', 'max:12'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['title' => 'titre', 'icon' => 'icône'];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return array_merge(
            collect($this->validated())->except(['image', 'photos'])->all(),
            [
                'is_active' => $this->boolean('is_active'),
                'position' => (int) $this->input('position', 0),
            ]
        );
    }
}
