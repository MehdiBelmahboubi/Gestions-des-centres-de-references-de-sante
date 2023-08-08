<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrondissement;
use App\Models\Province;
class Arrondissement_Gouverneur_Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $arrondissements = Arrondissement::paginate(10);
        $provinces = Province::paginate(10);
        return view('arrondissement_gouverneur.index', compact('arrondissements','provinces'));
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
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string',
        ]);
    
        $arrondissement = new Arrondissement();
        $arrondissement->name = $request->input('name');
        $arrondissement->province_id = $request->input('province_id');
        $arrondissement->save();
    
        return redirect()->route('arrondissements.index')->with('success', 'Arrondissement ajouter avec succès !');
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
        $arrondissement = Arrondissement::findOrFail($id);
        $province = Province::findOrFail($arrondissement->province_id);
        $provinces = Province::all();

    return view('arrondissements.show', compact('arrondissement', 'province', 'provinces'));
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
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string',
        ]);
    
        $arrondissement = Arrondissement::findOrFail($id);
        $arrondissement->name = $request->input('name');
        $arrondissement->province_id = $request->input('province_id');
        $arrondissement->save();
    
        return redirect()->route('arrondissements.index')->with('success', 'modifiaction réussite!');
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
        Arrondissement::destroy($id);
        return redirect()->back()->with('success', 'Suppression réussite !!');
    }
}
