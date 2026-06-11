<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Professeur\DashboardController as ProfDashboardController;
use App\Http\Controllers\Professeur\NoteController;
use App\Http\Controllers\Professeur\PresenceController;
use App\Http\Controllers\Professeur\JournalController;
use App\Http\Controllers\Professeur\EspaceCoursController;
use App\Http\Controllers\Professeur\ReservationController;
use App\Http\Controllers\Professeur\EmploiDuTempsController;
use App\Http\Controllers\Professeur\ProfesseurDemandeController;
use App\Http\Controllers\Professeur\ProfilController;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboardController;
use App\Http\Controllers\Etudiant\NoteController as EtudiantNoteController;
use App\Http\Controllers\Etudiant\EmploiDuTempsController as EtudiantEmploiDuTempsController;
use App\Http\Controllers\Etudiant\PresenceController as EtudiantPresenceController;
use App\Http\Controllers\Etudiant\EspaceCoursController as EtudiantEspaceCoursController;
use App\Http\Controllers\Etudiant\DemandeController as EtudiantDemandeController;
use App\Http\Controllers\Etudiant\ProfilController as EtudiantProfilController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Routes d'authentification (invités seulement)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
});

// Routes protégées par authentification
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Redirection racine
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->professeur) return redirect()->route('admin.professeur.dashboard');
        if ($user->etudiant) return redirect()->route('etudiant.dashboard');
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Routes Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active'])->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // ==================== ESPACE PROFESSEUR ====================
    Route::prefix('professeur')->name('professeur.')->group(function () {
        
        // Tableau de bord professeur
        Route::get('/dashboard', [ProfDashboardController::class, 'index'])->name('dashboard');
        
        // Notes
        Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
        Route::post('/notes/save', [NoteController::class, 'save'])->name('notes.save');
        Route::get('/notes/export-pdf', [NoteController::class, 'exportPdf'])->name('notes.export-pdf');
        
        // Présences
        Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
        Route::post('/presences', [PresenceController::class, 'store'])->name('presences.store');
        
        // Journal pédagogique
        Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
        Route::post('/journal', [JournalController::class, 'store'])->name('journal.store');
        Route::get('/journal/create', [JournalController::class, 'create'])->name('journal.create');
        Route::get('/journal/{id}/edit', [JournalController::class, 'edit'])->name('journal.edit');
        Route::put('/journal/{id}', [JournalController::class, 'update'])->name('journal.update');
        Route::delete('/journal/{id}', [JournalController::class, 'destroy'])->name('journal.destroy');
        
        // Espace cours
        Route::get('/espace-cours', [EspaceCoursController::class, 'index'])->name('espace-cours.index');
        Route::post('/espace-cours/annonce', [EspaceCoursController::class, 'storeAnnonce'])->name('espace-cours.annonce.store');
        Route::delete('/espace-cours/annonce/{annonce}', [EspaceCoursController::class, 'destroyAnnonce'])->name('espace-cours.annonce.destroy');
        Route::post('/espace-cours/document', [EspaceCoursController::class, 'storeDocument'])->name('espace-cours.document.store');
        Route::delete('/espace-cours/document/{document}', [EspaceCoursController::class, 'destroyDocument'])->name('espace-cours.document.destroy');
        Route::post('/espace-cours/commentaire', [EspaceCoursController::class, 'storeCommentaire'])->name('espace-cours.commentaire.store');
        
        // Réservation de salle
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
        
        // Emploi du temps
        Route::get('/emploi-du-temps', [EmploiDuTempsController::class, 'index'])->name('emploi-du-temps.index');
        
        // Demandes administratives
        Route::get('/demandes', [ProfesseurDemandeController::class, 'index'])->name('demandes.index');
        Route::post('/demandes', [ProfesseurDemandeController::class, 'store'])->name('demandes.store');
        Route::get('/demandes/{demande}/telecharger', [ProfesseurDemandeController::class, 'telecharger'])->name('demandes.telecharger');
        
        // Profil
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
        Route::post('/profil', [ProfilController::class, 'update'])->name('profil.update');
    });

    // ==================== GESTION DES ÉTUDIANTS ====================
    Route::prefix('etudiants')->name('etudiants.')->group(function () {
        Route::get('/', [Admin\EtudiantController::class, 'index'])->name('index');
        Route::get('/create', [Admin\EtudiantController::class, 'create'])->name('create');
        Route::post('/', [Admin\EtudiantController::class, 'store'])->name('store');
        Route::get('/{etudiant}', [Admin\EtudiantController::class, 'show'])->name('show');
        Route::get('/{etudiant}/edit', [Admin\EtudiantController::class, 'edit'])->name('edit');
        Route::patch('/{etudiant}', [Admin\EtudiantController::class, 'update'])->name('update');
        Route::delete('/{etudiant}', [Admin\EtudiantController::class, 'destroy'])->name('destroy');
        Route::get('/{etudiant}/releve', [Admin\EtudiantController::class, 'releve'])->name('releve');
        Route::get('/{etudiant}/attestation', [Admin\EtudiantController::class, 'attestation'])->name('attestation');
    });

    // ==================== GESTION DES PROFESSEURS ====================
    Route::prefix('professeurs')->name('professeurs.')->group(function () {
        Route::get('/', [Admin\ProfesseurController::class, 'index'])->name('index');
        Route::get('/create', [Admin\ProfesseurController::class, 'create'])->name('create');
        Route::post('/', [Admin\ProfesseurController::class, 'store'])->name('store');
        Route::get('/{professeur}', [Admin\ProfesseurController::class, 'show'])->name('show');
        Route::get('/{professeur}/edit', [Admin\ProfesseurController::class, 'edit'])->name('edit');
        Route::patch('/{professeur}', [Admin\ProfesseurController::class, 'update'])->name('update');
        Route::delete('/{professeur}', [Admin\ProfesseurController::class, 'destroy'])->name('destroy');
    });

    // ==================== PROGRAMMES & MODULES ====================
    Route::prefix('programmes')->name('programmes.')->group(function () {
        Route::get('/', [Admin\ProgrammeController::class, 'index'])->name('index');
        Route::post('/', [Admin\ProgrammeController::class, 'store'])->name('store');
        Route::get('/{programme}/modules', [Admin\ProgrammeController::class, 'modules'])->name('modules');
        Route::delete('/{programme}', [Admin\ProgrammeController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('modules')->name('modules.')->group(function () {
        Route::post('/', [Admin\ProgrammeController::class, 'storeModule'])->name('store');
        Route::patch('/{module}', [Admin\ProgrammeController::class, 'updateModule'])->name('update');
        Route::delete('/{module}', [Admin\ProgrammeController::class, 'destroyModule'])->name('destroy');
    });

    // ==================== EMPLOI DU TEMPS ====================
    Route::prefix('emploi-du-temps')->name('emploi-du-temps.')->group(function () {
        Route::get('/', [Admin\EmploiDuTempsController::class, 'index'])->name('index');
        Route::post('/', [Admin\EmploiDuTempsController::class, 'store'])->name('store');
        Route::delete('/{emploiDuTemps}', [Admin\EmploiDuTempsController::class, 'destroy'])->name('destroy');
        Route::get('/export-pdf', [Admin\EmploiDuTempsController::class, 'exportPdf'])->name('export-pdf');
    });

    // ==================== NOTES ====================
    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/', [Admin\NoteController::class, 'index'])->name('index');
        Route::post('/', [Admin\NoteController::class, 'store'])->name('store');
        Route::patch('/{note}', [Admin\NoteController::class, 'update'])->name('update');
        Route::post('/bulk', [Admin\NoteController::class, 'bulkUpdate'])->name('bulk');
        Route::get('/export-pdf', [Admin\NoteController::class, 'exportPdf'])->name('export-pdf');
    });

    // ==================== PRÉSENCES ====================
    Route::prefix('presences')->name('presences.')->group(function () {
        Route::get('/', [Admin\PresenceController::class, 'index'])->name('index');
        Route::post('/', [Admin\PresenceController::class, 'store'])->name('store');
        Route::get('/export-pdf', [Admin\PresenceController::class, 'exportPdf'])->name('export-pdf');
    });

    // ==================== SALLES ====================
    Route::prefix('salles')->name('salles.')->group(function () {
        Route::get('/', [Admin\SalleController::class, 'index'])->name('index');
        Route::post('/', [Admin\SalleController::class, 'store'])->name('store');
        Route::patch('/{salle}', [Admin\SalleController::class, 'update'])->name('update');
        Route::delete('/{salle}', [Admin\SalleController::class, 'destroy'])->name('destroy');
        
        Route::prefix('{salle}/reservations')->name('reservations.')->group(function () {
            Route::post('/', [Admin\SalleController::class, 'storeReservation'])->name('store');
            Route::delete('/{reservation}', [Admin\SalleController::class, 'destroyReservation'])->name('destroy');
        });
    });

    // ==================== DEMANDES ====================
    Route::prefix('demandes')->name('demandes.')->group(function () {
        Route::get('/', [Admin\DemandeController::class, 'index'])->name('index');
        Route::post('/{demande}/approuver', [Admin\DemandeController::class, 'approuver'])->name('approuver');
        Route::post('/{demande}/rejeter', [Admin\DemandeController::class, 'rejeter'])->name('rejeter');
        Route::get('/{demande}/telecharger', [Admin\DemandeController::class, 'telecharger'])->name('telecharger');
    });

    // ==================== RAPPORTS ====================
    Route::prefix('rapports')->name('rapports.')->group(function () {
        Route::get('/', [Admin\RapportController::class, 'index'])->name('index');
        Route::get('/export-pdf', [Admin\RapportController::class, 'exportPdf'])->name('export-pdf');
    });

    // ==================== PARAMÈTRES ====================
    Route::prefix('parametres')->name('parametres.')->group(function () {
        Route::get('/', [Admin\ParametreController::class, 'index'])->name('index');
        Route::post('/config', [Admin\ParametreController::class, 'updateConfig'])->name('config.update');
        Route::get('/role/{role}/permissions', [Admin\ParametreController::class, 'getRolePermissions'])->name('role.permissions.list');
        Route::post('/role/{role}/permissions', [Admin\ParametreController::class, 'updateRolePermissions'])->name('role.permissions');
        Route::post('/user/{user}/role', [Admin\ParametreController::class, 'assignRole'])->name('user.role');
    });
});

