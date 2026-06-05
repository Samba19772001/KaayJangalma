<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parent_profiles')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->unique(['parent_id', 'teacher_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};