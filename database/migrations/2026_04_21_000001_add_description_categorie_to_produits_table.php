<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->text('description')->nullable()->after('nom');
            $table->string('categorie')->nullable()->after('description');
            $table->integer('stock_alerte')->default(20)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['description', 'categorie', 'stock_alerte']);
        });
    }
};
