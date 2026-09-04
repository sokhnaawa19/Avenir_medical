@extends('admin.layout')

@section('title', $user->exists ? 'Modifier le compte' : 'Nouveau compte')

@section('content')

    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if ($user->exists)
            @method('PUT')
        @endif

        <div class="card" style="max-width:680px">
            <div class="grid g2">
                <div class="field">
                    <label for="name">Nom complet <span class="required">*</span></label>
                    <input class="input @error('name') invalid @enderror" type="text" id="name" name="name"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="phone">Téléphone</label>
                    <input class="input" type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>

            <div class="field">
                <label for="email">Email <span class="required">*</span></label>
                <input class="input @error('email') invalid @enderror" type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')<span class="err">{{ $message }}</span>@enderror
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="city">Ville</label>
                    <input class="input" type="text" id="city" name="city" value="{{ old('city', $user->city) }}">
                </div>

                <div class="field">
                    <label for="address">Adresse</label>
                    <input class="input" type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
                </div>
            </div>

            <div class="grid g2">
                <div class="field">
                    <label for="password">Mot de passe @unless ($user->exists)<span class="required">*</span>@endunless</label>
                    <input class="input @error('password') invalid @enderror" type="password" id="password" name="password"
                           autocomplete="new-password" @unless ($user->exists) required @endunless>
                    @if ($user->exists)<span class="hint">Laissez vide pour ne pas le changer.</span>@endif
                    @error('password')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input class="input" type="password" id="password_confirmation" name="password_confirmation"
                           autocomplete="new-password" @unless ($user->exists) required @endunless>
                </div>
            </div>

            <div class="field">
                <label class="check">
                    <input type="checkbox" name="is_admin" value="1" @checked(old('is_admin', $user->is_admin))
                           @if ($user->exists && $user->is(auth()->user())) disabled checked @endif>
                    Ce compte peut accéder à l'administration
                </label>
                @if ($user->exists && $user->is(auth()->user()))
                    <span class="hint">Vous ne pouvez pas retirer vos propres droits.</span>
                @endif
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">💾 Enregistrer</button>
                <a class="btn btn-line" href="{{ route('admin.users.index') }}">Annuler</a>
            </div>
        </div>
    </form>

@endsection
