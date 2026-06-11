<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalPedagogique extends Model
{
    protected $table = 'journal_pedagogiques';
    
    protected $fillable = [
        'professeur_id',
        'module_id',
        'titre',
        'type_seance',
        'date',
        'heure_debut',
        'heure_fin',
        'salle',
        'notes',
        'presents',
        'total_inscrits'
    ];
    
    protected $casts = [
        'date' => 'date',
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
    ];
    
    public function professeur(): BelongsTo
    {
        return $this->belongsTo(Professeur::class);
    }
    
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}