<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    use HandlesMediaUploads;

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()
                ->withCount('products')
                ->ordered()
                ->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new Category(['is_active' => true]),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = new Category($request->payload());
        $category->image = $this->storeUpload($request->file('image'), 'categories');
        $category->save();

        return redirect()->to(admin_back('admin.categories.index'))
            ->with('success', 'La catégorie a été ajoutée.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->fill($request->payload());
        $category->image = $this->storeUpload($request->file('image'), 'categories', $category->image);
        $category->save();

        return redirect()->to(admin_back('admin.categories.index'))
            ->with('success', 'La catégorie a été mise à jour.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->deleteMedia($category->image);
        $category->delete();

        return redirect()->to(admin_back('admin.categories.index'))
            ->with('success', 'La catégorie a été supprimée. Les produits liés restent disponibles.');
    }
}
