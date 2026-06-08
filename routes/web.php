<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class,'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class,'login']);
    Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
});
Route::post('/logout', [LoginController::class,'logout'])->name('logout')->middleware('auth');
Route::get('/', fn() => redirect()->route('login'));

// Admin (accessible admin + professeur + etudiant — role check inside views)
Route::prefix('admin')->name('admin.')->middleware(['auth','active'])->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class,'index'])->name('dashboard');

    // Étudiants
    Route::get('etudiants', [Admin\EtudiantController::class,'index'])->name('etudiants.index');
    Route::get('etudiants/create', [Admin\EtudiantController::class,'create'])->name('etudiants.create');
    Route::post('etudiants', [Admin\EtudiantController::class,'store'])->name('etudiants.store');
    Route::get('etudiants/{etudiant}', [Admin\EtudiantController::class,'show'])->name('etudiants.show');
    Route::get('etudiants/{etudiant}/edit', [Admin\EtudiantController::class,'edit'])->name('etudiants.edit');
    Route::patch('etudiants/{etudiant}', [Admin\EtudiantController::class,'update'])->name('etudiants.update');
    Route::delete('etudiants/{etudiant}', [Admin\EtudiantController::class,'destroy'])->name('etudiants.destroy');
    Route::get('etudiants/{etudiant}/releve', [Admin\EtudiantController::class,'releve'])->name('etudiants.releve');
    Route::get('etudiants/{etudiant}/attestation', [Admin\EtudiantController::class,'attestation'])->name('etudiants.attestation');

    // Professeurs
    Route::get('professeurs', [Admin\ProfesseurController::class,'index'])->name('professeurs.index');
    Route::get('professeurs/create', [Admin\ProfesseurController::class,'create'])->name('professeurs.create');
    Route::post('professeurs', [Admin\ProfesseurController::class,'store'])->name('professeurs.store');
    Route::get('professeurs/{professeur}', [Admin\ProfesseurController::class,'show'])->name('professeurs.show');
    Route::get('professeurs/{professeur}/edit', [Admin\ProfesseurController::class,'edit'])->name('professeurs.edit');
    Route::patch('professeurs/{professeur}', [Admin\ProfesseurController::class,'update'])->name('professeurs.update');
    Route::delete('professeurs/{professeur}', [Admin\ProfesseurController::class,'destroy'])->name('professeurs.destroy');

    // Programmes & Modules
    Route::get('programmes', [Admin\ProgrammeController::class,'index'])->name('programmes.index');
    Route::post('programmes', [Admin\ProgrammeController::class,'store'])->name('programmes.store');
    Route::get('programmes/{programme}/modules', [Admin\ProgrammeController::class,'modules'])->name('programmes.modules');
    Route::delete('programmes/{programme}', [Admin\ProgrammeController::class,'destroy'])->name('programmes.destroy');
    Route::post('modules', [Admin\ProgrammeController::class,'storeModule'])->name('modules.store');
    Route::patch('modules/{module}', [Admin\ProgrammeController::class,'updateModule'])->name('modules.update');
    Route::delete('modules/{module}', [Admin\ProgrammeController::class,'destroyModule'])->name('modules.destroy');

    // Emploi du temps
    Route::get('emploi-du-temps', [Admin\EmploiDuTempsController::class,'index'])->name('emploi-du-temps.index');
    Route::post('emploi-du-temps', [Admin\EmploiDuTempsController::class,'store'])->name('emploi-du-temps.store');
    Route::delete('emploi-du-temps/{emploiDuTemps}', [Admin\EmploiDuTempsController::class,'destroy'])->name('emploi-du-temps.destroy');
    Route::get('emploi-du-temps/export-pdf', [Admin\EmploiDuTempsController::class,'exportPdf'])->name('emploi-du-temps.export-pdf');

    // Notes
    Route::get('notes', [Admin\NoteController::class,'index'])->name('notes.index');
    Route::post('notes', [Admin\NoteController::class,'store'])->name('notes.store');
    Route::patch('notes/{note}', [Admin\NoteController::class,'update'])->name('notes.update');
    Route::post('notes/bulk', [Admin\NoteController::class,'bulkUpdate'])->name('notes.bulk');
    Route::get('notes/export-pdf', [Admin\NoteController::class,'exportPdf'])->name('notes.export-pdf');

    // Présences
    Route::get('presences', [Admin\PresenceController::class,'index'])->name('presences.index');
    Route::post('presences', [Admin\PresenceController::class,'store'])->name('presences.store');
    Route::get('presences/export-pdf', [Admin\PresenceController::class,'exportPdf'])->name('presences.export-pdf');

    // Salles
    Route::get('salles', [Admin\SalleController::class,'index'])->name('salles.index');
    Route::post('salles', [Admin\SalleController::class,'store'])->name('salles.store');
    Route::patch('salles/{salle}', [Admin\SalleController::class,'update'])->name('salles.update');
    Route::delete('salles/{salle}', [Admin\SalleController::class,'destroy'])->name('salles.destroy');
    Route::post('salles/reservations', [Admin\SalleController::class,'storeReservation'])->name('salles.reservations.store');
    Route::delete('salles/reservations/{reservation}', [Admin\SalleController::class,'destroyReservation'])->name('salles.reservations.destroy');

    // Demandes
    Route::get('demandes', [Admin\DemandeController::class,'index'])->name('demandes.index');
    Route::post('demandes/{demande}/approuver', [Admin\DemandeController::class,'approuver'])->name('demandes.approuver');
    Route::post('demandes/{demande}/rejeter', [Admin\DemandeController::class,'rejeter'])->name('demandes.rejeter');
    Route::get('demandes/{demande}/telecharger', [Admin\DemandeController::class,'telecharger'])->name('demandes.telecharger');

    // Rapports
    Route::get('rapports', [Admin\RapportController::class,'index'])->name('rapports.index');
    Route::get('rapports/export-pdf', [Admin\RapportController::class,'exportPdf'])->name('rapports.export-pdf');

    // Espace cours
    Route::get('espace-cours', [Admin\EspaceCoursController::class,'index'])->name('espace-cours.index');
    Route::post('espace-cours/annonces', [Admin\EspaceCoursController::class,'storeAnnonce'])->name('espace-cours.annonces.store');
    Route::delete('espace-cours/annonces/{annonce}', [Admin\EspaceCoursController::class,'destroyAnnonce'])->name('espace-cours.annonces.destroy');
    Route::post('espace-cours/documents', [Admin\EspaceCoursController::class,'storeDocument'])->name('espace-cours.documents.store');
    Route::delete('espace-cours/documents/{document}', [Admin\EspaceCoursController::class,'destroyDocument'])->name('espace-cours.documents.destroy');
    Route::post('espace-cours/commentaires', [Admin\EspaceCoursController::class,'storeCommentaire'])->name('espace-cours.commentaires.store');

    // Paramètres
    Route::get('parametres', [Admin\ParametreController::class,'index'])->name('parametres.index');
});
