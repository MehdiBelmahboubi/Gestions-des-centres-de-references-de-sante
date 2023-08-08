<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Region;

class ProvinceController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
    $provinces = Province::paginate(5);

    $regions = Region::all();

    return view('provinces.index', compact('provinces', 'regions'));
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
      'region_id' => 'required|exists:regions,id',
      'name' => 'required|string',
    ]);

    $province = new Province();
    $province->name = $request->input('name');
    $province->region_id = $request->input('region_id');
    $province->save();

    return redirect()->back()->with('success', 'Province ajouter avec succès !');
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $province = Province::findOrFail($id);
    $region = Region::findOrFail($province->region_id);
    $regions = Region::all();

    return view('provinces.show', compact('province', 'region', 'regions'));
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
      'region_id' => 'required|exists:regions,id',
      'name' => 'required|string',
    ]);

    $province = Province::findOrFail($id);
    $province->name = $request->input('name');
    $province->region_id = $request->input('region_id');
    $province->save();

    return redirect()->route('provinces.index')->with('success', 'modifiaction réussite!');
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
    Province::destroy($id);
    return redirect()->back()->with('success', 'Suppression réussite !!');
  }


  public function search(Request $request)
  {
    $request->validate([
      'query-search' => 'required|string',
    ]);

    $search_text = $request->input('query-search');

    $provinces = Province::where('name', 'LIKE', '%' . $search_text . '%')->with('region')->get();

    $regions = Region::where('name', 'LIKE', '%' . $search_text . '%')->get();

    if ($provinces->isEmpty() && $regions->isEmpty()) {
      return redirect()->back()->with('error', 'No results found!');
    }

    return view('provinces.search', compact('provinces', 'regions'));
  }
}
