<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{Demande, Etudiant};

class DemandesSeeder extends Seeder {
    public function run(): void {
        $etudiants = Etudiant::take(5)->get();
        $types = ['Attestation de scolarité','Relevé de notes','Attestation de scolarité','Relevé de notes','Relevé de notes'];
        $motifs = ['Demande de bourse CROUS','Candidature master','Dossier logement','Candidature emploi','Concours fonction publique'];
        $statuts = ['En attente','En attente','En attente','Approuvée','Rejetée'];
        foreach ($etudiants as $i => $etudiant) {
            Demande::create([
                'etudiant_id' => $etudiant->id,
                'type'        => $types[$i] ?? 'Attestation de scolarité',
                'motif'       => $motifs[$i] ?? '',
                'statut'      => $statuts[$i] ?? 'En attente',
                'created_at'  => now()->subDays(rand(1,10)),
            ]);
        }
    }
}
