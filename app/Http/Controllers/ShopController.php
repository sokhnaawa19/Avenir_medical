<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(settings()->boolean('shop_enabled', true), 404);

        $validated = $request->validate([
            'categorie' => ['nullable', 'string', 'exists:categories,slug'],
            'q' => ['nullable', 'string', 'max:120'],
            'tri' => ['nullable', 'in:recent,prix-croissant,prix-decroissant,nom'],
        ]);

        $category = isset($validated['categorie'])
            ? Category::query()->where('slug', $validated['categorie'])->first()
            : null;

        $sort = $validated['tri'] ?? 'recent';

        $query = Product::query()
            ->active()
            ->with(['category', 'brand', 'translations'])
            ->when($category, fn ($builder) => $builder->where('category_id', $category->id))
            ->search($validated['q'] ?? null);

        match ($sort) {
            'prix-croissant' => $query->orderBy('price'),
            'prix-decroissant' => $query->orderByDesc('price'),
            'nom' => $query->orderBy('name'),
            default => $query->ordered(),
        };

        return view('shop.index', [
            'products' => $query
                ->paginate(settings()->integer('products_per_page', 12))
                ->withQueryString(),
            'categories' => Category::query()
                ->active()
                ->ordered()
                ->withCount(['products' => fn ($builder) => $builder->where('is_active', true)])
                ->get(),
            'currentCategory' => $category,
            'search' => $validated['q'] ?? null,
            'sort' => $sort,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('shop.show', [
            'product' => $product->load(['category', 'brand', 'domain']),
            'related' => Product::query()
                ->active()
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->getKey())
                ->ordered()
                ->take(4)
                ->get(),
        ]);
    }
}
