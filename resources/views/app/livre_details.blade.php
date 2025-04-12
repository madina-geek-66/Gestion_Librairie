@php use App\Models\Cart;use Illuminate\Support\Facades\Auth; @endphp
@extends('layout.app')
@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('app.catalogue.index') }}">Catalogue</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $livre->titre }}</li>
            </ol>
        </nav>

        <div class="card shadow-sm mb-5">
            <div class="row g-0">
                <div class="col-md-4 p-4">
                    <img src="{{ asset('storage/' . $livre->image) }}" class="img-fluid rounded" alt="{{ $livre->titre }}">
                </div>
                <div class="col-md-8">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 class="card-title fw-bold">{{ $livre->titre }}</h2>
                            <span class="badge bg-primary rounded-pill">{{ $livre->categorie->libelle }}</span>
                        </div>

                        <h6 class="card-subtitle mb-3 text-muted">
                            {{ $livre->auteur->prenom }} {{ $livre->auteur->nom }}
                        </h6>

                        <div class="mb-4">
                            @if($livre->qte_stock > 5)
                                <span class="badge bg-success p-2"><i class="bi bi-check-circle me-1"></i> En stock ({{ $livre->qte_stock }} disponibles)</span>
                            @elseif($livre->qte_stock > 0 && $livre->qte_stock <= 5)
                                <span class="badge bg-warning p-2"><i class="bi bi-exclamation-circle me-1"></i> Stock faible ({{ $livre->qte_stock }} restants)</span>
                            @else
                                <span class="badge bg-danger p-2"><i class="bi bi-x-circle me-1"></i> Rupture de stock</span>
                            @endif
                        </div>

                        <p class="card-text fs-5 mb-4">{{ $livre->description }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <span class="fs-3 fw-bold text-primary">{{ number_format($livre->prix, 0, ',', ' ') }} FCFA</span>
                            </div>

                            @php
                                $isInCart = Cart::where('user_id', Auth::id())
                                    ->where('livre_id', $livre->id)
                                    ->exists();
                            @endphp

                            <div class="d-flex">
                                @if(!$isInCart && $livre->qte_stock > 0)
                                    <form action="{{ route('cart.add', $livre->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $livre->qte_stock }}" class="form-control" style="max-width: 80px;">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-cart-plus me-1"></i> Ajouter au panier
                                            </button>
                                        </div>
                                    </form>
                                @elseif($isInCart)
                                    <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-cart me-1"></i> Voir le panier
                                    </a>
                                @else
                                    <button disabled class="btn btn-danger">
                                        <i class="bi bi-x-circle me-1"></i> Indisponible
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="bookTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Détails</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="livraison-tab" data-bs-toggle="tab" data-bs-target="#livraison" type="button" role="tab" aria-controls="livraison" aria-selected="false">Livraison</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="bookTabContent">
                                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                                        <table class="table table-striped">
                                            <tbody>
                                            <tr>
                                                <th scope="row">Auteur</th>
                                                <td>{{ $livre->auteur->prenom }} {{ $livre->auteur->nom }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Catégorie</th>
                                                <td>{{ $livre->categorie->libelle }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">ISBN</th>
                                                <td>{{ $livre->isbn ?? 'Non disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Nombre de pages</th>
                                                <td>{{ $livre->pages ?? 'Non disponible' }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Date de publication</th>
                                                <td>{{ $livre->date_publication ? date('d/m/Y', strtotime($livre->date_publication)) : 'Non disponible' }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="livraison" role="tabpanel" aria-labelledby="livraison-tab">
                                        <div class="p-3">
                                            <h5><i class="bi bi-truck me-2"></i>Informations de livraison</h5>
                                            <ul class="list-group list-group-flush mt-3">
                                                <li class="list-group-item d-flex align-items-center">
                                                    <i class="bi bi-clock me-3 text-primary fs-4"></i>
                                                    <div>
                                                        <h6 class="mb-0">Délai de livraison</h6>
                                                        <small>2-3 jours ouvrables dans la ville</small>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <i class="bi bi-cash me-3 text-primary fs-4"></i>
                                                    <div>
                                                        <h6 class="mb-0">Frais de livraison</h6>
                                                        <small>1 000 FCFA dans la ville, 2 500 FCFA hors ville</small>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <i class="bi bi-arrow-return-left me-3 text-primary fs-4"></i>
                                                    <div>
                                                        <h6 class="mb-0">Politique de retour</h6>
                                                        <small>Retour possible sous 7 jours (article non endommagé)</small>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livres similaires -->
        @if($livresSimilaires->count() > 0)
            <section class="mt-5">
                <h3 class="mb-4"><i class="bi bi-bookmarks me-2"></i>Livres similaires</h3>
                <div class="row">
                    @foreach($livresSimilaires as $livreSimilaire)
                        <div class="col-md-4">
                            <div class="card book-card h-100">
                                <img src="{{ asset('storage/' . $livreSimilaire->image) }}" class="card-img-top" alt="{{ $livreSimilaire->titre }}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $livreSimilaire->titre }}</h5>
                                    <p class="card-text text-muted fw-bold">{{ $livreSimilaire->categorie->libelle }}</p>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{\Illuminate\Support\Str::limit($livreSimilaire->description, 100, '...')}}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="text-primary fw-bold mb-0">{{ number_format($livreSimilaire->prix, 0, ',', ' ') }} FCFA</p>
                                        <a href="{{ route('app.livre.details', $livreSimilaire->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
