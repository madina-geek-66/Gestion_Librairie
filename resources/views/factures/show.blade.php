@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Facture N° {{ $facture->num_fac }}</h4>
                        <div>
                            <a href="{{ route('order.facture.download', $order->id) }}" class="btn btn-primary">
                                <i class="bi bi-download"></i> Télécharger PDF
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- En-tête de la facture -->
                        <div class="text-center mb-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <i class="bi bi-book fs-4 me-2"></i>
                                <span style="font-size: 24px; font-weight: bold;">BookStore</span>
                            </div>
                            <p>Votre librairie en ligne de confiance</p>
                        </div>

                        <div class="row mb-4">
                            <!-- Informations de l'entreprise -->
                            <div class="col-md-6">
                                <p><strong>BookStore</strong><br>
                                    123 Rue des Livres<br>
                                    75001 Dakar, Sénégal<br>
                                    Téléphone: +221 77 888 80 80<br>
                                    Email: contact@bookstore.sn
                                </p>
                            </div>

                            <!-- Informations du client -->
                            <div class="col-md-6">
                                <h5>CLIENT</h5>
                                <p><strong>{{ $user->prenom }} {{ $user->nom }}</strong><br>
                                    {{ $user->email }}<br>
                                    @if($user->adresse)
                                        {{ $user->adresse }}<br>
                                    @endif
                                    @if($user->telephone)
                                        Téléphone: {{ $user->telephone }}<br>
                                    @endif
                                    Client N° {{ $user->id }}</p>
                            </div>
                        </div>

                        <!-- Détails de la facture -->
                        <div class="mb-4">
                            <p>
                                <strong>Date de facturation:</strong> {{ date('d/m/Y', strtotime($facture->date_facture)) }} |
                                <strong>N° de commande:</strong> CMD-000{{ $order->id }} |
                                <strong>Date de commande:</strong> {{ date('d/m/Y', strtotime($order->date_commande)) }} |
                                <strong>Statut:</strong> {{ $order->statut }}
                            </p>
                        </div>

                        <!-- Tableau des articles -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Auteur</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->livre->titre }}</td>
                                        <td>{{ $item->livre->auteur->prenom." ".$item->livre->auteur->nom }}</td>
                                        <td>{{ number_format($item->prix, 0, ',', ' ') }} CFA</td>
                                        <td>{{ $item->quantite }}</td>
                                        <td>{{ number_format($item->prix * $item->quantite, 0, ',', ' ') }} CFA</td>
                                    </tr>
                                @endforeach
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-end">Total HT</td>
                                    <td>{{ number_format($order->total, 2, ',', ' ') }} CFA</td>
                                </tr>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-end">TVA (18%)</td>
                                    <td>{{ number_format($order->total * 0.18, 2, ',', ' ') }} CFA</td>
                                </tr>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-end">Total TTC</td>
                                    <td>{{ number_format($order->total + ($order->total * 0.18), 2, ',', ' ') }} CFA</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Modalités de paiement -->
                        <div class="mt-4">
                            <h5>MODALITÉS DE PAIEMENT</h5>
                            <p>Paiement à la livraison</p>
                        </div>

                        <!-- Pied de page -->
                        <div class="mt-5 text-center text-muted">
                            <p>Nous vous remercions pour votre commande.</p>
                            <p>Pour toute question concernant cette facture, veuillez contacter notre service client.</p>
                            <p>BookStore - 123 Rue des Livres, 75001 Dakar - www.bookstore.sn</p>
                            <p class="mt-3 text-end fst-italic small">Document généré le {{ date('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
