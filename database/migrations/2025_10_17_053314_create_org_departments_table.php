<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('org_departments', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();        // ej: vinculacion, escolares, sistemas
            $t->string('nombre');                // ej: "Vinculación", "Servicios Escolares", etc.
            $t->foreignId('area_id')
              ->nullable()
              ->constrained('areas')
              ->nullOnDelete();

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_departments');
    }
};
