<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Programme, Module};

class ProgrammeSeeder extends Seeder {
    public function run(): void {
        $linf = Programme::create(['nom'=>'Licence Informatique','code'=>'LINF','type'=>'Licence','duree_annees'=>3]);
        $mgc  = Programme::create(['nom'=>'Master Génie Civil','code'=>'MGC','type'=>'Master','duree_annees'=>2]);
        $dute = Programme::create(['nom'=>'DUT Électronique','code'=>'DUTE','type'=>'DUT','duree_annees'=>2]);
        $lgest= Programme::create(['nom'=>'Licence Gestion','code'=>'LGEST','type'=>'Licence','duree_annees'=>3]);

        $modulesLinf = [
            ['nom'=>'Algorithmique','code'=>'ALGO','semestre_type'=>'Impair','heures'=>20,'credits'=>4],
            ['nom'=>'POO Java','code'=>'POO','semestre_type'=>'Pair','heures'=>25,'credits'=>4],
            ['nom'=>'Base de données','code'=>'BDD','semestre_type'=>'Impair','heures'=>30,'credits'=>4],
            ['nom'=>'Systèmes','code'=>'SYS','semestre_type'=>'Pair','heures'=>35,'credits'=>4],
            ['nom'=>'Réseaux','code'=>'RES','semestre_type'=>'Impair','heures'=>40,'credits'=>4],
            ['nom'=>'Génie logiciel','code'=>'GL','semestre_type'=>'Pair','heures'=>45,'credits'=>4],
            ['nom'=>'Mathématiques','code'=>'MATH','semestre_type'=>'Impair','heures'=>30,'credits'=>3],
            ['nom'=>'Probabilités','code'=>'PROBA','semestre_type'=>'Pair','heures'=>25,'credits'=>3],
        ];
        foreach ($modulesLinf as $m) {
            Module::create(array_merge($m,['programme_id'=>$linf->id]));
        }

        $modulesMgc = [
            ['nom'=>'Béton Armé','code'=>'BA','semestre_type'=>'Impair','heures'=>30,'credits'=>4],
            ['nom'=>'Résistance Matériaux','code'=>'RM','semestre_type'=>'Pair','heures'=>25,'credits'=>4],
            ['nom'=>'Hydraulique','code'=>'HYD','semestre_type'=>'Impair','heures'=>20,'credits'=>3],
            ['nom'=>'Topographie','code'=>'TOPO','semestre_type'=>'Pair','heures'=>20,'credits'=>3],
        ];
        foreach ($modulesMgc as $m) {
            Module::create(array_merge($m,['programme_id'=>$mgc->id]));
        }

        $modulesDute = [
            ['nom'=>'Electronique Analogique','code'=>'EA','semestre_type'=>'Impair','heures'=>30,'credits'=>4],
            ['nom'=>'Electronique Numérique','code'=>'EN','semestre_type'=>'Pair','heures'=>25,'credits'=>4],
            ['nom'=>'Traitement du signal','code'=>'TS','semestre_type'=>'Impair','heures'=>20,'credits'=>3],
            ['nom'=>'Microcontrôleurs','code'=>'MCU','semestre_type'=>'Pair','heures'=>25,'credits'=>3],
        ];
        foreach ($modulesDute as $m) {
            Module::create(array_merge($m,['programme_id'=>$dute->id]));
        }

        $modulesGest = [
            ['nom'=>'Comptabilité','code'=>'CPT','semestre_type'=>'Impair','heures'=>25,'credits'=>4],
            ['nom'=>'Droit des affaires','code'=>'DROIT','semestre_type'=>'Pair','heures'=>20,'credits'=>3],
            ['nom'=>'Marketing','code'=>'MKT','semestre_type'=>'Impair','heures'=>20,'credits'=>3],
            ['nom'=>'Gestion financière','code'=>'GF','semestre_type'=>'Pair','heures'=>25,'credits'=>4],
        ];
        foreach ($modulesGest as $m) {
            Module::create(array_merge($m,['programme_id'=>$lgest->id]));
        }
    }
}
