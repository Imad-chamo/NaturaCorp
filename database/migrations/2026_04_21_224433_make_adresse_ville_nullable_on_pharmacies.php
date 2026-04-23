<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->string('adresse')->nullable()->change();
            $table->string('ville')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->string('adresse')->nullable(false)->change();
            $table->string('ville')->nullable(false)->change();
        });
    }
};
