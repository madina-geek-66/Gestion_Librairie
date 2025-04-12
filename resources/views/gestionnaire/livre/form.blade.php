@extends('gestionnaire.layout')
@section('title', $livre->exists ? "Modification des informations du livre" : "Ajouter un livre")
@section('content')
    <div class="page-header">
        <div>
            <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
            <p class="mb-0 text-muted">Gestion des livres</p>
        </div>
    </div>
<form id="addBookForm" action="{{ route($livre->exists ? 'gestion.livre.update' : 'gestion.livre.store', $livre) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method($livre->exists ? 'put' : 'post')
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="bookTitle" class="form-label">Titre*</label>
                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" id="bookTitle" value="{{ old('titre', $livre->titre) }}" required>
                @error('titre')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="bookAuthor" class="form-label">Auteur*</label>

                <select class="form-select @error('auteur_id') is-invalid @enderror" name="auteur_id" id="bookCategory" required>
                    <option value="">Sélectionner l'auteur</option>
                    @foreach($auteurs as $auteur)
                        <option value="{{ $auteur->id }}" {{ old('auteur_id', $livre->auteur_id) == $auteur->id ? 'selected' : '' }}>
                            {{ $auteur->prenom." ".$auteur->nom }}
                        </option>
                    @endforeach
                </select>
                @error('auteur')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="bookPrice" class="form-label">Prix (XOF)*</label>
                        <input type="number" name="prix" class="form-control @error('prix') is-invalid @enderror" id="bookPrice" step="0.01" min="0" value="{{ old('prix', $livre->prix) }}" required>
                        @error('prix')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="bookStock" class="form-label">Stock*</label>
                        <input type="number" name="qte_stock" class="form-control @error('qte_stock') is-invalid @enderror" id="bookStock" min="0" value="{{ old('qte_stock', $livre->qte_stock) }}" required>
                        @error('qte_stock')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="bookCategory" class="form-label">Catégorie*</label>
                <select class="form-select @error('categorie_id') is-invalid @enderror" name="categorie_id" id="bookCategory" required>
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ old('categorie_id', $livre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->libelle }}
                        </option>
                    @endforeach
                </select>
                @error('categorie_id')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
                <div class="mb-3">
                    <label for="bookImage" class="form-label">Image de couverture</label>
                    <div class="image-preview" id="imagePreview">
                        <i class="bi bi-image" style="font-size: 3rem; color: #adb5bd;"></i>
                    </div>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="bookImage" accept="image/*">
                    @error('image')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="bookDescription" class="form-label">Description</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="bookDescription" rows="4">{{ old('description', $livre->description) }}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
</form>
<button type="button" class="btn btn-secondary">Annuler</button>
<button type="submit" form="addBookForm" class="btn btn-primary">
    @if($livre->exists)
        Modifier
    @else
        Enregistrer
    @endif
</button>
@endsection


