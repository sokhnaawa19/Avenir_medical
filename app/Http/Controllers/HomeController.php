<?php

namespace App\Http\Controllers;

use App\Models\CompanyValue;
use App\Models\Domain;
use App\Models\Post;
use App\Models\Agency;
use App\Models\Establishment;
use App\Models\GalleryPhoto;
use App\Models\Partner;
use App\Models\Subsidiary;
use App\Models\Training;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\Service;
use App\Support\SiteCache;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Les contenus de l'accueil sont mis en cache : la page se charge
        // sans interroger la base de donnees a chaque visite.
        $sections = SiteCache::remember('site.home', function (): array {
            $domains = Domain::query()
                ->active()
                ->ordered()
                ->with(['partners', 'translations'])
                ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
                ->take(4)
                ->get();

            return [
                'domains' => $domains,
                'services' => Service::query()->active()->ordered()->take(3)->get(),
                'steps' => ProcessStep::query()->active()->ordered()->get(),
                'values' => CompanyValue::query()->active()->ordered()->take(3)->get(),
                'gallery' => $this->galleryItems($domains),
                'featured' => Product::query()->active()->featured()->with(['brand', 'translations'])->ordered()->take(4)->get(),
                'posts' => Post::query()->published()->recent()->take(3)->get(),
                'partners' => Partner::query()->active()->featured()->ordered()->take(12)->get(),
                'exclusives' => Partner::query()->active()->exclusive()->ordered()->take(4)->get(),
                'establishments' => Establishment::query()->active()->featured()->ordered()->take(4)->get(),
                // La réalisation la plus parlante, présentée en grand.
                'project' => Establishment::query()->active()->flagship()->ordered()->first(),
                'subsidiaries' => Subsidiary::query()->active()->ordered()->take(4)->get(),
                'photos' => GalleryPhoto::query()->active()->ordered()->take(6)->get(),
                'trainings' => Training::query()->active()->with(['photos', 'translations'])->ordered()->take(3)->get(),
                'agencies' => Agency::query()->active()->ordered()->get(),
            ];
        });

        return view('public.home', $sections);
    }

    /**
     * Images de la galerie « Nos equipements » : les domaines coches
     * dans l'administration, sinon les premiers domaines disponibles.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, Domain>  $domains
     * @return \Illuminate\Database\Eloquent\Collection<int, Domain>|\Illuminate\Support\Collection<int, Domain>
     */
    private function galleryItems($domains)
    {
        $gallery = Domain::query()->active()->inGallery()->ordered()->take(4)->get();

        return $gallery->isNotEmpty() ? $gallery : $domains->take(4);
    }
}
