<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Salle, ReservationSalle, Module, Professeur};
use Illuminate\Http\Request;

class SalleController extends Controller {
    public function index() {
        $salles = Salle::withCount('reservations')->get();
        $reservations = ReservationSalle::with(['salle','module','professeur.user'])
            ->where('date','>=',today())->orderBy('date')->orderBy('heure_debut')->take(20)->get();
        return view('admin.salles.index', compact('salles','reservations'));
    }
    public function store(Request $request) {
        $request->validate(['nom'=>'required','batiment'=>'nullable','capacite'=>'required|integer','type'=>'required','statut'=>'required']);
        Salle::create($request->only('nom','batiment','capacite','type','statut'));
        return redirect()->route('admin.salles.index')->with('success','Salle ajoutée.');
    }
    public function update(Request $request, Salle $salle) {
        $salle->update($request->only('nom','batiment','capacite','type','statut'));
        return redirect()->route('admin.salles.index')->with('success','Salle modifiée.');
    }
    public function destroy(Salle $salle) {
        $salle->delete();
        return redirect()->route('admin.salles.index')->with('success','Salle supprimée.');
    }
    public function storeReservation(Request $request) {
        $v = $request->validate([
            'salle_id'=>'required|exists:salles,id',
            'module_id'=>'nullable|exists:modules,id',
            'professeur_id'=>'nullable|exists:professeurs,id',
            'titre'=>'required|string',
            'date'=>'required|date',
            'heure_debut'=>'required',
            'heure_fin'=>'required',
        ]);
        ReservationSalle::create($v);
        return redirect()->route('admin.salles.index')->with('success','Réservation créée.');
    }
    public function destroyReservation(ReservationSalle $reservation) {
        $reservation->delete();
        return redirect()->route('admin.salles.index')->with('success','Réservation supprimée.');
    }
}
