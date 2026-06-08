<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Commentaire extends Model {
    protected $fillable = ['annonce_id','module_id','user_id','contenu'];
    public function user() { return $this->belongsTo(User::class); }
    public function annonce() { return $this->belongsTo(Annonce::class); }
}
