<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pat;
use App\Models\Hospital;
use App\Models\Dosspat;

class PatienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Pat::paginate(10);
        $hospitals = Hospital::paginate(10);

        // Initialize an empty array to store dossiers for each patient
        $dossiers = [];

        foreach ($patients as $patient) {
            // Assuming you have a patiente_id or patient_id foreign key column in the Dosspat table
            $dossier = Dosspat::where('patiente_id', '=', $patient->id)->get();
            // Add the retrieved dossiers to the $dossiers array
            $dossiers[$patient->id] = $dossier;
        }

        return view('patients.index', compact('patients', 'hospitals', 'dossiers'));
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
            'hospital_id' => 'required|exists:hospitals,id',
            'cin' => 'required|string',
            'name' => 'required|string',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
        ]);

        $patiente = new Pat();
        $patiente->cin = $request->input('cin');
        $patiente->name = $request->input('name');
        $patiente->date_naissance = $request->input('date_naissance');
        $patiente->adresse = $request->input('adresse');
        $patiente->hospital_id = $request->input('hospital_id');
        $patiente->save();

        return redirect()->route('patients.index')->with('success', 'patiente ajouter avec succès !');
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
        $patiente = Pat::findOrFail($id);
        $hospital = Hospital::findOrFail($patiente->hospital_id);
        $hospitals = Hospital::all();
        $dossiers = Dosspat::where('patiente_id', $id)->get();
        

        return view('patients.show', compact('patiente', 'hospital', 'hospitals', 'dossiers'));
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
            'hospital_id' => 'required|exists:hospitals,id',
            'cin' => 'required|string',
            'name' => 'required|string',
            'date_naissance' => 'required|date',
            'adresse' => 'required|string',
        ]);

        $patiente = Pat::findOrFail($id);
        $patiente->cin = $request->input('cin');
        $patiente->name = $request->input('name');
        $patiente->date_naissance = $request->input('date_naissance');
        $patiente->adresse = $request->input('adresse');
        $patiente->hospital_id = $request->input('hospital_id');
        $patiente->save();

        return redirect()->route('patients.index')->with('success', 'modifiaction réussite!');
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
        Pat::destroy($id);
        return redirect()->back()->with('success', 'Suppression réussite !!');
    }

    public function search()
    {
        $search_text = $_GET['query-search'];
        $patients = Pat::where('cin', 'LIKE', '%' . $search_text . '%')->with('hospital')->get();
        $hospitals = Hospital::all();

        if ($patients->isEmpty()) {
            return redirect()->back()->with('error', 'La patiente n\'existe pas !!');
        }

        return view('patients.search', compact('patients','hospitals'));
    }

    
}
