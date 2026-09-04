/* ============================================
   AVENIR MEDICAL — script commun à toutes les pages
   ============================================ */

// 1. Sur la page d'accueil, le menu devient blanc quand on descend
var header = document.getElementById('hdr');
if (header && !header.classList.contains('always-solid')) {
  window.addEventListener('scroll', function () {
    header.classList.toggle('solid', window.scrollY > 60);
  });
}

// 2. Menu mobile (bouton ☰ sur téléphone)
var burger = document.getElementById('burger');
var menu = document.getElementById('menu');
if (burger && menu) {
  burger.addEventListener('click', function () {
    menu.classList.toggle('open');
  });
  menu.querySelectorAll('a').forEach(function (lien) {
    lien.addEventListener('click', function () {
      menu.classList.remove('open');
    });
  });
}

// 3. Les sections apparaissent en douceur au défilement
var observer = new IntersectionObserver(function (entries) {
  entries.forEach(function (entry) {
    if (entry.isIntersecting) entry.target.classList.add('visible');
  });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(function (el) { observer.observe(el); });

// 4. Vidéos : l'iframe n'est chargée qu'au clic (la page reste légère)
document.querySelectorAll('.video-lite').forEach(function (bloc) {
  bloc.addEventListener('click', function () {
    var iframe = document.createElement('iframe');
    iframe.src = bloc.dataset.video;
    iframe.title = bloc.getAttribute('aria-label') || 'Vidéo';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;
    iframe.loading = 'lazy';
    bloc.replaceWith(iframe);
  });
});

// 5. Assistant de discussion (chatbot)
(function () {
  var chat = document.getElementById('chat');
  if (!chat) return;

  var toggle = document.getElementById('chatToggle');
  var box = document.getElementById('chatBox');
  var body = document.getElementById('chatBody');
  var teaser = document.getElementById('chatTeaser');
  var teaserClose = document.getElementById('chatTeaserClose');
  var close = document.getElementById('chatClose');
  var choices = document.getElementById('chatChoices');

  // Ouvre ou ferme la fenêtre
  function setOpen(open) {
    chat.classList.toggle('is-open', open);
    box.hidden = !open;
    if (open) {
      teaser.hidden = true;
      try { sessionStorage.setItem('am_chat_seen', '1'); } catch (e) {}
    }
  }

  toggle.addEventListener('click', function () { setOpen(box.hidden); });
  close.addEventListener('click', function () { setOpen(false); });

  teaser.addEventListener('click', function (event) {
    if (event.target !== teaserClose) setOpen(true);
  });

  teaserClose.addEventListener('click', function (event) {
    event.stopPropagation();
    teaser.hidden = true;
    try { sessionStorage.setItem('am_chat_seen', '1'); } catch (e) {}
  });

  // Ajoute un message dans la conversation
  function addMessage(text, who) {
    var msg = document.createElement('div');
    msg.className = 'chat-msg chat-msg--' + who;
    msg.textContent = text;
    body.appendChild(msg);
    body.scrollTop = body.scrollHeight;
    return msg;
  }

  // Clic sur une question rapide
  choices.addEventListener('click', function (event) {
    var button = event.target.closest('.chat-choice');
    if (!button) return;

    addMessage(button.textContent.trim(), 'user');

    // Petite animation « en train d'écrire »
    var typing = document.createElement('div');
    typing.className = 'chat-msg chat-msg--bot chat-typing';
    typing.innerHTML = '<span></span><span></span><span></span>';
    body.appendChild(typing);
    body.scrollTop = body.scrollHeight;

    setTimeout(function () {
      typing.remove();
      addMessage(button.dataset.answer, 'bot');
    }, 700);
  });

  // Ouverture automatique de la bulle, une seule fois par visite
  var delay = parseInt(chat.dataset.delay, 10);
  var dejaVu = false;
  try { dejaVu = sessionStorage.getItem('am_chat_seen') === '1'; } catch (e) {}

  if (delay > 0 && !dejaVu) {
    setTimeout(function () {
      if (box.hidden) teaser.hidden = false;
    }, delay * 1000);
  }
})();

// 6. Galerie photos : agrandissement au clic
(function () {
  var photos = Array.prototype.slice.call(document.querySelectorAll('.photo-item'));
  if (photos.length === 0) return;

  var boite = document.createElement('div');
  boite.className = 'lightbox';
  boite.setAttribute('role', 'dialog');
  boite.innerHTML =
    '<button class="lightbox-close" type="button" aria-label="Fermer">×</button>' +
    '<button class="lightbox-nav lightbox-prev" type="button" aria-label="Photo précédente">‹</button>' +
    '<img alt="">' +
    '<button class="lightbox-nav lightbox-next" type="button" aria-label="Photo suivante">›</button>' +
    '<div class="lightbox-caption"><b></b><small></small></div>';
  document.body.appendChild(boite);

  var image = boite.querySelector('img');
  var titre = boite.querySelector('.lightbox-caption b');
  var legende = boite.querySelector('.lightbox-caption small');
  var index = 0;

  function afficher(i) {
    index = (i + photos.length) % photos.length;
    var photo = photos[index];
    image.src = photo.dataset.full;
    image.alt = photo.dataset.title || '';
    titre.textContent = photo.dataset.title || '';
    legende.textContent = photo.dataset.caption || '';
    boite.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function fermer() {
    boite.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  photos.forEach(function (photo, i) {
    photo.addEventListener('click', function () { afficher(i); });
    photo.addEventListener('keydown', function (event) {
      if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); afficher(i); }
    });
  });

  boite.querySelector('.lightbox-close').addEventListener('click', fermer);
  boite.addEventListener('click', function (event) { if (event.target === boite) fermer(); });

  boite.querySelector('.lightbox-prev').addEventListener('click', function (e) { e.stopPropagation(); afficher(index - 1); });
  boite.querySelector('.lightbox-next').addEventListener('click', function (e) { e.stopPropagation(); afficher(index + 1); });

  document.addEventListener('keydown', function (event) {
    if (!boite.classList.contains('is-open')) return;
    if (event.key === 'Escape') fermer();
    if (event.key === 'ArrowLeft') afficher(index - 1);
    if (event.key === 'ArrowRight') afficher(index + 1);
  });
})();

// 7. WhatsApp : choix du service
(function () {
  var zone = document.getElementById('waZone');
  if (!zone) return;

  var bouton = document.getElementById('waToggle');
  var menu = document.getElementById('waMenu');

  function fermer() {
    menu.hidden = true;
    zone.classList.remove('is-open');
  }

  bouton.addEventListener('click', function (event) {
    event.stopPropagation();
    menu.hidden = !menu.hidden;
    zone.classList.toggle('is-open', !menu.hidden);
  });

  document.addEventListener('click', function (event) {
    if (!zone.contains(event.target)) fermer();
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') fermer();
  });
})();

// 8. Indicateur de chargement entre les pages
(function () {
  // Une fine barre en haut de l'écran, plutôt qu'une page blanche.
  var barre = document.createElement('div');
  barre.className = 'page-loader';
  barre.innerHTML = '<span></span>';
  document.body.appendChild(barre);

  var avancement = 0;
  var minuteur = null;
  var depart = 0;

  function demarrer() {
    if (minuteur) return;
    depart = Date.now();
    avancement = 0;
    barre.classList.add('is-active');

    // La barre ralentit à mesure qu'elle avance : elle n'atteint
    // jamais 100 % tant que la page n'est pas arrivée.
    minuteur = setInterval(function () {
      avancement += Math.max(0.6, (92 - avancement) / 14);
      if (avancement > 92) avancement = 92;
      barre.firstChild.style.width = avancement + '%';
    }, 160);
  }

  function terminer() {
    if (!minuteur) return;
    clearInterval(minuteur);
    minuteur = null;
    barre.firstChild.style.width = '100%';

    setTimeout(function () {
      barre.classList.remove('is-active');
      barre.firstChild.style.width = '0%';
    }, 320);
  }

  // Au clic sur un lien interne
  document.addEventListener('click', function (event) {
    var lien = event.target.closest('a');
    if (!lien) return;

    var href = lien.getAttribute('href');
    if (!href || href.charAt(0) === '#') return;
    if (lien.target === '_blank' || lien.hasAttribute('download')) return;
    if (/^(mailto|tel|javascript|whatsapp):/i.test(href)) return;
    if (event.metaKey || event.ctrlKey || event.shiftKey || event.button !== 0) return;

    // Uniquement les liens de ce site
    var destination = new URL(lien.href, window.location.href);
    if (destination.origin !== window.location.origin) return;
    if (destination.pathname === window.location.pathname && destination.search === window.location.search) return;

    demarrer();
  });

  // À l'envoi d'un formulaire (recherche, commande, contact)
  document.addEventListener('submit', function (event) {
    if (event.target.dataset && event.target.dataset.noLoader === '1') return;
    demarrer();
  });

  // Quand la nouvelle page s'affiche, ou en revenant en arrière
  window.addEventListener('pageshow', terminer);
  window.addEventListener('load', terminer);

  // Si le visiteur annule sa navigation, la barre ne reste pas bloquée
  window.addEventListener('pagehide', terminer);
  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible' && Date.now() - depart > 12000) terminer();
  });
})();
