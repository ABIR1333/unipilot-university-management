<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{EmploiDuTemps, Module, Professeur, Salle};

class EmploiDuTempsSeeder extends Seeder {
    public function run(): void {
        $modules = Module::all()->keyBy('code');
        $professeurs = Professeur::all();
        $salles = Salle::all()->keyBy('nom');
        if ($professeurs->isEmpty() || $modules->isEmpty()) return;

        $seances = [
            ['module'=>'ALGO','jour'=>'Lundi','debut'=>'08:00','fin'=>'10:00','type'=>'CM','salle'=>'Amphi A1'],
            ['module'=>'POO','jour'=>'Lundi','debut'=>'10:15','fin'=>'12:15','type'=>'TD','salle'=>'Salle 201'],
            ['module'=>'BDD','jour'=>'Lundi','debut'=>'14:00','fin'=>'16:00','type'=>'TP','salle'=>'Labo L3'],
            ['module'=>'MATH','jour'=>'Mardi','debut'=>'09:00','fin'=>'11:00','type'=>'CM','salle'=>'Amphi B2'],
            ['module'=>'RES','jour'=>'Mardi','debut'=>'13:30','fin'=>'15:30','type'=>'TD','salle'=>'Salle 302'],
            ['module'=>'GL','jour'=>'Mercredi','debut'=>'08:00','fin'=>'10:00','type'=>'CM','salle'=>'Salle 302'],
            ['module'=>'SYS','jour'=>'Mercredi','debut'=>'10:15','fin'=>'12:15','type'=>'TP','salle'=>'Labo L1'],
            ['module'=>'PROBA','jour'=>'Jeudi','debut'=>'09:00','fin'=>'11:00','type'=>'CM','salle'=>'Salle 201'],
            ['module'=>'ALGO','jour'=>'Jeudi','debut'=>'10:15','fin'=>'16:00','type'=>'TD','salle'=>'Amphi A1'],
            ['module'=>'POO','jour'=>'Vendredi','debut'=>'08:00','fin'=>'10:00','type'=>'TP','salle'=>'Salle 201'],
            ['module'=>'BDD','jour'=>'Vendredi','debut'=>'10:15','fin'=>'12:15','type'=>'TD','salle'=>'Labo L2'],
        ];

        foreach ($seances as $s) {
            $module = $modules[$s['module']] ?? $modules->first();
            if (!$module) continue;
            $prof = $module->professeurs()->first() ?? $professeurs->first();
            $salle = $salles[$s['salle']] ?? $salles->first();
            EmploiDuTemps::create([
                'module_id'        => $module->id,
                'professeur_id'    => $prof->id,
                'salle_id'         => $salle?->id,
                'jour'             => $s['jour'],
                'heure_debut'      => $s['debut'],
                'heure_fin'        => $s['fin'],
                'type_seance'      => $s['type'],
                'annee_academique' => '2024-2025',
                'semestre'         => 4,
            ]);
        }
    }
}
