@extends('gestionnaire.layout')
@section('title', 'Détail de la commande #' . $order->id)
@section('content')

    <div class="container-fluid">
        <!-- En-tête de la page -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Commande #{{ $order->id }}</h1>
            <a href="{{ route('gestion.orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Messages d'alerte -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Informations sur la commande -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informations sur la commande</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Commande #</th>
                                <td>{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    <form action="{{ route('gestion.orders.updateStatut', $order->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="input-group">
                                            <select class="form-select" name="statut">
                                                <option value="En attente" {{ $order->statut == 'En attente' ? 'selected' : '' }}>En attente</option>
                                                <option value="En préparation" {{ $order->statut == 'En préparation' ? 'selected' : '' }}>En préparation</option>
                                                <option value="Expédiée" {{ $order->statut == 'Expédiée' ? 'selected' : '' }}>Expédiée</option>
                                                <option value="Payée" {{ $order->statut == 'Payée' ? 'selected' : '' }}>Payée</option>
                                            </select>
                                            <button class="btn btn-primary" type="submit">Mettre à jour</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td class="fw-bold">{{ number_format($order->total, 0, ',', ' ') }} XOF</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Informations sur le client -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informations sur le client</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $order->user->prenom }} {{ $order->user->nom }}</h5>
                                <p class="text-muted mb-0">Client depuis {{ $order->user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <th><i class="bi bi-envelope me-2"></i>Email</th>
                                <td>{{ $order->user->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-telephone me-2"></i>Téléphone</th>
                                <td>{{ $order->user->telephone ?? 'Non spécifié' }}</td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-bag me-2"></i>Commandes</th>
                                <td>{{ $order->user->orders->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Adresse de livraison -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Adresse de livraison</h6>
                    </div>
                    <div class="card-body">
                        <address class="mb-0 mt-2">
                            <strong>{{ $order->user->prenom }} {{ $order->user->nom }}</strong><br>
                            {{ $order->user->adresse ?? 'Adresse non spécifiée' }}<br>
                            {{ $order->user->code_postal ?? '' }} {{ $order->user->ville ?? '' }}<br>
                            {{ $order->user->pays ?? 'Sénégal' }}<br>
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles de la commande -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Articles commandés</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Titre du livre</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="text-center" width="80">
                                    @if($item->livre->image)
                                        <img src="{{ asset('storage/' . $item->livre->image) }}" alt="{{ $item->livre->titre }}" class="book-thumbnail">
                                    @else
                                        <div class="book-thumbnail d-flex align-items-center justify-content-center bg-light">
                                            <i class="bi bi-book"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->livre->titre }}</strong>
                                    <div class="text-muted small">{{ $item->livre->auteur->prenom." ".$item->livre->auteur->nom }}</div>
                                </td>
                                <td>{{ number_format($item->prix, 0, ',', ' ') }} XOF</td>
                                <td>{{ $item->quantite }}</td>
                                <td>{{ number_format($item->prix * $item->quantite, 0, ',', ' ') }} XOF</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total</strong></td>
                            <td><strong>{{ number_format($order->total, 0, ',', ' ') }} XOF</strong></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Détails du paiement -->
        @if($order->paiement)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Détails du paiement</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Date de paiement</th>
                                    <td>{{ \Carbon\Carbon::parse($order->paiement->date_paiement)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Montant HT</th>
                                    <td>{{ number_format($order->total, 0, ',', ' ') }} XOF</td>
                                </tr>
                                <tr>
                                    <th>TVA (18%)</th>
                                    <td>{{ number_format($order->total * 0.18, 0, ',', ' ') }} XOF</td>
                                </tr>
                                <tr>
                                    <th>Montant total (TTC)</th>
                                    <td class="fw-bold">{{ number_format($order->paiement->montant, 0, ',', ' ') }} XOF</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <div class="text-center">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Paiement validé</h5>
                                    <p class="text-muted">Le paiement a été enregistré avec succès</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Chronologie de la commande -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Suivi de la commande</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $order->statut == 'En attente' || $order->statut == 'En préparation' || $order->statut == 'Expédiée' || $order->statut == 'Payée' ? 'active' : '' }}">
                        <div class="timeline-badge bg-primary"><i class="bi bi-cart"></i></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Commande reçue</h6>
                            <p class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $order->statut == 'En préparation' || $order->statut == 'Expédiée' || $order->statut == 'Payée' ? 'active' : '' }}">
                        <div class="timeline-badge {{ $order->statut == 'En préparation' || $order->statut == 'Expédiée' || $order->statut == 'Payée' ? 'bg-primary' : 'bg-secondary' }}"><i class="bi bi-box-seam"></i></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">En préparation</h6>
                            <p class="text-muted">{{ $order->statut == 'En préparation' ? $order->updated_at->format('d/m/Y H:i') : 'En attente' }}</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $order->statut == 'Expédiée' || $order->statut == 'Payée' ? 'active' : '' }}">
                        <div class="timeline-badge {{ $order->statut == 'Expédiée' || $order->statut == 'Payée' ? 'bg-primary' : 'bg-secondary' }}"><i class="bi bi-truck"></i></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Expédiée</h6>
                            <p class="text-muted">{{ $order->statut == 'Expédiée' || $order->statut == 'Payée' ? $order->updated_at->format('d/m/Y H:i') : 'En attente' }}</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $order->statut == 'Payée' ? 'active' : '' }}">
                        <div class="timeline-badge {{ $order->statut == 'Payée' ? 'bg-primary' : 'bg-secondary' }}"><i class="bi bi-check-circle"></i></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Payée</h6>
                            <p class="text-muted">{{ $order->statut == 'Payée' ? $order->updated_at->format('d/m/Y H:i') : 'En attente' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex justify-content-between mb-4">
            <div>
                @if($order->statut != 'Expédiée' && $order->statut != 'Payée')
                    <form action="{{ route('gestion.orders.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle"></i> Annuler la commande
                        </button>
                    </form>
                @endif
            </div>
            <div>
                @if($order->statut === 'Expédiée' && !$order->paiement)
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paiementModal">
                        <i class="bi bi-cash"></i> Enregistrer le paiement
                    </button>
                @endif
                @if($order->statut === 'Expédiée' || $order->statut === 'Payée')
                    <a href="{{ route('gestion.factures.show', $order->id) }}" class="btn btn-primary">
                        <i class="bi bi-eye"></i> Voir la facture
                    </a>
                @endif


{{--                <a href="#" class="btn btn-primary me-2">--}}
{{--                    <i class="bi bi-printer"></i> Imprimer la facture--}}
{{--                </a>--}}
{{--                <a href="#" class="btn btn-outline-primary">--}}
{{--                    <i class="bi bi-envelope"></i> Contacter le client--}}
{{--                </a>--}}
            </div>
        </div>
    </div>

    <!-- Modal Paiement -->
    <div class="modal fade" id="paiementModal" tabindex="-1" aria-labelledby="paiementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('gestion.paiement.store', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="paiementModalLabel">Enregistrer le paiement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="montant" class="form-label">Montant HT</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="montant" value="{{ number_format($order->total, 0, ',', ' ') }}" readonly>
                                <span class="input-group-text">XOF</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tva" class="form-label">TVA (18%)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tva" value="{{ number_format($order->total * 0.18, 0, ',', ' ') }}" readonly>
                                <span class="input-group-text">XOF</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="totalTTC" class="form-label">Montant total (TTC)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="totalTTC" value="{{ number_format($order->total * 1.18, 0, ',', ' ') }}" readonly>
                                <span class="input-group-text">XOF</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="date_paiement" class="form-label">Date de paiement</label>
                            <input type="text" class="form-control" id="date_paiement" value="{{ now()->format('d/m/Y') }}" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Enregistrer le paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        .timeline:before {
            content: '';
            position: absolute;
            height: 100%;
            width: 2px;
            background: #e9ecef;
            left: 25px;
            top: 0;
        }
        .timeline-item {
            display: flex;
            margin-bottom: 30px;
        }
        .timeline-badge {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            z-index: 1;
            color: white;
        }
        .timeline-content {
            padding-top: 5px;
        }
        .timeline-title {
            margin: 0;
            font-weight: 600;
        }
    </style>

@endsection
