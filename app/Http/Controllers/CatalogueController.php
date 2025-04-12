<?php

namespace App\Http\Controllers;

use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Livre;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categorie::all();
        $auteurs = Auteur::all();

        $query = Livre::query();
        $query->where('is_archived', false);
        if ($request->has('categories') && !empty($request->categories)) {
            $query->whereIn('categorie_id', $request->categories);
        }


        if ($request->has('auteurs') && !empty($request->auteurs)) {
            $query->whereIn('auteur_id', $request->auteurs);
        }


        if ($request->has('prix_min') && is_numeric($request->prix_min)) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->has('prix_max') && is_numeric($request->prix_max)) {
            $query->where('prix', '<=', $request->prix_max);
        }


        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $livres = $query->paginate(9)->withQueryString();

        return view('app.catalogue', [
            'categories' => $categories,
            'auteurs' => $auteurs,
            'livres' => $livres,
            'request' => $request
        ]);
    }

    public function show($id)
    {
        $livre = Livre::findOrFail($id);
        $livresSimilaires = Livre::where('categorie_id', $livre->categorie_id)
            ->where('id', '!=', $livre->id)
            ->take(3)
            ->get();

        return view('app.livre_details', [
            'livre' => $livre,
            'livresSimilaires' => $livresSimilaires
        ]);
    }
}
