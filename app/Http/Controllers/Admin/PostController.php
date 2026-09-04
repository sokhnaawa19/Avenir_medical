<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use HandlesMediaUploads;

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        return view('admin.posts.index', [
            'posts' => Post::query()
                ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('title', 'like', '%'.$term.'%'))
                ->recent()
                ->paginate(20)
                ->withQueryString(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', [
            'post' => new Post(['is_published' => true, 'category' => 'Actualité']),
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $post = new Post($request->payload());
        $post->user_id = $request->user()->id;
        $post->image = $this->storeUpload($request->file('image'), 'articles');
        $post->video_url = $this->storeVideo($request, null);
        $post->save();

        return redirect()->to(admin_back('admin.posts.index'))
            ->with('success', 'L’article « '.$post->title.' » a été enregistré.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', ['post' => $post]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->fill($request->payload());
        $post->image = $this->storeUpload($request->file('image'), 'articles', $post->image);
        $post->video_url = $this->storeVideo($request, $post->video_url);
        $post->save();

        return redirect()->to(admin_back('admin.posts.index'))
            ->with('success', 'L’article a été mis à jour.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->deleteMedia($post->image);
        $this->deleteMedia($post->video_url);
        $post->delete();

        return redirect()->to(admin_back('admin.posts.index'))
            ->with('success', 'L’article a été supprimé.');
    }
}