// ==================== ESPACE ÉTUDIANT ====================
Route::prefix('etudiant')->name('etudiant.')->middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [EtudiantDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notes', [EtudiantNoteController::class, 'index'])->name('notes.index');
    Route::get('/emploi-du-temps', [EtudiantEmploiDuTempsController::class, 'index'])->name('emploi-du-temps.index');
    Route::get('/presences', [EtudiantPresenceController::class, 'index'])->name('presences.index');
    Route::get('/espace-cours', [EtudiantEspaceCoursController::class, 'index'])->name('espace-cours.index');
    Route::get('/demandes', [EtudiantDemandeController::class, 'index'])->name('demandes.index');
    Route::post('/demandes', [EtudiantDemandeController::class, 'store'])->name('demandes.store');
    Route::get('/profil', [EtudiantProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil', [EtudiantProfilController::class, 'update'])->name('profil.update');
});
// ==================== ESPACE ÉTUDIANT ====================
Route::prefix('etudiant')->name('etudiant.')->middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [EtudiantDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notes', [EtudiantNoteController::class, 'index'])->name('notes.index');
    Route::get('/emploi-du-temps', [EtudiantEmploiDuTempsController::class, 'index'])->name('emploi-du-temps.index');
    Route::get('/presences', [EtudiantPresenceController::class, 'index'])->name('presences.index');
    Route::get('/espace-cours', [EtudiantEspaceCoursController::class, 'index'])->name('espace-cours.index');
    Route::get('/espace-cours/download/{document}', [EtudiantEspaceCoursController::class, 'download'])->name('espace-cours.download');
    Route::post('/espace-cours/commentaire', [EtudiantEspaceCoursController::class, 'storeCommentaire'])->name('espace-cours.commentaire.store');
    Route::get('/demandes', [EtudiantDemandeController::class, 'index'])->name('demandes.index');
    Route::post('/demandes', [EtudiantDemandeController::class, 'store'])->name('demandes.store');
    Route::get('/profil', [EtudiantProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil', [EtudiantProfilController::class, 'update'])->name('profil.update');
    Route::get('/demandes/{demande}/telecharger', [EtudiantDemandeController::class, 'telecharger'])->name('demandes.telecharger');
});