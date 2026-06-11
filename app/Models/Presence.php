<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Presence extends Model {
    protected $table = 'presences';
    protected $fillable = ['etudiant_id', 'module_id', 'date', 'statut', 'justification', 'emploi_du_temps_id'];
    protected $casts = ['date'=>'date'];
    
    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function module() { return $this->belongsTo(Module::class); }
    public function seance() { return $this->belongsTo(EmploiDuTemps::class, 'emploi_du_temps_id'); }
}