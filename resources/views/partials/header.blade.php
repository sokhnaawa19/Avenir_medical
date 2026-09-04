@php
    // Sur la page d'accueil, le menu est transparent puis devient blanc au defilement.
    $isHome = request()->routeIs('home');
    $links = [
        ['route' => 'home', 'label' => __('site.menu.home'), 'match' => ['home']],
        ['route' => 'company', 'label' => __('site.menu.company'), 'match' => ['company']],
        ['route' => 'domains', 'label' => __('site.menu.domains'), 'match' => ['domains']],
        ['route' => 'services', 'label' => __('site.menu.services'), 'match' => ['services']],
        ['route' => 'references', 'label' => __('site.menu.references'), 'match' => ['references', 'gallery']],
        ['route' => 'shop.index', 'label' => __('site.menu.shop'), 'match' => ['shop.*', 'cart.*', 'checkout.*']],
        ['route' => 'blog.index', 'label' => __('site.menu.blog'), 'match' => ['blog.*']],
        ['route' => 'contact', 'label' => __('site.menu.contact'), 'match' => ['contact', 'contact.*']],
    ];
@endphp

<header id="hdr" @class(['always-solid' => ! $isHome])>
    <div class="wrap nav">
        <a class="logo notranslate" translate="no" href="{{ lroute('home') }}">
            <svg viewBox="0 0 40 40" width="52" height="52" aria-hidden="true">
                <polygon points="8,7 8,33 24,33" fill="currentColor"/>
                <polygon points="12,6 32,6 27,30" fill="currentColor"/>
            </svg>
            <span>{!! nl2br(e(str_replace(' ', "\n", setting('site_name')))) !!}</span>
        </a>

        <ul id="menu">
            @foreach ($links as $link)
                @continue($link['route'] === 'shop.index' && ! settings()->boolean('shop_enabled', true))
                <li>
<a href="{{ lroute($link['route']) }}"
                       @class(['active' => is_current(...$link['match'])])
                       @if (is_current(...$link['match'])) aria-current="page" @endif>                        
                    {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
            {{-- Sur téléphone, la langue et le compte sont dans le menu --}}
            <li class="nav-mobile-only">
                <div class="lang-switch lang-switch--menu" role="group" aria-label="{{ __('site.langue_du_site') }}">
                    @foreach (config('app.locales') as $code)
                        <a class="lang-option @if (app()->getLocale() === $code) on @endif"
                           href="{{ route('locale.switch', ['locale' => $code, 'retour' => locale_url($code)]) }}"
                           hreflang="{{ $code }}" lang="{{ $code }}" rel="nofollow">{{ strtoupper($code) }}</a>
                    @endforeach
                </div>
            </li>

            <li class="nav-mobile-only">
                <a href="{{ auth()->check() ? route('account.index') : route('login') }}">
                    {{ auth()->check() ? __('site.account') : __('site.login') }}
                </a>
            </li>
        </ul>

        <div class="icons">
            {{-- Choix de la langue (masqué sur téléphone : il passe dans le menu) --}}
            <div class="lang-switch lang-switch--header" role="group" aria-label="{{ __('site.langue_du_site') }}">
                @foreach (config('app.locales') as $code)
                    <a class="lang-option @if (app()->getLocale() === $code) on @endif"
                       href="{{ route('locale.switch', ['locale' => $code, 'retour' => locale_url($code)]) }}"
                       hreflang="{{ $code }}" lang="{{ $code }}" rel="nofollow"
                       @if (app()->getLocale() === $code) aria-current="true" @endif>{{ strtoupper($code) }}</a>
                @endforeach
            </div>

            <a class="icon-link" href="{{ auth()->check() ? route('account.index') : route('login') }}"
               title="{{ auth()->check() ? __('site.account') : __('site.login') }}">@include('partials.icon', ['name' => 'user', 'size' => 32])</a>

            @if (settings()->boolean('shop_enabled', true))
                <a class="icon-link" href="{{ lroute('cart.index') }}" title="{{ __('site.cart') }}">
                    @include('partials.icon', ['name' => 'cart', 'size' => 32])
                    @if (cart()->count() > 0)
                        <b>{{ cart()->count() }}</b>
                    @endif
                </a>
            @endif

            <button class="burger" id="burger" aria-label="{{ __('site.menu_open') }}">@include('partials.icon', ['name' => 'menu', 'size' => 38])</button>
        </div>
    </div>
</header>
