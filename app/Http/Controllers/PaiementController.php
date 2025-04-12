<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // Vérifier si la commande est au statut "Expédiée"
        if ($order->statut !== 'Expédiée') {
            return redirect()->back()->with('error', 'Seules les commandes expédiées peuvent être payées.');
        }

        // Vérifier si la commande a déjà été payée
        if ($order->paiement) {
            return redirect()->back()->with('error', 'Cette commande a déjà été payée.');
        }

        // Calculer le montant avec TVA
        $tva = $order->total * 0.18;
        $montantTotal = $order->total + $tva;

        // Créer le paiement
        $paiement = new Paiement();
        $paiement->order_id = $order->id;
        $paiement->montant = $montantTotal;
        $paiement->date_paiement = now();
        $paiement->save();

        // Mettre à jour le statut de la commande
        $order->statut = 'Payée';
        $order->save();

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Accès non autorisé.');
        }

        $paiements = Paiement::with(['order', 'order.user'])
            ->orderBy('date_paiement', 'desc')
            ->paginate(15);

        return view('gestionnaire.paiement.index', compact('paiements'));
    }
}
