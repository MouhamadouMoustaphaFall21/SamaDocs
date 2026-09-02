@extends('layouts.app')
@section('title', 'Connexion — SamaDocs')

@section('body')

<div class="auth-layout">
  <!-- Theme toggle -->
  <button type="button" class="btn btn-icon btn-ghost theme-toggle-btn auth-theme-toggle" aria-label="Changer de thème">
    <i class="fas fa-moon"></i>
  </button>
  <!-- Illustration -->
  <div class="auth-illustration">
    <div class="auth-illustration-brand">
      <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <i class="fas fa-file-alt"></i>
      </div>
      SamaDocs
    </div>
    <div class="auth-illustration-title">Tous vos documents.<br>Un seul endroit.</div>
    <div class="auth-illustration-text">Stockez, organisez et retrouvez facilement tous vos documents depuis n'importe quel appareil.</div>
  </div>

  <!-- Formulaire -->
  <div class="auth-form-side">
    <div class="auth-form-wrapper">
      <a href="{{ route('landing') }}" class="auth-back-btn">
        <i class="fas fa-arrow-left"></i> Retour à l'accueil
      </a>
      <h1 class="auth-form-title">Bienvenue sur SamaDocs</h1>
      <p class="auth-form-subtitle">Connectez-vous à votre espace personnel.</p>

      @if ($errors->any())
        <div style="padding:12px 16px;background:var(--danger-50);border:1px solid rgba(239,68,68,0.2);border-radius:8px;margin-bottom:20px;color:var(--danger-500);font-size:0.875rem;">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" placeholder="votre@email.com" value="{{ old('email') }}" required autofocus>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Mot de passe</label>
          <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" placeholder="••••••••" required>
          @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="flex items-center justify-between" style="margin-bottom:24px;">
          <label class="form-checkbox">
            <input type="checkbox" name="remember">
            <span style="font-size:0.875rem;color:var(--text-secondary);">Se souvenir de moi</span>
          </label>
          <a href="{{ route('password.request') }}" style="font-size:0.875rem;color:var(--primary-600);font-weight:500;">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">Se connecter</button>
      </form>

      <div class="auth-form-divider">OU</div>

      <p class="auth-form-footer">
        Pas encore de compte ?
        <a href="{{ route('register') }}">Créer un compte</a>
      </p>
    </div>
  </div>
</div>

@endsection
