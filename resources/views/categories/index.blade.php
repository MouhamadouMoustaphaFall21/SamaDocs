@extends('layouts.dashboard')
@section('title', 'Catégories — SamaDocs')

@section('header-title')
  <div>
    <h1 class="heading-md">Mes catégories</h1>
    <p class="text-sm text-secondary">Organisez vos documents par thème.</p>
  </div>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
  <div></div>
  <button class="btn btn-primary" data-modal-trigger="add-category-modal"><i class="fas fa-plus"></i> Nouvelle catégorie</button>
</div>

@if ($categories->isEmpty())
  <div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-folder"></i></div>
    <div class="empty-state-title">Aucune catégorie</div>
    <div class="empty-state-text">Créez votre première catégorie pour organiser vos documents.</div>
    <button class="btn btn-primary" data-modal-trigger="add-category-modal"><i class="fas fa-plus"></i> Créer une catégorie</button>
  </div>
@else
  <div class="grid grid-3 gap-4">
    @foreach ($categories as $cat)
      <div class="category-card">
        <div class="category-card-icon" style="background:{{ $cat->color }}15;color:{{ $cat->color }};">
          <i class="fas {{ $cat->icon }}"></i>
        </div>
        <div style="flex:1;">
          <a href="{{ route('categories.show', $cat) }}" style="text-decoration:none;">
            <div class="category-card-name" style="color:var(--text-primary);">{{ $cat->name }}</div>
          </a>
          <div class="category-card-count">{{ $cat->documents_count }} document{{ $cat->documents_count > 1 ? 's' : '' }}</div>
        </div>
        <div class="flex items-center gap-1">
          <button class="btn btn-icon btn-sm btn-ghost" title="Modifier" onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')"><i class="fas fa-edit"></i></button>
          <button class="btn btn-icon btn-sm btn-ghost" title="Supprimer" style="color:var(--danger-500);" onclick="deleteCategory({{ $cat->id }})"><i class="fas fa-trash-alt"></i></button>
        </div>
      </div>
    @endforeach
  </div>
@endif

<!-- MODAL NOUVELLE CATÉGORIE -->
<div class="modal-overlay" id="add-category-modal">
  <div class="modal" style="max-width:420px;">
    <form method="POST" action="{{ route('categories.store') }}">
      @csrf
      <div class="modal-header">
        <h3 class="heading-sm">Nouvelle catégorie</h3>
        <button type="button" class="btn btn-icon btn-sm btn-ghost" data-modal-close><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label" for="cat-name">Nom de la catégorie</label>
          <input type="text" id="cat-name" name="name" class="form-input" placeholder="Ex: Santé" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="cat-color">Couleur</label>
          <input type="color" id="cat-color" name="color" value="#3b82f6" style="width:48px;height:40px;border:none;cursor:pointer;">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-modal-close>Annuler</button>
        <button type="submit" class="btn btn-primary">Créer</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL ÉDITER CATÉGORIE -->
<div class="modal-overlay" id="edit-category-modal">
  <div class="modal" style="max-width:420px;">
    <form method="POST" id="edit-category-form" action="#">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h3 class="heading-sm">Modifier la catégorie</h3>
        <button type="button" class="btn btn-icon btn-sm btn-ghost" data-modal-close><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label" for="edit-cat-name">Nom</label>
          <input type="text" id="edit-cat-name" name="name" class="form-input" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-modal-close>Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
function editCategory(id, name) {
  document.getElementById('edit-category-form').action = '/categories/' + id;
  document.getElementById('edit-cat-name').value = name;
  openModal('edit-category-modal');
}
function deleteCategory(id) {
  confirmDelete('Supprimer cette catégorie ? Les documents seront déplacés vers "Sans catégorie".', function() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/categories/' + id;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(csrf);
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
  });
}
</script>
@endpush
