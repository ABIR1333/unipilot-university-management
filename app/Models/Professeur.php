<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professeur extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','employee_id','specialite','bio','bureau','telephone','statut','date_embauche'];
    protected $casts = ['date_embauche'=>'date'];
    public function user() { return $this->belongsTo(User::class); }
    public function modules() { return $this->belongsToMany(Module::class,'module_professeur'); }
    public function emploiDuTemps() { return $this->hasMany(EmploiDuTemps::class); }
    public function reservations() { return $this->hasMany(ReservationSalle::class); }
    public function getNomAttribute(): string { return $this->user->name ?? ''; }
    public function getEmailAttribute(): string { return $this->user->email ?? ''; }
    public function getInitialsAttribute(): string { return $this->user->initials ?? '??'; }
    public function getNombreEtudiantsAttribute(): int {
        return Inscription::whereIn('module_id', $this->modules->pluck('id'))->distinct('etudiant_id')->count('etudiant_id');
    }
    public function getStatutColorAttribute(): string {
        return match($this->statut) { 'Actif'=>'green','Congé'=>'yellow','Inactif'=>'red', default=>'gray' };
    }
    public function getAvatarUrlAttribute(): string { return $this->user->avatar_url ?? ''; }
}
