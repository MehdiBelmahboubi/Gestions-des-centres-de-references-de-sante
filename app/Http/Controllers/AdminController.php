<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilisateurs = User::where('role', '!=', 'admin')->paginate(10);
        return view('admin.index', ['utilisateurs' => $utilisateurs]);
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
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    // Check if the user already exists with the given email
    $existingUser = User::where('name', $request->name)->first();

    if ($existingUser) {
        return redirect()->back()->with('error', 'utilisateur exists déja !!');
    }

    // Create a new user if the email is not taken
    $utilisateur = new User();
    $utilisateur->name = $request->name;
    $utilisateur->email = $request->email;
    $utilisateur->role = $request->role;
    $utilisateur->password = Hash::make($request->input('password'));

    $utilisateur->save();

    return redirect()->back()->with('success', 'L\'utilisateur a été bien ajouté !!');
  }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.show', ['user' => $user]);
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
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'],
    ]);

    $utilisateur = User::findOrFail($id);

    $utilisateur->name = $request->name;
    $utilisateur->email = $request->email;
    $utilisateur->role = $request->role;

    if ($request->filled('password')) {
        if ($request->input('password') === $request->input('password_confirmation')) {
            $utilisateur->password = Hash::make($request->input('password'));
        } else {
            return redirect()->back()->withErrors(['password' => 'le mot de passe et la confirmation ne correspondent pas !!'])->withInput();
        }
    }

    $utilisateur->save();

    return redirect()->route('admin.index')->with('success', 'L\'utilisateur a été bien modifié !!');
    
   }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'L\'utilisateur a été bien supprimé !!');
    }

    public function search()
    {
        $search_text = $_GET['query-search'];
        $utilisateurs = User::where('name', 'LIKE', '%' . $search_text . '%')->get();
        
        if ($utilisateurs->isEmpty()) {
            return redirect()->back()->with('error', 'L\'utilisateur n\'existe pas !!');
        }
    
        return view('admin.search', ['utilisateurs' => $utilisateurs]);
    }
}