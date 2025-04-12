@extends('gestionnaire.layout')
@section('title', "Les informations du livre")
@section('content')
    <div class="page-header">
        <div>
            <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
            <p class="mb-0 text-muted">Gestion des livres</p>
        </div>
    </div>
    <form id="addBookForm" action="" method="post" enctype="multipart/form-data">
        @csrf
        @method($livre->exists ? 'put' : 'post')
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="bookTitle" class="form-label">Titre*</label>
                    <input type="text" name="titre" class="form-control" id="bookTitle" value="{{ old('titre', $livre->titre) }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="bookAuthor" class="form-label">Auteur*</label>
                    <input type="text" name="auteur" class="form-control" id="bookAuthor" value="{{ old('auteur', $livre->auteur->prenom." ".$livre->auteur->nom) }}" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bookPrice" class="form-label">Prix (XOF)*</label>
                            <input type="number" name="prix" class="form-control" id="bookPrice" step="0.01" min="0" value="{{ old('prix', $livre->prix) }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bookStock" class="form-label">Stock*</label>
                            <input type="number" name="qte_stock" class="form-control" id="bookStock" min="0" value="{{ old('qte_stock', $livre->qte_stock) }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="bookCategory" class="form-label">Catégorie*</label>
                    <input type="text" name="categorie_id" class="form-control" id="bookTitle" value="{{ old('categorie_id', $livre->categorie->libelle) }}" readonly>
                </div>
            </div>
            <div class="col-md-4">
                    <div class="mb-3">
                        <label for="bookImage" class="form-label">Image de couverture</label>
                        <div class="image-preview" id="imagePreview">
                            <img src="{{ asset('storage/' . $livre->image) }}" style="font-size: 3rem;">
                        </div>

                    </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="bookDescription" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="bookDescription" rows="4" readonly>{{ old('description', $livre->description) }}</textarea>
        </div>
    </form>
    <button type="button" class="btn btn-secondary">Annuler</button>
@endsection


