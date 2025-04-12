@extends('layout.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 login-card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4"><i class="bi bi-person-circle me-2"></i>Connexion</h2>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="bi bi-envelope me-2"></i>Adresse Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="form-control"
                                    style="background-color: #f0f4f8; border: none;"
                                >
                                @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="bi bi-lock me-2"></i>Mot de passe</label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    required
                                    autocomplete="current-password"
                                    class="form-control"
                                    style="background-color: #f0f4f8; border: none;"
                                >
                                @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me and Forgot Password -->
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        class="form-check-input"
                                    >
                                    <label for="remember" class="form-check-label">
                                        Se souvenir de moi
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                                        Mot de passe oublié ?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-dark w-100 py-2 mb-3">
                                Se connecter
                            </button>

                            <!-- Register Link -->
                            <div class="text-center">
                                <span class="text-muted">Pas encore de compte ? </span>
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none">
                                    Inscrivez-vous
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f0f4f8 !important;
        }
        .login-card {
            border-radius: 10px !important;
            box-shadow:
                0 4px 6px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(0, 0, 0, 0.08) !important;
        }
        .card {
            border-radius: 10px !important;
        }
        .form-control {
            background-color: #f0f4f8 !important;
            border: none !important;
            padding: 12px !important;
        }
        .form-control:focus {
            box-shadow: none !important;
            border-color: #d1d5db !important;
        }
    </style>
@endsection
