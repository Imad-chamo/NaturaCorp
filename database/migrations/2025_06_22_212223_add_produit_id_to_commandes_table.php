<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->foreignId('produit_id')->nullable()->constrained()->nullOnDelete();
        });

        DB::table('commandes')->update(['produit_id' => 1]);

        Schema::table('commandes', function (Blueprint $table) {
            $table->foreignId('produit_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropForeign(['produit_id']);
            $table->dropColumn('produit_id');
        });
    }

};
