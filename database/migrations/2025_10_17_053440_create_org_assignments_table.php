<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('org_assignments', function (Blueprint $t) {
            $t->id();

            // A qué puesto corresponde esta asignación
            $t->foreignId('org_position_id')
              ->constrained('org_positions')
              ->cascadeOnDelete();

            $t->foreignId('user_id')->nullable()
              ->constrained('users')->nullOnDelete();

            // Datos libres si aún no hay user
            $t->string('nombre')->nullable();
            $t->string('correo')->nullable();
            $t->string('telefono')->nullable();

            // Historial (fin = NULL => vigente)
            $t->date('inicio')->nullable();
            $t->date('fin')->nullable();

            $t->timestamps();

            // Índices útiles
            $t->index(['org_position_id', 'fin']);
        });

        // Postgres: índice único parcial para asegurar SOLO UNA asignación vigente por puesto
        // (fin IS NULL) => solo aplica a las vigentes.
        DB::statement('
            CREATE UNIQUE INDEX org_assignments_one_active_per_position
            ON org_assignments (org_position_id)
            WHERE fin IS NULL
        ');
    }

    public function down(): void
    {
        // Borrar el índice parcial antes de la tabla
        DB::statement('DROP INDEX IF EXISTS org_assignments_one_active_per_position');

        Schema::dropIfExists('org_assignments');
    }
};
