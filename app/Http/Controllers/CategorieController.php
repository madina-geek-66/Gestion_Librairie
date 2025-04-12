<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gestionnaire\CategorieFormRequest;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gestionnaire.categorie.categories', ['categories' => Categorie::orderBy('created_at', 'asc')->paginate(5)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorie = new Categorie();
        //return view('gestion.categorie.form', ['categorie' => $categorie]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategorieFormRequest $request)
    {
        $data = $request->validated();
        $categorie = Categorie::create($request->validated());
        return to_route('gestion.categorie.index')->with('success', 'La categorie a été ajouté avec succés');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(CategorieFormRequest $request, Categorie $categorie)
    {
        $categorie->update($request->validated());
        return to_route('gestion.categorie.index')->with('success', 'la categorie a été modifiée avec succés!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return to_route('gestion.categorie.index')->with('successDelete', 'la categorie a été supprimée avec succés!');
    }
}
