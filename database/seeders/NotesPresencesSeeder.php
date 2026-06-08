<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Note, Presence, Etudiant, Module, Inscription};

class NotesPresencesSeeder extends Seeder {
    public function run(): void {
        $etudiants = Etudiant::with('programme')->get();
        $modules = Module::all()->keyBy('id');

        foreach ($etudiants as $etudiant) {
            $inscriptions = Inscription::where('etudiant_id',$etudiant->id)->get();
            foreach ($inscriptions as $inscription) {
                $module = $modules[$inscription->module_id] ?? null;
                if (!$module) continue;

                // Seed notes
                $cc1  = round(rand(80,190)/10, 1);
                $cc2  = round(rand(80,190)/10, 1);
                $exam = round(rand(70,190)/10, 1);
                $note = Note::create([
                    'etudiant_id'      => $etudiant->id,
                    'module_id'        => $module->id,
                    'annee_academique' => '2024-2025',
                    'semestre'         => $etudiant->semestre_actuel,
                    'note_cc1'         => $cc1,
                    'note_cc2'         => $cc2,
                    'note_examen'      => $exam,
                ]);
                $note->calculerEtSauvegarder();

                // Seed presences (last 24 sessions)
                for ($day = 24; $day >= 1; $day--) {
                    $date = now()->subDays($day * 3);
                    if ($date->isWeekend()) continue;
                    $rand = rand(1,10);
                    $statut = $rand <= 7 ? 'Présent' : ($rand <= 9 ? 'Absent' : 'Justifié');
                    Presence::create([
                        'etudiant_id' => $etudiant->id,
                        'module_id'   => $module->id,
                        'date'        => $date->toDateString(),
                        'statut'      => $statut,
                    ]);
                }
            }
        }
    }
}
