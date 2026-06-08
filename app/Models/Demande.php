<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Demande extends Model {
    protected $fillable = ['etudiant_id','type','motif','statut','commentaire_admin','traite_par','traite_le','fichier_genere'];
    protected $casts = ['traite_le'=>'datetime'];
    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function traitePar() { return $this->belongsTo(User::class,'traite_par'); }
    public function getStatutColorAttribute(): string {
        return match($this->statut) { 'En attente'=>'yellow','Approuvée'=>'green','Rejetée'=>'red', default=>'gray' };
    }
}
