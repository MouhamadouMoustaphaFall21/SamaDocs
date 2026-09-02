@extends('layouts.app')
@section('title', 'Mot de passe oublié — SamaDocs')

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
    <div class="auth-illustration-title">Réinitialisez votre<br>mot de passe</div>
    <div class="auth-illustration-text">Indiquez votre adresse e-mail et recevez un lien pour créer un nouveau mot de passe.</div>
  </div>

  <!-- Formulaire -->
  <div class="auth-form-side">
    <div class="auth-form-wrapper">
      <a href="{{ route('login') }}" class="auth-back-btn">
        <i class="fas fa-arrow-left"></i> Retour à la connexion
      </a>
      <h1 class="auth-form-title">Mot de passe oublié</h1>
      <p class="auth-form-subtitle">Entrez votre adresse e-mail pour recevoir le lien de réinitialisation.</p>

      @if (session('status'))
        <div style="padding:12px 16px;background:var(--success-50,#ecfdf5);border:1px solid rgba(16,185,129,0.25);border-radius:8px;margin-bottom:20px;color:var(--success-600,#047857);font-size:0.875rem;">
          {{ session('status') }}
        </div>
      @endif

      @if ($errors->any())
        <div style="padding:12px 16px;background:var(--danger-50);border:1px solid rgba(239,68,68,0.2);border-radius:8px;margin-bottom:20px;color:var(--danger-500);font-size:0.875rem;">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" placeholder="votre@email.com" value="{{ old('email') }}" required autofocus>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">Envoyer le lien de réinitialisation</button>
      </form>

      <p class="auth-form-footer" style="margin-top:20px;">
        Vous vous êtes souvenu de votre mot de passe ?
        <a href="{{ route('login') }}">Se connecter</a>
      </p>
    </div>
  </div>
</div>

@endsection
