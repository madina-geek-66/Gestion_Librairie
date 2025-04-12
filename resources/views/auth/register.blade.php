@extends('layout.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-dark text-white text-center py-4">
                        <h2 class="mb-0">
                            <i class="bi bi-person-plus me-2"></i>Inscription
                        </h2>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="firstname" class="form-label">
                                        <i class="bi bi-person me-2"></i>Prénom
                                    </label>
                                    <input type="text" class="form-control" id="firstname" name="firstname"
                                           value="{{ old('firstname') }}" required autofocus>
                                    @error('firstname')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="lastname" class="form-label">
                                        <i class="bi bi-person me-2"></i>Nom
                                    </label>
                                    <input type="text" class="form-control" id="lastname" name="lastname"
                                           value="{{ old('lastname') }}" required>
                                    @error('lastname')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="adresse" class="form-label">
                                    <i class="bi bi-geo-alt me-2"></i>Adresse
                                </label>
                                <input type="text" class="form-control" id="adresse" name="adresse"
                                       value="{{ old('adresse') }}" required>
                                @error('adresse')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="telephone" class="form-label">
                                    <i class="bi bi-telephone me-2"></i>Téléphone
                                </label>
                                <input type="tel" class="form-control" id="telephone" name="telephone"
                                       value="{{ old('telephone') }}" required>
                                @error('telephone')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Adresse Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Mot de passe
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Confirmation du mot de passe
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" required>
                            </div>
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    J'accepte les conditions générales d'utilisation
                                </label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>Créer mon compte
                                </button>
                            </div>
                        </form>
                        <div class="text-center mt-4">
                            <p>Déjà un compte ?
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    Connectez-vous
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
