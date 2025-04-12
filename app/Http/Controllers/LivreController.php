<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gestionnaire\LivreFormRequest;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Livre;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gestionnaire.livre.liste', ['livres' => Livre::active()->orderBy('created_at', 'asc')->paginate(10)]);
    }

    public function archived()
    {
        return view('gestionnaire.livre.archived', ['livres' => Livre::archived()->orderBy('created_at', 'asc')->paginate(10)]);
    }

    public function archive(Livre $livre)
    {
        $livre->update(['is_archived' => true]);
        return to_route('gestion.livre.index')->with('success', 'Le livre a été archivé avec succès');
    }

    public function restore(Livre $livre)
    {
        $livre->update(['is_archived' => false]);
        return to_route('gestion.livre.archived')->with('success', 'Le livre a été restauré avec succès');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $livre = new Livre();
        $categories = Categorie::all();
        $auteurs = Auteur::all();
        return view('gestionnaire.livre.form', ['livre' => $livre, 'categories' => $categories, 'auteurs' => $auteurs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LivreFormRequest $request)
    {
        $data = $request->validated();
        if($request->hasFile('image')){
            $path = $request->validated('image')->store('images', 'public');
            $data['image'] = $path;
        }
        $livre = Livre::create($data);

        return to_route('gestion.livre.index')->with('success', 'Le livre a été ajouté avec succés');
    }


    /**
     * Display the specified resource.
     */
    public function show(Livre $livre)
    {
        $categories = Categorie::all();
        $auteurs = Auteur::all();
        return view('gestionnaire.livre.show', [
            'livre' => $livre,
            'categories' => $categories,
            'auteurs' => $auteurs,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livre $livre)
    {
        $categories = Categorie::all();
        $auteurs = Auteur::all();
        return view('gestionnaire.livre.form', [
            'livre' => $livre,
            'categories' => $categories,
            'auteurs' => $auteurs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LivreFormRequest $request, Livre $livre)
    {
        $data = $request->validated();
        if($request->hasFile('image')){
            Storage::delete('public/'.$livre->image);
            $path = $request->validated('image')->store('images', 'public');
            $data['image'] = $path;
        }else{
            $path = $livre->image;
            $data['image'] = $path;
        }
        $livre->update($data);
        return to_route('gestion.livre.index')->with('success', 'le livre a été modifié avec succés');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livre $livre)
    {
        if ($livre->image) {
            Storage::delete('public/'.$livre->image);
        }
        $livre->delete();
        return to_route('gestion.livre.index')->with('successDelete', 'Le livre a été bien spprimé');
    }
}
