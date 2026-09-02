@extends('layouts.app')
@section('title', 'SamaDocs — Tous vos documents. Un seul endroit.')

@section('body')

<!-- NAVBAR -->
<nav class="landing-nav">
  <div class="landing-nav-inner">
    <a href="/" class="landing-nav-brand">
      <div class="landing-nav-brand-icon"><i class="fas fa-file-alt"></i></div>
      SamaDocs
    </a>
    <div class="landing-nav-links">
      <a href="#accueil" class="landing-nav-link">Accueil</a>
      <a href="#fonctionnalites" class="landing-nav-link">Fonctionnalités</a>
      <a href="#comment-ca-marche" class="landing-nav-link">Comment ça marche</a>
      <a href="#securite" class="landing-nav-link">Sécurité</a>
    </div>
    <div class="landing-nav-actions">
      <button class="btn btn-icon btn-ghost theme-toggle-btn" aria-label="Changer de thème">
        <i class="fas fa-moon"></i>
      </button>
      <a href="{{ route('login') }}" class="btn btn-ghost landing-nav-login">Se connecter</a>
      <a href="{{ route('register') }}" class="btn btn-primary landing-nav-register">Commencer gratuitement</a>
      <button class="landing-hamburger" id="landing-hamburger" aria-label="Menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <!-- MOBILE MENU -->
  <div class="landing-mobile-menu" id="landing-mobile-menu">
    <a href="#accueil" class="landing-mobile-link">Accueil</a>
    <a href="#fonctionnalites" class="landing-mobile-link">Fonctionnalités</a>
    <a href="#comment-ca-marche" class="landing-mobile-link">Comment ça marche</a>
    <a href="#securite" class="landing-mobile-link">Sécurité</a>
    <div class="landing-mobile-divider"></div>
    <a href="{{ route('login') }}" class="btn btn-secondary landing-mobile-action">Se connecter</a>
    <a href="{{ route('register') }}" class="btn btn-primary landing-mobile-action">Commencer gratuitement</a>
  </div>
</nav>

<!-- HERO -->
<section class="landing-hero" id="accueil">
  <div class="landing-hero-inner">
    <div>
      <h1 class="landing-hero-title">Tous vos documents.<br>Un seul endroit.</h1>
      <p class="landing-hero-subtitle">Stockez, organisez et retrouvez facilement tous vos documents depuis n'importe quel appareil.</p>
      <div class="landing-hero-actions">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Commencer gratuitement</a>
        <a href="#fonctionnalites" class="btn btn-secondary btn-lg">Découvrir SamaDocs</a>
      </div>
    </div>
    <div class="landing-hero-visual">
      <div class="landing-hero-dashboard">
        <div class="landing-hero-dashboard-header">
          <div class="landing-hero-dashboard-dot" style="background:#ef4444;"></div>
          <div class="landing-hero-dashboard-dot" style="background:#f59e0b;"></div>
          <div class="landing-hero-dashboard-dot" style="background:#22c55e;"></div>
        </div>
        <div class="landing-hero-dashboard-body">
          <div class="mock-search">
            <i class="fas fa-search"></i>
            <span>Rechercher un document...</span>
          </div>
          <div class="mock-stats">
            <div class="mock-stat">
              <div class="mock-stat-value">127</div>
              <div class="mock-stat-label">Documents</div>
            </div>
            <div class="mock-stat">
              <div class="mock-stat-value">8</div>
              <div class="mock-stat-label">Catégories</div>
            </div>
            <div class="mock-stat">
              <div class="mock-stat-value">1.4 GB</div>
              <div class="mock-stat-label">Stockage</div>
            </div>
          </div>
          <div class="mock-docs">
            <div class="mock-doc">
              <div class="mock-doc-icon" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-file-pdf"></i></div>
              <div class="mock-doc-info">
                <div class="mock-doc-name">Certificat de scolarité.pdf</div>
                <div class="mock-doc-meta">Éducation · 1.2 MB</div>
              </div>
            </div>
            <div class="mock-doc">
              <div class="mock-doc-icon" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-file-pdf"></i></div>
              <div class="mock-doc-info">
                <div class="mock-doc-name">Contrat de travail.pdf</div>
                <div class="mock-doc-meta">Travail · 850 KB</div>
              </div>
            </div>
            <div class="mock-doc">
              <div class="mock-doc-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-file-image"></i></div>
              <div class="mock-doc-info">
                <div class="mock-doc-name">CNI.jpg</div>
                <div class="mock-doc-meta">Personnel · 2.1 MB</div>
              </div>
            </div>
            <div class="mock-doc">
              <div class="mock-doc-icon" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-file-pdf"></i></div>
              <div class="mock-doc-info">
                <div class="mock-doc-name">CV.pdf</div>
                <div class="mock-doc-meta">Personnel · 950 KB</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PROBLEME -->
