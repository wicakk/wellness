@extends('layouts.app')
@section('title', isset($employee) ? 'Edit Pegawai' : 'Tambah Pegawai')
@section('page-title', isset($employee) ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ isset($employee) ? route('admin.employees.update', $employee) : route('admin.employees.store') }}"
          method="POST" class="space-y-5">
        @csrf
        @if(isset($employee)) @method('PUT') @endif

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-5">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Informasi Dasar</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $employee->name ?? '') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $employee->email ?? '') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $employee->nip ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $employee->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Unit Kerja <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', $employee->unit ?? '') }}"
                           list="unit-list" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <datalist id="unit-list">
                        @foreach($units as $unit)<option value="{{ $unit }}">@endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $employee->jabatan ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Gender</label>
                    <select name="gender" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('gender', $employee->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $employee->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Password --}}
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

        <div class="flex justify-between gap-4">
            <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">← Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors">
                {{ isset($employee) ? 'Simpan Perubahan' : 'Tambah Pegawai' }}
            </button>
        </div>
    </form>
</div>
@endsection
