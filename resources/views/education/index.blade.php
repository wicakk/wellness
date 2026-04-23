@extends('layouts.app')
@section('title', 'Edukasi Mental Health')
@section('page-title', 'Edukasi Mental Health')

@section('content')
<div class="space-y-5">

    {{-- Header + Upload Button --}}
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <p class="text-sm text-slate-500 dark:text-slate-400">Artikel, video, dan materi edukasi kesehatan mental</p>
        @if(auth()->user()->canManageCases())
        <a href="{{ route('education.create') }}"
           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
            + Upload Konten
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..."
               class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 w-48">
        <select name="category" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="type" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
            <option value="">Semua Tipe</option>
            <option value="artikel" {{ request('type') === 'artikel' ? 'selected' : '' }}><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg> Artikel</option>
            <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Video</option>
            <option value="infografis" {{ request('type') === 'infografis' ? 'selected' : '' }}><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> Infografis</option>
            <option value="panduan" {{ request('type') === 'panduan' ? 'selected' : '' }}><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Panduan</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm rounded-xl hover:bg-teal-700 transition-colors">Filter</button>
        @if(request()->hasAny(['search','category','type']))
        <a href="{{ route('education.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Reset</a>
        @endif
    </form>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($contents as $edu)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col">
            <div class="p-5 flex-1">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <span class="text-2xl">{{ match($edu->type) { 'artikel' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>', 'video' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>', 'infografis' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>', default => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' } }}</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ $edu->category }}</span>
                </div>
                <h3 class="font-semibold text-sm leading-snug line-clamp-2 mb-2">{{ $edu->title }}</h3>
                @if($edu->description)
                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $edu->description }}</p>
                @endif
            </div>
            <div class="px-5 pb-4 flex items-center justify-between">
                <span class="text-xs text-slate-400">👁 {{ $edu->view_count }}</span>
                <div class="flex gap-2">
                    @if(auth()->user()->canManageCases())
                    <a href="{{ route('education.edit', $edu) }}" class="text-xs text-slate-500 hover:text-teal-600 transition-colors">Edit</a>
                    @endif
                    <a href="{{ route('education.show', $edu) }}" class="text-xs font-medium text-teal-600 hover:text-teal-800 transition-colors">Buka →</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 p-12 text-center">
            <div class="text-5xl mb-3"><svg class="w-5 h-5 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
            <p class="text-sm text-slate-500">Belum ada konten edukasi.</p>
            @if(auth()->user()->canManageCases())
            <a href="{{ route('education.create') }}" class="mt-3 inline-block text-sm text-teal-600 hover:underline">Upload konten pertama →</a>
            @endif
        </div>
        @endforelse
    </div>

    {{ $contents->links() }}
</div>
@endsection
