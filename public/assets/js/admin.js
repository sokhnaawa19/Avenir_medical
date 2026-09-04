/* ============================================================
   AVENIR MEDICAL — Administration
   Retours visuels pendant les chargements et les enregistrements
   ============================================================ */

(function () {
  'use strict';

  /* ----------------------------------------------------------
     1. Barre de progression en haut de l'écran
     ---------------------------------------------------------- */
  var barre = document.createElement('div');
  barre.className = 'load-bar';
  barre.innerHTML = '<span></span>';
  document.body.appendChild(barre);

  var avancement = 0;
  var minuteur = null;

  function demarrerBarre() {
    if (minuteur) return;
    avancement = 0;
    barre.classList.add('is-active');

    // La barre avance de plus en plus lentement : elle n'atteint
    // jamais 100 % tant que la page n'est pas réellement chargée.
    minuteur = setInterval(function () {
      avancement += Math.max(0.5, (90 - avancement) / 12);
      if (avancement > 90) avancement = 90;
      barre.firstChild.style.width = avancement + '%';
    }, 180);
  }

  function terminerBarre() {
    clearInterval(minuteur);
    minuteur = null;
    barre.firstChild.style.width = '100%';

    setTimeout(function () {
      barre.classList.remove('is-active');
      barre.firstChild.style.width = '0%';
    }, 350);
  }

  /* ----------------------------------------------------------
     2. Voile d'attente (pour les envois de fichiers)
     ---------------------------------------------------------- */
  var voile = document.createElement('div');
  voile.className = 'load-overlay';
  voile.innerHTML =
    '<div class="load-card">' +
      '<div class="load-spin" aria-hidden="true"></div>' +
      '<b class="load-title">Enregistrement en cours…</b>' +
      '<span class="load-text">Merci de patienter, ne fermez pas la page.</span>' +
      '<div class="load-progress"><span></span></div>' +
    '</div>';
  document.body.appendChild(voile);

  function afficherVoile(titre, texte) {
    voile.querySelector('.load-title').textContent = titre;
    voile.querySelector('.load-text').textContent = texte;
    voile.classList.add('is-active');
  }

  /* ----------------------------------------------------------
     3. Boutons : indicateur pendant l'enregistrement
     ---------------------------------------------------------- */
  function occuperBouton(bouton, texte) {
    if (!bouton || bouton.dataset.busy === '1') return;

    bouton.dataset.busy = '1';
    bouton.dataset.labelOrigine = bouton.innerHTML;
    bouton.classList.add('is-loading');
    bouton.innerHTML = '<span class="btn-spin" aria-hidden="true"></span>' + texte;

    // Empêche le double clic (et donc les doublons en base)
    setTimeout(function () { bouton.disabled = true; }, 0);
  }

  /* ----------------------------------------------------------
     4. Envoi des formulaires
     ---------------------------------------------------------- */
  document.addEventListener('submit', function (event) {
    var form = event.target;
    if (form.dataset.noLoader === '1' || event.defaultPrevented) return;

    var bouton = form.querySelector('button[type="submit"], button:not([type])');

    // Y a-t-il un fichier sélectionné dans ce formulaire ?
    var octets = 0;
    Array.prototype.forEach.call(form.querySelectorAll('input[type="file"]'), function (champ) {
      Array.prototype.forEach.call(champ.files || [], function (fichier) { octets += fichier.size; });
    });

    demarrerBarre();
    occuperBouton(bouton, 'Enregistrement…');

    if (octets > 0) {
      var mo = octets / 1048576;
      var taille = mo >= 1 ? mo.toFixed(1) + ' Mo' : Math.round(octets / 1024) + ' Ko';

      afficherVoile(
        'Envoi du fichier en cours…',
        'Fichier de ' + taille + '. Selon votre connexion, cela peut prendre un moment. ' +
        'Ne fermez pas la page.'
      );
    }
  }, true);

  /* ----------------------------------------------------------
     5. Navigation entre les pages
     ---------------------------------------------------------- */
  document.addEventListener('click', function (event) {
    var lien = event.target.closest('a');
    if (!lien) return;

    var href = lien.getAttribute('href');

    // On ignore : ancres, nouveaux onglets, téléchargements, liens spéciaux
    if (!href || href.charAt(0) === '#' || lien.target === '_blank' ||
        lien.hasAttribute('download') || /^(mailto|tel|javascript):/i.test(href)) {
      return;
    }

    if (event.metaKey || event.ctrlKey || event.shiftKey) return;

    demarrerBarre();
  });

  // Si le visiteur revient en arrière, la barre ne doit pas rester bloquée.
  window.addEventListener('pageshow', terminerBarre);
  window.addEventListener('load', terminerBarre);

  /* ----------------------------------------------------------
     6. Les messages de confirmation disparaissent seuls
     ---------------------------------------------------------- */
  Array.prototype.forEach.call(document.querySelectorAll('.alert-success'), function (message) {
    setTimeout(function () { message.classList.add('is-gone'); }, 6000);
  });

  /* ----------------------------------------------------------
     7. Aperçu immédiat de l'image choisie
     ---------------------------------------------------------- */
  Array.prototype.forEach.call(document.querySelectorAll('input[type="file"]'), function (champ) {
    champ.addEventListener('change', function () {
      var fichier = champ.files && champ.files[0];
      if (!fichier) return;

      var zone = champ.closest('.media-row');
      var apercu = zone && zone.querySelector('.preview');

      if (apercu && fichier.type.indexOf('image/') === 0) {
        apercu.style.backgroundImage = 'url(' + URL.createObjectURL(fichier) + ')';
        apercu.style.backgroundSize = 'cover';
        apercu.textContent = '';
      }

      // Affiche le nom et la taille du fichier choisi
      var mo = fichier.size / 1048576;
      var info = champ.parentNode.querySelector('.file-info') || document.createElement('span');
      info.className = 'file-info';
      info.textContent = '📎 ' + fichier.name + ' — ' +
        (mo >= 1 ? mo.toFixed(1) + ' Mo' : Math.round(fichier.size / 1024) + ' Ko');
      champ.parentNode.insertBefore(info, champ.nextSibling);
    });
  });
})();

