<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder {
    public function run(): void {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        foreach (['admin','professeur','etudiant'] as $role) {
            Role::firstOrCreate(['name'=>$role,'guard_name'=>'web']);
        }
    }
}
