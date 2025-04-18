@extends('gestionnaire.layout')
@section('title', 'Commandes payées')
@section('content')

    <div class="container-fluid">
        <!-- En-tête de la page -->
        <div class="page-header">
            <h1 class="h3 mb-0 text-gray-800">Commandes payées</h1>
        </div>

        <!-- Messages d'alerte -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filtres de commandes -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Filtrer les commandes payées</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <form class="d-flex gap-2" action="{{ route('gestion.orders.paid') }}" method="GET">
                            <div class="form-group flex-grow-1 mb-0">
                                <input type="text" class="form-control" name="search" placeholder="Rechercher par ID ou client" value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="{{ route('gestion.orders.paid') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                        </form>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Exporter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des commandes payées</h6>
                <span class="badge bg-primary">{{ $orders->total() }} commandes</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Paiement</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->nom }} {{ $order->user->prenom }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ number_format($order->total, 0, ',', ' ') }} XOF</td>
                                <td>
                                    @if($order->paiement)
                                        <span class="badge bg-success">Payée le {{ \Carbon\Carbon::parse($order->paiement->date_paiement)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="badge bg-success">Payée</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('gestion.orders.show', $order->id) }}" class="btn btn-sm btn-view action-btn">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('gestion.factures.show', $order->id) }}" class="btn btn-sm btn-info action-btn">
                                            <i class="bi bi-file-text"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucune commande payée trouvée</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
