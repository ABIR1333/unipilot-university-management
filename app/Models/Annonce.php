<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Annonce extends Model {
    protected $fillable = ['module_id','created_by','titre','contenu','audience','is_epinglé'];
    protected $casts = ['is_epinglé'=>'boolean'];
    public function module() { return $this->belongsTo(Module::class); }
    public function creator() { return $this->belongsTo(User::class,'created_by'); }
    public function commentaires() { return $this->hasMany(Commentaire::class); }
}
