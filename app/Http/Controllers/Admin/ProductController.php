<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesMediaUploads;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Partner;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HandlesMediaUploads;

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'exists:categories,id'],
            'state' => ['nullable', 'in:actifs,inactifs'],
        ]);

        $products = Product::query()
            ->with('category')
            ->search($filters['q'] ?? null)
            ->when($filters['category'] ?? null, fn ($query, $id) => $query->where('category_id', $id))
            ->when(($filters['state'] ?? null) === 'actifs', fn ($query) => $query->where('is_active', true))
            ->when(($filters['state'] ?? null) === 'inactifs', fn ($query) => $query->where('is_active', false))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::query()->ordered()->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['is_active' => true, 'emoji' => '📦']),
            'categories' => Category::query()->ordered()->get(),
            'domains' => Domain::query()->ordered()->get(),
            'brands' => Partner::query()->ordered()->get(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = new Product($request->payload());
        $product->image = $this->storeUpload($request->file('image'), 'produits');
        $product->video_url = $this->storeVideo($request, null);
        $product->save();

        return redirect()->to(admin_back('admin.products.index'))
            ->with('success', 'Le produit « '.$product->name.' » a été ajouté.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->ordered()->get(),
            'domains' => Domain::query()->ordered()->get(),
            'brands' => Partner::query()->ordered()->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->fill($request->payload());
        $product->image = $this->storeUpload($request->file('image'), 'produits', $product->image);
        $product->video_url = $this->storeVideo($request, $product->video_url);
        $product->save();

        return redirect()->to(admin_back('admin.products.index'))
            ->with('success', 'Le produit « '.$product->name.' » a été mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteMedia($product->image);
        $this->deleteMedia($product->video_url);
        $product->delete();

        return redirect()->to(admin_back('admin.products.index'))
            ->with('success', 'Le produit a été supprimé.');
    }
}
