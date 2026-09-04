<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\CompanyValue;
use App\Models\Establishment;
use App\Models\GalleryPhoto;
use App\Models\Training;
use App\Models\Domain;
use App\Models\Milestone;
use App\Models\Partner;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function company(): View
    {
        return view('public.company', [
            'values' => CompanyValue::query()->active()->ordered()->get(),
            'agencies' => Agency::query()->active()->ordered()->get(),
            'milestones' => Milestone::query()->active()->ordered()->get(),
            'partners' => Partner::query()->active()->ordered()->get(),
        ]);
    }

    public function domains(): View
    {
        // Les marques sont chargées d'avance : une seule requête pour toute la page.
        $domains = Domain::query()
            ->active()
            ->ordered()
            ->with(['partners' => fn ($query) => $query->where('partners.is_active', true), 'translations'])
            ->get();

        return view('public.domains', [
            'domains' => $domains,
            // Les chiffres du bandeau de résumé.
            'stats' => [
                'domains' => $domains->count(),
                'equipments' => $domains->sum(fn (Domain $domain): int => count($domain->equipmentList())),
                'brands' => Partner::query()->active()->count(),
            ],
        ]);
    }

    public function services(): View
    {
        return view('public.services', [
            'services' => Service::query()->active()->with('photos')->ordered()->get(),
            'trainings' => Training::query()->active()->with(['photos', 'translations'])->ordered()->get(),
            'exclusives' => Partner::query()->active()->exclusive()->ordered()->get(),
            'partners' => Partner::query()->active()->ordered()->get(),
        ]);
    }



    /** Les établissements de santé que nous avons équipés. */
    public function references(): View
    {
        $establishments = Establishment::query()->active()->ordered()->get();

        return view('public.references', [
            'flagships' => $establishments->where('is_flagship', true)->values(),
            'others' => $establishments->where('is_flagship', false)->values(),
            'stats' => [
                'total' => $establishments->count(),
                'flagship' => $establishments->where('is_flagship', true)->count(),
                'cities' => $establishments->pluck('city')->filter()->unique()->count(),
            ],
            // Un aperçu de la galerie, pour donner envie d'aller la voir.
            'photos' => GalleryPhoto::query()->active()->ordered()->take(6)->get(),
        ]);
    }


    /** La galerie photos : événements, installations, formations. */
    public function gallery(Request $request): View
    {
        $validated = $request->validate([
            'album' => ['nullable', 'string', 'max:120'],
        ]);

        $album = $validated['album'] ?? null;

        return view('public.gallery', [
            'photos' => GalleryPhoto::query()
                ->active()
                ->when($album, fn ($query) => $query->where('album', $album))
                ->ordered()
                ->paginate(24)
                ->withQueryString(),
            'albums' => GalleryPhoto::query()->active()->whereNotNull('album')
                ->distinct()->orderBy('album')->pluck('album'),
            'currentAlbum' => $album,
        ]);
    }


    public function contact(): View
    {
        return view('public.contact');
    }

    public function legal(): View
    {
        return view('public.legal', [
            'title' => 'Mentions légales',
            'content' => (string) setting('legal_notice'),
        ]);
    }

    public function terms(): View
    {
        return view('public.legal', [
            'title' => 'Conditions générales de vente',
            'content' => (string) setting('terms'),
        ]);
    }
}
