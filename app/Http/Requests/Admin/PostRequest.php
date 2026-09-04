<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:190'],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:60000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'video_url' => ['nullable', 'string', 'max:255'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v', 'max:102400'],
            'remove_video' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'titre',
            'category' => 'catégorie',
            'content' => 'contenu',
            'image' => 'photo',
            'published_at' => 'date de publication',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return array_merge(
            collect($this->validated())->except(['image', 'video_file', 'remove_video'])->all(),
            [
                'is_published' => $this->boolean('is_published'),
                'published_at' => $this->filled('published_at') ? $this->date('published_at') : now(),
            ]
        );
    }
}
