<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <title>Facture {{ $facture->num_fac }}</title>
    <style>

        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #343a40;
            padding-bottom: 20px;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo i {
            font-size: 24px;
            margin-right: 10px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .company-info, .client-info {
            width: 48%;
        }
        h1 {
            color: #343a40;
            font-size: 24px;
            margin-bottom: 20px;
        }
        h2 {
            color: #343a40;
            font-size: 18px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #343a40;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #6c757d;
        }
        .print-date {
            font-style: italic;
            font-size: 12px;
            margin-top: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">

            <span style="font-size: 28px; font-weight: bold;"><i class="bi bi-book"></i>BookStore</span>
        </div>
        <p>Votre librairie en ligne de confiance</p>
    </div>

    <h1>FACTURE N° {{ $facture->num_fac }}</h1>

    <div class="invoice-info ">
        <div class="company-info">
            <p><strong>BookStore</strong><br>
                123 Rue des Livres<br>
                75001 Dakar, Sénégal<br>
                Téléphone: +221 77 888 80 80<br>
                Email: contact@bookstore.sn
                </p>
        </div>

        <div class="client-info">
            <h2>CLIENT</h2>
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

    <div class="invoice-details">
        <p><strong>Date de facturation:</strong> {{ date('d/m/Y', strtotime($facture->date_facture)) }} |
            <strong>N° de commande:</strong> CMD-000{{ $order->id }} |
            <strong>Date de commande:</strong> {{ date('d/m/Y', strtotime($order->date_commande)) }} |
            <strong>Statut:</strong> {{ $order->statut }}</p>
    </div>

    <table>
        <thead>
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
                <td>{{ number_format($item->prix, 0, ',', ' ') }} </td>
                <td>{{ $item->quantite }}</td>
                <td>{{ number_format($item->prix * $item->quantite, 0, ',', ' ') }} </td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="4" style="text-align: right;">Total HT</td>
            <td>{{ number_format($order->total, 2, ',', ' ') }} CFA</td>
        </tr>
        <tr class="total-row">
            <td colspan="4" style="text-align: right;">TVA</td>
            <td>{{ number_format($order->total * 0.18, 2, ',', ' ') }} </td>
        </tr>
        <tr class="total-row">
            <td colspan="4" style="text-align: right;">Total TTC</td>
            <td>{{ number_format($order->total + ($order->total * 0.18), 2, ',', ' ') }} CFA</td>
        </tr>
        </tbody>
    </table>

    <div class="payment-info">
        <h2>MODALITÉS DE PAIEMENT</h2>
        <p>Paiement à la livraison</p>
    </div>

    <div class="footer">
        <p>Nous vous remercions pour votre commande.</p>
        <p>Pour toute question concernant cette facture, veuillez contacter notre service client.</p>
        <p>BookStore - 123 Rue des Livres, 75001 Dakar - www.bookstore.sn</p>
    </div>

    <div class="print-date">
        Document généré le {{ date('d/m/Y à H:i') }}
    </div>
</div>
</body>
</html>
