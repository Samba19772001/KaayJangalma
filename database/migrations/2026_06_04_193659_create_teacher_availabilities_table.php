<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->enum('day', ['monday','tuesday','wednesday','thursday','friday','saturday','sunday']);
            $table->enum('slot', ['morning', 'afternoon', 'evening']);
            $table->unique(['teacher_id', 'day', 'slot']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_availabilities');
    }
};