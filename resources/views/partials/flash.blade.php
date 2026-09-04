@if (session('success'))
    <div class="alert alert-success">@include('partials.icon', ['name' => 'check']) {{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-error">@include('partials.icon', ['name' => 'alert']) {{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <strong>{{ __('site.merci_de_corriger_les_points_suivants') }}</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
