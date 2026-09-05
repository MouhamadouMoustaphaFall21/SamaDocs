@extends('layouts.dashboard')
@section('title', $document->name . ' — SamaDocs')

@section('header-title')
  <a href="{{ route('documents.index') }}" class="text-sm text-secondary flex items-center gap-2" style="text-decoration:none;">
    <i class="fas fa-arrow-left"></i> Retour à mes documents
  </a>
@endsection

@section('content')

<div style="max-width:900px;">
  <div class="flex items-center gap-3 mb-6">
    <h1 class="heading-lg">{{ $document->name }}</h1>
    @if ($document->is_favorite)
      <span class="badge badge-warning"><i class="fas fa-star"></i> Favori</span>
    @endif
  </div>

  <!-- PREVIEW -->
  <div class="card mb-6" style="padding:0;overflow:hidden;">
    @if ($document->file_path && in_array(strtolower($document->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
      <img src="{{ route('documents.preview', $document) }}" alt="{{ $document->name }}" style="width:100%;max-height:600px;object-fit:contain;display:block;background:var(--bg-secondary);" loading="lazy">
    @elseif ($document->file_path && strtolower($document->extension) === 'pdf')
      <iframe src="{{ route('documents.preview', $document) }}" title="{{ $document->name }}" style="width:100%;height:70vh;border:none;display:block;background:var(--bg-secondary);"></iframe>
    @else
      <div style="background:var(--bg-secondary);min-height:300px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:16px;">
        <div style="width:80px;height:80px;border-radius:var(--radius-xl);background:{{ $document->category->color ?? '#9ca3af' }}18;color:{{ $document->category->color ?? '#9ca3af' }};display:flex;align-items:center;justify-content:center;font-size:2.5rem;">
          <i class="fas {{ $document->icon }}"></i>
        </div>
        <div class="text-secondary">Aperçu non disponible pour ce type de fichier</div>
        @if ($document->file_path)
          <a href="{{ route('documents.download', $document) }}" class="btn btn-primary"><i class="fas fa-download"></i> Télécharger</a>
        @endif
      </div>
    @endif
  </div>

  <!-- INFOS -->
  <div class="card mb-6">
    <h3 class="heading-sm mb-4">Informations du document</h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="doc-info-grid">
      <div>
        <div class="text-xs text-muted mb-2" style="text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">Type</div>
        <div class="text-md flex items-center gap-2"><i class="fas {{ $document->icon }}" style="color:{{ $document->category->color ?? '#9ca3af' }};"></i> {{ $document->extension }}</div>
      </div>
      <div>
        <div class="text-xs text-muted mb-2" style="text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">Taille</div>
        <div class="text-md">{{ $document->human_size }}</div>
      </div>
      <div>
        <div class="text-xs text-muted mb-2" style="text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">Catégorie</div>
        <div class="text-md"><span class="badge badge-primary">{{ $document->category->name ?? 'Sans catégorie' }}</span></div>
      </div>
      <div>
        <div class="text-xs text-muted mb-2" style="text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">Ajouté le</div>
        <div class="text-md">{{ $document->created_at->format('d M Y') }}</div>
      </div>
    </div>
    @if ($document->description)
      <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border-secondary);">
        <div class="text-xs text-muted mb-2" style="text-transform:uppercase;letter-spacing:0.05em;font-weight:600;">Description</div>
        <div class="text-md text-secondary">{{ $document->description }}</div>
      </div>
    @endif
  </div>

  <!-- ACTIONS -->
  <div class="flex gap-3" style="flex-wrap:wrap;">
    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary"><i class="fas fa-download"></i> Télécharger</a>
    <form method="POST" action="{{ route('documents.toggle-favorite', $document) }}" style="margin:0;">
      @csrf
      <button type="submit" class="btn btn-secondary">
        <i class="fas fa-star" style="color:{{ $document->is_favorite ? '#f59e0b' : 'var(--text-muted)' }};"></i>
        {{ $document->is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
      </button>
    </form>
    <button class="btn btn-ghost" style="color:var(--danger-500);" onclick="document.getElementById('delete-form').submit();">
      <i class="fas fa-trash-alt"></i> Supprimer
    </button>
  </div>
</div>

<form id="delete-form" method="POST" action="{{ route('documents.destroy', $document) }}" style="display:none;">
  @csrf
  @method('DELETE')
</form>

@endsection
