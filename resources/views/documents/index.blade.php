@extends('layouts.dashboard')
@section('title', 'Mes documents — SamaDocs')

@section('header-title')
  <div>
    <h1 class="heading-md">Mes documents</h1>
    <p class="text-sm text-secondary">Gérez et retrouvez facilement tous vos fichiers.</p>
  </div>
@endsection

@section('content')

<!-- TOP BAR -->
<form method="GET" action="{{ route('documents.index') }}" class="flex items-center justify-between mb-6" style="flex-wrap:wrap;gap:16px;">
  <div class="search-bar" style="flex:1;min-width:300px;">
    <div class="search-input-wrapper" style="flex:1;">
      <i class="fas fa-search"></i>
      <input type="text" name="search" value="{{ request('search') }}" class="form-input doc-search-input" placeholder="Rechercher un document...">
    </div>
    <select name="category" class="form-select" style="width:auto;min-width:160px;" onchange="this.form.submit()">
      <option value="">Toutes les catégories</option>
      @foreach ($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
      @endforeach
    </select>
    <select name="type" class="form-select" style="width:auto;min-width:140px;" onchange="this.form.submit()">
      <option value="">Tous les types</option>
      <option value="pdf" {{ request('type') == 'pdf' ? 'selected' : '' }}>PDF</option>
      <option value="doc" {{ request('type') == 'doc' ? 'selected' : '' }}>Word</option>
      <option value="jpg" {{ request('type') == 'jpg' ? 'selected' : '' }}>Image</option>
      <option value="xlsx" {{ request('type') == 'xlsx' ? 'selected' : '' }}>Excel</option>
    </select>
  </div>
  <div class="flex items-center gap-3">
    <div class="view-toggle" data-target="docs-container">
      <button class="btn btn-icon btn-ghost active" data-view="grid-view" aria-label="Vue grille"><i class="fas fa-th-large"></i></button>
      <button class="btn btn-icon btn-ghost" data-view="list-view" aria-label="Vue liste"><i class="fas fa-list"></i></button>
    </div>
    <button type="button" class="btn btn-primary" data-modal-trigger="add-doc-modal">
      <i class="fas fa-plus"></i> Ajouter un document
    </button>
  </div>
</form>

@if ($documents->isEmpty())
  <!-- EMPTY STATE -->
  <div class="empty-state" id="empty-state">
    <div class="empty-state-icon"><i class="fas fa-file-alt"></i></div>
    <div class="empty-state-title">Aucun document trouvé</div>
    <div class="empty-state-text">Commencez par ajouter votre premier document ou essayez avec un autre mot-clé ou filtre.</div>
    <button class="btn btn-primary" data-modal-trigger="add-doc-modal"><i class="fas fa-plus"></i> Ajouter un document</button>
  </div>
@else
  <!-- GRID VIEW -->
  <div id="docs-container" class="grid" style="grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:20px;">
    @foreach ($documents as $doc)
      <div class="doc-card">
        <div class="flex items-center justify-between">
          <div class="doc-card-icon" style="background:{{ $doc->category->color ?? '#9ca3af' }}18;color:{{ $doc->category->color ?? '#9ca3af' }};">
            <i class="fas {{ $doc->icon }}"></i>
          </div>
          <div class="dropdown">
            <button class="btn btn-icon btn-sm btn-ghost" data-dropdown-trigger aria-label="Actions">
              <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu">
              <a href="{{ route('documents.show', $doc) }}" class="dropdown-item"><i class="fas fa-eye"></i> Consulter</a>
              <a href="{{ route('documents.download', $doc) }}" class="dropdown-item"><i class="fas fa-download"></i> Télécharger</a>
              <form method="POST" action="{{ route('documents.toggle-favorite', $doc) }}" style="margin:0;">
                @csrf
                <button type="submit" class="dropdown-item"><i class="fas fa-star"></i> {{ $doc->is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}</button>
              </form>
              <div style="height:1px;background:var(--border-secondary);margin:4px 0;"></div>
              <button class="dropdown-item danger" onclick="deleteDocument({{ $doc->id }})"><i class="fas fa-trash-alt"></i> Supprimer</button>
            </div>
          </div>
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
          <form method="POST" action="{{ route('documents.toggle-favorite', $doc) }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-icon btn-sm btn-ghost" title="Favori" style="color:{{ $doc->is_favorite ? '#f59e0b' : 'var(--text-muted)' }};">
              <i class="fas fa-star"></i>
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  <!-- LIST VIEW (hidden by default) -->
  <div id="docs-container-list" class="hidden">
    <div class="table-wrapper card" style="padding:0;">
      <table class="table">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Type</th>
            <th>Taille</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($documents as $doc)
            <tr>
              <td>
                <div class="flex items-center gap-3">
                  <div class="doc-card-icon" style="width:36px;height:36px;font-size:1rem;margin-bottom:0;background:{{ $doc->category->color ?? '#9ca3af' }}18;color:{{ $doc->category->color ?? '#9ca3af' }};"><i class="fas {{ $doc->icon }}"></i></div>
                  <span style="font-weight:500;">{{ $doc->name }}</span>
                </div>
              </td>
              <td><span class="badge badge-primary">{{ $doc->category->name ?? 'Sans catégorie' }}</span></td>
              <td class="text-secondary">{{ $doc->extension }}</td>
              <td class="text-secondary">{{ $doc->human_size }}</td>
              <td class="text-secondary">{{ $doc->created_at->format('d M Y') }}</td>
              <td>
                <div class="flex items-center gap-1">
                  <a href="{{ route('documents.show', $doc) }}" class="btn btn-icon btn-sm btn-ghost" title="Consulter"><i class="fas fa-eye"></i></a>
                  <a href="{{ route('documents.download', $doc) }}" class="btn btn-icon btn-sm btn-ghost" title="Télécharger"><i class="fas fa-download"></i></a>
                  <div class="dropdown">
                    <button class="btn btn-icon btn-sm btn-ghost" data-dropdown-trigger><i class="fas fa-ellipsis-v"></i></button>
                    <div class="dropdown-menu">
                      <form method="POST" action="{{ route('documents.toggle-favorite', $doc) }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-star"></i> {{ $doc->is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}</button>
                      </form>
                      <button class="dropdown-item danger" onclick="deleteDocument({{ $doc->id }})"><i class="fas fa-trash-alt"></i> Supprimer</button>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif

<!-- MODAL AJOUTER UN DOCUMENT -->
<div class="modal-overlay" id="add-doc-modal">
  <div class="modal">
    <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" id="add-doc-form">
      @csrf
      <div class="modal-header">
        <h3 class="heading-sm">Ajouter un document</h3>
        <button type="button" class="btn btn-icon btn-sm btn-ghost" data-modal-close><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="upload-zone" id="upload-zone">
          <input type="file" name="file" id="file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.jpg,.jpeg,.png" style="display:none;">
          <div class="upload-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
          <div class="upload-zone-title">Glissez votre fichier ici</div>
          <div class="upload-zone-text">ou <a href="#" style="color:var(--primary-600);font-weight:500;" onclick="event.preventDefault();document.getElementById('file-input').click();">Choisir un fichier</a></div>
          <div class="upload-zone-formats">PDF, DOC, DOCX, XLS, XLSX, PPT, JPG, PNG</div>
        </div>

        <div id="upload-progress" class="upload-progress" style="display:none;margin-top:16px;">
          <div class="upload-progress-icon"><i class="fas fa-cloud-upload-alt"></i></div>
          <div class="upload-progress-info">
            <div class="upload-progress-name">Transfert de votre document...</div>
            <div class="upload-progress-meta"><span id="upload-progress-label">0%</span> envoyé</div>
            <div style="height:6px;background:var(--bg-primary);border-radius:999px;overflow:hidden;margin-top:8px;">
              <div class="upload-progress-bar" style="width:0%;height:100%;background:var(--primary-500);transition:width .2s ease;"></div>
            </div>
          </div>
        </div>

        <div id="upload-error" style="display:none;margin-top:16px;"></div>

        <div class="form-group" style="margin-top:20px;">
          <label class="form-label" for="doc-name">Nom du document</label>
          <input type="text" id="doc-name" name="name" class="form-input" placeholder="Nom du document" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="doc-category">Catégorie</label>
          <select id="doc-category" name="category_id" class="form-select">
            <option value="">Sélectionner une catégorie</option>
            @foreach ($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label" for="doc-desc">Description <span class="text-muted">(optionnel)</span></label>
          <textarea id="doc-desc" name="description" class="form-textarea" rows="3" placeholder="Ajouter une description..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-modal-close>Annuler</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Enregistrer</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function formatBytes(bytes) {
  if (!bytes) return '0 B';
  if (bytes < 1024) return bytes + ' B';
  if (bytes < 1048576) return (bytes / (1024)).toFixed(1) + ' KB';
  return (bytes / (1048576)).toFixed(1) + ' MB';
}

function deleteDocument(id) {
  const form = document.createElement('form');
  form.method = 'POST';
  const csrf = document.createElement('input');
  csrf.type = 'hidden';
  csrf.name = '_token';
  csrf.value = @json(csrf_token());
  const method = document.createElement('input');
  method.type = 'hidden';
  method.name = '_method';
  method.value = 'DELETE';
  form.appendChild(csrf);
  form.appendChild(method);
  form.action = '/documents/' + id;
  document.body.appendChild(form);
  form.submit();
}
</script>
@endpush