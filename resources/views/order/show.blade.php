@extends('layout.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-bag me-2"></i>Détails de votre commande #CMD-000{{ $order->id }}</h5>
                    <span class="badge
                        @if($order->statut == "En attente") bg-info text-dark
                        @elseif($order->statut == "En préparation") bg-warning text-dark
                        @elseif($order->statut == "Expédiée") bg-info text-white
                        @elseif($order->statut == "Payée") bg-success text-white
                        @else bg-danger text-white
                        @endif">
                        {{ $order->statut }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Informations de commande</h6>
                        <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->date_commande)->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Numéro de commande:</strong> CMD-000{{ $order->id }}</p>
                        <p class="mb-1"><strong>Paiement:</strong> En espèce</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Adresse de livraison</h6>
                        <p class="mb-1">{{ $order->user->prenom." ".$order->user->nom }}</p>
                        <p class="mb-1">{{ $order->user->adresse ?? 'Adresse non spécifiée' }}</p>
                    </div>
                </div>

                <h6 class="fw-bold">Articles commandés</h6>
                <div class="table-responsive mb-4">
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
                <div class="d-flex justify-content-between timeline mb-4">
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

                <div class="d-flex justify-content-between">
                    <a href="{{ route('order.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Retour à mes commandes
                    </a>

                    @if($order->statut != 'Annulée' && $order->statut != 'Payée')
                        <form action="{{ route('order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle me-1"></i>Annuler la commande
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
