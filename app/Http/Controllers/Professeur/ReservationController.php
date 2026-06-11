<?php
namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\{Salle, ReservationSalle};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller {
    public function index() {
        $professeur = Auth::user()->professeur;
        $salles     = Salle::all();
        $modules    = $professeur->modules()->get();
        $mesReservations = ReservationSalle::where('professeur_id', $professeur->id)
            ->with('salle','module')
            ->orderByDesc('date')
            ->get();

        return view('professeur.reservations', compact('salles','modules','mesReservations'));
    }

    public function store(Request $request) {
        $professeur = Auth::user()->professeur;
        $v = $request->validate([
            'salle_id'    => 'required|exists:salles,id',
            'module_id'   => 'nullable|exists:modules,id',
            'titre'       => 'required|string|max:255',
            'date'        => 'required|date',
            'heure_debut' => 'required',
            'heure_fin'   => 'required',
        ]);

        ReservationSalle::create(array_merge($v, ['professeur_id' => $professeur->id]));

        return redirect()->route('admin.professeur.reservations.index')
            ->with('success', 'Réservation créée.');
    }

    public function destroy(ReservationSalle $reservation) {
        abort_if($reservation->professeur_id !== Auth::user()->professeur->id, 403);
        $reservation->delete();
        return redirect()->back()->with('success', 'Réservation annulée.');
    }
}