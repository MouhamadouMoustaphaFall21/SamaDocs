@extends('layouts.dashboard')
@section('title', 'Corbeille — SamaDocs')

@section('header-title')
  <div>
    <h1 class="heading-md">Corbeille</h1>
    <p class="text-sm text-secondary">Documents supprimés récemment.</p>
  </div>
@endsection

@section('content')

<div class="trash-notice">
  <i class="fas fa-exclamation-triangle" style="color:var(--warning-500);"></i>
  <span>Les documents de la corbeille seront supprimés définitivement après <strong>30 jours</strong>.</span>
</div>

@if ($trashed->isEmpty())
  <div class="empty-state">
    <div class="empty-state-icon"><i class="fas fa-trash-alt"></i></div>
    <div class="empty-state-title">La corbeille est vide</div>
    <div class="empty-state-text">Aucun document supprimé pour le moment.</div>
  </div>
@else
  <div class="table-wrapper card" style="padding:0;">
    <table class="table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Catégorie</th>
          <th>Taille</th>
          <th>Supprimé le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($trashed as $doc)
          <tr>
            <td>
              <div class="flex items-center gap-3">
                <div class="doc-card-icon" style="width:36px;height:36px;font-size:1rem;margin-bottom:0;background:var(--danger-50);color:var(--danger-500);"><i class="fas {{ $doc->icon }}"></i></div>
                <span style="font-weight:500;">{{ $doc->name }}</span>
              </div>
            </td>
            <td><span class="badge badge-neutral">{{ $doc->category->name ?? 'N/A' }}</span></td>
            <td class="text-secondary">{{ $doc->human_size }}</td>
            <td class="text-secondary">{{ $doc->deleted_at->format('d M Y') }}</td>
            <td>
              <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('trash.restore', $doc) }}" style="margin:0;">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-undo"></i> Restaurer</button>
                </form>
                <button class="btn btn-sm btn-ghost" style="color:var(--danger-500);" onclick="forceDelete({{ $doc->id }})"><i class="fas fa-trash-alt"></i></button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

@endsection

@push('scripts')
<script>
function forceDelete(id) {
  confirmDelete('Supprimer définitivement ce document ?', function() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/trash/' + id + '/force';
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
