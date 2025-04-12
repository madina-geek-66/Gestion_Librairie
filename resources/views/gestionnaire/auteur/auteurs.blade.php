@php
    $name ??= '';
@endphp
@extends('gestionnaire.layout')
@section('title', 'Liste des auteurs')
@section('content')
    <!-- Liste des auteurs Container -->
    <div class="books-list-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
                <p class="mb-0 text-muted">Gérez vos auteurs de livres</p>
            </div>
            <button type="button" class="btn btn-add-book" data-bs-toggle="modal" data-bs-target="#auteurModal" onclick="resetForm()">
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

        <!-- Auteurs Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($auteurs as $auteur)
                    <tr>
                        <td>{{$auteur->id}}</td>
                        <td>{{$auteur->nom}}</td>
                        <td>{{$auteur->prenom}}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn action-btn btn-view" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn action-btn btn-edit" title="Modifier"
                                        onclick="editAuteur({{ $auteur->id }}, '{{ $auteur->nom }}', '{{ $auteur->prenom }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('gestion.auteur.destroy', $auteur) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn action-btn btn-delete" title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet auteur?')">
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
        {{ $auteurs->links() }}
    </div>

    <!-- Auteur Modal -->
    <div class="modal fade" id="auteurModal" tabindex="-1" aria-labelledby="auteurModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="auteurModalLabel">Ajouter un nouvel auteur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="auteurForm" method="POST" action="{{ route('gestion.auteur.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="POST">
                        <input type="hidden" name="id" id="auteur_id" value="">

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom*</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                   id="nom" name="nom" required>
                            @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom*</label>
                            <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                   id="prenom" name="prenom" required>
                            @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="auteurForm" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    function resetForm() {
        document.getElementById('auteurForm').reset();
        document.getElementById('method').value = 'POST';
        document.getElementById('auteur_id').value = '';
        document.getElementById('auteurForm').action = "{{ route('gestion.auteur.store') }}";
        document.getElementById('auteurModalLabel').textContent = 'Ajouter un nouvel auteur';
    }


    function editAuteur(id, nom, prenom) {
        document.getElementById('auteurForm').reset();
        document.getElementById('nom').value = nom;
        document.getElementById('prenom').value = prenom;
        document.getElementById('auteur_id').value = id;
        document.getElementById('method').value = 'PUT';
        document.getElementById('auteurForm').action = "{{ route('gestion.auteur.update', '') }}/" + id;
        document.getElementById('auteurModalLabel').textContent = 'Modifier l\'auteur';
        var myModal = new bootstrap.Modal(document.getElementById('auteurModal'));
        myModal.show();
    }

    document.getElementById('auteurModal').addEventListener('hidden.bs.modal', function () {
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
