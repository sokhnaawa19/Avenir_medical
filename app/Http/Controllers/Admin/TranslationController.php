<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Category;
use App\Models\CompanyValue;
use App\Models\Domain;
use App\Models\Establishment;
use App\Models\GalleryPhoto;
use App\Models\Milestone;
use App\Models\Partner;
use App\Models\Post;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Subsidiary;
use App\Models\Training;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Relecture et correction des traductions.
 *
 * La traduction automatique fait le gros du travail ; cet écran
 * permet de corriger les textes les plus visibles.
 */
class TranslationController extends Controller
{
    /** Les contenus traduisibles, par rubrique. */
    private const RUBRIQUES = [
        'reglages' => ['label' => 'Textes du site', 'model' => Setting::class],
        'domaines' => ['label' => 'Domaines', 'model' => Domain::class],
        'produits' => ['label' => 'Produits', 'model' => Product::class],
        'categories' => ['label' => 'Catégories', 'model' => Category::class],
        'services' => ['label' => 'Services', 'model' => Service::class],
        'parcours' => ['label' => 'Parcours client', 'model' => ProcessStep::class],
        'articles' => ['label' => 'Articles', 'model' => Post::class],
        'valeurs' => ['label' => 'Valeurs', 'model' => CompanyValue::class],
        'partenaires' => ['label' => 'Partenaires', 'model' => Partner::class],
        'historique' => ['label' => 'Historique', 'model' => Milestone::class],
        'references' => ['label' => 'Références', 'model' => Establishment::class],
        'formations' => ['label' => 'Formations', 'model' => Training::class],
        'agences' => ['label' => 'Agences', 'model' => Agency::class],
        'groupe' => ['label' => 'Le groupe', 'model' => Subsidiary::class],
        'galerie' => ['label' => 'Galerie', 'model' => GalleryPhoto::class],
    ];

    public function index(Request $request, string $group = 'reglages'): View
    {
        abort_unless(array_key_exists($group, self::RUBRIQUES), 404);

        $locale = $request->string('locale', 'en')->toString();
        abort_unless(in_array($locale, config('app.locales', []), true), 404);

        $classe = self::RUBRIQUES[$group]['model'];

        $elements = $classe::query()
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->when($classe === Setting::class, fn ($query) => $query->whereNotIn('group', ['seo', 'contact', 'reseaux', 'identite', 'videos']))
            ->paginate(25)
            ->withQueryString();

        return view('admin.translations.index', [
            'rubriques' => self::RUBRIQUES,
            'group' => $group,
            'locale' => $locale,
            'elements' => $elements,
            'estReglage' => $classe === Setting::class,
        ]);
    }

    public function update(Request $request, string $group): RedirectResponse
    {
        abort_unless(array_key_exists($group, self::RUBRIQUES), 404);

        $donnees = $request->validate([
            'locale' => ['required', 'string', 'max:5'],
            'traductions' => ['array'],
            'traductions.*' => ['array'],
            'traductions.*.*' => ['nullable', 'string', 'max:20000'],
        ]);

        $classe = self::RUBRIQUES[$group]['model'];
        $locale = $donnees['locale'];
        $modifies = 0;

        foreach ($donnees['traductions'] ?? [] as $id => $champs) {
            $element = $classe::query()->find($id);

            if (! $element) {
                continue;
            }

            foreach ($champs as $champ => $valeur) {
                if (! in_array($champ, $element->translatableFields(), true)) {
                    continue;
                }

                if ($element->translationFor($champ, $locale) === $valeur) {
                    continue;
                }

                // Corrigé à la main : la traduction ne sera plus écrasée.
                $element->setTranslation($champ, $locale, $valeur, automatic: false);
                $modifies++;
            }
        }

        if ($classe === Setting::class) {
            settings()->flush();
        }

        return back()->with('success', $modifies > 0
            ? $modifies.' traduction(s) enregistrée(s).'
            : 'Aucune modification.');
    }
}
