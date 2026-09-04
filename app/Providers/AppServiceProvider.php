<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\CompanyValue;
use App\Models\Agency;
use App\Models\Domain;
use App\Models\Establishment;
use App\Models\GalleryPhoto;
use App\Models\Subsidiary;
use App\Models\Training;
use App\Models\Milestone;
use App\Models\Partner;
use App\Models\Post;
use App\Models\ProcessStep;
use App\Models\Product;
use App\Models\Service;
use App\Services\CartService;
use App\Services\SettingsRepository;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsRepository::class);

        $this->app->scoped(CartService::class, fn ($app) => new CartService($app['session.store']));
    }

    public function boot(): void
    {
        // Dates en francais (12 juillet 2026).
        Carbon::setLocale(config('app.locale'));

        // Des qu'un contenu est ajoute ou modifie, le cache du site est vide.
        foreach ([Product::class, Post::class, Domain::class, Service::class, CompanyValue::class, Category::class, Partner::class, Milestone::class, Establishment::class, Training::class, GalleryPhoto::class, Agency::class, Subsidiary::class, ProcessStep::class] as $model) {
            $model::saved(static fn (Model $item) => SiteCache::flush());
            $model::deleted(static fn (Model $item) => SiteCache::flush());
        }

        // Liens de pagination adaptes au design du site.
        Paginator::defaultView('vendor.pagination.default');
        Paginator::defaultSimpleView('vendor.pagination.default');

        // Les liens ne passent en HTTPS que si l'adresse du site (APP_URL)
        // commence elle-meme par https. Cela evite que le CSS et les images
        // soient introuvables lors des essais en local.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
