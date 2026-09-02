@extends('layouts.app')
@section('title', 'Nouveau mot de passe — SamaDocs')

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
    <div class="auth-illustration-title">Créez votre<br>nouveau mot de passe</div>
    <div class="auth-illustration-text">Choisissez un mot de passe sécurisé pour protéger votre espace SamaDocs.</div>
  </div>

  <!-- Formulaire -->
  <div class="auth-form-side">
    <div class="auth-form-wrapper">
      <a href="{{ route('login') }}" class="auth-back-btn">
        <i class="fas fa-arrow-left"></i> Retour à la connexion
      </a>
      <h1 class="auth-form-title">Nouveau mot de passe</h1>
      <p class="auth-form-subtitle">Définissez un nouveau mot de passe pour votre compte.</p>

      @if ($errors->any())
        <div style="padding:12px 16px;background:var(--danger-50);border:1px solid rgba(239,68,68,0.2);border-radius:8px;margin-bottom:20px;color:var(--danger-500);font-size:0.875rem;">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
          <label class="form-label" for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" placeholder="votre@email.com" value="{{ old('email', $email) }}" required autofocus>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Nouveau mot de passe</label>
          <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" placeholder="••••••••" required>
          <div class="form-help">Minimum 8 caractères, avec lettres et chiffres.</div>
          @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">Réinitialiser le mot de passe</button>
      </form>
    </div>
  </div>
</div>

@endsection
