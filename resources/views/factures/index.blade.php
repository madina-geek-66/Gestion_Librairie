@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Mes Factures</h4>
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
                                Vous n'avez pas encore de factures.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>N° Facture</th>
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
                                                    <a href="{{ route('order.facture.download', $facture->order->id) }}" class="btn btn-sm btn-outline-success" title="Télécharger">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $factures->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
