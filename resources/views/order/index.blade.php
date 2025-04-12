@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="tab-pane fade show active" id="orders" role="tabpanel">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-bag me-2"></i>Mes commandes</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm active">Toutes</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">En cours</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">Terminées</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover order-list">
                            <thead class="table-light">
                            <tr>
                                <th>Commande #</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Articles</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr data-bs-toggle="modal" data-bs-target="#orderDetailsModal">
                                    <td><strong>CMD-000{{ $order->id }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y') }}</td>
                                    <td> {{ number_format($order->total, 0, ',', ' ') }}</td>
                                    <td>{{ $order->items->sum('quantite') }} article(s)</td>
                                    <td>
                                        @if($order->statut == "En attente")
                                            <span class="order-status bg-info text-dark">En attente</span>
                                        @elseif($order->statut == "En préparation")
                                            <span class="order-status bg-warning text-dark">En préparation</span>
                                        @elseif($order->statut == "Expédiée")
                                            <span class="order-status bg-info text-white">Expédiée</span>
                                        @elseif($order->statut == "Payée")
                                            <span class="order-status bg-success text-white">Payée</span>
                                        @else
                                            <span class="order-status bg-danger text-white">Annuler</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderDetailsModal{{ $order->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
{{--                                        <button class="btn btn-sm btn-outline-primary">--}}
{{--                                            <i class="bi bi-eye"></i>--}}
{{--                                        </button>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        @foreach($orders as $order)
            <div class="modal fade" id="orderDetailsModal{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Détails de la commande #CMD-000{{ $order->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Informations de commande</h6>
                                    <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y') }}</p>
                                    <p class="mb-1"><strong>Statut:</strong>
                                        @if($order->statut == "En attente")
                                            <span class="badge bg-gray-100 text-dark">En attente</span>
                                        @elseif($order->statut == "En préparation")
                                            <span class="badge bg-warning text-dark">En préparation</span>
                                        @elseif($order->statut == "Expédiée")
                                            <span class="badge bg-info text-white">Expédiée</span>
                                        @elseif($order->statut == "Payée")
                                            <span class="badge bg-success text-white">Payée</span>
                                        @else
                                            <span class="badge bg-danger text-white">Annulée</span>
                                        @endif
                                    </p>
                                    <p class="mb-1"><strong>Paiement:</strong> En espèce</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Adresse de livraison</h6>
                                    <p class="mb-1">{{ $order->user->prenom." ".$order->user->nom }}</p>
                                    <p class="mb-1">{{ $order->user->adresse ?? 'Adresse non spécifiée' }}</p>
                                </div>
                            </div>

                            <h6 class="fw-bold">Articles commandés</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">Prix unitaire</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $subtotal = 0; @endphp
                                    @foreach($order->items as $item)
                                        @php
                                            $itemTotal = $item->prix * $item->quantite;
                                            $subtotal += $itemTotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/' . $item->livre->image) }}" class="me-3" alt="Livre" style="width:50px;height:70px;object-fit:cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->livre->titre ?? 'Livre non disponible' }}</h6>
                                                        <small class="text-muted">{{ $item->livre->auteur->prenom." ".$item->livre->auteur->nom ?? 'Auteur inconnu' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantite }}</td>
                                            <td class="text-end">{{ number_format($item->prix, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($itemTotal, 0, ',', ' ') }} </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                        <td class="text-end fw-bold">{{ number_format($order->total, 0, ',', ' ') }} XOF</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <h6 class="fw-bold">Suivi de commande</h6>
                            <div class="d-flex justify-content-between timeline">
                                <div class="text-center">
                                    <div class="rounded-circle {{ $order->created_at ? 'bg-success text-white' : 'bg-light text-dark' }} d-flex align-items-center justify-content-center mx-auto mb-2" style="width:40px;height:40px;">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <p class="small">Commande reçue</p>
                                    <p class="small text-muted">{{ $order->created_at ? $order->created_at->format('d/m H:i') : '-' }}</p>
                                </div>

                                <div class="text-center">
                                    <div class="rounded-circle {{ $order->statut == 'En préparation' ? 'bg-warning text-dark' : ($order->statut == 'Expédiée' || $order->statut == 'Payée' ? 'bg-success text-white' : 'bg-light text-dark') }} d-flex align-items-center justify-content-center mx-auto mb-2" style="width:40px;height:40px;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <p class="small">En préparation</p>
                                    <p class="small text-muted">{{ $order->updated_at && ($order->statut == 'En préparation' || $order->statut == 'Expédiée' || $order->statut == 'Payée') ? $order->updated_at->format('d/m H:i') : '-' }}</p>
                                </div>
                                <div class="text-center">
                                    <div class="rounded-circle {{ $order->statut == 'Expédiée' || $order->statut == 'Payée' ? 'bg-success text-white' : 'bg-light text-dark' }} d-flex align-items-center justify-content-center mx-auto mb-2" style="width:40px;height:40px;">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <p class="small">Expédiée</p>
                                    <p class="small text-muted">{{ $order->updated_at && ($order->statut == 'Expédiée' || $order->statut == 'Payée') ? $order->updated_at->format('d/m H:i') : '-' }}</p>
                                </div>

                                <div class="text-center">
                                    <div class="rounded-circle {{ $order->statut == 'Payée' ? 'bg-success text-white' : 'bg-light text-dark' }} d-flex align-items-center justify-content-center mx-auto mb-2" style="width:40px;height:40px;">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <p class="small">Paiement confirmé</p>
                                    <p class="small text-muted">{{ $order->created_at && $order->statut == 'Payée' ? $order->created_at->format('d/m H:i') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
    </div>
@endsection
