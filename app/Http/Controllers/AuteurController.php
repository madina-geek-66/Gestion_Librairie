<?php

namespace App\Http\Controllers;

use App\Models\Auteur;
use Illuminate\Http\Request;

class AuteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auteurs = Auteur::paginate(10);
        return view('gestionnaire.auteur.auteurs', compact('auteurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
        ]);

        Auteur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
        ]);

        return redirect()->route('gestion.auteur.index')->with('success', 'Auteur ajouté avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Auteur $auteur)
    {
        //return view('gestionnaire.auteur.show', compact('auteur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auteur $auteur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
        ]);

        $auteur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
        ]);

        return redirect()->route('gestion.auteur.index')->with('success', 'Auteur modifié avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auteur $auteur)
    {
        $auteur->delete();
        return redirect()->route('gestion.auteur.index')->with('successDelete', 'Auteur supprimé avec succès!');
    }
}
