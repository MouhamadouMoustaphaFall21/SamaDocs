@extends('layouts.dashboard')
@section('title', 'Tableau de bord — SamaDocs')

@section('header-title')
  <div>
    <h1 class="heading-md">Bonjour {{ auth()->user()->first_name }}</h1>
    <p class="text-sm text-secondary">Voici un aperçu de votre espace documentaire.</p>
  </div>
@endsection

@section('content')

<!-- DATE / HEURE TEMPS RÉEL -->
<div class="flex items-center justify-between mb-6" style="flex-wrap:wrap;gap:12px;">
  <div class="flex items-center gap-2">
    <i class="fas fa-calendar-alt" style="color:var(--primary-600);"></i>
    <span id="current-date" class="text-sm text-secondary" style="font-weight:500;">{{ now()->format('Y-m-d') }}</span>
  </div>
  <div class="flex items-center gap-2">
    <i class="fas fa-clock" style="color:var(--primary-600);"></i>
    <span id="current-time" class="text-sm text-secondary" style="font-weight:500;font-variant-numeric:tabular-nums;">{{ now()->format('H:i:s') }}</span>
  </div>
</div>

<!-- STATISTIQUES -->
<div class="grid grid-4 gap-4 mb-6">
  <div class="stat-card">
    <div class="stat-card-icon blue"><i class="fas fa-file-alt"></i></div>
    <div>
      <div class="stat-card-value">{{ $stats['documents'] }}</div>
      <div class="stat-card-label">Documents</div>
      <div class="stat-card-meta">En stockage</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon green"><i class="fas fa-folder"></i></div>
    <div>
      <div class="stat-card-value">{{ $stats['categories'] }}</div>
      <div class="stat-card-label">Catégories</div>
      <div class="stat-card-meta">Pour organiser</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon orange"><i class="fas fa-hdd"></i></div>
    <div>
      <div class="stat-card-value">{{ \App\Models\Document::decodeSize($stats['storage_used']) }}</div>
      <div class="stat-card-label">Stockage utilisé</div>
      <div class="stat-card-meta">sur 5 GB</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card-icon purple"><i class="fas fa-star"></i></div>
    <div>
      <div class="stat-card-value">{{ $stats['favorites'] }}</div>
      <div class="stat-card-label">Favoris</div>
      <div class="stat-card-meta">Accès rapide</div>
    </div>
  </div>
</div>

<!-- ANALYTICS & RECENT -->
<div class="grid dash-analytics-grid" style="grid-template-columns: 1.5fr 1fr;gap:24px;">
  <!-- GRAPHIQUE -->
  <div class="card">
    <div class="card-header">
      <h3 class="heading-sm">Documents par catégorie</h3>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px;"
         data-categories="{{ $categories->pluck('name')->implode(',') }}"
         data-counts="{{ $categories->pluck('documents_count')->implode(',') }}"
         data-colors="{{ $categories->pluck('color')->implode(',') }}">
      @foreach ($categories as $cat)
        <div>
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm" style="font-weight:500;">{{ $cat->name }}</span>
            <span class="text-xs text-muted">{{ $cat->documents_count }} docs</span>
          </div>
          <div class="progress-bar">
            <div class="progress-bar-fill" style="width:{{ $categories->max('documents_count') > 0 ? ($cat->documents_count / $categories->max('documents_count')) * 100 : 0 }}%;background:{{ $cat->color }};"></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- DOCUMENTS RÉCENTS -->
  <div class="card">
    <div class="card-header">
      <h3 class="heading-sm">Documents récemment ajoutés</h3>
      <a href="{{ route('documents.index') }}" class="text-sm" style="color:var(--primary-600);font-weight:500;">Voir tout</a>
    </div>
    <div style="display:flex;flex-direction:column;gap:4px;">
      @forelse ($recent as $doc)
        @php
          $catColor = $doc->category->color ?? '#9ca3af';
        @endphp
        <a href="{{ route('documents.show', $doc) }}" class="flex items-center gap-3" style="padding:10px 12px;border-radius:8px;transition:background 0.15s;text-decoration:none;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background=''">
          <div style="width:36px;height:36px;border-radius:8px;background:{{ $catColor }}18;color:{{ $catColor }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas {{ $doc->icon }}"></i>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:0.875rem;font-weight:500;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $doc->name }}</div>
            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $doc->category->name ?? 'Sans catégorie' }} · {{ $doc->human_size }}</div>
          </div>
        </a>
      @empty
        <div class="empty-state">
          <div class="empty-state-icon"><i class="fas fa-file-alt"></i></div>
          <div class="empty-state-title">Aucun document</div>
          <div class="empty-state-text">Ajoutez votre premier document.</div>
        </div>
      @endforelse
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
  var dateEl = document.getElementById('current-date');
  var timeEl = document.getElementById('current-time');
  if (!dateEl || !timeEl) return;

  var days = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];
  var months = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];

  function pad(n) { return n < 10 ? '0' + n : '' + n; }

  function update() {
    var d = new Date();
    dateEl.textContent = days[d.getDay()] + ' ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
    timeEl.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
  }
  update();
  setInterval(update, 1000);
})();
</script>
@endpush
