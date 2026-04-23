@extends('layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PATCH')

        {{-- Info Dasar --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-5">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-16 h-16 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-2xl font-bold text-teal-700 dark:text-teal-400">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-bold">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->role->display_name }} — {{ $user->unit }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Gender</label>
                    <select name="gender" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">—</option>
                        <option value="L" {{ old('gender', $user->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $user->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Upload Foto</label>
                    <input type="file" name="avatar" accept="image/*"
                           class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium mb-1.5">Alamat</label>
                    <textarea name="address" rows="2"
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-4">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ganti Password (opsional)</h3>
            <div>
                <label class="block text-sm font-medium mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('current_password') border-red-400 @enderror">
                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Password Baru</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Konfirmasi</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
