<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Secteur;
use App\Models\Arrondissement;
use App\Models\Province;
use App\Models\Region;
class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hospitals = Hospital::paginate(5);
        $secteurs = Secteur::all();
        $arrondissements = Arrondissement::all();
        $provinces = Province::all();
        $regions = Region::all();
        return view('hospitals.index', compact('hospitals','secteurs','arrondissements','provinces','regions'));
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
            'secteur_id' => 'required|exists:secteurs,id',
            'name' => 'required|string',
            'emplacement' => 'required|string',
            'specialité' => 'required|string',
            'capacité' => 'required|integer|min:1',
        ]);
    
        $hospital = new Hospital();
        $hospital->name = $request->input('name');
        $hospital->emplacement = $request->input('emplacement');
        $hospital->specialité = $request->input('specialité');
        $hospital->capacité = $request->input('capacité');
        $hospital->secteur_id = $request->input('secteur_id');
        $hospital->save();
    
        return redirect()->route('hospitals.index')->with('success', 'Hopital ajouter avec succès !');
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
        $hospital = Hospital::findOrFail($id);
        $secteur = Secteur::findOrFail($hospital->secteur_id);
        $secteurs = Secteur::all();
        $arrondissement = Arrondissement::findOrFail($secteur->arrondissement_id);
        $arrondissements = Arrondissement::all();
        $provinces = Province::all();
        $regions = Region::all();

       return view('hospitals.show', compact('hospital', 'secteur', 'secteurs','arrondissement','arrondissements','provinces','regions'));
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
            'secteur_id' => 'required|exists:secteurs,id',
            'name' => 'required|string',
            'emplacement' => 'required|string',
            'specialité' => 'required|string',
            'capacité' => 'required|integer|min:1',

        ]);
    
        $hospital = Hospital::findOrFail($id);
        $hospital->name = $request->input('name');
        $hospital->emplacement = $request->input('emplacement');
        $hospital->specialité = $request->input('specialité');
        $hospital->capacité = $request->input('capacité');
        $hospital->secteur_id = $request->input('secteur_id');
        $hospital->save();
    
        return redirect()->route('hospitals.index')->with('success', 'modifiaction réussite!');
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
        Hospital::destroy($id);
        return redirect()->back()->with('success', 'Suppression réussite !!');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query-search' => 'required|string',
        ]);

        $search_text = $request->input('query-search');

        $hospitals = Hospital::where('name', 'LIKE', '%' . $search_text . '%')->with('secteur.arrondissement.province.region')->get();

        $secteurs = Secteur::where('name', 'LIKE', '%' . $search_text . '%')->with('arrondissement.province.region')->get();

        $arrondissements = Arrondissement::where('name', 'LIKE', '%' . $search_text . '%')->with('province.region')->get();

        $provinces = Province::where('name', 'LIKE', '%' . $search_text . '%')->with('region')->get();

        $regions = Region::where('name', 'LIKE', '%' . $search_text . '%')->get();

        if ($hospitals->isEmpty() && $secteurs->isEmpty() && $arrondissements->isEmpty() && $provinces->isEmpty() && $regions->isEmpty()) {
            return redirect()->back()->with('error', 'No results found!');
        }

        return view('hospitals.search', compact('hospitals','secteurs','arrondissements', 'provinces', 'regions'));
    }
}
