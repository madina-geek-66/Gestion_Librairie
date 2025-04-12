@extends('layout.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white">
                        <h4 class="text-center my-2">
                            <i class="bi bi-lock-fill me-2"></i> Mot de passe oublié
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-4">
                            Vous avez oublié votre mot de passe ? Pas de problème.
                            Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation.
                        </p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autofocus>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark">
                                    <i class="bi bi-send me-2"></i> Envoyer le lien de réinitialisation
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('login') }}" class="text-dark text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i> Retour à la connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
