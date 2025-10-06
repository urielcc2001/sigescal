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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->nullable();
            $table->date('fecha')->nullable();

            $table->foreignId('documento_id')->nullable()->constrained('lista_maestra')->nullOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('tipo', ['creacion','modificacion','baja'])->default('modificacion');
            $table->text('cambio_dice')->nullable();
            $table->text('cambio_debe_decir')->nullable();
            $table->text('justificacion')->nullable();
            $table->boolean('requiere_capacitacion')->default(false);
            $table->boolean('requiere_difusion')->default(true);
            $table->enum('estado', ['en_revision','aprobada','rechazada'])->default('en_revision');

            $table->timestamps();

            $table->index(['documento_id','area_id','tipo','estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
