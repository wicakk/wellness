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
            $table->string('unit');                               // Unit kerja
            $table->string('jabatan')->nullable();
            $table->string('gender')->nullable();                 // L / P
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
