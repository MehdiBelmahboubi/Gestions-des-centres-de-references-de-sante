<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secteur;
use App\Models\Arrondissement;
use App\Models\Province;
use App\Models\Region;

class SecteurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $secteurs = Secteur::paginate(5);
        $arrondissements = Arrondissement::all();
        $provinces = Province::all();
        $regions = Region::all();
        return view('secteurs.index', compact('secteurs','arrondissements','provinces','regions'));
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
    
        return redirect()->route('secteurs.index')->with('success', 'Secteur ajouter avec succès !');
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
        $provinces = Province::all();
        $regions = Region::all();

       return view('secteurs.show', compact('secteur', 'arrondissement', 'arrondissements','provinces','regions'));
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

    public function search(Request $request)
    {
        $request->validate([
            'query-search' => 'required|string',
        ]);

        $search_text = $request->input('query-search');

        $secteurs = Secteur::where('name', 'LIKE', '%' . $search_text . '%')->with('arrondissement.province.region')->get();

        $arrondissements = Arrondissement::where('name', 'LIKE', '%' . $search_text . '%')->with('province.region')->get();

        $provinces = Province::where('name', 'LIKE', '%' . $search_text . '%')->with('region')->get();

        $regions = Region::where('name', 'LIKE', '%' . $search_text . '%')->get();

        if ($secteurs->isEmpty() && $arrondissements->isEmpty() && $provinces->isEmpty() && $regions->isEmpty()) {
            return redirect()->back()->with('error', 'No results found!');
        }

        return view('secteurs.search', compact('secteurs','arrondissements', 'provinces', 'regions'));
    }
}
