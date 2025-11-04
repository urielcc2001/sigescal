<?php

use App\Models\User;
use App\Models\Area;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('area_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Area::class)->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'area_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area_user');
    }
};
