<?php
namespace App\Http\Controllers\Professeur;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage};

class ProfilController extends Controller {
    public function index() {
        $professeur = Auth::user()->professeur->load('user','modules');
        return view('professeur.profil', compact('professeur'));
    }

    public function update(Request $request) {
        $user       = Auth::user();
        $professeur = $user->professeur;

        $v = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => "required|email|unique:users,email,{$user->id}",
            'telephone'     => 'nullable|string|max:20',
            'bureau'        => 'nullable|string|max:100',
            'specialite'    => 'nullable|string|max:255',
            'current_password' => 'nullable|string',
            'password'      => 'nullable|string|min:8|confirmed',
            'avatar'        => 'nullable|image|max:2048',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
            }
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $v['avatar'] = $request->file('avatar')->store('avatars','public');
        }

        $user->update([
            'name'     => $v['name'],
            'email'    => $v['email'],
            'avatar'   => $v['avatar'] ?? $user->avatar,
            'password' => $request->filled('password') ? Hash::make($v['password']) : $user->password,
        ]);

        $professeur->update([
            'telephone'  => $v['telephone'] ?? $professeur->telephone,
            'bureau'     => $v['bureau'] ?? $professeur->bureau,
            'specialite' => $v['specialite'] ?? $professeur->specialite,
        ]);

        return back()->with('success', 'Profil mis à jour.');
    }
}
