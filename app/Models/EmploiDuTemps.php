<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EmploiDuTemps extends Model {
    protected $table = 'emploi_du_temps';
    protected $fillable = ['module_id','professeur_id','salle_id','jour','heure_debut','heure_fin','type_seance','annee_academique','semestre'];
    public function module() { return $this->belongsTo(Module::class); }
    public function professeur() { return $this->belongsTo(Professeur::class); }
    public function salle() { return $this->belongsTo(Salle::class); }
    public function getBgColorAttribute(): string {
        return match($this->type_seance) {
            'CM'=>'bg-blue-100 border-l-4 border-blue-400',
            'TD'=>'bg-purple-100 border-l-4 border-purple-400',
            'TP'=>'bg-green-100 border-l-4 border-green-400',
            default=>'bg-gray-100'
        };
    }
    public function getTextColorAttribute(): string {
        return match($this->type_seance) { 'CM'=>'text-blue-800','TD'=>'text-purple-800','TP'=>'text-green-800', default=>'text-gray-800' };
    }
}
