
@extends('gestionnaire.layout')
@section('title', 'Liste des livres')
@section('content')

<!-- Liste des livres Container -->
<div class="books-list-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
            <p class="mb-0 text-muted">Gérez votre catalogue de livres</p>
        </div>
        <a type="button" class="btn btn-add-book" href="{{ route('gestion.livre.create') }}">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>
    <div>
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
    </div>

    <!-- Books Table -->
    <div class="table-responsive">
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
{{--                        <span class="badge bg-success book-stock-badge">En stock ({{ $livre->qte_stock }})</span>--}}
                        @if($livre->qte_stock > 5)
                            <span class="badge bg-success book-stock-badge">En stock ({{ $livre->qte_stock }})</span>
                        @elseif($livre->qte_stock > 0 && $livre->qte_stock <= 5)
                            <span class="badge bg-warning text-dark book-stock-badge">Stock faible ({{ $livre->qte_stock }})</span>
                        @else
                            <span class="badge bg-danger book-stock-badge">Rupture de stock({{ $livre->qte_stock }})</span>
                        @endif
                        <!-- <span class="badge bg-danger book-stock-badge">Rupture de stock</span>
                        <span class="badge bg-warning text-dark book-stock-badge">Stock faible (3)</span>
                        -->
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('gestion.livre.show', $livre) }}" class="btn action-btn btn-view" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('gestion.livre.edit', $livre) }}" class="btn action-btn btn-edit" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('gestion.livre.archive', $livre) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn action-btn btn-archive" title="Archiver">
                                    <i class="bi bi-archive"></i>
                                </button>
                            </form>
                            <form action="{{ route('gestion.livre.destroy', $livre) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn action-btn btn-delete" title="Supprimer">
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


</div>
@endsection

<script>

</script>


