<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Paiement;
use App\Models\User;
use App\Notifications\InvoiceNotification;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderConfirmation;
use App\Services\FactureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    protected $factureService;

    public function __construct(FactureService $factureService)
    {
        $this->factureService = $factureService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.index', ["orders" => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Veuillez sélectionner au moins un article.');
        }

        // Créer la commande
        $total = 0;
        $cartItems = Cart::whereIn('id', $selectedItems)
            ->with('livre')
            ->where('user_id', Auth::id())
            ->get();

        // Vérifier le stock disponible
        foreach ($cartItems as $cartItem) {
            if ($cartItem->livre->qte_stock < $cartItem->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stock insuffisant pour {$cartItem->livre->titre}. Disponible: {$cartItem->livre->qte_stock}");
            }

            $total += $cartItem->livre->prix * $cartItem->quantity;
        }

        // Créer la commande
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'statut' => 'En attente',
            'date_commande' => now()
        ]);

        // Créer les éléments de commande et mettre à jour le stock
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'livre_id' => $cartItem->livre_id,
                'quantite' => $cartItem->quantity,
                'prix' => $cartItem->livre->prix
            ]);


            $livre = $cartItem->livre;
            $livre->qte_stock -= $cartItem->quantity;
            $livre->save();


            $cartItem->delete();
        }


        $user = Auth::user();
        $user->notify(new OrderConfirmation($order));

        // Notifier le gestionnaire
        //$admins = User::hasRole('admin')->get();
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();
        Notification::send($admins, new NewOrderNotification($order));

        return redirect()->route('order.show', $order->id)
            ->with('success', 'Votre commande a été passée avec succès!');
    }

    public function adminIndex()
    {

        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('gestionnaire.orders.index', compact('orders'));
    }

    public function pendingOrders(Request $request)
    {
        $query = Order::with('user')
            ->where('statut', 'En attente');

        // Appliquer la recherche si elle est fournie
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                        $userQuery->where('nom', 'like', "%{$searchTerm}%")
                            ->orWhere('prenom', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('gestionnaire.orders.pending', compact('orders'));
    }

    /**
     * Affiche les commandes payées
     */
    public function paidOrders(Request $request)
    {
        $query = Order::with('user')
            ->where('statut', 'Payée');

        // Appliquer la recherche si elle est fournie
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                        $userQuery->where('nom', 'like', "%{$searchTerm}%")
                            ->orWhere('prenom', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('gestionnaire.orders.paid', compact('orders'));
    }

    public function adminShow(Order $order)
    {
        return view('gestionnaire.orders.show', compact('order'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if (Auth::user()->id !== $order->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('orders.index')->with('error', 'Vous n\'êtes pas autorisé à voir cette commande.');
        }

        return view('order.show', compact('order'));
    }

    public function updateStatut(Request $request, Order $order)
    {
        $request->validate([
            'statut' => 'required|in:En attente,En préparation,Expédiée,Payée,Annulée'
        ]);

        $oldStatus = $order->statut;
        $newStatus = $request->statut;

        if ($oldStatus === 'Payée' && $newStatus !== 'Payée') {
            return redirect()->back()->with('error', 'Une commande payée ne peut pas retourner à un statut antérieur.');
        }

        $order->statut = $newStatus;
        $order->save();


        if ($newStatus === 'Expédiée' && $oldStatus !== 'Expédiée') {
            $this->sendInvoice($order);
        }

//        if ($newStatus === 'Payée' && $oldStatus !== 'Payée') {
//            $paiement = new Paiement();
//            $paiement->date_paiement = now();
//            $paiement->montant = $order->montant;
//            $paiement->save();
//        }

        return redirect()->back()->with('success', 'Statut de la commande mis à jour.');
    }

    protected function sendInvoice(Order $order)
    {

        $facture = $this->factureService->generateFacture($order);

        $order->user->notify(new InvoiceNotification($order, $facture));

        return $facture;
    }

    /**
     * Télécharger la facture
     */
    public function downloadInvoice(Order $order)
    {
        // Vérifier les autorisations
        if (Auth::user()->id !== $order->user_id && !Auth::user()->hasRole('admin')) {
            return redirect()->route('orders.index')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette facture.');
        }

        // Vérifier si une facture existe déjà
        $facture = $order->facture;

        if (!$facture) {
            // Si pas de facture et que la commande n'est pas expédiée, rediriger
            if ($order->statut !== 'Expédiée' && $order->statut !== 'Payée') {
                return redirect()->back()->with('error', 'La facture n\'est pas encore disponible.');
            }

            // Créer la facture si elle n'existe pas
            $facture = $this->factureService->generateFacture($order);
        }

        // Générer le PDF
        $pdf = $this->factureService->generatePDF($facture);

        return $pdf->download('facture-' . $facture->num_fac . '.pdf');
    }

    public function cancel(Order $order)
    {
        // Vérifier que la commande peut être annulée
        if ($order->statut === 'Expédiée' || $order->statut === 'Payée') {
            return redirect()->back()
                ->with('error', 'Impossible d\'annuler une commande expédiée ou payée.');
        }

        // Remettre les produits en stock
        foreach ($order->items as $item) {
            $livre = $item->livre;
            $livre->stock += $item->quantite;
            $livre->save();
        }

        // Supprimer la commande
        $order->delete();

        return redirect()->route('gestion.orders.index')
            ->with('success', 'Commande annulée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
