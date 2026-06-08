<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::post('/auth/login', [LoginController::class,'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', fn() => response()->json(auth()->user()->load('roles')));
    Route::get('/stats', fn() => response()->json([
        'etudiants'  => \App\Models\Etudiant::where('statut','Actif')->count(),
        'professeurs'=> \App\Models\Professeur::where('statut','Actif')->count(),
        'modules'    => \App\Models\Module::where('is_active',true)->count(),
        'demandes'   => \App\Models\Demande::where('statut','En attente')->count(),
    ]));
});
