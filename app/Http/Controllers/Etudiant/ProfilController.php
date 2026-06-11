<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        return view('etudiant.profil', compact('etudiant'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        $etudiant->update([
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ]);
        
        return redirect()->back()->with('success', 'Profil mis à jour.');
    }
}