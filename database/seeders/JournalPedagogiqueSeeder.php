<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JournalPedagogique;
use App\Models\Professeur;
use App\Models\Module;

class JournalPedagogiqueSeeder extends Seeder
{
    public function run()
    {
        $professeur = Professeur::first();
        $module = Module::where('nom', 'LIKE', '%Algorithmique%')->orWhere('nom', 'LIKE', '%POO%')->first();
        
        if ($professeur && $module) {
            $seances = [
                [
                    'titre' => 'Introduction aux graphes — définitions et représentations',
                    'type_seance' => 'CM',
                    'date' => '2025-05-26',
                    'heure_debut' => '08:00',
                    'heure_fin' => '10:00',
                    'salle' => 'Amphi A1',
                    'notes' => 'Bon déroulement, participation active. 47/48 présents.'
                ],
                [
                    'titre' => 'Exercices sur les arbres binaires de recherche',
                    'type_seance' => 'TD',
                    'date' => '2025-05-22',
                    'heure_debut' => '10:15',
                    'heure_fin' => '12:15',
                    'salle' => 'Salle 201',
                    'notes' => 'Quelques difficultés sur l\'algorithme de suppression.'
                ],
                [
                    'titre' => 'Algorithmes de tri avancés (QuickSort, MergeSort)',
                    'type_seance' => 'CM',
                    'date' => '2025-05-19',
                    'heure_debut' => '08:00',
                    'heure_fin' => '10:00',
                    'salle' => 'Amphi A1',
                    'notes' => 'Session enregistrée pour les absents.'
                ],
                [
                    'titre' => 'Implémentation d\'un arbre AVL en Java',
                    'type_seance' => 'TD',
                    'date' => '2025-05-15',
                    'heure_debut' => '14:00',
                    'heure_fin' => '16:00',
                    'salle' => 'Labo L3',
                    'notes' => null
                ]
            ];
            
            foreach ($seances as $seance) {
                JournalPedagogique::create(array_merge($seance, [
                    'professeur_id' => $professeur->id,
                    'module_id' => $module->id
                ]));
            }
        }
    }
}