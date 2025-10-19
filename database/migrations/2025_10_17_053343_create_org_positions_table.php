<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('org_positions', function (Blueprint $t) {
            $t->id();

            $t->string('slug')->unique();     // ej: director, subdir-academica
            $t->string('titulo');             // Título visible del puest
            $t->enum('nivel', [
                'director',
                'subdirector',
                'coordinacion',
                'control',
                'jefe_depto',
                'centro', 
            ])->index();

            // Anclajes flexibles (uno u otro pueden ser null según el puesto)
            $t->foreignId('area_id')->nullable()
              ->constrained('areas')->nullOnDelete();

            $t->foreignId('org_department_id')->nullable()
              ->constrained('org_departments')->nullOnDelete();

            $t->unsignedInteger('orden')->default(0);

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_positions');
    }
};
