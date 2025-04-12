@extends('layout.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1>Découvrez notre sélection de livres</h1>
        <p class="lead">Des milliers de livres à portée de clic</p>
        <div class="input-group mt-4 w-50 mx-auto">
            <input type="text" class="form-control" placeholder="Rechercher un livre, un auteur...">
            <button class="btn btn-primary" type="button"><i class="bi bi-search"></i></button>
        </div>
    </div>
</section>

<!-- Featured Books -->
<section class="container">
    <h2 class="text-center mb-4">Livres populaires</h2>
    <div class="row">
        @foreach($livres as $livre)
            <div class="col-md-3">
                <div class="card featured-book">
                    <img src="{{ asset('storage/' . $livre->image) }}" class="card-img-top" alt="Livre">
                    <div class="card-body">
                        <h5 class="card-title">{{ $livre->titre }}</h5>
                        <p class="card-text">{{\Illuminate\Support\Str::limit($livre->description, 20, '...')}}</p>
                        <p class="text-primary fw-bold">{{ number_format($livre->prix, 0, ',', ' ') }} FCFA</p>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Détails</button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</section>

<!-- Categories Section -->
<section class="container mt-5">
    <h2 class="text-center mb-4">Catégories populaires</h2>
    <div class="row text-center">
        <div class="col-md-2 col-6 mb-4">
            <div class="d-flex flex-column align-items-center">
                <div class="bg-light p-3 rounded-circle mb-2">
                    <i class="bi bi-journal-richtext fs-1 text-primary"></i>
                </div>
                <h5>Romans</h5>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
            <div class="d-flex flex-column align-items-center">
                <div class="bg-light p-3 rounded-circle mb-2">
                    <i class="bi bi-rocket fs-1 text-primary"></i>
                </div>
                <h5>Sci-Fi</h5>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
            <div class="d-flex flex-column align-items-center">
                <div class="bg-light p-3 rounded-circle mb-2">
                    <i class="bi bi-person-vcard fs-1 text-primary"></i>
                </div>
                <h5>Biographies</h5>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
            <div class="d-flex flex-column align-items-center">
                <div class="bg-light p-3 rounded-circle mb-2">
                    <i class="bi bi-lightning fs-1 text-primary"></i>
                </div>
                <h5>Sciences</h5>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
            <div class="d-flex flex-column align-items-center">
                <div class="bg-light p-3 rounded-circle mb-2">
                    <i class="bi bi-clock-history fs-1 text-primary"></i>
                </div>
                <h5>Histoire</h5>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-4">
            <div class="d-flex flex-column align-items-center">
                <div class="bg-light p-3 rounded-circle mb-2">
                    <i class="bi bi-emoji-smile fs-1 text-primary"></i>
                </div>
                <h5>Jeunesse</h5>
            </div>
        </div>
    </div>
</section>
@endsection