<section class="landing-section landing-section-alt">
  <div class="landing-section-inner">
    <div class="section-header">
      <h2>Vous cherchez encore vos documents pendant des heures ?</h2>
      <p>Soyez honnête... Combien de temps perdez-vous à retrouver un simple fichier ?</p>
    </div>
    <div class="features-grid features-grid-4">
      <div class="feature-card">
        <div class="feature-card-icon" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-mobile-alt"></i></div>
        <h3>Documents dispersés</h3>
        <p>Vos fichiers sont éparpillés sur votre téléphone, sans aucun ordre.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-laptop"></i></div>
        <h3>Fichiers éparpillés</h3>
        <p>Plusieurs appareils, plusieurs dossiers, aucune centralisation.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon" style="background:#fff7ed;color:#ea580c;"><i class="fas fa-search"></i></div>
        <h3>Recherche difficile</h3>
        <p>Vous perdez du temps à chercher un document parmi des centaines.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon" style="background:#f3e8ff;color:#9333ea;"><i class="fas fa-folder-open"></i></div>
        <h3>Aucune organisation</h3>
        <p>Pas de catégories, pas de classement, juste du désordre.</p>
      </div>
    </div>
    <div style="text-align:center;margin-top:48px;">
      <p class="heading-md" style="color:var(--primary-600);">SamaDocs centralise tout au même endroit.</p>
    </div>
  </div>
</section>

<!-- FONCTIONNALITES -->
<section class="landing-section" id="fonctionnalites">
  <div class="landing-section-inner">
    <div class="section-header">
      <h2>Tout ce dont vous avez besoin</h2>
      <p>Des fonctionnalités pensées pour vous simplifier la vie au quotidien.</p>
    </div>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-card-icon"><i class="fas fa-search"></i></div>
        <h3>Recherche rapide</h3>
        <p>Retrouvez instantanément vos documents grâce à une recherche intelligente.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon"><i class="fas fa-folder"></i></div>
        <h3>Organisation</h3>
        <p>Classez vos fichiers par catégories personnalisées pour y voir clair.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon"><i class="fas fa-cloud"></i></div>
        <h3>Stockage sécurisé</h3>
        <p>Gardez vos documents dans un espace centralisé et protégé.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon"><i class="fas fa-download"></i></div>
        <h3>Téléchargement</h3>
        <p>Récupérez vos fichiers quand vous en avez besoin, en un clic.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon"><i class="fas fa-eye"></i></div>
        <h3>Aperçu</h3>
        <p>Consultez vos documents sans devoir les télécharger d'abord.</p>
      </div>
      <div class="feature-card">
        <div class="feature-card-icon"><i class="fas fa-shield-alt"></i></div>
        <h3>Sécurité</h3>
        <p>Vos documents restent dans votre espace personnel, protégés en permanence.</p>
      </div>
    </div>
  </div>
</section>

