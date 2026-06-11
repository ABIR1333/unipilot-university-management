<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    protected $fillable = [
        'user_id', 'num_etudiant', 'programme_id', 'date_naissance',
        'nationalite', 'adresse', 'telephone', 'statut'
    ];
    
    protected $appends = ['nom', 'initials', 'email', 'moyenne_color', 'statut_color'];

    public function user() { return $this->belongsTo(User::class); }
    public function programme() { return $this->belongsTo(Programme::class); }
    public function inscriptions() { return $this->hasMany(Inscription::class); }
    public function notes() { return $this->hasMany(Note::class); }
    public function presences() { return $this->hasMany(Presence::class); }
    public function demandes() { return $this->hasMany(Demande::class); }
    
    public function getNomAttribute()
    {
        return $this->user->name ?? '';
    }
    
    public function getEmailAttribute()
    {
        return $this->user->email ?? '';
    }
    
    public function getInitialsAttribute()
    {
        $name = $this->user->name ?? '';
        $parts = explode(' ', $name);
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }
    
    public function getMoyenneColorAttribute()
    {
        $moyenne = $this->moyenne_generale;
        if ($moyenne >= 16) return 'text-green-600';
        if ($moyenne >= 14) return 'text-blue-600';
        if ($moyenne >= 12) return 'text-indigo-600';
        if ($moyenne >= 10) return 'text-yellow-600';
        return 'text-red-600';
    }
    
    public function getStatutColorAttribute()
    {
        return match($this->statut) {
            'Actif' => 'green',
            'Suspendu' => 'red',
            'Diplômé' => 'blue',
            default => 'gray'
        };
    }
    
    public function recalculerMoyenne()
    {
        $notes = $this->notes()->whereNotNull('note_finale')->get();
        if ($notes->isEmpty()) {
            $this->moyenne_generale = null;
        } else {
            $total = $notes->sum('note_finale');
            $this->moyenne_generale = $total / $notes->count();
        }
        $this->saveQuietly();
        return $this->moyenne_generale;
    }
}