<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programme extends Model {
    use SoftDeletes;
    protected $fillable = ['nom','code','type','duree_annees','description','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function modules() { return $this->hasMany(Module::class); }
    public function etudiants() { return $this->hasMany(Etudiant::class); }
}
