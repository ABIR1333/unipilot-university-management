<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Inscription extends Model {
    protected $fillable = ['etudiant_id','module_id','annee_academique','semestre'];
    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function module() { return $this->belongsTo(Module::class); }
}
