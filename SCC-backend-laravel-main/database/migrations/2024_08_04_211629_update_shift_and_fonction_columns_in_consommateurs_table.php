<?php

// database/migrations/xxxx_xx_xx_update_shift_and_fonction_columns_in_consommateurs_table.php
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
            $table->string('shift')->nullable()->change();
            $table->string('fonction')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consommateurs', function (Blueprint $table) {
            $table->string('shift')->nullable(false)->change();
            $table->string('fonction')->nullable(false)->change();
        });
    }
};

