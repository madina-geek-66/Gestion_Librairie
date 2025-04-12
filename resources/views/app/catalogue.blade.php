@php use App\Models\Cart;use Illuminate\Support\Facades\Auth; @endphp
@extends('layout.app')
@section('content')
    <section class="hero-section text-center">
        <div class="container">
            <h1>Découvrez notre catalogue de livres</h1>
            <p class="lead">Des milliers de livres à portée de clic</p>
            <form action="{{ route('app.catalogue.index') }}" method="GET" class="mt-4">
                <div class="input-group w-50 mx-auto">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher un livre, un auteur..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </section>
    <section class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <form action="{{ route('app.catalogue.index') }}" method="GET" id="filterForm">
                        <!-- Préserver la recherche si elle existe -->
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="filter-box">
                            <h5><i class="bi bi-funnel me-2"></i>Filtres</h5>
                            <hr>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Catégories</label>
                                @foreach($categories as $categorie)
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox"
                                               id="cat_{{ $categorie->id }}"
                                               name="categories[]" value="{{ $categorie->id }}"
                                            {{ in_array($categorie->id, request('categories', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cat_{{ $categorie->id }}">
                                            {{ $categorie->libelle }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Auteurs</label>
                                @foreach($auteurs as $auteur)
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox"
                                               id="auteur_{{ $auteur->id }}"
                                               name="auteurs[]" value="{{ $auteur->id }}"
                                            {{ in_array($auteur->id, request('auteurs', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auteur_{{ $auteur->id }}">
                                            {{ $auteur->prenom." ".$auteur->nom }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Prix</label>
                                <div class="d-flex">
                                    <input type="number" name="prix_min" class="form-control form-control-sm me-2"
                                           placeholder="Min" value="{{ request('prix_min') }}">
                                    <input type="number" name="prix_max" class="form-control form-control-sm"
                                           placeholder="Max" value="{{ request('prix_max') }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-filter me-1"></i>Appliquer</button>
                                <a href="{{ route('app.catalogue.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Réinitialiser</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Livres -->
                <div class="col-md-9">
                    @if($livres->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-1"></i> Aucun livre ne correspond à vos critères de recherche.
                        </div>
                    @else
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="mb-0">{{ $livres->total() }} résultat(s) trouvé(s)</p>
                        </div>

                        <div class="row g-4">
                            @foreach($livres as $livre)
                                <div class="col-md-4">
                                    <div class="card book-card h-100">
                                        <img src="{{ asset('storage/' . $livre->image) }}" class="card-img-top"
                                             alt="{{ $livre->titre }}">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $livre->titre }}</h5>
                                            <p class="card-text text-muted fw-bold">{{ $livre->categorie->libelle }}</p>
                                            <p class="card-text text-muted flex-grow-1">
                                                {{\Illuminate\Support\Str::limit($livre->description, 130, '...')}}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <p class="text-primary fw-bold mb-0">
                                                    {{ number_format($livre->prix, 0, ',', ' ') }} FCFA
                                                </p>
                                                @if($livre->qte_stock > 5)
                                                    <span class="badge bg-success">En stock({{ $livre->qte_stock }})</span>
                                                @elseif($livre->qte_stock > 0 && $livre->qte_stock <= 5)
                                                    <span
                                                        class="badge bg-warning">Stock faible({{ $livre->qte_stock }})</span>
                                                @else
                                                    <span class="badge bg-danger">Rupture({{ $livre->qte_stock }})</span>
                                                @endif
                                            </div>

                                            @php
                                                $isInCart = Cart::where('user_id', Auth::id())
                                                    ->where('livre_id', $livre->id)
                                                    ->exists();
                                            @endphp
                                            <div class="d-flex">
                                                @if(!$isInCart && $livre->qte_stock > 0)
                                                    <form action="{{ route('cart.add', $livre->id) }}" method="POST" class="me-1 flex-grow-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary w-100">
                                                            <i class="bi bi-cart-plus me-1"></i> Ajouter
                                                        </button>
                                                    </form>
                                                @elseif($isInCart)
                                                    <a href="{{ route('cart.index') }}" class="btn btn-sm btn-secondary me-1 flex-grow-1">
                                                        Voir le panier
                                                    </a>
                                                @else
                                                    <button disabled class="btn btn-sm btn-primary me-1 flex-grow-1">
                                                        <i class="bi bi-cart-plus me-1"></i> Rupture
                                                    </button>
                                                @endif
                                                <a href="{{ route('app.livre.details', $livre->id) }}" class="btn btn-sm btn-outline-primary flex-shrink-0">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $livres->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Activer le filtrage automatique quand une option est sélectionnée
        document.addEventListener('DOMContentLoaded', function() {
            const filterCheckboxes = document.querySelectorAll('.filter-checkbox');

            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            });
        });
    </script>
@endsection
