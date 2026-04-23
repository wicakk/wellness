<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');         // Pegawai
            $table->foreignId('screening_id')->constrained()->onDelete('restrict');    // Triggered by
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // PIC
            $table->string('priority');           // rendah, sedang, tinggi, kritis
            $table->string('status')->default('belum_ditindaklanjuti'); // belum_ditindaklanjuti, diproses, selesai
            $table->text('notes')->nullable();
            $table->date('target_completion')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->foreignId('performed_by')->constrained('users')->onDelete('restrict'); // Psikolog/Wellness
            $table->string('type'); // konseling, rujukan, edukasi, pendampingan, lainnya
            $table->date('intervention_date');
            $table->text('notes');
            $table->string('outcome')->nullable();       // Hasil intervensi
            $table->string('follow_up_status')->nullable(); // perlu_followup, selesai
            $table->date('next_follow_up')->nullable();
            $table->timestamps();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Target pegawai (nullable = semua)
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // konseling, program, reminder, webinar
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->boolean('is_reminder')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('interventions');
        Schema::dropIfExists('cases');
    }
};
