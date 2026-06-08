<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Presence extends Model {
    protected $table = 'presences';
    protected $fillable = ['etudiant_id','module_id','date','statut','justification'];
    protected $casts = ['date'=>'date'];
    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function module() { return $this->belongsTo(Module::class); }
}