<!-- COMMENT CA MARCHE -->
<section class="landing-section landing-section-alt" id="comment-ca-marche">
  <div class="landing-section-inner">
    <div class="section-header">
      <h2>Comment ça marche ?</h2>
      <p>Commencer est simple. Trois étapes suffisent.</p>
    </div>
    <div class="timeline">
      <div class="timeline-item">
        <div class="timeline-step">01</div>
        <div class="timeline-title">Créez votre compte</div>
        <div class="timeline-text">Inscrivez-vous gratuitement en quelques secondes.</div>
      </div>
      <div class="timeline-item">
        <div class="timeline-step">02</div>
        <div class="timeline-title">Ajoutez vos documents</div>
        <div class="timeline-text">Importez vos PDF, images et autres fichiers.</div>
      </div>
      <div class="timeline-item">
        <div class="timeline-step">03</div>
        <div class="timeline-title">Retrouvez-les facilement</div>
        <div class="timeline-text">Recherchez, consultez ou téléchargez vos documents à tout moment.</div>
      </div>
    </div>
  </div>
</section>

<!-- SECURITE -->
<section class="landing-section" id="securite">
  <div class="landing-section-inner" style="text-align:center;max-width:700px;margin:0 auto;">
    <div class="feature-card-icon" style="margin:0 auto 24px;width:72px;height:72px;font-size:2rem;background:var(--success-50);color:var(--success-600);border-radius:var(--radius-xl);">
      <i class="fas fa-shield-alt"></i>
    </div>
    <h2 style="font-size:2rem;font-weight:700;margin-bottom:16px;">Vos données sont entre de bonnes mains</h2>
    <p style="font-size:1.0625rem;color:var(--text-tertiary);line-height:1.7;">
      SamaDocs utilise un chiffrement avancé pour protéger vos documents. Vos fichiers sont stockés de manière sécurisée et ne sont jamais partagés avec des tiers. Vous avez le contrôle total de vos données.
    </p>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <h2>Ne cherchez plus vos documents.<br>Retrouvez-les.</h2>
  <p>Centralisez vos fichiers et gardez-les toujours à portée de main.</p>
  <a href="{{ route('register') }}" class="btn btn-lg">Commencer gratuitement</a>
</section>

<!-- FOOTER -->
<footer class="landing-footer">
  <div class="landing-footer-inner">
    <div class="landing-footer-grid">
      <div>
        <a href="/" class="landing-nav-brand">
          <div class="landing-nav-brand-icon"><i class="fas fa-file-alt"></i></div>
          SamaDocs
        </a>
        <p class="footer-brand-desc">La plateforme qui centralise tous vos documents personnels dans un espace sécurisé et moderne.</p>
      </div>
      <div class="footer-col">
        <h4>Produit</h4>
        <a href="#fonctionnalites">Fonctionnalités</a>
        <a href="#securite">Sécurité</a>
        <a href="#">Tarifs</a>
      </div>
      <div class="footer-col">
        <h4>À propos</h4>
        <a href="#">Notre histoire</a>
        <a href="#">Blog</a>
        <a href="#">Contact</a>
      </div>
      <div class="footer-col">
        <h4>Légal</h4>
        <a href="#">Conditions d'utilisation</a>
        <a href="#">Politique de confidentialité</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span class="footer-bottom-text">&copy; 2026 SamaDocs. Tous droits réservés.</span>
      <div class="footer-social">
        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
      </div>
    </div>
  </div>
</footer>

@endsection

@push('scripts')
<script>
(function() {
  var hamburger = document.getElementById('landing-hamburger');
  var menu = document.getElementById('landing-mobile-menu');
  if (!hamburger || !menu) return;

  hamburger.addEventListener('click', function(e) {
    e.preventDefault();
    var open = menu.classList.toggle('open');
    hamburger.classList.toggle('active', open);
    hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
  });

  menu.querySelectorAll('a').forEach(function(link) {
    link.addEventListener('click', function() {
      menu.classList.remove('open');
      hamburger.classList.remove('active');
      hamburger.setAttribute('aria-expanded', 'false');
    });
  });
})();
</script>
@endpush
