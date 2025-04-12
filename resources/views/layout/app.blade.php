@php
    $route = request()->route()->getName();
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>Librairie en Ligne</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .order-status {
            display: inline-block;
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
        }
        .order-list tbody tr {
            cursor: pointer;
        }
        .user-profile-dropdown .dropdown-toggle::after {
            display: none;
        }
        .cart-badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.6rem;
        }
        .book-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .cart-badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.6rem;
        }
        .filter-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://via.placeholder.com/1200x400');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 30px;
        }
        .featured-book {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .featured-book:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .secondary-nav {
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            background-color: #212529;
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<!-- Navbar supérieure -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-book"></i> BookStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            @guest
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> S'inscrire
                        </a>
                    </li>
                </ul>
            @endguest

            @auth
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart fs-5"></i>
{{--                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">--}}
{{--                    3--}}
{{--                </span>--}}
                            <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
            {{ \App\Models\Cart::where('user_id', Auth::id())->sum('quantity') }}
        </span>
                        </a>
                    </li>
                    <li class="nav-item dropdown user-profile-dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 me-2"></i>
                            <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i>Mon profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('order.index') }}">
                                    <i class="bi bi-bag me-2"></i>Mes commandes
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('facture.index') }}">
                                    <i class="bi bi-file-earmark-text me-1"></i>Mes Factures
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>

<!-- Menu de navigation secondaire -->
<nav class="navbar navbar-expand-lg navbar-light secondary-nav">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#secondaryNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="secondaryNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $currentRoute == 'home' ? 'active' : '' }}" href="{{ route('home') }}"><i class="bi bi-house"></i> Accueil</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ $currentRoute == 'app.catalogue.index' ? 'active' : '' }}" href="{{ route('app.catalogue.index') }}"><i class="bi bi-grid"></i> Catalogue</a>
                </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-star"></i> Nouveautés</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-percent"></i> Promotions</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    @yield('content')


<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>À propos de BookStore</h5>
                <p>Votre librairie en ligne préférée pour tous vos besoins littéraires.</p>
            </div>
            <div class="col-md-4">
                <h5>Liens rapides</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Conditions générales</a></li>
                    <li><a href="#" class="text-white">Politique de confidentialité</a></li>
                    <li><a href="#" class="text-white">Livraisons</a></li>
                    <li><a href="#" class="text-white">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Suivez-nous</h5>
                <div class="d-flex gap-3 fs-4">
                    <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-light mt-4">
        <div class="text-center">
            <p>&copy; 2025 BookStore - Tous droits réservés</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var successAlert = document.getElementById('successAlert');
        var successDeleteAlert = document.getElementById('successDeleteAlert');

        if(successAlert) {
            setTimeout(function () {
                successAlert.style.display = 'none';
            }, 3000);
        }

        if(successDeleteAlert) {
            setTimeout(function () {
                successDeleteAlert.style.display = 'none';
            }, 3000);
        }
    });

    function toggleSelectAll(source) {
        checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
        updateOrderButton();
    }

    function updateOrderButton() {
        const checkedItems = document.querySelectorAll('.item-checkbox:checked').length;
        document.getElementById('orderButton').disabled = checkedItems === 0;
    }

    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', updateOrderButton);
    });

    document.addEventListener('DOMContentLoaded', function() {
        function updateTotal() {
            let total = 0;
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');

            checkedBoxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const price = parseFloat(row.querySelector('td:nth-child(5)').textContent.replace(' FCFA', '').replace(/\s/g, ''));
                const quantity = parseInt(row.querySelector('input[name="quantity"]').value);

                total += price * quantity;
            });

            document.getElementById('selectedTotal').textContent = total.toLocaleString() + ' FCFA';
        }

        // Ajouter des écouteurs d'événements pour les cases à cocher et les quantités
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        document.querySelectorAll('input[name="quantity"]').forEach(input => {
            input.addEventListener('change', updateTotal);
        });
    });
</script>
</body>
</html>
