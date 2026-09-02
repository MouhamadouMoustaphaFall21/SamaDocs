@extends('layouts.dashboard')
@section('title', 'Paramètres — SamaDocs')

@section('header-title')
  <div>
    <h1 class="heading-md">Paramètres</h1>
    <p class="text-sm text-secondary">Gérez votre compte et vos préférences.</p>
  </div>
@endsection

@section('content')

<div class="settings-layout">
  <!-- NAV -->
  <div class="settings-nav">
    <button class="settings-nav-item active" data-section="profile"><i class="fas fa-user"></i> Profil</button>
    <button class="settings-nav-item" data-section="security"><i class="fas fa-shield-alt"></i> Sécurité</button>
    <button class="settings-nav-item" data-section="preferences"><i class="fas fa-palette"></i> Préférences</button>
    <button class="settings-nav-item" data-section="storage"><i class="fas fa-hdd"></i> Stockage</button>
  </div>

  <div>
    <!-- PROFIL -->
    <div class="settings-section settings-section-panel" id="settings-profile">
      <h3 class="settings-section-title">Mon profil</h3>

      <form method="POST" action="{{ route('settings.update-profile') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex items-center gap-4 mb-6">
          <div class="settings-avatar-wrap">
            @if ($user->avatarUrl())
              <img src="{{ $user->avatarUrl() }}" alt="Avatar">
            @else
              <div class="settings-avatar-initials">{{ $user->initials() }}</div>
            @endif
          </div>
          <div>
            <label class="btn btn-sm btn-secondary" style="cursor:pointer;margin-bottom:6px;">
              <i class="fas fa-camera"></i> Changer la photo
              <input type="file" name="avatar" id="settings-avatar-input" accept="image/*" style="display:none;">
            </label>
            <div class="form-help">JPG, PNG. Max 2 MB.</div>
          </div>
        </div>

        <div class="grid grid-2" style="gap:20px;">
          <div class="form-group">
            <label class="form-label">Prénom</label>
            <input type="text" name="first_name" class="form-input" value="{{ old('first_name', $user->first_name) }}" required>
            @error('first_name') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Nom</label>
            <input type="text" name="last_name" class="form-input" value="{{ old('last_name', $user->last_name) }}" required>
            @error('last_name') <div class="form-error">{{ $message }}</div> @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" class="form-input" value="{{ $user->email }}" readonly>
          <div class="form-help">L'email ne peut pas être modifié.</div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer le profil</button>
      </form>
    </div>

    <!-- SÉCURITÉ -->
    <div class="settings-section settings-section-panel hidden" id="settings-security">
      <h3 class="settings-section-title">Sécurité</h3>

      <form method="POST" action="{{ route('settings.update-password') }}" class="settings-section-panel" style="padding:0;margin-bottom:24px;">
        @csrf
        @method('PUT')
        <h4 class="heading-xs mb-4">Changer le mot de passe</h4>
        <div class="form-group" style="max-width:380px;">
          <label class="form-label">Mot de passe actuel</label>
          <input type="password" name="current_password" class="form-input" required>
          @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="grid grid-2" style="gap:20px;max-width:780px;">
          <div class="form-group">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-input" required placeholder="Min. 8 caractères, lettres et chiffres">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" class="form-input" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Mettre à jour le mot de passe</button>
      </form>

      <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-bottom:1px solid var(--border-secondary);">
        <div>
          <div class="text-md" style="font-weight:500;">Notifications email</div>
          <div class="text-sm text-muted">Recevoir des alertes par email.</div>
        </div>
        <label class="form-checkbox" style="margin:0;">
          <input type="checkbox" checked>
        </label>
      </div>
      <div style="padding:16px 0;">
        <button class="btn btn-ghost" style="color:var(--danger-500);" onclick="document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> Se déconnecter
        </button>
      </div>
    </div>

    <!-- PRÉFÉRENCES -->
    <div class="settings-section settings-section-panel hidden" id="settings-preferences">
      <h3 class="settings-section-title">Préférences</h3>

      <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-bottom:1px solid var(--border-secondary);">
        <div>
          <div class="text-md" style="font-weight:500;">Mode sombre</div>
          <div class="text-sm text-muted">Basculer entre le mode clair et sombre.</div>
        </div>
        <button class="btn btn-sm btn-secondary" data-theme-toggle>
          <i class="fas fa-moon"></i> Basculer
        </button>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;">
        <div>
          <div class="text-md" style="font-weight:500;">Langue</div>
          <div class="text-sm text-muted">Choisir la langue de l'interface.</div>
        </div>
        <select class="form-select" style="width:auto;">
          <option>Français</option>
          <option>English</option>
        </select>
      </div>
    </div>

    <!-- STOCKAGE -->
    <div class="settings-section settings-section-panel hidden" id="settings-storage">
      <h3 class="settings-section-title">Stockage</h3>
      <div style="margin-bottom:24px;">
        <div class="flex items-center justify-between mb-2">
          <span class="text-md" style="font-weight:500;">{{ \App\Models\Document::decodeSize($storageUsed) }} / {{ \App\Models\Document::decodeSize($storageLimit) }} utilisés</span>
          <span class="text-sm text-muted">{{ $storagePercent }}%</span>
        </div>
        <div class="progress-bar" style="height:12px;">
          <div class="progress-bar-fill" style="width:{{ $storagePercent }}%;"></div>
        </div>
      </div>

      @if ($documents->count() > 0)
        <h4 class="heading-xs mb-4">Répartition</h4>
        <div style="display:flex;flex-direction:column;gap:12px;">
          @foreach ($documents as $doc)
            @php
              $colors = ['pdf' => 'var(--primary-500)', 'jpg' => '#8b5cf6', 'jpeg' => '#8b5cf6', 'png' => '#8b5cf6', 'doc' => '#22c55e', 'docx' => '#22c55e', 'xls' => '#f59e0b', 'xlsx' => '#f59e0b'];
              $color = $colors[strtolower($doc->file_type)] ?? '#9ca3af';
              $labels = ['pdf' => 'PDF', 'jpg' => 'Images', 'jpeg' => 'Images', 'png' => 'Images', 'doc' => 'Documents Word', 'docx' => 'Documents Word', 'xls' => 'Excel', 'xlsx' => 'Excel'];
              $label = $labels[strtolower($doc->file_type)] ?? strtoupper($doc->file_type);
            @endphp
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div style="width:12px;height:12px;border-radius:3px;background:{{ $color }};"></div>
                <span class="text-sm">{{ $label }}</span>
              </div>
              <span class="text-sm text-muted">{{ \App\Models\Document::decodeSize($doc->total) }}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>

@endsection
