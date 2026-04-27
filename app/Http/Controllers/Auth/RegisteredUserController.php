<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form registrasi.
     */
    public function create(): View
    {
        $roles = Role::all();
        $units = User::distinct()->pluck('unit')->filter()->sort()->values();

        return view('auth.register', compact('roles', 'units'));
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // ── Informasi Dasar ────────────────────────────────────
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nip'                 => ['required', 'string', 'max:50', 'unique:users,nip'],
            'role_id'             => ['required', 'exists:roles,id'],
            'unit'                => ['required', 'string', 'max:100'],
            'jabatan'             => ['required', 'string', 'max:100'],
            'phone'               => ['nullable', 'string', 'max:20'],

            // ── Data Personal ──────────────────────────────────────
            'gender'              => ['required', 'in:L,P'],
            'usia'                => ['required', 'integer', 'min:18', 'max:70'],
            'pendidikan'          => ['required', 'in:SMA/SMK,D1,D2,D3,D4,S1,Profesi,S2,S3'],
            'status_pernikahan'   => ['required', 'in:belum_menikah,menikah,cerai_hidup,cerai_mati'],
            'lama_kerja_tahun'    => ['required', 'integer', 'min:0', 'max:50'],
            'lama_kerja_bulan'    => ['required', 'integer', 'min:0', 'max:11'],

            // ── Riwayat Kesehatan ──────────────────────────────────
            'has_health_issue'    => ['required', 'boolean'],
            'health_issue_detail' => ['nullable', 'string', 'max:1000', 'required_if:has_health_issue,1'],

            // ── Password ───────────────────────────────────────────
            'password'            => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            // Pesan validasi dalam Bahasa Indonesia
            'name.required'              => 'Nama lengkap wajib diisi.',
            'email.required'             => 'Email wajib diisi.',
            'email.unique'               => 'Email sudah terdaftar.',
            'nip.required'               => 'NIP wajib diisi.',
            'nip.unique'                 => 'NIP sudah terdaftar.',
            'role_id.required'           => 'Role wajib dipilih.',
            'role_id.exists'             => 'Role tidak valid.',
            'unit.required'              => 'Unit kerja wajib diisi.',
            'jabatan.required'           => 'Jabatan wajib diisi.',
            'gender.required'            => 'Jenis kelamin wajib dipilih.',
            'usia.required'              => 'Usia wajib diisi.',
            'usia.min'                   => 'Usia minimal 18 tahun.',
            'usia.max'                   => 'Usia maksimal 70 tahun.',
            'pendidikan.required'        => 'Pendidikan terakhir wajib dipilih.',
            'status_pernikahan.required' => 'Status pernikahan wajib dipilih.',
            'lama_kerja_tahun.required'  => 'Lama kerja (tahun) wajib diisi.',
            'lama_kerja_bulan.required'  => 'Lama kerja (bulan) wajib diisi.',
            'has_health_issue.required'  => 'Riwayat kesehatan wajib dipilih.',
            'health_issue_detail.required_if' => 'Detail kondisi kesehatan wajib diisi.',
            'password.required'          => 'Password wajib diisi.',
            'password.min'               => 'Password minimal 8 karakter.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            // Informasi Dasar
            'name'                => $request->name,
            'email'               => $request->email,
            'nip'                 => $request->nip,
            'role_id'             => $request->role_id,
            'unit'                => $request->unit,
            'jabatan'             => $request->jabatan,
            'phone'               => $request->phone,

            // Data Personal
            'gender'              => $request->gender,
            'usia'                => $request->usia,
            'pendidikan'          => $request->pendidikan,
            'status_pernikahan'   => $request->status_pernikahan,
            'lama_kerja_tahun'    => $request->lama_kerja_tahun,
            'lama_kerja_bulan'    => $request->lama_kerja_bulan,

            // Riwayat Kesehatan
            'has_health_issue'    => (bool) $request->has_health_issue,
            'health_issue_detail' => $request->boolean('has_health_issue')
                                        ? $request->health_issue_detail
                                        : null,

            // Password & Default
            'password'            => Hash::make($request->password),
            'is_active'           => true,
            'theme_preference'    => 'light',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}