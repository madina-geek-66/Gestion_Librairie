<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
//    public function addToCart(Request $request, $livreId)
//    {
//        $livre = Livre::findOrFail($livreId);
//
//        // Vérifier si le livre est déjà dans le panier
//        $cartItem = Cart::where('user_id', Auth::id())
//            ->where('livre_id', $livreId)
//            ->first();
//
//        if ($cartItem) {
//            $cartItem->increment('quantity');
//        } else {
//            Cart::create([
//                'user_id' => Auth::id(),
//                'livre_id' => $livreId,
//                'quantity' => 1
//            ]);
//        }
//
//        return redirect()->route('cart.index')->with('success', 'Livre ajouté au panier !');
//    }

    public function addToCart($livreId)
    {
        $livre = Livre::findOrFail($livreId);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('livre_id', $livreId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'livre_id' => $livreId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Livre ajouté au panier !');
    }


    public function index()
    {
        $cartItems = Cart::with('livre')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', ['cartItems' => $cartItems]);
    }

//    public function updateQuantity(Request $request, $cartItemId)
//    {
//        $cartItem = Cart::findOrFail($cartItemId);
//        $cartItem->update(['quantity' => $request->quantity]);
//
//        return redirect()->route('cart.index')->with('success', 'Quantité mise à jour !');
//    }

    public function updateQuantity(Request $request, $cartItemId)
    {

        $cartItem = Cart::findOrFail($cartItemId);
        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Quantité mise à jour !');
    }


    public function removeFromCart($cartItemId)
    {
        $cartItem = Cart::findOrFail($cartItemId);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Livre retiré du panier.');
    }


    public function placeOrder(Request $request)
    {
        $selectedItems = $request->input('selected_items');

        if (!$selectedItems) {
            return redirect()->route('cart.index')->with('error', 'Veuillez sélectionner au moins un article.');
        }

        // Logique de commande à implémenter (exemple : stocker les articles commandés en base)

        return redirect()->route('cart.index')->with('success', 'Commande passée avec succès !');
    }
}
