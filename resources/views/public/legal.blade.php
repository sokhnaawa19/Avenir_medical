@extends('layouts.public')

@section('title', $title.' — '.setting('site_name'))

@section('content')

    @include('partials.page-hero', ['title' => $title, 'crumb' => $title])

    <section>
        <div class="wrap">
            <div class="article-corps">
                @if (filled($content))
                    {!! nl2br(e($content)) !!}
                @else
                    <p class="text-muted">Ce texte n’a pas encore été renseigné. Il peut être ajouté depuis
                        l’administration, dans « Réglages du site » → « Pied de page & mentions ».</p>
                @endif
            </div>
        </div>
    </section>

@endsection
