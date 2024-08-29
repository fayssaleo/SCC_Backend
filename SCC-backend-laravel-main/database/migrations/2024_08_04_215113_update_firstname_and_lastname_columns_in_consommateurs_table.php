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
        Schema::table('consommateurs', function (Blueprint $table) {
            // Modifie les colonnes firstname et lastname pour qu'elles acceptent les valeurs nulles
            $table->string('firstname')->nullable()->change();
            $table->string('lastname')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consommateurs', function (Blueprint $table) {
            // Réinitialise les colonnes pour ne pas accepter les valeurs nulles
            $table->string('firstname')->nullable(false)->change();
            $table->string('lastname')->nullable(false)->change();
        });
    }
};
