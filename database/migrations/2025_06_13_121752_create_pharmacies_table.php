<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('siret')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse');
            $table->string('code_postal');
            $table->string('ville');
            $table->enum('statut', ['prospect', 'client_actif', 'client_inactif'])->default('prospect');
            $table->date('derniere_prise_contact')->nullable();
            $table->foreignId('commercial_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pharmacies');
    }
};

