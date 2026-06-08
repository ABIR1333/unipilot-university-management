<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Note extends Model {
    protected $fillable = ['etudiant_id','module_id','annee_academique','semestre','note_cc1','note_cc2','note_examen','note_finale','mention'];
    protected $casts = ['note_cc1'=>'decimal:2','note_cc2'=>'decimal:2','note_examen'=>'decimal:2','note_finale'=>'decimal:2'];
    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function module() { return $this->belongsTo(Module::class); }
    public function calculerEtSauvegarder(): void {
        $cc = (((float)($this->note_cc1??0)) + ((float)($this->note_cc2??0))) / 2;
        $exam = (float)($this->note_examen??0);
        $this->note_finale = round($cc * 0.4 + $exam * 0.6, 2);
        $this->mention = $this->calculerMention();
        $this->save();
        $this->etudiant->recalculerMoyenne();
    }
    public function calculerMention(): string {
        $note = (float)($this->note_finale ?? 0);
        return match(true) {
            $note >= 16 => 'Très Bien', $note >= 14 => 'Bien',
            $note >= 12 => 'Assez Bien', $note >= 10 => 'Passable',
            default => 'Insuffisant'
        };
    }
    public function getMentionColorAttribute(): string {
        return match($this->mention) {
            'Très Bien'=>'badge-green','Bien'=>'badge-blue',
            'Assez Bien'=>'badge-purple','Passable'=>'badge-yellow',
            default=>'badge-red'
        };
    }
    public function getNoteFinaleColorAttribute(): string {
        $n = (float)($this->note_finale ?? 0);
        if ($n >= 14) return 'text-green-600 font-bold';
        if ($n >= 10) return 'text-blue-600';
        return 'text-red-600 font-bold';
    }
}
