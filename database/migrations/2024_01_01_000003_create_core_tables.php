<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // Programmes
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->enum('type', ['Licence','Master','DUT','BTS','Doctorat']);
            $table->integer('duree_annees')->default(3);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Modules
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->string('code')->nullable();
            $table->enum('semestre_type', ['Impair','Pair'])->default('Impair');
            $table->integer('heures')->default(20);
            $table->integer('credits')->default(3);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Professeurs
        Schema::create('professeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_id')->unique();
            $table->string('specialite')->nullable();
            $table->text('bio')->nullable();
            $table->string('bureau')->nullable();
            $table->string('telephone')->nullable();
            $table->enum('statut', ['Actif','Congé','Inactif'])->default('Actif');
            $table->date('date_embauche');
            $table->timestamps();
            $table->softDeletes();
        });

        // Module-Professeur pivot
        Schema::create('module_professeur', function (Blueprint $table) {
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professeur_id')->constrained()->cascadeOnDelete();
            $table->primary(['module_id','professeur_id']);
        });

        // Étudiants
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('programme_id')->constrained()->cascadeOnDelete();
            $table->string('numero_carte')->unique();
            $table->integer('semestre_actuel')->default(1);
            $table->date('date_inscription');
            $table->date('date_diplome')->nullable();
            $table->enum('statut', ['Actif','Suspendu','Diplômé','Retiré'])->default('Actif');
            $table->decimal('moyenne_generale', 4, 2)->default(0);
            $table->string('tuteur_nom')->nullable();
            $table->string('tuteur_telephone')->nullable();
            $table->string('tuteur_email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Salles
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('batiment')->nullable();
            $table->integer('capacite')->default(30);
            $table->enum('type', ['Amphithéâtre','Cours','TD','Laboratoire','Salle informatique']);
            $table->enum('statut', ['Disponible','Occupée','Maintenance'])->default('Disponible');
            $table->timestamps();
        });

        // Emploi du temps
        Schema::create('emploi_du_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professeur_id')->constrained()->cascadeOnDelete();
            $table->foreignId('salle_id')->nullable()->constrained('salles')->nullOnDelete();
            $table->enum('jour', ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('type_seance', ['CM','TD','TP']);
            $table->string('annee_academique')->default('2024-2025');
            $table->integer('semestre')->default(4);
            $table->timestamps();
        });

        // Inscriptions module-étudiant
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('annee_academique')->default('2024-2025');
            $table->integer('semestre');
            $table->timestamps();
            $table->unique(['etudiant_id','module_id','annee_academique']);
        });

        // Notes
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('annee_academique')->default('2024-2025');
            $table->integer('semestre');
            $table->decimal('note_cc1', 4, 2)->nullable();
            $table->decimal('note_cc2', 4, 2)->nullable();
            $table->decimal('note_examen', 4, 2)->nullable();
            $table->decimal('note_finale', 4, 2)->nullable();
            $table->string('mention')->nullable();
            $table->timestamps();
            $table->unique(['etudiant_id','module_id','annee_academique']);
        });

        // Présences
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('statut', ['Présent','Absent','Justifié'])->default('Présent');
            $table->text('justification')->nullable();
            $table->timestamps();
            $table->unique(['etudiant_id','module_id','date']);
        });

        // Réservations salles
        Schema::create('reservations_salles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salle_id')->constrained('salles')->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('professeur_id')->nullable()->constrained()->nullOnDelete();
            $table->string('titre');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->timestamps();
        });

        // Demandes administratives
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['Attestation de scolarité','Relevé de notes','Certificat de diplôme','Autre']);
            $table->text('motif')->nullable();
            $table->enum('statut', ['En attente','Approuvée','Rejetée'])->default('En attente');
            $table->text('commentaire_admin')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('traite_le')->nullable();
            $table->string('fichier_genere')->nullable();
            $table->timestamps();
        });

        // Documents espace cours
        Schema::create('documents_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('titre');
            $table->string('fichier');
            $table->string('nom_fichier');
            $table->integer('taille')->default(0);
            $table->timestamps();
        });

        // Annonces espace cours
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('titre');
            $table->text('contenu');
            $table->enum('audience', ['tous','etudiants','professeurs'])->default('tous');
            $table->boolean('is_epinglé')->default(false);
            $table->timestamps();
        });

        // Commentaires
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annonce_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('contenu');
            $table->timestamps();
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Sanctum tokens
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('commentaires');
        Schema::dropIfExists('annonces');
        Schema::dropIfExists('documents_cours');
        Schema::dropIfExists('demandes');
        Schema::dropIfExists('reservations_salles');
        Schema::dropIfExists('presences');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('inscriptions');
        Schema::dropIfExists('emploi_du_temps');
        Schema::dropIfExists('etudiants');
        Schema::dropIfExists('module_professeur');
        Schema::dropIfExists('professeurs');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('salles');
    }
};
