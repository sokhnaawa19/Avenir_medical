<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CompanyValueController as AdminValueController;
use App\Http\Controllers\Admin\ContactMessageController as AdminMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AgencyController as AdminAgencyController;
use App\Http\Controllers\Admin\DomainController as AdminDomainController;
use App\Http\Controllers\Admin\EstablishmentController as AdminEstablishmentController;
use App\Http\Controllers\Admin\GalleryPhotoController as AdminPhotoController;
use App\Http\Controllers\Admin\MilestoneController as AdminMilestoneController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackagingController as AdminPackagingController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProcessStepController as AdminProcessController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubsidiaryController as AdminSubsidiaryController;
use App\Http\Controllers\Admin\TrainingController as AdminTrainingController;
use App\Http\Controllers\Admin\TranslationController as AdminTranslationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Pages publiques — français (par défaut) et anglais (préfixe /en)
|--------------------------------------------------------------------------
| Les deux versions partagent exactement les mêmes routes : seules les
| adresses changent. Les noms de routes anglaises sont préfixés par « en. ».
*/
$pagesPubliques = function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Envoi des vidéos (lecture immédiate et déplacement dans la vidéo)
    Route::get('/media/video/{path}', [MediaController::class, 'video'])
        ->where('path', '.*')
        ->name('media.video');
    Route::get('/entreprise', [PageController::class, 'company'])->name('company');
    Route::get('/domaines', [PageController::class, 'domains'])->name('domains');
    Route::get('/services', [PageController::class, 'services'])->name('services');
    Route::get('/references', [PageController::class, 'references'])->name('references');
    Route::get('/galerie', [PageController::class, 'gallery'])->name('gallery');

    /*
     * Anciennes adresses conservées : elles redirigent vers les pages
     * qui regroupent désormais ces contenus (aucun lien cassé, bon pour Google).
     */
    Route::permanentRedirect('/partenaires', '/services');
    Route::permanentRedirect('/expertise', '/services');
    Route::permanentRedirect('/le-groupe', '/entreprise');
    Route::get('/mentions-legales', [PageController::class, 'legal'])->name('legal');
    Route::get('/conditions-generales', [PageController::class, 'terms'])->name('terms');

    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('contact.store');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

    /*
    |--------------------------------------------------------------------------
    | Boutique, panier et commande
    |--------------------------------------------------------------------------
    */

    Route::get('/boutique', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/boutique/{product}', [ShopController::class, 'show'])->name('shop.show');

    Route::controller(CartController::class)->prefix('panier')->name('cart.')->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::post('/{product}', 'store')->name('store');
        Route::patch('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
        Route::delete('/', 'clear')->name('clear');
    });

    Route::controller(CheckoutController::class)->prefix('commande')->name('checkout.')->group(function (): void {
        Route::get('/', 'create')->name('create');
        Route::post('/', 'store')->middleware('throttle:10,1')->name('store');
        Route::get('/{order}/confirmation', 'confirmation')->name('confirmation');
    });

    /*
    |--------------------------------------------------------------------------
    | Comptes clients
    |--------------------------------------------------------------------------
    */

    Route::middleware('guest')->group(function (): void {
        Route::get('/inscription', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/inscription', [RegisteredUserController::class, 'store']);

        Route::get('/connexion', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/connexion', [AuthenticatedSessionController::class, 'store']);

        Route::get('/mot-de-passe-oublie', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('/mot-de-passe-oublie', [PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('/reinitialisation/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reinitialisation', [NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::post('/deconnexion', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::middleware('auth')->prefix('compte')->name('account.')->group(function (): void {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/profil', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profil', [AccountController::class, 'update'])->name('update');
        Route::get('/commandes/{order}', [AccountController::class, 'show'])->name('order');
    });

    /*
    |--------------------------------------------------------------------------
    | Administration
    |--------------------------------------------------------------------------
    */
};

// Choix explicite de la langue par le visiteur (mémorisé un an)
Route::get('/langue/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Version française : avenir-medic.com/boutique
Route::middleware('locale:fr')->group($pagesPubliques);

// Version anglaise : avenir-medic.com/en/boutique
Route::prefix('en')->name('en.')->middleware('locale:en')->group($pagesPubliques);

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('conditionnement', [AdminPackagingController::class, 'index'])->name('packaging.index');
    Route::put('conditionnement', [AdminPackagingController::class, 'update'])->name('packaging.update');

    Route::resource('produits', AdminProductController::class)
        ->parameters(['produits' => 'product'])
        ->names('products')
        ->except('show');

    Route::resource('categories', AdminCategoryController::class)
        ->parameters(['categories' => 'category'])
        ->names('categories')
        ->except('show');

    Route::resource('articles', AdminPostController::class)
        ->parameters(['articles' => 'post'])
        ->names('posts')
        ->except('show');

    Route::resource('domaines', AdminDomainController::class)
        ->parameters(['domaines' => 'domain'])
        ->names('domains')
        ->except('show');

    Route::delete('services/{service}/photos/{photo}', [AdminServiceController::class, 'destroyPhoto'])
        ->name('services.photos.destroy');

    Route::resource('services', AdminServiceController::class)
        ->parameters(['services' => 'service'])
        ->names('services')
        ->except('show');

    Route::resource('partenaires', AdminPartnerController::class)
        ->parameters(['partenaires' => 'partner'])
        ->names('partners')
        ->except('show');

    Route::resource('historique', AdminMilestoneController::class)
        ->parameters(['historique' => 'milestone'])
        ->names('milestones')
        ->except('show');

    Route::resource('references', AdminEstablishmentController::class)
        ->parameters(['references' => 'establishment'])
        ->names('establishments')
        ->except('show');

    Route::delete('formations/{training}/photos/{photo}', [AdminTrainingController::class, 'destroyPhoto'])
        ->name('trainings.photos.destroy');

    Route::get('traductions/{group?}', [AdminTranslationController::class, 'index'])
        ->name('translations.index');
    Route::put('traductions/{group}', [AdminTranslationController::class, 'update'])
        ->name('translations.update');

    Route::resource('parcours', AdminProcessController::class)
        ->parameters(['parcours' => 'step'])
        ->names('process')
        ->except('show');

    Route::resource('formations', AdminTrainingController::class)
        ->parameters(['formations' => 'training'])
        ->names('trainings')
        ->except('show');

    Route::resource('galerie', AdminPhotoController::class)
        ->parameters(['galerie' => 'photo'])
        ->names('photos')
        ->except('show');

    Route::resource('agences', AdminAgencyController::class)
        ->parameters(['agences' => 'agency'])
        ->names('agencies')
        ->except('show');

    Route::resource('groupe', AdminSubsidiaryController::class)
        ->parameters(['groupe' => 'subsidiary'])
        ->names('subsidiaries')
        ->except('show');

    Route::resource('valeurs', AdminValueController::class)
        ->parameters(['valeurs' => 'value'])
        ->names('values')
        ->except('show');

    Route::resource('comptes', AdminUserController::class)
        ->parameters(['comptes' => 'user'])
        ->names('users')
        ->except('show');

    Route::controller(AdminOrderController::class)->prefix('commandes')->name('orders.')->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::patch('/{order}', 'update')->name('update');
        Route::delete('/{order}', 'destroy')->name('destroy');
    });

    Route::controller(AdminMessageController::class)->prefix('messages')->name('messages.')->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::get('/{message}', 'show')->name('show');
        Route::patch('/{message}', 'update')->name('update');
        Route::delete('/{message}', 'destroy')->name('destroy');
    });

    Route::controller(SettingController::class)->prefix('reglages')->name('settings.')->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::get('/{group}', 'edit')->name('edit');
        Route::put('/{group}', 'update')->name('update');
    });
});
