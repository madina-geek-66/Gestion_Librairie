@extends('gestionnaire.layout')
@section('title', 'Livres archivés')
@section('content')

    <!-- Liste des livres archivés Container -->
    <div class="books-list-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
                <p class="mb-0 text-muted">Gestion des livres archivés</p>
            </div>
            <a type="button" class="btn btn-outline-primary" href="{{ route('gestion.livre.index') }}">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
        <div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Books Table -->
        <div class="table-responsive">
            @if($livres->count() > 0)
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($livres as $livre)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $livre->image) }}" alt="Couverture du livre" class="book-thumbnail">
                            </td>
                            <td>
                                <div class="book-title">{{ $livre->titre }}</div>
                                <small class="text-muted">ID: #{{ $livre->id }}</small>
                            </td>
                            <td class="text-center">{{\Illuminate\Support\Str::limit($livre->description, 20, '...')}}</td>
                            <td>{{ $livre->auteur->prenom." ".$livre->auteur->nom }}</td>
                            <td><span class="category-badge">{{ $livre->categorie->libelle }}</span></td>
                            <td class="book-price">{{ number_format($livre->prix, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($livre->qte_stock > 5)
                                    <span class="badge bg-success book-stock-badge">En stock ({{ $livre->qte_stock }})</span>
                                @elseif($livre->qte_stock > 0 && $livre->qte_stock <= 5)
                                    <span class="badge bg-warning text-dark book-stock-badge">Stock faible ({{ $livre->qte_stock }})</span>
                                @else
                                    <span class="badge bg-danger book-stock-badge">Rupture de stock ({{ $livre->qte_stock }})</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('gestion.livre.show', $livre) }}" class="btn action-btn btn-view" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('gestion.livre.restore', $livre) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn action-btn btn-restore" title="Restaurer">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('gestion.livre.destroy', $livre) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn action-btn btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce livre?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $livres->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-archive" style="font-size: 3rem; color: #adb5bd;"></i>
                    </div>
                    <h4>Aucun livre archivé</h4>
                    <p class="text-muted">Les livres que vous archivez apparaîtront ici.</p>
                    <a href="{{ route('gestion.livre.index') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-arrow-left"></i> Retour à la liste des livres
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

<style>
    .btn-restore {
        background-color: #4e73df;
        color: white;
    }
    .btn-restore:hover {
        background-color: #375ad3;
        color: white;
    }
</style>
