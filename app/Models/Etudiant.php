<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etudiant extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','programme_id','numero_carte','semestre_actuel','date_inscription','date_diplome','statut','moyenne_generale','tuteur_nom','tuteur_telephone','tuteur_email'];
    protected $casts = ['date_inscription'=>'date','date_diplome'=>'date','moyenne_generale'=>'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
    public function programme() { return $this->belongsTo(Programme::class); }
    public function notes() { return $this->hasMany(Note::class); }
    public function presences() { return $this->hasMany(Presence::class); }
    public function demandes() { return $this->hasMany(Demande::class); }
    public function inscriptions() { return $this->hasMany(Inscription::class); }
    public function getNomAttribute(): string { return $this->user->name ?? ''; }
    public function getEmailAttribute(): string { return $this->user->email ?? ''; }
    public function getInitialsAttribute(): string { return $this->user->initials ?? '??'; }
    public function getAvatarUrlAttribute(): string { return $this->user->avatar_url ?? ''; }
    public function getStatutColorAttribute(): string {
        return match($this->statut) { 'Actif'=>'green','Suspendu'=>'red','Diplômé'=>'blue','Retiré'=>'gray', default=>'gray' };
    }
    public function getMoyenneColorAttribute(): string {
        $m = (float)$this->moyenne_generale;
        if ($m >= 14) return 'text-green-600';
        if ($m >= 10) return 'text-blue-600';
        return 'text-red-600';
    }
    public function recalculerMoyenne(): void {
        $avg = $this->notes()->whereNotNull('note_finale')->avg('note_finale') ?? 0;
        $this->update(['moyenne_generale' => round($avg, 2)]);
    }
}
