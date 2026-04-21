<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacie_id')->constrained('pharmacies')->onDelete('cascade');
            $table->date('date_commande');
            $table->enum('statut', ['en_cours', 'validee', 'livree'])->default('en_cours');
            $table->integer('quantite');
            $table->decimal('tarif_unitaire', 8, 2);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('commandes');
    }
};
