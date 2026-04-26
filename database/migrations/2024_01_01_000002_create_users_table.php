<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nip')->unique()->nullable();          // Nomor Induk Pegawai
            $table->string('phone')->nullable();
            $table->string('unit');                               // Asal ruangan / unit kerja
            $table->string('jabatan')->nullable();
            $table->string('gender')->nullable();                 // L / P

            // ── Data Personal Tambahan ──────────────────────────────
            $table->unsignedTinyInteger('usia')->nullable();                          // Usia (tahun)
            $table->string('pendidikan')->nullable();                                 // SMA, D3, S1, dst.
            $table->string('status_pernikahan')->nullable();                          // belum_menikah | menikah | cerai_hidup | cerai_mati
            $table->unsignedTinyInteger('lama_kerja_tahun')->nullable();              // Lama kerja – tahun
            $table->unsignedTinyInteger('lama_kerja_bulan')->nullable();              // Lama kerja – bulan (0–11)

            // ── Riwayat Kesehatan ──────────────────────────────────
            $table->boolean('has_health_issue')->nullable();                          // null = belum diisi
            $table->text('health_issue_detail')->nullable();                          // Detail penyakit jika ada

            // ── Kolom Lama ─────────────────────────────────────────
            $table->date('tanggal_lahir')->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->string('password');
            $table->string('theme_preference')->default('light'); // light / dark
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};