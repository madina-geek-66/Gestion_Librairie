@php
    $name ??= '';
@endphp
@extends('gestionnaire.layout')
@section('title', 'Liste des catégories')
@section('content')
    <!-- Liste des catégories Container -->
    <div class="books-list-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
                <p class="mb-0 text-muted">Gérez vos catégories de livres</p>
            </div>
            <button type="button" class="btn btn-add-book" data-bs-toggle="modal" data-bs-target="#categorieModal" onclick="resetForm()">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>

        <!-- Message de succès -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('successDelete'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="successDeleteAlert">
                {{ session('successDelete') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Libelle</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $categorie)
                    <tr>
                        <td>{{$categorie->id}}</td>
                        <td><span class="category-badge">{{$categorie->libelle}}</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn action-btn btn-view" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn action-btn btn-edit" title="Modifier"
                                        onclick="editCategorie({{ $categorie->id }}, '{{ $categorie->libelle }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('gestion.categorie.destroy', $categorie) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn action-btn btn-delete" title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
{{--        <div class="pagination-container">--}}
{{--            {{ $categories->links('pagination::bootstrap-5') }}--}}
{{--        </div>--}}
        {{ $categories->links() }}
    </div>

    <!-- Catégorie Modal -->
    <div class="modal fade" id="categorieModal" tabindex="-1" aria-labelledby="categorieModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categorieModalLabel">Ajouter une nouvelle catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categorieForm" method="POST" action="{{ route('gestion.categorie.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="categorie_id" value="">

                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libellé*</label>
                            <input type="text" class="form-control @error('libelle') is-invalid @enderror"
                                   id="libelle" name="libelle" required>
                            @error('libelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="categorieForm" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    function resetForm() {
        document.getElementById('categorieForm').reset();
        document.getElementById('method').value = 'POST';
        document.getElementById('categorie_id').value = '';
        document.getElementById('categorieForm').action = "{{ route('gestion.categorie.store') }}";
        document.getElementById('categorieModalLabel').textContent = 'Ajouter une nouvelle catégorie';
    }


    function editCategorie(id, libelle) {
        document.getElementById('categorieForm').reset();
        document.getElementById('libelle').value = libelle;
        document.getElementById('categorie_id').value = id;
        document.getElementById('method').value = 'PUT';
        document.getElementById('categorieForm').action = "{{ route('gestion.categorie.update', '') }}/" + id;
        document.getElementById('categorieModalLabel').textContent = 'Modifier la catégorie';
        var myModal = new bootstrap.Modal(document.getElementById('categorieModal'));
        myModal.show();
    }

    document.getElementById('categorieModal').addEventListener('hidden.bs.modal', function () {
        resetForm();
    });

    document.addEventListener('DOMContentLoaded', function () {
        var successAlert = document.getElementById('successAlert');
        var successDeleteAlert = document.getElementById('successDeleteAlert');

        if(successAlert) {
            setTimeout(function () {
                successAlert.style.display = 'none';
            }, 3000);
        }

        if(successDeleteAlert) {
            setTimeout(function () {
                successDeleteAlert.style.display = 'none';
            }, 3000);
        }
    });




</script>

