<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'domain_id' => ['nullable', 'exists:domains,id'],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'name' => ['required', 'string', 'max:190'],
            'reference' => ['nullable', 'string', 'max:60'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:20000'],
            'price' => ['required', 'integer', 'min:0'],
            'units_per_box' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'box_label' => ['nullable', 'string', 'max:40'],
            'old_price' => ['nullable', 'integer', 'min:0', 'gt:price'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'emoji' => ['nullable', 'string', 'max:8'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v', 'max:102400'],
            'remove_video' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'catégorie',
            'domain_id' => "domaine d'intervention",
            'partner_id' => 'marque',
            'name' => 'nom du produit',
            'price' => 'prix unitaire',
            'units_per_box' => 'unités par carton',
            'old_price' => 'ancien prix',
            'image' => 'photo',
        ];
    }

    /**
     * Donnees pretes a etre enregistrees (les cases a cocher deviennent vrai/faux).
     *
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return array_merge(
            collect($this->validated())->except(['image', 'video_file', 'remove_video'])->all(),
            [
                'is_featured' => $this->boolean('is_featured'),
                'is_active' => $this->boolean('is_active'),
                'position' => (int) $this->input('position', 0),
            ]
        );
    }
}
