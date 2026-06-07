<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('photo')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('education_level')->nullable();
            $table->string('university')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('monthly_rate', 8, 2)->nullable();
            $table->enum('verified_status', ['pending', 'verified', 'refused'])->default('pending');
            $table->boolean('is_premium')->default(false);
            $table->float('average_rating')->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->unsignedInteger('profile_views')->default(0);
            $table->unsignedInteger('whatsapp_clicks')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};