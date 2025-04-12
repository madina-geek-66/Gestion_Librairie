<?php

namespace App\Services;

use App\Models\Facture;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureService
{
    public function generateFacture(Order $order)
    {
        // Vérifier si une facture existe déjà pour cette commande
        if ($order->facture) {
            return $order->facture;
        }

        // Créer une nouvelle facture
        $facture = Facture::create([
            'order_id' => $order->id,
            'date_facture' => now(),
            'montant_total' => $order->total,
        ]);

        return $facture;
    }

    public function generatePDF(Facture $facture)
    {
        $order = $facture->order;
        $user = $order->user;
        $items = $order->items;

        $pdf = PDF::loadView('factures.template', [
            'facture' => $facture,
            'order' => $order,
            'user' => $user,
            'items' => $items
        ]);

        return $pdf;
    }
}
