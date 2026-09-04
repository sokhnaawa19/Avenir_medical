@php
    $unreadMessages = \App\Models\ContactMessage::query()->where('is_read', false)->count();
    $pendingOrders = \App\Models\Order::query()->where('status', \App\Models\Order::STATUS_PENDING)->count();
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') — {{ setting('site_name') }}</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @php $fontsUrl = 'https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Inter:wght@400;500;600&display=swap'; @endphp
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ $fontsUrl }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="{{ $fontsUrl }}"></noscript>
    <link rel="stylesheet" href="{{ asset_v('assets/css/admin.css') }}">
</head>
<body>

<div class="layout">
    <aside class="side" id="side">
        <a class="side-logo" href="{{ route('admin.dashboard') }}">
            <svg viewBox="0 0 40 40" width="70" height="70" aria-hidden="true">
                <polygon points="8,7 8,33 24,33" fill="#fff"/>
                <polygon points="12,6 32,6 27,30" fill="#fff"/>
            </svg>
            <span>{{ setting('site_name') }}</span>
        </a>

        <nav>
            <a class="item @if (is_current('admin.dashboard')) on @endif" href="{{ route('admin.dashboard') }}">
                <span class="ic">🏠</span> Tableau de bord
            </a>

            <div class="group">Boutique</div>
            <a class="item @if (is_current('admin.orders.*')) on @endif" href="{{ route('admin.orders.index') }}">
                <span class="ic">🧾</span> Commandes
                @if ($pendingOrders > 0)<span class="count">{{ $pendingOrders }}</span>@endif
            </a>
            <a class="item @if (is_current('admin.products.*')) on @endif" href="{{ route('admin.products.index') }}">
                <span class="ic">📦</span> Produits
            </a>
            <a class="item @if (is_current('admin.packaging.*')) on @endif" href="{{ route('admin.packaging.index') }}">
                <span class="ic">📐</span> Conditionnement
            </a>
            <a class="item @if (is_current('admin.categories.*')) on @endif" href="{{ route('admin.categories.index') }}">
                <span class="ic">🗂️</span> Catégories
            </a>

            <div class="group">Contenu du site</div>
            <a class="item @if (is_current('admin.posts.*')) on @endif" href="{{ route('admin.posts.index') }}">
                <span class="ic">📰</span> Articles du blog
            </a>
            <a class="item @if (is_current('admin.domains.*')) on @endif" href="{{ route('admin.domains.index') }}">
                <span class="ic">🏥</span> Domaines
            </a>
            <a class="item @if (is_current('admin.services.*')) on @endif" href="{{ route('admin.services.index') }}">
                <span class="ic">🛠️</span> Services
            </a>
            <a class="item @if (is_current('admin.process.*')) on @endif" href="{{ route('admin.process.index') }}">
                <span class="ic">🧭</span> Parcours client
            </a>
            <a class="item @if (is_current('admin.values.*')) on @endif" href="{{ route('admin.values.index') }}">
                <span class="ic">💎</span> Valeurs
            </a>
            <a class="item @if (is_current('admin.partners.*')) on @endif" href="{{ route('admin.partners.index') }}">
                <span class="ic">🤝</span> Partenaires
            </a>
            <a class="item @if (is_current('admin.milestones.*')) on @endif" href="{{ route('admin.milestones.index') }}">
                <span class="ic">📜</span> Historique
            </a>
            <a class="item @if (is_current('admin.establishments.*')) on @endif" href="{{ route('admin.establishments.index') }}">
                <span class="ic">🏥</span> Références
            </a>
            <a class="item @if (is_current('admin.trainings.*')) on @endif" href="{{ route('admin.trainings.index') }}">
                <span class="ic">🎓</span> Formations
            </a>
            <a class="item @if (is_current('admin.agencies.*')) on @endif" href="{{ route('admin.agencies.index') }}">
                <span class="ic">📍</span> Agences
            </a>
            <a class="item @if (is_current('admin.photos.*')) on @endif" href="{{ route('admin.photos.index') }}">
                <span class="ic">📷</span> Galerie photos
            </a>

            <div class="group">Gestion</div>
            <a class="item @if (is_current('admin.messages.*')) on @endif" href="{{ route('admin.messages.index') }}">
                <span class="ic">✉️</span> Messages
                @if ($unreadMessages > 0)<span class="count">{{ $unreadMessages }}</span>@endif
            </a>
            <a class="item @if (is_current('admin.users.*')) on @endif" href="{{ route('admin.users.index') }}">
                <span class="ic">👥</span> Comptes
            </a>
            <a class="item @if (is_current('admin.translations.*')) on @endif" href="{{ route('admin.translations.index') }}">
                <span class="ic">🌍</span> Traductions
            </a>
            <a class="item @if (is_current('admin.settings.*')) on @endif" href="{{ route('admin.settings.index') }}">
                <span class="ic">⚙️</span> Réglages du site
            </a>
        </nav>

        <div class="side-foot">
            <a href="{{ route('home') }}" target="_blank" rel="noopener">🌍 Voir le site</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:10px">
                @csrf
                <button class="btn btn-line btn-sm" type="submit" style="width:100%">Se déconnecter</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <button class="burger-admin" onclick="document.getElementById('side').classList.toggle('open')" aria-label="Menu">☰</button>
            <h1>@yield('title', 'Administration')</h1>
            <div class="spacer"></div>
            <div class="who">
                <b>{{ auth()->user()->name }}</b>
                Administrateur
            </div>
        </div>

        <div class="content">
            @include('admin.partials.flash')
            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset_v('assets/js/admin.js') }}" defer></script>
{{-- Formulaires de suppression, rendus hors de tout autre formulaire.
     Un <form> imbriqué serait fusionné par le navigateur. --}}
@stack('formulaires-hors-page')

</body>
</html>
