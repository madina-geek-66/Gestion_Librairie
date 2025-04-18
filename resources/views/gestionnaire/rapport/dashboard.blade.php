@extends('gestionnaire.layout')

@section('styles')
    <style>
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .border-left-primary, .border-left-success, .border-left-info, .border-left-warning {
            border-left: 4px solid;
        }

        .border-left-primary {
            border-color: #4e73df;
        }

        .border-left-success {
            border-color: #1cc88a;
        }

        .border-left-info {
            border-color: #36b9cc;
        }

        .border-left-warning {
            border-color: #f6c23e;
        }

        .card-metrics .card-body {
            padding: 1.25rem;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .filter-form {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
        }

        /* Animation pour les graphiques */
        .chart-container {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
        }

        .chart-container:nth-child(1) { animation-delay: 0.1s; }
        .chart-container:nth-child(2) { animation-delay: 0.2s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation pour les cartes statistiques */
        .card-metrics {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        .card-metrics:nth-child(1) { animation-delay: 0.1s; }
        .card-metrics:nth-child(2) { animation-delay: 0.2s; }
        .card-metrics:nth-child(3) { animation-delay: 0.3s; }
        .card-metrics:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeIn {
            to { opacity: 1; }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
            <div>
                <a href="#" class="btn btn-sm btn-primary shadow-sm" id="downloadReport">
                    <i class="bi bi-download me-1"></i> Télécharger le rapport
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-form">
            <form action="{{ route('gestion.dashboard') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="periode" class="form-label">Période</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="day" {{ $periode == 'day' ? 'selected' : '' }}>Jour</option>
                            <option value="month" {{ $periode == 'month' ? 'selected' : '' }}>Mois</option>
                            <option value="year" {{ $periode == 'year' ? 'selected' : '' }}>Année</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="anneeContainer">
                        <label for="annee" class="form-label">Année</label>
                        <select class="form-select" id="annee" name="annee">
                            @foreach($annees as $a)
                                <option value="{{ $a }}" {{ $annee == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3" id="moisContainer" {{ $periode == 'day' ? 'style=display:none' : '' }}>
                        <label for="mois" class="form-label">Mois</label>
                        <select class="form-select" id="mois" name="mois">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $mois == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="{{ route('gestion.dashboard') }}" class="btn btn-secondary">Réinitialiser</a>
                    </div>
                </div>
            </form>
        </div><br><br>

        <!-- Statistiques en cartes -->
        <div class="row mb-4">
            <!-- Commandes en cours -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 card-metrics">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="stat-icon bg-primary text-white">
                                    <i class="bi bi-cart"></i>
                                </div>
                            </div>
                            <div class="col ms-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Commandes</div>
                                <div class="h4 mb-0 font-weight-bold">{{ $commandesJour }}</div>
                                <div class="mt-2 mb-0 text-sm">
                                    @if($pourcentageCommandes > 0)
                                        <span class="text-success me-2">
                                            <i class="bi bi-arrow-up"></i> {{ $pourcentageCommandes }}%
                                        </span>
                                    @else
                                        <span class="text-danger me-2">
                                            <i class="bi bi-arrow-down"></i> {{ abs($pourcentageCommandes) }}%
                                        </span>
                                    @endif
                                    <span class="text-muted">vs période précédente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commandes validées -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2 card-metrics">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="stat-icon bg-success text-white">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                            <div class="col ms-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Commandes validées</div>
                                <div class="h4 mb-0 font-weight-bold">{{ $commandesValidees }}</div>
                                <div class="mt-2 mb-0 text-sm">
                                    @if($pourcentageValidees > 0)
                                        <span class="text-success me-2">
                                            <i class="bi bi-arrow-up"></i> {{ $pourcentageValidees }}%
                                        </span>
                                    @else
                                        <span class="text-danger me-2">
                                            <i class="bi bi-arrow-down"></i> {{ abs($pourcentageValidees) }}%
                                        </span>
                                    @endif
                                    <span class="text-muted">vs période précédente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recettes -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2 card-metrics">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="stat-icon bg-info text-white">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                            </div>
                            <div class="col ms-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Recettes</div>
                                <div class="h4 mb-0 font-weight-bold">{{ number_format($recettesJour, 0, ',', ' ') }} XOF</div>
                                <div class="mt-2 mb-0 text-sm">
                                    @if($pourcentageRecettes > 0)
                                        <span class="text-success me-2">
                                            <i class="bi bi-arrow-up"></i> {{ $pourcentageRecettes }}%
                                        </span>
                                    @else
                                        <span class="text-danger me-2">
                                            <i class="bi bi-arrow-down"></i> {{ abs($pourcentageRecettes) }}%
                                        </span>
                                    @endif
                                    <span class="text-muted">vs période précédente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livres vendus -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2 card-metrics">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="stat-icon bg-warning text-white">
                                    <i class="bi bi-book"></i>
                                </div>
                            </div>
                            <div class="col ms-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Livres vendus</div>
                                <div class="h4 mb-0 font-weight-bold">{{ $livresVendus }}</div>
                                <div class="mt-2 mb-0 text-sm">
                                    @if($pourcentageLivres > 0)
                                        <span class="text-success me-2">
                                            <i class="bi bi-arrow-up"></i> {{ $pourcentageLivres }}%
                                        </span>
                                    @else
                                        <span class="text-danger me-2">
                                            <i class="bi bi-arrow-down"></i> {{ abs($pourcentageLivres) }}%
                                        </span>
                                    @endif
                                    <span class="text-muted">vs période précédente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques - Première rangée -->
        <div class="row">
            <!-- Nombre de commandes par mois -->
            <div class="col-lg-8 mb-4 chart-container">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Ventes mensuelles {{ $annee }}</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="commandesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Livres vendus par catégorie -->
            <div class="col-lg-4 mb-4 chart-container">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ventes par catégorie</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="categoriesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques - Deuxième rangée -->
        <div class="row">
            <!-- Revenus journaliers du mois -->
            @if($periode === 'month')
                <div class="col-lg-8 mb-4 chart-container">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Revenus journaliers - {{ date('F Y', mktime(0, 0, 0, $mois, 1, $annee)) }}</h6>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px;">
                                <canvas id="revenusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Top 5 des livres vendus -->
            <div class="col-lg-{{ $periode === 'month' ? '4' : '12' }} mb-4 chart-container">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Top livres vendus</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th class="text-end">Quantité</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($topLivres as $livre)
                                    <tr>
                                        <td>{{ $livre->titre }}</td>
                                        <td class="text-end">{{ $livre->total_vendu }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Aucune vente pour cette période</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes récentes -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Commandes récentes</h6>
                        <a href="{{ route('gestion.orders.index') }}" class="btn btn-sm btn-primary">Voir toutes</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($commandesRecentes as $commande)
                                    <tr>
                                        <td>#{{ $commande->id }}</td>
                                        <td>{{ $commande->user->nom }} {{ $commande->user->prenom }}</td>
                                        <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format($commande->total, 0, ',', ' ') }} XOF</td>
                                        <td>
                                            @if($commande->statut == 'En attente')
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @elseif($commande->statut == 'En préparation')
                                                <span class="badge bg-primary">En préparation</span>
                                            @elseif($commande->statut == 'Expédiée')
                                                <span class="badge bg-info">Expédiée</span>
                                            @elseif($commande->statut == 'Payée')
                                                <span class="badge bg-success">Payée</span>
                                            @elseif($commande->statut == 'Annulée')
                                                <span class="badge bg-danger">Annulée</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('gestion.orders.show', $commande->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune commande récente</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des filtres
            const periodeSelect = document.getElementById('periode');
            const moisContainer = document.getElementById('moisContainer');

            periodeSelect.addEventListener('change', function() {
                if (this.value === 'year') {
                    moisContainer.style.display = 'none';
                } else if (this.value === 'month') {
                    moisContainer.style.display = 'block';
                } else {
                    moisContainer.style.display = 'none';
                }
            });

            // Graphique des commandes mensuelles
            const commandesCtx = document.getElementById('commandesChart').getContext('2d');
            const commandesChart = new Chart(commandesCtx, {
                type: 'bar',
                data: {
                    labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                    datasets: [{
                        label: 'Nombre de commandes',
                        data: @json($commandesParMois),
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' commande(s)';
                                }
                            }
                        }
                    }
                }
            });

            // Graphique des ventes par catégorie
            const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
            const categoriesChart = new Chart(categoriesCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($ventesParCategorie['labels']),
                    datasets: [{
                        data: @json($ventesParCategorie['data']),
                        backgroundColor: @json($ventesParCategorie['colors']),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return context.label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Graphique des revenus journaliers (seulement si période = mois)
            @if($periode === 'month')
            const revenusCtx = document.getElementById('revenusChart').getContext('2d');
            const revenusChart = new Chart(revenusCtx, {
                type: 'line',
                data: {
                    labels: @json($revenusParJour['labels']),
                    datasets: [{
                        label: 'Revenus (XOF)',
                        data: @json($revenusParJour['data']),
                        backgroundColor: 'rgba(54, 185, 204, 0.1)',
                        borderColor: 'rgba(54, 185, 204, 1)',
                        pointBackgroundColor: 'rgba(54, 185, 204, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(54, 185, 204, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' XOF';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toLocaleString() + ' XOF';
                                }
                            }
                        }
                    }
                }
            });
            @endif

            // Fonctionnalité de téléchargement du rapport
            document.getElementById('downloadReport').addEventListener('click', function(e) {
                e.preventDefault();

                const options = {
                    margin: 10,
                    filename: 'rapport-librairie.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                // Créer une copie du contenu pour le PDF
                const content = document.querySelector('.container-fluid').cloneNode(true);

                // Masquer les éléments inutiles pour le PDF
                const elementsToRemove = content.querySelectorAll('button, select, .filter-form, #downloadReport');
                elementsToRemove.forEach(el => el.style.display = 'none');

                // Créer un conteneur pour le PDF
                const pdfContainer = document.createElement('div');
                pdfContainer.style.padding = '20px';
                pdfContainer.appendChild(content);

                // Ajouter un titre et une date au rapport
                const title = document.createElement('div');
                title.innerHTML = `
                    <h1 style="text-align: center; margin-bottom: 20px;">Rapport de la Librairie</h1>
                    <p style="text-align: center; margin-bottom: 30px;">Généré le ${new Date().toLocaleDateString()}</p>
                `;
                pdfContainer.insertBefore(title, pdfContainer.firstChild);

                // Générer le PDF
                html2pdf().from(pdfContainer).set(options).save();
            });
        });
    </script>
{{--@endsection--}}

