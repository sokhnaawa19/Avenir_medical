<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
            'is_admin' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nom', 'password' => 'mot de passe'];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $data['is_admin'] = $this->boolean('is_admin');

        return $data;
    }
}
