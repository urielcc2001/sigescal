<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Folio oficial (único)
            $table->string('folio', 30)->unique()->nullable(); // se llenará después del insert

            // Relación con students
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();

            // Datos principales
            $table->string('tipo', 20);                // 'queja' | 'sugerencia'
            $table->string('titulo', 200)->nullable(); // opcional, ayuda en listados
            $table->text('descripcion');               // contenido de la queja/sugerencia

            // Flujo simple
            $table->string('estado', 20)->default('abierta'); // abierta | en_revision | respondida | cerrada
            $table->text('respuesta')->nullable();            // respuesta del encargado
            $table->timestamp('respondida_at')->nullable();   // cuándo se respondió
            $table->timestamp('visto_por_alumno_at')->nullable(); // para ocultarla del inbox del alumno

            // Auditoría ligera opcional
            $table->string('origen_ip', 45)->nullable();      // IPv4/IPv6

            $table->timestamps();

            // Índices útiles para bandejas y filtros
            $table->index(['student_id', 'estado']);
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
