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
        Schema::create('consommations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consommateur_id')
                    ->constrained('consommateurs')
                    ->onDelete('cascade');
            $table->date('date');
            $table->time('heure');
            $table->boolean('anomalie')->default(false);
            $table->boolean('absence')->default(false);
            $table->date('date_controle')->nullable();
            $table->integer('qte');
            $table->text('description')->nullable();
            $table->decimal('mt_sub', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consommations');
    }
};
