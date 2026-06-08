<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Salle extends Model {
    protected $fillable = ['nom','batiment','capacite','type','statut'];
    public function reservations() { return $this->hasMany(ReservationSalle::class); }
    public function getStatutColorAttribute(): string {
        return match($this->statut) { 'Disponible'=>'green','Occupée'=>'red','Maintenance'=>'yellow', default=>'gray' };
    }
}
