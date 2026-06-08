<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Salle;

class SalleSeeder extends Seeder {
    public function run(): void {
        $salles = [
            ['nom'=>'Amphi A1','batiment'=>'Bât. A','capacite'=>300,'type'=>'Amphithéâtre','statut'=>'Disponible'],
            ['nom'=>'Salle 201','batiment'=>'Bât. B','capacite'=>45,'type'=>'Cours','statut'=>'Occupée'],
            ['nom'=>'Salle 302','batiment'=>'Bât. C','capacite'=>30,'type'=>'TD','statut'=>'Disponible'],
            ['nom'=>'Labo L1','batiment'=>'Bât. L','capacite'=>24,'type'=>'Laboratoire','statut'=>'Disponible'],
            ['nom'=>'Labo L2','batiment'=>'Bât. L','capacite'=>24,'type'=>'Laboratoire','statut'=>'Disponible'],
            ['nom'=>'Labo L3','batiment'=>'Bât. L','capacite'=>24,'type'=>'Laboratoire','statut'=>'Occupée'],
            ['nom'=>'Amphi B2','batiment'=>'Bât. B','capacite'=>200,'type'=>'Amphithéâtre','statut'=>'Disponible'],
            ['nom'=>'Salle Info 1','batiment'=>'Bât. D','capacite'=>30,'type'=>'Salle informatique','statut'=>'Disponible'],
        ];
        foreach ($salles as $s) Salle::create($s);
    }
}
