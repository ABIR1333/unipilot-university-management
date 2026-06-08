<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{User, Professeur, Etudiant, Programme, Module, Inscription};
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder {
    public function run(): void {
        // Admin
        $admin = User::create(['name'=>'Admin UPP','email'=>'admin@upp.fr','password'=>Hash::make('password'),'is_active'=>true]);
        $admin->assignRole('admin');

        // Professeurs
        $profsData = [
            ['name'=>'Dr. Marie Dubois','email'=>'m.dubois@upp.fr','specialite'=>'Algorithmique & Structures de données','modules'=>['ALGO','POO']],
            ['name'=>'Prof. Jean-Pierre Martin','email'=>'jp.martin@upp.fr','specialite'=>'Systèmes d\'exploitation & Réseaux','modules'=>['SYS','RES']],
            ['name'=>'Dr. Sophie Leclerc','email'=>'s.leclerc@upp.fr','specialite'=>'Base de données & Génie logiciel','modules'=>['BDD','GL']],
            ['name'=>'Prof. Ahmed Benali','email'=>'a.benali@upp.fr','specialite'=>'Mathématiques & Probabilités','modules'=>['MATH','PROBA']],
            ['name'=>'Dr. Claire Moreau','email'=>'c.moreau@upp.fr','specialite'=>'Architecture logicielle','modules'=>[],'statut'=>'Congé'],
        ];

        foreach ($profsData as $i => $pd) {
            $user = User::create(['name'=>$pd['name'],'email'=>$pd['email'],'password'=>Hash::make('password'),'is_active'=>true]);
            $user->assignRole('professeur');
            $prof = Professeur::create([
                'user_id'     => $user->id,
                'employee_id' => 'PROF2024'.str_pad($i+1,3,'0',STR_PAD_LEFT),
                'specialite'  => $pd['specialite'],
                'bureau'      => 'Bureau '.($i+101),
                'telephone'   => '+33 1 42 76 31 '.($i+10),
                'statut'      => $pd['statut'] ?? 'Actif',
                'date_embauche'=> '2020-09-01',
            ]);
            if (!empty($pd['modules'])) {
                $modules = Module::whereIn('code',$pd['modules'])->get();
                $prof->modules()->sync($modules->pluck('id'));
            }
        }

        // Étudiants
        $programmes = Programme::all()->keyBy('code');
        $etudiantsData = [
            ['name'=>'Lucas Petit','email'=>'l.petit@etu.upp.fr','programme'=>'LINF','semestre'=>4,'moyenne'=>14.2,'statut'=>'Actif'],
            ['name'=>'Emma Bernard','email'=>'e.bernard@etu.upp.fr','programme'=>'LINF','semestre'=>4,'moyenne'=>17.8,'statut'=>'Actif'],
            ['name'=>'Thomas Durand','email'=>'t.durand@etu.upp.fr','programme'=>'MGC','semestre'=>2,'moyenne'=>11.1,'statut'=>'Actif'],
            ['name'=>'Camille Laurent','email'=>'c.laurent@etu.upp.fr','programme'=>'DUTE','semestre'=>3,'moyenne'=>13.7,'statut'=>'Actif'],
            ['name'=>'Hugo Simon','email'=>'h.simon@etu.upp.fr','programme'=>'LGEST','semestre'=>5,'moyenne'=>15.4,'statut'=>'Actif'],
            ['name'=>'Manon Dumont','email'=>'m.dumont@etu.upp.fr','programme'=>'MGC','semestre'=>1,'moyenne'=>8.9,'statut'=>'Suspendu'],
            ['name'=>'Antoine Moreau','email'=>'a.moreau@etu.upp.fr','programme'=>'LINF','semestre'=>2,'moyenne'=>12.6,'statut'=>'Actif'],
            ['name'=>'Sophie Leroy','email'=>'s.leroy@etu.upp.fr','programme'=>'LGEST','semestre'=>4,'moyenne'=>16.1,'statut'=>'Actif'],
        ];

        foreach ($etudiantsData as $i => $ed) {
            $user = User::create(['name'=>$ed['name'],'email'=>$ed['email'],'password'=>Hash::make('password'),'is_active'=>$ed['statut']==='Actif']);
            $user->assignRole('etudiant');
            $prog = $programmes[$ed['programme']];
            $etudiant = Etudiant::create([
                'user_id'         => $user->id,
                'programme_id'    => $prog->id,
                'numero_carte'    => '20241'.str_pad($i+1,3,'0',STR_PAD_LEFT),
                'semestre_actuel' => $ed['semestre'],
                'date_inscription'=> '2022-09-01',
                'statut'          => $ed['statut'],
                'moyenne_generale'=> $ed['moyenne'],
            ]);

            // Inscrire aux modules du programme
            $modules = Module::where('programme_id',$prog->id)->take(4)->get();
            foreach ($modules as $module) {
                Inscription::create([
                    'etudiant_id'      => $etudiant->id,
                    'module_id'        => $module->id,
                    'annee_academique' => '2024-2025',
                    'semestre'         => $ed['semestre'],
                ]);
            }
        }
    }
}
