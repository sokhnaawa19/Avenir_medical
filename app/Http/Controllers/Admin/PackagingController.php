<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Saisie groupée du conditionnement.
 *
 * Renseigner « unités par carton » produit par produit prendrait
 * un temps fou. Cet écran affiche tous les produits dans un seul
 * tableau : on remplit les cases, on enregistre une fois.
 */
class PackagingController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'etat' => ['nullable', 'in:tous,a-remplir,remplis'],
        ]);

        $etat = $validated['etat'] ?? 'tous';

        $products = Product::query()
            ->with('category')
            ->when($validated['category'] ?? null, fn ($query, $id) => $query->where('category_id', $id))
            ->when($etat === 'a-remplir', fn ($query) => $query->whereNull('units_per_box'))
            ->when($etat === 'remplis', fn ($query) => $query->whereNotNull('units_per_box'))
            // Les consommables d'abord : ce sont eux qui se vendent au carton.
            ->orderByRaw("CASE WHEN units_per_box IS NULL THEN 0 ELSE 1 END")
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        return view('admin.packaging.index', [
            'products' => $products,
            'categories' => Category::query()->ordered()->get(),
            'currentCategory' => $validated['category'] ?? null,
            'etat' => $etat,
            'restants' => Product::query()->whereNull('units_per_box')->count(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $donnees = $request->validate([
            'units' => ['array'],
            'units.*' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'labels' => ['array'],
            'labels.*' => ['nullable', 'string', 'max:40'],
        ]);

        $modifies = 0;

        foreach ($donnees['units'] ?? [] as $id => $unites) {
            $produit = Product::query()->find($id);

            if (! $produit) {
                continue;
            }

            $libelle = $donnees['labels'][$id] ?? null;
            $unites = filled($unites) ? (int) $unites : null;

            if ($produit->units_per_box === $unites && $produit->box_label === $libelle) {
                continue;
            }

            $produit->forceFill([
                'units_per_box' => $unites,
                'box_label' => filled($libelle) ? $libelle : null,
            ])->save();

            $modifies++;
        }

        return back()->with('success', $modifies > 0
            ? $modifies.' produit(s) mis à jour. Les prix carton sont recalculés.'
            : 'Aucune modification.');
    }
}
