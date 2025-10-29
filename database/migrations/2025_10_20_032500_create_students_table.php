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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Identidad y login
            $table->string('numcontrol', 20)->unique();   // clave de acceso (única)
            $table->string('nombre', 150);

            // Datos académicos (texto para facilitar importación desde CSV)
            $table->unsignedTinyInteger('semestre')->nullable();     // 1..12 (si aplica)
            $table->string('carrera_code', 10)->nullable();          // ej. IBQOK, ICOK, CPOK, ESPOK
            $table->string('grupo', 10)->nullable();                 // ej. A, B1, etc.
            $table->string('turno', 12)->nullable();                 // ej. MATUTINO / VESPERTINO / M / V
            $table->string('aula', 20)->nullable();                  // ej. B-203

            // Contacto y autenticación
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();                // no lo usamos para login
            $table->string('password');                              // hash (bcrypt/argon)
            $table->boolean('must_change_password')->default(false); // forzar cambio en 1er login

            // Estado y métricas
            $table->timestamp('last_login_at')->nullable();
            $table->string('status', 16)->default('active');         // active | inactive (u otros si quieres)

            // Índices auxiliares para filtros/reportes
            $table->index('carrera_code');
            $table->index(['semestre', 'grupo', 'turno']);

            $table->timestamps();
            $table->softDeletes(); // por si requieres bajas lógicas sin perder histórico
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
