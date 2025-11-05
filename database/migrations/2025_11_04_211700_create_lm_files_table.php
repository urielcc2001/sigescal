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
        Schema::create('lm_files', function (Blueprint $t) {
            $t->id();
            $t->foreignId('folder_id')->constrained('lm_folders')->cascadeOnDelete();
            $t->string('filename');           
            $t->string('disk_path');    
            $t->string('mime', 191)->nullable();
            $t->unsignedBigInteger('size_bytes')->default(0);
            $t->timestamps();

            $t->index(['folder_id', 'filename']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lm_files');
    }
};
