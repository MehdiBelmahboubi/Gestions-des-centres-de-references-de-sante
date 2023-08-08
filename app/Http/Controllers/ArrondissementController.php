<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrondissement;
use App\Models\Province;
use App\Models\Region;

class ArrondissementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $arrondissements = Arrondissement::paginate(5);
        $provinces = Province::all();
        $regions = Region::all();
        return view('arrondissements.index', compact('arrondissements', 'provinces', 'regions'));
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
        $arrondissement = Arrondissement::with('province')->findOrFail($id);
        $provinces = Province::all();
        $regions = Region::all();
    
        return view('arrondissements.show', compact('arrondissement', 'provinces', 'regions'));
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
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'name' => 'required|string',
        ]);

        $arrondissement = Arrondissement::findOrFail($id);
        $arrondissement->name = $request->input('name');
        $arrondissement->province_id = $request->input('province_id');
        $arrondissement->save();


        return redirect()->route('arrondissements.index')->with('success', 'Modification réussie !');
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

    public function search(Request $request)
    {
        $request->validate([
            'query-search' => 'required|string',
        ]);

        $search_text = $request->input('query-search');

        $arrondissements = Arrondissement::where('name', 'LIKE', '%' . $search_text . '%')->with('province.region')->get();

        $provinces = Province::where('name', 'LIKE', '%' . $search_text . '%')->with('region')->get();

        $regions = Region::where('name', 'LIKE', '%' . $search_text . '%')->get();

        if ($arrondissements->isEmpty() && $provinces->isEmpty() && $regions->isEmpty()) {
            return redirect()->back()->with('error', 'No results found!');
        }

        return view('arrondissements.search', compact('arrondissements', 'provinces', 'regions'));
    }
}
