<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('document_joints', function (Blueprint $table) {
            $table->dropForeign(['commande_id']);
            $table->foreign('commande_id')
                ->references('id')
                ->on('commandes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('document_joints', function (Blueprint $table) {
            $table->dropForeign(['commande_id']);
            $table->foreign('commande_id')
                ->references('id')
                ->on('commandes')
                ->onDelete('no action');
        });
    }

};
