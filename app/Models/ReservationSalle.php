<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReservationSalle extends Model {
    protected $table = 'reservations_salles';
    protected $fillable = ['salle_id','module_id','professeur_id','titre','date','heure_debut','heure_fin'];
    protected $casts = ['date'=>'date'];
    public function salle() { return $this->belongsTo(Salle::class); }
    public function module() { return $this->belongsTo(Module::class); }
    public function professeur() { return $this->belongsTo(Professeur::class); }
}
