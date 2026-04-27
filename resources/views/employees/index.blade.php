@extends('layouts.app')
@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola data seluruh pegawai</p>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.employees.create') }}"
           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
            + Tambah Pegawai
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-medium text-slate-500 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NIP..."
                       class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Role</label>
                <select name="role" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Unit</label>
                <select name="unit" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit }}" {{ request('unit') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm rounded-xl hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['search','role','unit']))
            <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-semibold text-sm">Daftar Pegawai <span class="text-slate-400 font-normal">({{ $employees->total() }})</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-5 py-3 text-left">Pegawai</th>
                        <th class="px-5 py-3 text-left">NIP</th>
                        <th class="px-5 py-3 text-left">Jabatan</th>
                        <th class="px-5 py-3 text-left">Unit</th>
                        <th class="px-5 py-3 text-left">Role</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($employees as $emp)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-sm font-bold text-teal-700 dark:text-teal-400 flex-shrink-0">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ $emp->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $emp->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $emp->nip ?? '—' }}</td>
                        <td class="px-5 py-3 text-xs text-slate-600 dark:text-slate-300">{{ $emp->jabatan ?? '—' }}</td>
                        <td class="px-5 py-3 text-xs text-slate-600 dark:text-slate-300">{{ $emp->unit }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                {{ $emp->role->display_name }}
                            </span>
                        </td>
                        <td class="px-5 py-3 flex gap-2">
                            <a href="{{ route('admin.employees.show', $emp) }}" class="text-xs text-teal-600 hover:underline">Detail</a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.employees.edit', $emp) }}" class="text-xs text-slate-500 hover:text-slate-700">Edit</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                            <div class="mb-2 flex justify-center">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            Tidak ada pegawai ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($employees->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">{{ $employees->links() }}</div>
        @endif
    </div>
</div>
@endsection