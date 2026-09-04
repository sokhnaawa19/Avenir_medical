<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgencyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'region' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'status' => ['required', Rule::in(array_keys(\App\Models\Agency::statuses()))],
            'opening_year' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'position' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nom', 'region' => 'région', 'status' => 'état'];
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
