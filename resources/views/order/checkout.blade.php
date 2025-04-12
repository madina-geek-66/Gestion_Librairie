@extends('layout.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Récapitulatif de votre commande</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Livre</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\Cart::session(auth()->id())->getContent() as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total</td>
                                <td>{{ number_format(\Cart::session(auth()->id())->getTotal(), 0, ',', ' ') }} FCFA</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Finaliser la commande</h4>
                    </div>
                    <div class="card-body">
                        <p>Adresse de livraison: {{ auth()->user()->adresse }}</p>
                        <p>Email: {{ auth()->user()->email }}</p>
                        <p>Téléphone: {{ auth()->user()->telephone }}</p>

                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Confirmer la commande
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
