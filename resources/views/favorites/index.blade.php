@extends('layouts.dashboard')
@section('title', 'Favoris — SamaDocs')

@section('header-title')
  <div>
    <h1 class="heading-md">Mes favoris</h1>
    <p class="text-sm text-secondary">Vos documents épinglés en accès rapide.</p>
  </div>
@endsection

@section('content')

@if ($favorites->isEmpty())
  <div class="empty-state">
    <div class="empty-state-icon" style="background:var(--warning-50);color:var(--warning-500);"><i class="fas fa-star"></i></div>
    <div class="empty-state-title">Aucun favori pour le moment</div>
    <div class="empty-state-text">Ajoutez des documents en favori pour les retrouver rapidement.</div>
  </div>
@else
  <div class="grid" style="grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:20px;">
    @foreach ($favorites as $doc)
      <div class="doc-card">
        <div class="flex items-center justify-between">
          <div class="doc-card-icon" style="background:{{ $doc->category->color ?? '#9ca3af' }}18;color:{{ $doc->category->color ?? '#9ca3af' }};">
            <i class="fas {{ $doc->icon }}"></i>
          </div>
          <form method="POST" action="{{ route('documents.toggle-favorite', $doc) }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-icon btn-sm btn-ghost" style="color:#f59e0b;" title="Retirer des favoris"><i class="fas fa-star"></i></button>
          </form>
        </div>
        <a href="{{ route('documents.show', $doc) }}" style="text-decoration:none;">
          <div class="doc-card-name">{{ $doc->name }}</div>
          <div class="doc-card-category">{{ $doc->category->name ?? 'Sans catégorie' }}</div>
        </a>
        <div class="doc-card-meta">
          <span>{{ $doc->human_size }}</span>
          <span>{{ $doc->created_at->format('d M Y') }}</span>
        </div>
        <div class="doc-card-actions">
          <a href="{{ route('documents.show', $doc) }}" class="btn btn-icon btn-sm btn-ghost" title="Consulter"><i class="fas fa-eye"></i></a>
          <a href="{{ route('documents.download', $doc) }}" class="btn btn-icon btn-sm btn-ghost" title="Télécharger"><i class="fas fa-download"></i></a>
        </div>
      </div>
    @endforeach
  </div>
@endif

@endsection
