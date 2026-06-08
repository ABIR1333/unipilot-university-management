<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            RolesSeeder::class,
            ProgrammeSeeder::class,
            UsersSeeder::class,
            SalleSeeder::class,
            EmploiDuTempsSeeder::class,
            NotesPresencesSeeder::class,
            DemandesSeeder::class,
        ]);
    }
}