/* ============================================================
   Fenêtre de confirmation avant suppression
   ============================================================ */
(function () {
  'use strict';

  // Construction de la fenêtre (une seule pour toute la page)
  var fenetre = document.createElement('div');
  fenetre.className = 'modal';
  fenetre.setAttribute('role', 'dialog');
  fenetre.setAttribute('aria-modal', 'true');
  fenetre.innerHTML =
    '<div class="modal-card">' +
      '<div class="modal-icon" aria-hidden="true">🗑️</div>' +
      '<h2 class="modal-title">Confirmer la suppression</h2>' +
      '<p class="modal-name"></p>' +
      '<p class="modal-text"></p>' +
      '<div class="modal-actions">' +
        '<button type="button" class="btn btn-line modal-cancel">Annuler</button>' +
        '<button type="button" class="btn btn-danger modal-ok">Oui, supprimer</button>' +
      '</div>' +
    '</div>';
  document.body.appendChild(fenetre);

  var titre   = fenetre.querySelector('.modal-title');
  var nom     = fenetre.querySelector('.modal-name');
  var texte   = fenetre.querySelector('.modal-text');
  var annuler = fenetre.querySelector('.modal-cancel');
  var valider = fenetre.querySelector('.modal-ok');

  var formulaireEnAttente = null;
  var elementDeclencheur = null;

  function ouvrir(form) {
    formulaireEnAttente = form;
    elementDeclencheur = document.activeElement;

    var nomElement = form.dataset.confirmName;
    nom.textContent = nomElement ? '« ' + nomElement +' »' : '';
    nom.style.display = nomElement ? 'block' : 'none';
    texte.textContent = form.dataset.confirm || 'Cette action est définitive.';

    fenetre.classList.add('is-open');
    document.body.classList.add('modal-open');
    valider.focus();
  }

  function fermer() {
    fenetre.classList.remove('is-open');
    document.body.classList.remove('modal-open');
    formulaireEnAttente = null;
    if (elementDeclencheur && elementDeclencheur.focus) elementDeclencheur.focus();
  }

  // Interception de l'envoi des formulaires marqués « data-confirm »
  document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!form.dataset || form.dataset.confirm === undefined) return;
    if (form.dataset.confirmed === '1') return;   // déjà confirmé : on laisse passer

    event.preventDefault();
    event.stopPropagation();
    ouvrir(form);
  }, true);   // « true » : on intercepte AVANT l'indicateur de chargement

  valider.addEventListener('click', function () {
    if (!formulaireEnAttente) return;
    var form = formulaireEnAttente;
    form.dataset.confirmed = '1';
    fermer();
    // On relance l'envoi : l'indicateur de chargement prend alors le relais.
    if (typeof form.requestSubmit === 'function') form.requestSubmit();
    else form.submit();
  });

  annuler.addEventListener('click', fermer);

  fenetre.addEventListener('click', function (event) {
    if (event.target === fenetre) fermer();
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && fenetre.classList.contains('is-open')) fermer();
  });
})();

/* ============================================================
   Conditionnement : le prix du carton se met à jour en direct
   ============================================================ */
(function () {
  var champs = document.querySelectorAll('.pack-input');
  if (champs.length === 0) return;

  function formater(montant) {
    // Même présentation que le reste du site : 45 000 FCFA
    return montant.toLocaleString('fr-FR').replace(/\u202f|\u00a0/g, ' ') + ' FCFA';
  }

  champs.forEach(function (champ) {
    var ligne = champ.closest('tr');
    var total = ligne ? ligne.querySelector('.pack-total') : null;
    if (!total) return;

    champ.addEventListener('input', function () {
      var unites = parseInt(champ.value, 10);
      var prix = parseFloat(champ.dataset.prix);

      if (!unites || unites < 1 || isNaN(prix)) {
        total.textContent = '—';
        total.classList.remove('is-updated');
        return;
      }

      total.textContent = formater(Math.round(prix * unites));
      total.classList.add('is-updated');
    });
  });
})();
