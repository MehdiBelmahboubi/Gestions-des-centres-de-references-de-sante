<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $regions = Region::with('provinces')->paginate(5);

        return view('regions.index', compact('regions'));
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
            'region_name' => 'required|string',
        ]);

        $region = Region::create([
            'name' => $request->input('region_name'),
        ]);


        return redirect()->back()->with('success', 'Region ajouter avec succes!');
        
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
        $region = Region::find($id);
        return view('regions.show', ['region' => $region]);
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
            'region_name' => 'required|string',
        ]);

        $region = Region::findOrFail($id);

        $region->update([
            'name' => $request->input('region_name'),
        ]);

        return redirect()->route('regions.index')->with('success', 'modifiaction réussite !!');
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
        Region::destroy($id);
        return redirect()->back()->with('success', 'Suppression réussite !!');
    }

    public function search()
    {
        $search_text = $_GET['query-search'];
        $regions = Region::where('name', 'LIKE', '%' . $search_text . '%')->get();
        
        if ($regions->isEmpty()) {
            return redirect()->back()->with('error', 'L\'utilisateur n\'existe pas !!');
        }
    
        return view('regions.search', compact('regions'));
    }
}
