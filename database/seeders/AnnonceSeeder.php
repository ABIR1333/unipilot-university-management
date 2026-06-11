<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Annonce;
use App\Models\Module;

class AnnonceSeeder extends Seeder
{
    public function run()
    {
        $module = Module::first();
        
        if ($module) {
            Annonce::create([
                'module_id' => $module->id,
                'created_by' => 2,
                'titre' => 'Examen final — 13 Juin 2025',
                'contenu' => 'L\'examen aura lieu le vendredi 13 juin à 09h00 en Amphi A1. Programme : chapitres 1 à 7.',
                'audience' => 'etudiants'
            ]);
            
            Annonce::create([
                'module_id' => $module->id,
                'created_by' => 2,
                'titre' => 'Correction TD n°5 disponible',
                'contenu' => 'La correction est en ligne. Consultez la section Documents.',
                'audience' => 'etudiants'
            ]);
        }
    }
}