<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('journal_pedagogique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('titre');
            $table->enum('type_seance', ['CM','TD','TP']);
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('salle')->nullable();
            $table->text('notes')->nullable();
            $table->integer('presents')->default(0);
            $table->integer('total_inscrits')->default(0);
            $table->timestamps();
        });

        Schema::create('demandes_professeur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained()->cascadeOnDelete();
            $table->string('type');
$table->text('motif')->nullable();
$table->string('statut')->default('En attente');
            $table->text('commentaire_admin')->nullable();
            $table->string('fichier_genere')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('traite_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('demandes_professeur');
        Schema::dropIfExists('journal_pedagogique');
    }
};
