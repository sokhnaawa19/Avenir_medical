<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'categorie' => ['nullable', 'string', 'max:80'],
        ]);

        $category = $validated['categorie'] ?? null;

        return view('blog.index', [
            'posts' => Post::query()
                ->published()
                ->when($category, fn ($query) => $query->where('category', $category))
                ->recent()
                ->paginate(9)
                ->withQueryString(),
            'categories' => Post::query()->published()->whereNotNull('category')
                ->distinct()->orderBy('category')->pluck('category'),
            'currentCategory' => $category,
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->is_published, 404);

        $post->increment('views');

        return view('blog.show', [
            'post' => $post->load('author'),
            'related' => Post::query()
                ->published()
                ->whereKeyNot($post->getKey())
                ->when($post->category, fn ($query) => $query->where('category', $post->category))
                ->recent()
                ->take(2)
                ->get(),
        ]);
    }
}
