@extends('gestionnaire.layout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Liste des Factures</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($factures->isEmpty())
                            <div class="alert alert-info">
                                Aucune facture n'a été générée.
                            </div>
                        @else
                            <!-- Filtres de recherche -->
                            <div class="mb-3">
                                <form action="{{ route('gestion.factures.index') }}" method="GET" class="row g-3">
                                    <div class="col-md-3">
                                        <input type="text" name="facture" class="form-control" placeholder="N° Facture" value="{{ request('facture') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="client" class="form-control" placeholder="Nom client" value="{{ request('client') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="statut" class="form-select">
                                            <option value="">Tous les statuts</option>
                                            <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                                            <option value="En préparation" {{ request('statut') == 'En préparation' ? 'selected' : '' }}>En préparation</option>
                                            <option value="Expédiée" {{ request('statut') == 'Expédiée' ? 'selected' : '' }}>Expédiée</option>
                                            <option value="Payée" {{ request('statut') == 'Payée' ? 'selected' : '' }}>Payée</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="bi bi-search"></i> Filtrer
                                        </button>
                                        <a href="{{ route('gestion.factures.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle"></i> Réinitialiser
                                        </a>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>Client</th>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Montant TTC</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($factures as $facture)
                                        <tr>
                                            <td>{{ $facture->num_fac }}</td>
                                            <td>{{ $facture->order->user->prenom }} {{ $facture->order->user->nom }}</td>
                                            <td>CMD-000{{ $facture->order->id }}</td>
                                            <td>{{ date('d/m/Y', strtotime($facture->date_facture)) }}</td>
                                            <td>{{ number_format($facture->order->total + ($facture->order->total * 0.18), 2, ',', ' ') }} CFA</td>
                                            <td>
                                                <span class="badge bg-{{ $facture->order->statut === 'Payée' ? 'success' : ($facture->order->statut === 'Expédiée' ? 'info' : 'warning') }}">
                                                    {{ $facture->order->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('facture.show', $facture->order->id) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('order.downloadInvoice', $facture->order->id) }}" class="btn btn-sm btn-outline-success" title="Télécharger">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    @if($facture->order->statut !== 'Payée')
                                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal-{{ $facture->order->id }}" title="Modifier le statut">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Modal pour modifier le statut -->
                                                <div class="modal fade" id="updateStatusModal-{{ $facture->order->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Modifier le statut de la commande</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('order.updateStatut', $facture->order->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="statut" class="form-label">Statut:</label>
                                                                        <select name="statut" id="statut" class="form-select" required>
                                                                            <option value="En attente" {{ $facture->order->statut === 'En attente' ? 'selected' : '' }}>En attente</option>
                                                                            <option value="En préparation" {{ $facture->order->statut === 'En préparation' ? 'selected' : '' }}>En préparation</option>
                                                                            <option value="Expédiée" {{ $facture->order->statut === 'Expédiée' ? 'selected' : '' }}>Expédiée</option>
                                                                            <option value="Payée" {{ $facture->order->statut === 'Payée' ? 'selected' : '' }}>Payée</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $factures->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
