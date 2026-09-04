<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'website' => ['nullable', 'url', 'max:190'],
            'country' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:2000'],
            'position' => ['nullable', 'integer', 'min:0'],
            'domains' => ['nullable', 'array'],
            'domains.*' => ['integer', 'exists:domains,id'],
            'ranges' => ['nullable', 'array'],
            'ranges.*' => ['nullable', 'string', 'max:4000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_exclusive' => ['nullable', 'boolean'],
            'exclusivity_scope' => ['nullable', 'string', 'max:190'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nom du partenaire',
            'logo' => 'logo',
            'website' => 'site internet',
            'country' => 'pays',
            'domains' => "domaines d'intervention",
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return array_merge(
            collect($this->validated())->except(['logo', 'domains', 'ranges'])->all(),
            [
                'is_featured' => $this->boolean('is_featured'),
                'is_exclusive' => $this->boolean('is_exclusive'),
                'is_active' => $this->boolean('is_active'),
                'position' => (int) $this->input('position', 0),
            ]
        );
    }

    /**
     * Les domaines cochés, avec les gammes saisies en face.
     *
     * @return array<int, array{ranges: string, position: int}>
     */
    public function domainLinks(): array
    {
        $ranges = (array) $this->input('ranges', []);
        $links = [];

        foreach (array_values(array_unique((array) $this->input('domains', []))) as $position => $id) {
            $id = (int) $id;

            $links[$id] = [
                'ranges' => trim((string) ($ranges[$id] ?? '')),
                'position' => $position,
            ];
        }

        return $links;
    }
}
