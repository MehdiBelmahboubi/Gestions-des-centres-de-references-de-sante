<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Dosspat;

class DossierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $dossiers = Dosspat::paginate(10);
        $patients = Pat::paginate(10);
        return view('dossiers.index', compact('dossiers','patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'patiente_id' => 'required|exists:pat,id',
        'numero' => 'required|integer',
        'date_creation' => 'required|date',
        'date_accouchement_prévue' => 'required|date',
        'nom_medecin' => 'required|string',
    ]);

    $dossier = new Dosspat();
    $dossier->numero = $request->input('numero');
    $dossier->date_creation = $request->input('date_creation');
    $dossier->date_accouchement_prévue = $request->input('date_accouchement_prévue');
    $dossier->symptomes_actuel = $request->input('symptomes_actuel');
    $dossier->allergies = $request->input('allergies');
    $dossier->nom_medecin = $request->input('nom_medecin');
    $dossier->patiente_id = $request->input('patiente_id');
    $dossier->save();

    return redirect()->route('patients.index')->with('success', 'Dossier pour la patiente a été ajouté avec succès !');
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
        $dossier = Dosspat::findOrFail($id);
        $patiente = Pat::findOrFail($dossier->patiente_id);
        $patients = Pat::all();

       return view('dossiers.show', compact('dossier', 'patiente', 'patients'));
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
            'patiente_id' => 'required|exists:pat,id',
            'numero' => 'required|integer',
            'date_creation' => 'required|date',
            'date_accouchement_prévue' => 'required|date',
            'nom_medecin' => 'required|string',
        ]);
    
        $dossier = Dosspat::findOrFail($id);
        $dossier->numero = $request->input('numero');
        $dossier->date_creation = $request->input('date_creation');
        $dossier->date_accouchement_prévue = $request->input('date_accouchement_prévue');
        $dossier->symptomes_actuel = $request->input('symptomes_actuel');
        $dossier->allergies = $request->input('allergies');
        $dossier->nom_medecin = $request->input('nom_medecin');
        $dossier->patiente_id = $request->input('patiente_id');
        $dossier->save();
    
        return redirect()->route('patients.index')->with('success', 'modifiaction réussite !');

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
        Dosspat::destroy($id);
        return redirect()->back()->with('success', 'Suppression réussite !!');
    }
}
