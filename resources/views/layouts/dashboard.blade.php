<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'SamaDocs')</title>
  <meta name="description" content="Stockez, organisez et retrouvez facilement tous vos documents depuis n'importe quel appareil.">
  <meta name="theme-color" content="#3b82f6">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="SamaDocs">
  <script>
    // Apply saved theme before paint to avoid flash
    (function(){
      try {
        var saved = localStorage.getItem('samadocs-theme');
        if (saved) document.documentElement.setAttribute('data-theme', saved);
      } catch(e) {}
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body>
  <div class="dashboard-layout">
    <div class="sidebar-overlay"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-logo">
        <div class="sidebar-logo-icon"><i class="fas fa-file-alt"></i></div>
        <span class="sidebar-logo-text">SamaDocs</span>
      </div>

      <nav class="sidebar-nav">
        <div class="sidebar-nav-section">
          <div class="sidebar-nav-label">Principal</div>
          <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Tableau de bord
          </a>
          <a href="{{ route('documents.index') }}" class="sidebar-nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Mes documents
          </a>
          <a href="{{ route('categories.index') }}" class="sidebar-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="fas fa-folder"></i> Catégories
          </a>
          <a href="{{ route('favorites.index') }}" class="sidebar-nav-item {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Favoris
          </a>
          <a href="{{ route('trash.index') }}" class="sidebar-nav-item {{ request()->routeIs('trash.*') ? 'active' : '' }}">
            <i class="fas fa-trash-alt"></i> Corbeille
          </a>
        </div>

        <div class="sidebar-nav-divider"></div>

        <div class="sidebar-nav-section">
          <div class="sidebar-nav-label">Autres</div>
          <a href="{{ route('settings.index') }}" class="sidebar-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Paramètres
          </a>
        </div>
      </nav>

      <div class="sidebar-user">
        <div class="sidebar-user-info">
          @if(auth()->user()->avatarUrl())
            <img src="{{ auth()->user()->avatarUrl() }}" alt="Avatar" class="sidebar-user-avatar img">
          @else
            <div class="sidebar-user-avatar">{{ auth()->user()->initials() }}</div>
          @endif
          <div style="min-width:0;">
            <div class="sidebar-user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-email" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->email }}</div>
          </div>
        </div>
        <button class="btn sidebar-logout-btn" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> Déconnexion
        </button>
      </div>
    </aside>

    <!-- MAIN -->
    <main class="main-content">
      <header class="main-header">
        <div class="flex items-center gap-3">
          <button class="hamburger" aria-label="Menu">
            <span></span><span></span><span></span>
          </button>
          @yield('header-title')
        </div>
        <div class="flex items-center gap-3">
          <button class="btn btn-icon btn-ghost theme-toggle-btn" aria-label="Changer de thème">
            <i class="fas fa-moon"></i>
          </button>
          <button class="btn btn-icon btn-ghost" aria-label="Notifications">
            <i class="fas fa-bell"></i>
          </button>
          <div class="dropdown">
            @if(auth()->user()->avatarUrl())
              <button class="sidebar-user-avatar img" data-dropdown-trigger style="cursor:pointer;"><img src="{{ auth()->user()->avatarUrl() }}" alt="Avatar"></button>
            @else
              <button class="sidebar-user-avatar" data-dropdown-trigger style="cursor:pointer;">{{ auth()->user()->initials() }}</button>
            @endif
            <div class="dropdown-menu">
              <a href="{{ route('settings.index') }}" class="dropdown-item"><i class="fas fa-cog"></i> Paramètres</a>
              <button class="dropdown-item danger" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
            </div>
          </div>
        </div>
      </header>

      <div class="main-body">
        @yield('content')
      </div>
    </main>

    <!-- MOBILE NAV -->
    <nav class="mobile-nav">
      <div class="mobile-nav-inner">
        <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <i class="fas fa-home"></i>
          <span>Accueil</span>
        </a>
        <a href="{{ route('documents.index') }}" class="mobile-nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
          <i class="fas fa-file-alt"></i>
          <span>Docs</span>
        </a>
        <a href="{{ route('categories.index') }}" class="mobile-nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
          <i class="fas fa-folder"></i>
          <span>Catégories</span>
        </a>
        <a href="{{ route('settings.index') }}" class="mobile-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
          <i class="fas fa-cog"></i>
          <span>Paramètres</span>
        </a>
        <button class="mobile-nav-item mobile-nav-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i>
          <span>Quitter</span>
        </button>
      </div>
    </nav>
  </div>

  <div class="toast-container"></div>
  <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
  </form>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')

  @if (session('success'))
    <script>document.addEventListener('DOMContentLoaded', function(){ showToast('success', @json(session('success'))); });</script>
  @endif
  @if (session('error'))
    <script>document.addEventListener('DOMContentLoaded', function(){ showToast('error', @json(session('error'))); });</script>
  @endif
</body>
</html>
