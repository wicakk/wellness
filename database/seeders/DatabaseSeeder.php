<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ─────────────────────────────────────────────────────────
        $roles = [
            ['name' => 'admin',            'display_name' => 'Administrator',    'description' => 'Akses penuh ke seluruh sistem'],
            ['name' => 'wellness_warrior', 'display_name' => 'Wellness Warrior', 'description' => 'Koordinator program kesehatan mental'],
            ['name' => 'psikolog',         'display_name' => 'Psikolog',         'description' => 'Tenaga profesional psikologi'],
            ['name' => 'pegawai',          'display_name' => 'Pegawai',          'description' => 'Pengguna umum / karyawan'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // ── Demo Users ──────────────────────────────────────────────────────
        $adminRole    = Role::where('name', 'admin')->first();
        $wellnessRole = Role::where('name', 'wellness_warrior')->first();
        $psikologRole = Role::where('name', 'psikolog')->first();
        $pegawaiRole  = Role::where('name', 'pegawai')->first();

        User::firstOrCreate(['email' => 'admin@wellness.id'], [
            'role_id'  => $adminRole->id,
            'name'     => 'Admin Sistem',
            'nip'      => '199000001',
            'unit'     => 'IT & Sistem',
            'password' => Hash::make('password'),
        ]);

        User::firstOrCreate(['email' => 'wellness@wellness.id'], [
            'role_id'  => $wellnessRole->id,
            'name'     => 'Sari Wellness',
            'nip'      => '199000002',
            'unit'     => 'SDM',
            'password' => Hash::make('password'),
        ]);

        User::firstOrCreate(['email' => 'psikolog@wellness.id'], [
            'role_id'  => $psikologRole->id,
            'name'     => 'Dr. Budi Psikolog',
            'nip'      => '199000003',
            'unit'     => 'Kesehatan',
            'password' => Hash::make('password'),
        ]);

        User::firstOrCreate(['email' => 'pegawai@wellness.id'], [
            'role_id'  => $pegawaiRole->id,
            'name'     => 'Andi Pegawai',
            'nip'      => '199000004',
            'unit'     => 'Operasional',
            'password' => Hash::make('password'),
        ]);

        // ── Kuesioner ────────────────────────────────────────────────────────
        // Kuesioner SENGAJA dikosongkan — isi sendiri melalui UI aplikasi:
        //   Login sebagai Admin/Wellness → Kuesioner → Buat Kuesioner Baru
        //
        // Atau buat seeder baru:
        //   php artisan make:seeder QuestionnaireSeeder
        // ─────────────────────────────────────────────────────────────────────
    }
}
