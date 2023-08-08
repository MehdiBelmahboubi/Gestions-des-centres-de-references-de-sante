<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Secteur;
class HopitalController_Gouverneur extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $hospitals = Hospital::paginate(10);
        $secteurs = Secteur::paginate(10);
        return view('hopitals_gouverneur.index', compact('hospitals','secteurs'));
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
    
        return redirect()->route('hospitals.index')->with('success', 'hospital ajouter avec succès !');
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

       return view('hospitals.show', compact('hospital', 'secteur', 'secteurs'));
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
}
