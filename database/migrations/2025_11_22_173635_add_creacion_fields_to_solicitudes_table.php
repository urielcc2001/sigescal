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
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('codigo_nuevo')
                  ->nullable()
                  ->after('tipo');

            $table->string('titulo_nuevo')
                  ->nullable()
                  ->after('codigo_nuevo');

            $table->string('revision_nueva')
                  ->nullable()
                  ->after('titulo_nuevo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            //
        });
    }
};
