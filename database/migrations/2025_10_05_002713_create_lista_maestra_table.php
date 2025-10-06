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
        Schema::create('lista_maestra', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();     
            $table->string('nombre');               
            $table->unsignedInteger('revision')->default(0);
            $table->date('fecha_autorizacion')->nullable();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_maestra');
    }
};
