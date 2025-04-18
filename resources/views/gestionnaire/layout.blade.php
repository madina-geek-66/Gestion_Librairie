<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore - Administration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }

        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }

        .border-left-danger {
            border-left: 4px solid #e74a3b !important;
        }

        .text-xs {
            font-size: 0.7rem;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important;
            border: none;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .bg-primary {
            background-color: #4e73df !important;
        }

        .bg-success {
            background-color: #1cc88a !important;
        }

        .bg-info {
            background-color: #36b9cc !important;
        }

        .bg-warning {
            background-color: #f6c23e !important;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .dropdown-menu {
            font-size: 0.85rem;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fc;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
        }
        .btn-archive {
            background-color: #6c757d;
            color: white;
        }

        .btn-archive:hover {
            background-color: #5a6268;
            color: white;
        }

        .btn-restore {
            background-color: #4e73df;
            color: white;
        }

        .btn-restore:hover {
            background-color: #375ad3;
            color: white;
        }

        /* Badge pour indiquer qu'un livre est archivé */
        .archived-badge {
            background-color: #6c757d;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 50px;
            margin-left: 5px;
        }

        /* Style pour les états vides */
        .empty-state {
            text-align: center;
            padding: 3rem 0;
        }

        .empty-state i {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #858796;
            margin-bottom: 1.5rem;
        }
        :root {
            --sidebar-width: 280px;
        }
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .admin-wrapper {
            display: flex;
        }
        #sidebar {
            width: var(--sidebar-width);
            background: #343a40;
            color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        #sidebar.collapsed {
            margin-left: calc(-1 * var(--sidebar-width));
        }
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .sidebar-menu li a {
            color: rgba(255, 255, 255, 0.8);
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background: rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease-out;
        }
        .submenu.show {
            max-height: 500px;
        }
        .submenu li a {
            padding-left: 3rem;
            font-size: 0.9rem;
        }
        .content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            padding: 20px;
        }
        .content.expanded {
            width: 100%;
            margin-left: 0;
        }
        .top-navbar {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 1rem;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .stat-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .notification-badge {
            position: absolute;
            top: 0;
            right: 5px;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .recent-order-card {
            transition: transform 0.3s;
        }
        .recent-order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.active {
                margin-left: 0;
            }
            .content {
                width: 100%;
                margin-left: 0;
            }
        }

        <!-- Pour livre -->
        .books-list-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        .btn-add-book {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #4e73df;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }
        .btn-add-book:hover {
            background-color: #3756a4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .book-title {
            font-weight: 600;
        }
        .book-thumbnail {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .btn-view {
            background-color: #36b9cc;
            color: white;
        }
        .btn-edit {
            background-color: #f6c23e;
            color: white;
        }
        .btn-archive {
            background-color: #858796;
            color: white;
        }
        .btn-delete {
            background-color: #e74a3b;
            color: white;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
        .book-stock-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .table th {
            background-color: #f8f9fc;
            color: #5a5c69;
            font-weight: 600;
            border-top: none;
        }
        .modal-body {
            padding: 20px;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .category-badge {
            background-color: #4e73df;
            color: white;
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
        }
        .book-price {
            font-weight: 600;
            color: #2e59d9;
        }
        .image-preview {
            width: 100%;
            height: 200px;
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
<div class="admin-wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <a class="navbar-brand" href="#">
                <i class="bi bi-book"></i> BookStore
            </a>
            <p class="mb-0 small">Panneau d'administration</p>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('gestion.dashboard') }}" class="active" id="dashboard-link">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="has-submenu">
                    <i class="bi bi-book"></i> Livre, Catégorie & Auteur
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('gestion.livre.index') }}" id="books-list-link">Gestion des livres</a></li>
                    <li><a href="{{ route('gestion.livre.archived') }}" id="archived-books-link">Livres archivés</a></li>
                    <li><a href="{{ route('gestion.categorie.index') }}" id="categories-link">Gestion des Catégories</a></li>
                    <li><a href="{{ route('gestion.auteur.index') }}" id="categories-link">Gestion des Auteurs</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="has-submenu">
                    <i class="bi bi-cart3"></i> Gestion des Commandes
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('gestion.orders.index') }}" id="all-orders-link">Toutes les commandes</a></li>
                    <li><a href="{{ route('gestion.orders.pending') }}" id="pending-orders-link">Commandes en attente</a></li>
                    <li><a href="{{ route('gestion.orders.paid') }}" id="paid-orders-link">Commandes payées</a></li>
                </ul>
            </li>
            <li>
                <a href="#" class="has-submenu">
                    <i class="bi bi-cash-coin"></i> Gestion des Paiements
                    <i class="bi bi-chevron-down float-end"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('gestion.paiement.index') }}" id="payment-history-link">Historique des paiements</a></li>
                </ul>
            </li>
{{--            <li>--}}
{{--                <a href="#" class="has-submenu">--}}
{{--                    <i class="bi bi-bar-chart"></i> Rapports & Statistiques--}}
{{--                    <i class="bi bi-chevron-down float-end"></i>--}}
{{--                </a>--}}
{{--                <ul class="submenu">--}}
{{--                    <li><a href="#" id="monthly-sales-link">Ventes mensuelles</a></li>--}}
{{--                    <li><a href="#" id="popular-books-link">Livres populaires</a></li>--}}
{{--                    <li><a href="#" id="category-stats-link">Statistiques par catégorie</a></li>--}}
{{--                </ul>--}}
{{--            </li>--}}

        </ul>
    </nav>

    <!-- Content -->
    <div class="content" id="content">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <button id="sidebarToggle" class="btn btn-sm btn-light">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex align-items-center">
                <div class="dropdown me-3 position-relative">
                    <button class="btn btn-light position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                            0
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><a class="dropdown-item" href="#"></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">Voir toutes les notifications</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="me-2 d-none d-md-block">
                            <div class="fw-bold">{{ Auth::user()->prenom." ".Auth::user()->nom }}</div>
                            <div class="small text-muted">{{ Auth::user()->hasRole('admin') ? 'Administrateur' : 'Utilisateur' }}</div>
                        </div>
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="bi bi-person"></i>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-lines-fill me-2"></i>Mon profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                            this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')

    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle Sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('content').classList.toggle('expanded');
    });

    // Toggle Submenu
    document.querySelectorAll('.has-submenu').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            const parentLi = item.parentElement;
            const submenu = parentLi.querySelector('.submenu');
            submenu.classList.toggle('show');
            const icon = item.querySelector('.bi-chevron-down');
            icon.classList.toggle('bi-chevron-up');
        });
    });

    document.querySelectorAll('.sidebar-menu a').forEach(link => {
        // Vérifier si le lien correspond à l'URL actuelle ou à l'ID stocké
        const activeMenuId = sessionStorage.getItem('activeMenuId');

        if (link.href === window.location.href || link.id === activeMenuId) {
            document.querySelectorAll('.sidebar-menu a').forEach(el => {
                el.classList.remove('active');
            });
            link.classList.add('active');
        }

        // Pour les liens réguliers (pas de sous-menu), ajouter un écouteur de clic
        if (!link.classList.contains('has-submenu')) {
            link.addEventListener('click', function(e) {
                // Stocker l'ID du menu actif
                sessionStorage.setItem('activeMenuId', this.id);
            });
        }
    });


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

    document.getElementById('bookImage').addEventListener('change', function(e) {
        const imagePreview = document.getElementById('imagePreview');

        // Nettoyer la prévisualisation précédente
        while (imagePreview.firstChild) {
            imagePreview.removeChild(imagePreview.firstChild);
        }

        // Créer une prévisualisation si un fichier est sélectionné
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                imagePreview.appendChild(img);
            }
            reader.readAsDataURL(e.target.files[0]);
        } else {
            // Restaurer l'icône par défaut si aucun fichier n'est sélectionné
            const icon = document.createElement('i');
            icon.className = 'bi bi-image';
            icon.style.fontSize = '3rem';
            icon.style.color = '#adb5bd';
            imagePreview.appendChild(icon);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const submenus = document.querySelectorAll('.has-submenu');

        // Restaurer l'état du menu ouvert
        const activeSubmenuId = sessionStorage.getItem('activeSubmenuId');

        submenus.forEach(submenuParent => {
            const submenu = submenuParent.nextElementSibling;
            const submenuLinks = submenu.querySelectorAll('a');

            // Vérifier si un lien du sous-menu est actif
            const hasActiveLink = Array.from(submenuLinks).some(link =>
                link.classList.contains('active') ||
                link.getAttribute('href') === window.location.pathname
            );

            // Vérifier si ce sous-menu correspond à l'ID stocké
            const isStoredActiveSubmenu = submenuParent.id === activeSubmenuId;

            if (hasActiveLink || isStoredActiveSubmenu) {
                // Ouvrir le sous-menu actif
                submenu.classList.add('show');

                // Inverser l'icône du chevron
                const icon = submenuParent.querySelector('.bi-chevron-down');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');

                // Stocker l'ID du sous-menu actif
                sessionStorage.setItem('activeSubmenuId', submenuParent.id);

                // Fermer les autres sous-menus
                submenus.forEach(otherParent => {
                    if (otherParent !== submenuParent) {
                        const otherSubmenu = otherParent.nextElementSibling;
                        otherSubmenu.classList.remove('show');

                        const otherIcon = otherParent.querySelector('.bi-chevron-down, .bi-chevron-up');
                        otherIcon.classList.remove('bi-chevron-up');
                        otherIcon.classList.add('bi-chevron-down');
                    }
                });
            }
        });

        // Ajouter des ID aux éléments has-submenu si ce n'est pas déjà fait
        submenus.forEach((submenuParent, index) => {
            if (!submenuParent.id) {
                submenuParent.id = `submenu-${index}`;
            }
        });
    });


</script>

</body>
</html>
