{{-- resources/views/admin/rooms/show.blade.php --}}
@extends('layouts.app')

@section('title', $room->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.rooms.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">{{ $room->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-mono">{{ $room->code }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.rooms.edit', $room) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
        <h2 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Informasi Ruangan</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
            <div>
                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-1">Nama Ruangan</dt>
                <dd class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $room->name }}</dd>
            </div>
            <div>
                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-1">Kode</dt>
                <dd>
                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-mono">{{ $room->code }}</span>
                </dd>
            </div>
            <div>
                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-1">Status</dt>
                <dd>
                    <span class="px-2.5 py-1 rounded-lg text-sm font-medium
                        {{ $room->is_active
                            ? 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300'
                            : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                        {{ $room->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-1">Dibuat oleh</dt>
                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ $room->creator?->name ?? '-' }}</dd>
            </div>
            @if($room->description)
            <div class="sm:col-span-2">
                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-1">Deskripsi</dt>
                <dd class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $room->description }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-xs text-slate-400 dark:text-slate-500 mb-1">Tanggal dibuat</dt>
                <dd class="text-sm text-slate-800 dark:text-slate-100">{{ $room->created_at->format('d M Y, H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-red-200 dark:border-red-800/50 p-6">
        <h2 class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3">Zona Berbahaya</h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-100">Hapus Ruangan</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
            </div>
            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                  onsubmit="return confirm('Yakin ingin menghapus ruangan {{ $room->name }}?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 text-sm font-medium rounded-xl transition-colors border border-red-200 dark:border-red-700">
                    Hapus Ruangan
                </button>
            </form>
        </div>
    </div>

</div>
@endsection