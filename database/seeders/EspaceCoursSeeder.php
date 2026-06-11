<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Annonce;
use App\Models\DocumentCours;
use App\Models\Module;
use App\Models\User;

class EspaceCoursSeeder extends Seeder
{
    public function run()
    {
        $module = Module::first();
        $user = User::where('name', 'LIKE', '%admin%')->first();
        
        if ($module && $user) {
            // Annonces
            Annonce::create([
                'module_id' => $module->id,
                'created_by' => $user->id,
                'titre' => 'Examen final — 13 Juin 2025',
                'contenu' => 'L\'examen aura lieu le vendredi 13 juin à 09h00 en Amphi A1. Programme : chapitres 1 à 7.',
                'audience' => 'etudiants'
            ]);
            
            Annonce::create([
                'module_id' => $module->id,
                'created_by' => $user->id,
                'titre' => 'Correction TD n°5 disponible',
                'contenu' => 'La correction est en ligne. Consultez la section Documents.',
                'audience' => 'etudiants'
            ]);
            
            // Commentaires
            if ($etudiant = User::where('name', 'LIKE', '%etudiant%')->first()) {
                $annonce = Annonce::first();
                \App\Models\Commentaire::create([
                    'annonce_id' => $annonce->id,
                    'user_id' => $etudiant->id,
                    'contenu' => 'Merci pour les informations !'
                ]);
            }
        }
    }
}