<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        
        $notes = Note::where('etudiant_id', $etudiant->id)
            ->with('module')
            ->get();
        
        $moyenne = $notes->avg('note_finale') ?? 0;
        $creditsObtenus = $notes->where('note_finale', '>=', 10)->sum('module.credits');
        $meilleureNote = $notes->max('note_finale') ?? 0;
        
        return view('etudiant.notes', compact('notes', 'moyenne', 'creditsObtenus', 'meilleureNote'));
    }
}