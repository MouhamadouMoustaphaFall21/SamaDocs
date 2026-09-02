@extends('layouts.dashboard')
@section('title', $category->name . ' — SamaDocs')

@section('header-title')
  <a href="{{ route('categories.index') }}" class="text-sm text-secondary flex items-center gap-2" style="text-decoration:none;">
    <i class="fas fa-arrow-left"></i> Retour aux catégories
  </a>
@endsection

@section('content')

<div class="flex items-center gap-3 mb-6">
  <div class="category-card-icon" style="background:{{ $category->color }}15;color:{{ $category->color }};width:48px;height:48px;font-size:1.25rem;">
    <i class="fas {{ $category->icon }}"></i>
  </div>
  <div>
    <h1 class="heading-md">{{ $category->name }}</h1>
    <p class="text-sm text-secondary">{{ $documents->count() }} document{{ $documents->count() > 1 ? 's' : '' }}</p>
  </div>
</div>

@if ($documents->isEmpty())
  <div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-file-alt"></i></div>
    <div class="empty-state-title">Aucun document dans cette catégorie</div>
    <div class="empty-state-text">Ajoutez des documents et assignez-les à cette catégorie.</div>
  </div>
@else
  <div class="grid" style="grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:20px;">
    @foreach ($documents as $doc)
      <div class="doc-card">
        <div class="flex items-center justify-between">
          <div class="doc-card-icon" style="background:{{ $category->color }}18;color:{{ $category->color }};">
            <i class="fas {{ $doc->icon }}"></i>
          </div>
        </div>
        <a href="{{ route('documents.show', $doc) }}" style="text-decoration:none;">
          <div class="doc-card-name">{{ $doc->name }}</div>
        </a>
        <div class="doc-card-meta">
          <span>{{ $doc->human_size }}</span>
          <span>{{ $doc->created_at->format('d M Y') }}</span>
        </div>
      </div>
    @endforeach
  </div>
@endif

@endsection
