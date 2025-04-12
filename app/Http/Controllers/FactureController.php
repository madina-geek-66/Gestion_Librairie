<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Order;
use App\Services\FactureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    protected $factureService;

    public function __construct(FactureService $factureService)
    {
        $this->factureService = $factureService;
    }


    public function show(Order $order)
    {
        // Vérifier les permissions d'accès
        if (Auth::user()->id !== $order->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('orders.index')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
        }

        // Récupérer la facture ou la générer si elle n'existe pas encore
        $facture = $order->facture;

        if (!$facture) {
            // Si l'utilisateur est un admin ou si la commande est expédiée/payée, on peut générer la facture
            if (Auth::user()->hasRole('admin') || in_array($order->statut, ['Expédiée', 'Payée'])) {
                $facture = $this->factureService->generateFacture($order);
            } else {
                return redirect()->back()
                    ->with('error', 'La facture n\'est pas encore disponible pour cette commande.');
            }
        }

        // Récupérer les informations nécessaires pour la vue
        $user = $order->user;
        $items = $order->items;

        return view('factures.show', compact('facture', 'order', 'user', 'items'));
    }

    public function showAdmin(Order $order)
    {
        // Vérifier les permissions d'accès
        if (Auth::user()->id !== $order->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('home')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
        }

        $facture = $order->facture;

        if (!$facture) {
            // Si l'utilisateur est un admin ou si la commande est expédiée/payée, on peut générer la facture
            if (Auth::user()->hasRole('admin') || in_array($order->statut, ['Expédiée', 'Payée'])) {
                $facture = $this->factureService->generateFacture($order);
            } else {
                return redirect()->back()
                    ->with('error', 'La facture n\'est pas encore disponible pour cette commande.');
            }
        }

        // Récupérer les informations nécessaires pour la vue
        $user = $order->user;
        $items = $order->items;

        return view('gestionnaire.factures.show', compact('facture', 'order', 'user', 'items'));
    }

    public function index()
    {
        $user = Auth::user();

        // On montre uniquement les factures de l'utilisateur connecté
        $factures = Facture::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with('order')
            ->orderBy('date_facture', 'desc')
            ->paginate(10);

        return view('factures.index', compact('factures'));
    }


    public function adminIndex(Request $request)
    {
        // Vérifier que l'utilisateur est bien admin
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('facture.index')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        }

        $query = Facture::with('order.user')->orderBy('date_facture', 'desc');

        // Appliquer les filtres
        if ($request->has('facture') && !empty($request->facture)) {
            $query->where('num_fac', 'like', '%' . $request->facture . '%');
        }

        if ($request->has('client') && !empty($request->client)) {
            $search = $request->client;
            $query->whereHas('order.user', function ($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('prenom', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('statut') && !empty($request->statut)) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('statut', $request->statut);
            });
        }

        $factures = $query->paginate(10);

        return view('gestionnaire.factures.index', compact('factures'));
    }

}
