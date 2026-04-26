@extends('layouts.app')
@section('title', isset($employee) ? 'Edit Pegawai' : 'Tambah Pegawai')
@section('page-title', isset($employee) ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru')

@section('content')
<div class="max-w-8xl mx-auto">
    <form action="{{ isset($employee) ? route('admin.employees.update', $employee) : route('admin.employees.store') }}"
          method="POST" class="space-y-5">
        @csrf
        @if(isset($employee)) @method('PUT') @endif

        {{-- ==================== INFORMASI DASAR ==================== --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-5">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Informasi Dasar</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- 1. Nama Lengkap --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $employee->name ?? '') }}" required
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $employee->email ?? '') }}" required
                           placeholder="nama@rscm.co.id"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 2. NIP --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">NIP <span class="text-red-500">*</span></label>
                    <input type="text" name="nip" value="{{ old('nip', $employee->nip ?? '') }}" required
                           placeholder="Nomor Induk Pegawai"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('nip') border-red-400 @enderror">
                    @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 3. Jabatan --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $employee->jabatan ?? '') }}" required
                           placeholder="Contoh: Perawat, Dokter, Staf"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('jabatan') border-red-400 @enderror">
                    @error('jabatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 8. Asal Ruangan (Unit Kerja) --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Asal Ruangan / Unit Kerja <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', $employee->unit ?? '') }}"
                           list="unit-list" required
                           placeholder="Ketik atau pilih ruangan"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <datalist id="unit-list">
                        @foreach($units as $unit)<option value="{{ $unit }}">@endforeach
                    </datalist>
                    @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No. Telepon --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

            </div>
        </div>

        {{-- ==================== DATA PERSONAL ==================== --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-5">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Data Personal</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- 4. Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="gender" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('gender') border-red-400 @enderror">
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('gender', $employee->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $employee->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 5. Usia --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Usia <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="usia" value="{{ old('usia', $employee->usia ?? '') }}" required
                               min="18" max="70" placeholder="Tahun"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('usia') border-red-400 @enderror">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400 pointer-events-none">tahun</span>
                    </div>
                    @error('usia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 6. Pendidikan --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                    <select name="pendidikan" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('pendidikan') border-red-400 @enderror">
                        <option value="">— Pilih —</option>
                        @foreach([
                            'SMA/SMK'    => 'SMA / SMK',
                            'D1'         => 'Diploma 1 (D1)',
                            'D2'         => 'Diploma 2 (D2)',
                            'D3'         => 'Diploma 3 (D3)',
                            'D4'         => 'Diploma 4 (D4)',
                            'S1'         => 'Sarjana (S1)',
                            'Profesi'    => 'Profesi (Ners / Dokter / Apoteker)',
                            'S2'         => 'Magister (S2)',
                            'S3'         => 'Doktor (S3)',
                        ] as $val => $label)
                        <option value="{{ $val }}" {{ old('pendidikan', $employee->pendidikan ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('pendidikan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 7. Status Pernikahan --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Status Pernikahan <span class="text-red-500">*</span></label>
                    <select name="status_pernikahan" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('status_pernikahan') border-red-400 @enderror">
                        <option value="">— Pilih —</option>
                        @foreach([
                            'belum_menikah' => 'Belum Menikah',
                            'menikah'       => 'Menikah',
                            'cerai_hidup'   => 'Cerai Hidup',
                            'cerai_mati'    => 'Cerai Mati',
                        ] as $val => $label)
                        <option value="{{ $val }}" {{ old('status_pernikahan', $employee->status_pernikahan ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status_pernikahan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 9. Lama Kerja di RSCM --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium mb-1.5">Lama Bekerja di RSCM <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <input type="number" name="lama_kerja_tahun"
                                   value="{{ old('lama_kerja_tahun', $employee->lama_kerja_tahun ?? '') }}"
                                   min="0" max="50" placeholder="0"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('lama_kerja_tahun') border-red-400 @enderror">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400 pointer-events-none">tahun</span>
                        </div>
                        <div class="relative">
                            <input type="number" name="lama_kerja_bulan"
                                   value="{{ old('lama_kerja_bulan', $employee->lama_kerja_bulan ?? '') }}"
                                   min="0" max="11" placeholder="0"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('lama_kerja_bulan') border-red-400 @enderror">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400 pointer-events-none">bulan</span>
                        </div>
                    </div>
                    @error('lama_kerja_tahun') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- ==================== RIWAYAT KESEHATAN ==================== --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-4">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Riwayat Kesehatan</h3>

            {{-- 10. Apakah memiliki masalah kesehatan --}}
            <div>
                <label class="block text-sm font-medium mb-2">Apakah memiliki masalah kesehatan? <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="has_health_issue" value="1" id="health_yes"
                               {{ old('has_health_issue', $employee->has_health_issue ?? '') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-teal-600 focus:ring-teal-500">
                        <span class="text-sm">Ya</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="has_health_issue" value="0" id="health_no"
                               {{ old('has_health_issue', $employee->has_health_issue ?? '') === '0' ? 'checked' : '' }}
                               class="w-4 h-4 text-teal-600 focus:ring-teal-500">
                        <span class="text-sm">Tidak</span>
                    </label>
                </div>
                @error('has_health_issue') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Detail masalah kesehatan (muncul jika pilih Ya) --}}
            <div id="health-detail-wrapper" class="{{ old('has_health_issue', $employee->has_health_issue ?? '') == '1' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium mb-1.5">Sebutkan masalah kesehatan yang dimiliki</label>
                <textarea name="health_issue_detail" rows="3"
                          placeholder="Contoh: Hipertensi, Diabetes Melitus Tipe 2, Asma, dll."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none @error('health_issue_detail') border-red-400 @enderror">{{ old('health_issue_detail', $employee->health_issue_detail ?? '') }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Pisahkan dengan koma jika lebih dari satu kondisi.</p>
                @error('health_issue_detail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- ==================== PASSWORD ==================== --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-4">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                {{ isset($employee) ? 'Ganti Password (kosongkan jika tidak diubah)' : 'Password' }}
            </h3>
            @if(isset($employee))
            <div>
                <label class="block text-sm font-medium mb-1.5">Password Baru</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            @else
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('password') border-red-400 @enderror">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            @endif
        </div>

        {{-- ==================== ACTION BUTTONS ==================== --}}
        <div class="flex justify-between gap-4">
            <a href="{{ route('admin.employees.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                ← Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors">
                {{ isset($employee) ? 'Simpan Perubahan' : 'Tambah Pegawai' }}
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    // Toggle detail kesehatan
    document.querySelectorAll('input[name="has_health_issue"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var wrapper = document.getElementById('health-detail-wrapper');
            if (this.value === '1') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
                document.querySelector('textarea[name="health_issue_detail"]').value = '';
            }
        });
    });
</script>
@endpush
@endsection