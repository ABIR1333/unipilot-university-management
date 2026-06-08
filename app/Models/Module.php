<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Module extends Model {
    protected $fillable = ['programme_id','nom','code','semestre_type','heures','credits','description','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function programme() { return $this->belongsTo(Programme::class); }
    public function professeurs() { return $this->belongsToMany(Professeur::class,'module_professeur'); }
    public function notes() { return $this->hasMany(Note::class); }
    public function presences() { return $this->hasMany(Presence::class); }
    public function emploiDuTemps() { return $this->hasMany(EmploiDuTemps::class); }
    public function documents() { return $this->hasMany(DocumentCours::class); }
    public function annonces() { return $this->hasMany(Annonce::class); }
    public function inscriptions() { return $this->hasMany(Inscription::class); }
    public function reservations() { return $this->hasMany(ReservationSalle::class); }
    public function getStatsMoyenneAttribute(): float {
        return $this->notes()->whereNotNull('note_finale')->avg('note_finale') ?? 0;
    }
}
