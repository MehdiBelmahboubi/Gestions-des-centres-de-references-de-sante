<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secteur;
use App\Models\Arrondissement;
class SecteurController_Gouverneur extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $secteurs = Secteur::paginate(10);
        $arrondissements = Arrondissement::paginate(10);
        return view('secteurs_gouverneur.index', compact('secteurs','arrondissements'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'arrondissement_id' => 'required|exists:arrondissements,id',
            'name' => 'required|string',
        ]);
    
        $secteur = new Secteur();
        $secteur->name = $request->input('name');
        $secteur->arrondissement_id = $request->input('arrondissement_id');
        $secteur->save();
    
        return redirect()->route('secteurs.index')->with('success', 'secteur ajouter avec succès !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $secteur = Secteur::findOrFail($id);
        $arrondissement = Arrondissement::findOrFail($secteur->arrondissement_id);
        $arrondissements = Arrondissement::all();

       return view('secteurs.show', compact('secteur', 'arrondissement', 'arrondissements'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'arrondissement_id' => 'required|exists:arrondissements,id',
            'name' => 'required|string',
        ]);
    
        $secteur = Secteur::findOrFail($id);
        $secteur->name = $request->input('name');
        $secteur->arrondissement_id = $request->input('arrondissement_id');
        $secteur->save();
    
        return redirect()->route('secteurs.index')->with('success', 'modifiaction réussite!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Secteur::destroy($id);
        return redirect()->back()->with('success', 'Suppression réussite !!');
    }
}
