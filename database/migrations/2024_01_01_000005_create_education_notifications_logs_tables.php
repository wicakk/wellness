<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type');          // artikel, video, infografis, panduan
            $table->string('category');      // stres, kecemasan, depresi, mindfulness, produktivitas
            $table->string('file_path')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('external_url')->nullable();
            $table->integer('view_count')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('education_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('education_content_id')->constrained()->onDelete('cascade');
            $table->timestamp('accessed_at');
            $table->integer('duration_seconds')->nullable(); // Durasi baca/tonton
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');          // skrining_reminder, risiko_tinggi, follow_up, jadwal
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // extra context (case_id, screening_id, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');        // login, logout, create, update, delete, view
            $table->string('model')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('education_access_logs');
        Schema::dropIfExists('education_contents');
    }
};
