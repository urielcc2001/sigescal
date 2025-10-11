<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitud_adjuntos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('solicitud_id')
                  ->constrained('solicitudes')
                  ->cascadeOnDelete();

            // Sección a la que pertenece el adjunto
            // Puedes limitar a: cambio_dice | cambio_debe_decir | justificacion (si lo usas)
            $table->string('seccion', 40)->index();

            // Metadatos del archivo
            $table->string('path');                 // ej: "solicitudes/123/cambio_dice/archivo.png"
            $table->string('disk')->default('public'); // disco Laravel (public, s3, etc.)
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();    // bytes
            $table->unsignedInteger('width')->nullable();      // px (si es imagen)
            $table->unsignedInteger('height')->nullable();     // px
            $table->unsignedSmallInteger('orden')->default(0); // para ordenar

            $table->timestamps();

            $table->index(['solicitud_id','seccion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_adjuntos');
    }
};