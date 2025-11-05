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
        Schema::create('lm_folders', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('parent_id')->nullable()
              ->constrained('lm_folders')->nullOnDelete();
            $t->string('slug_path')->index(); 
            $t->timestamps();

            $t->unique(['slug_path']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lm_folders');
    }
};
