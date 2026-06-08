<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ParametreController extends Controller {
    public function index() {
        $roles = Role::withCount('users')->get();
        $config = [
            'annee_academique' => '2024-2025',
            'semestre_actuel'  => 4,
            'nom_universite'   => 'UniPilot',
        ];
        return view('admin.parametres.index', compact('roles','config'));
    }
}
