<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller {
    public function index() {
        $roles = Role::with('users')->get();
        $permissions = Permission::all();
        $config = [
            'annee_academique' => '2024-2025',
            'semestre_actuel'  => 4,
            'nom_universite'   => 'UniPilot',
        ];
        return view('admin.parametres.index', compact('roles', 'permissions', 'config'));
    }

    public function updateRolePermissions(Request $request, Role $role) {
        $request->validate([
            'permissions' => 'array',
        ]);
        
        $role->syncPermissions($request->permissions);
        
        return redirect()->back()->with('success', 'Permissions mises à jour avec succès.');
    }

    public function assignRole(Request $request, User $user) {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);
        
        $user->syncRoles([$request->role]);
        
        return redirect()->back()->with('success', 'Rôle assigné avec succès.');
    }

    public function getRolePermissions(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        $data = $permissions->map(function($permission) use ($rolePermissions) {
            return [
                'name' => $permission->name,
                'assigned' => in_array($permission->name, $rolePermissions)
            ];
        });
        
        return response()->json($data);
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'nom_universite' => 'required|string|max:255',
            'annee_academique' => 'required|string|max:20',
            'semestre_actuel' => 'required|integer|min:1|max:10',
        ]);
        
        // Sauvegarde dans la base ou dans un fichier de config
        \App\Models\Setting::updateOrCreate(['key' => 'nom_universite'], ['value' => $request->nom_universite]);
        \App\Models\Setting::updateOrCreate(['key' => 'annee_academique'], ['value' => $request->annee_academique]);
        \App\Models\Setting::updateOrCreate(['key' => 'semestre_actuel'], ['value' => $request->semestre_actuel]);
        
        return redirect()->back()->with('success', 'Configuration mise à jour.');
    }
}