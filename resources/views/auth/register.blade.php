@extends('layouts.app')
@section('title', 'Inscription — SamaDocs')

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
    <div class="auth-illustration-title">Créez votre espace<br>documentaire personnel</div>
    <div class="auth-illustration-text">Rejoignez des milliers d'utilisateurs qui centralisent leurs documents avec SamaDocs.</div>
  </div>

  <!-- Formulaire -->
  <div class="auth-form-side">
    <div class="auth-form-wrapper">
      <a href="{{ route('landing') }}" class="auth-back-btn">
        <i class="fas fa-arrow-left"></i> Retour à l'accueil
      </a>
      <h1 class="auth-form-title">Créez votre espace SamaDocs</h1>
      <p class="auth-form-subtitle">Gratuit, rapide et sécurisé.</p>

      @if ($errors->any())
        <div style="padding:12px 16px;background:var(--danger-50);border:1px solid rgba(239,68,68,0.2);border-radius:8px;margin-bottom:20px;color:var(--danger-500);font-size:0.875rem;">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="grid grid-2" style="gap:16px;">
          <div class="form-group">
            <label class="form-label" for="first_name">Prénom</label>
            <input type="text" id="first_name" name="first_name" class="form-input @error('first_name') error @enderror" placeholder="Mansour" value="{{ old('first_name') }}" required autofocus>
            @error('first_name') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="last_name">Nom</label>
            <input type="text" id="last_name" name="last_name" class="form-input @error('last_name') error @enderror" placeholder="Faye" value="{{ old('last_name') }}" required>
            @error('last_name') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" placeholder="votre@email.com" value="{{ old('email') }}" required>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Mot de passe</label>
          <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" placeholder="••••••••" required>
          <div class="password-strength">
            <div class="password-strength-bar"></div>
            <div class="password-strength-bar"></div>
            <div class="password-strength-bar"></div>
            <div class="password-strength-bar"></div>
          </div>
          <div class="form-help">Minimum 8 caractères, avec majuscules, chiffres et caractères spéciaux.</div>
          @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
        </div>

        <div class="form-group">
          <label class="form-checkbox">
            <input type="checkbox" name="terms" required>
            <span style="font-size:0.875rem;color:var(--text-secondary);">
              J'accepte les <a href="#" style="color:var(--primary-600);font-weight:500;">conditions d'utilisation</a> et la <a href="#" style="color:var(--primary-600);font-weight:500;">politique de confidentialité</a>.
            </span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">Créer mon compte</button>
      </form>

      <div class="auth-form-divider">OU</div>

      <p class="auth-form-footer">
        Vous avez déjà un compte ?
        <a href="{{ route('login') }}">Se connecter</a>
      </p>
    </div>
  </div>
</div>

@endsection
