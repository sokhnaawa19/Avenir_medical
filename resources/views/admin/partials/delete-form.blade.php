{{--
    Bouton de suppression avec fenêtre de confirmation.

    Variables :
      $action  — adresse de suppression (obligatoire)
      $name    — nom de l'élément, affiché dans la fenêtre
      $label   — texte personnalisé (facultatif)
      $button  — texte du bouton (facultatif)
--}}
<form method="POST" action="{{ $action }}"
      data-confirm="{{ $label ?? 'Cette action est définitive et ne pourra pas être annulée.' }}"
      @if (! empty($name)) data-confirm-name="{{ $name }}" @endif>
    @csrf
    @method('DELETE')
    <button class="btn btn-danger btn-sm" type="submit">{{ $button ?? 'Supprimer' }}</button>
</form>
