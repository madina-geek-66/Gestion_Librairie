<?php

use App\Http\Controllers\AuteurController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/adminboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('adminboard');

Route::middleware('auth')->prefix('gestionnaire')->name('gestion.')->group(function () {
    Route::resource('categorie', CategorieController::class);
    Route::resource('livre', LivreController::class);
    Route::resource('auteur', AuteurController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/gestion/livres/archives', [LivreController::class, 'archived'])->name('gestion.livre.archived');
    Route::put('/gestion/livres/{livre}/archive', [LivreController::class, 'archive'])->name('gestion.livre.archive');
    Route::put('/gestion/livres/{livre}/restore', [LivreController::class, 'restore'])->name('gestion.livre.restore');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('gestion.dashboard');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/catalogue', [App\Http\Controllers\CatalogueController::class, 'index'])->name('app.catalogue.index');
    Route::get('/livre/{id}', [App\Http\Controllers\CatalogueController::class, 'show'])->name('app.livre.details');
});



Route::get('/app', function () {
    return view('layout.app');
});


Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/logintest', function () {
    return view('test.login');
});

Route::get('/registertest', function () {
    return view('test.register');
});

//Route::middleware(['auth'])->group(function () {
//    Route::post('/cart/add/{livreId}', [CartController::class, 'addToCart'])->name('cart.add');
//    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
//    Route::post('/cart/update/{cartItemId}', [CartController::class, 'updateQuantity'])->name('cart.update');
//    Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
//});

Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add/{livreId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{cartItemId}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    //Route::post('/cart/order', [CartController::class, 'placeOrder'])->name('cart.order');
});

//Route::middleware(['auth'])->group(function () {
//    Route::resource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
//});

Route::middleware(['auth'])->group(function () {
    // Routes pour les utilisateurs normaux
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('order.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/order/{order}/facture', [OrderController::class, 'downloadInvoice'])->name('order.facture.download');
    Route::get('/facture/{order}', [FactureController::class, 'show'])
        ->name('facture.show');
    Route::get('/factures', [FactureController::class, 'index'])
        ->name('facture.index');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('order.cancel');
});

// Routes pour l'admin
Route::middleware(['auth'])->prefix('gestion')->group(function () {
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('gestion.orders.index');
    Route::get('/orders/pending', [OrderController::class, 'pendingOrders'])->name('gestion.orders.pending');
    Route::get('/orders/paid', [OrderController::class, 'paidOrders'])->name('gestion.orders.paid');
    Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('gestion.orders.show');
    Route::post('/orders/{order}/statut', [OrderController::class, 'updateStatut'])->name('gestion.orders.updateStatut');
    Route::delete('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('gestion.orders.cancel');
    Route::get('/gestion/factures', [FactureController::class, 'adminIndex'])
        ->name('gestion.factures.index');
    Route::get('/facture/{order}', [FactureController::class, 'showAdmin'])
        ->name('gestion.factures.show');
    Route::post('/orders/{order}/paiement', [PaiementController::class, 'store'])
        ->name('gestion.paiement.store');
    Route::get('/paiements', [PaiementController::class, 'index'])
        ->name('gestion.paiement.index');
});
