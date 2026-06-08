<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DocumentCours extends Model {
    protected $table = 'documents_cours';
    protected $fillable = ['module_id','uploaded_by','titre','fichier','nom_fichier','taille'];
    public function module() { return $this->belongsTo(Module::class); }
    public function uploadedBy() { return $this->belongsTo(User::class,'uploaded_by'); }
    public function getTailleHumainAttribute(): string {
        $b = $this->taille;
        if ($b >= 1048576) return round($b/1048576,1).' MB';
        if ($b >= 1024) return round($b/1024,0).' KB';
        return $b.' B';
    }
    public function getUrlAttribute(): string { return asset('storage/'.$this->fichier); }
}
