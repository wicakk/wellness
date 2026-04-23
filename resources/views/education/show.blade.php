@extends('layouts.app')
@section('title', $content->title)
@section('page-title', 'Edukasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-3xl">{{ match($content->type) { 'artikel' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>', 'video' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>', 'infografis' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>', default => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' } }}</span>
            <div>
                <span class="text-xs px-2 py-1 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400">{{ $content->category }}</span>
                <span class="text-xs text-slate-400 ml-2">{{ $content->view_count }} views</span>
            </div>
        </div>
        <h1 class="text-xl font-bold mb-3">{{ $content->title }}</h1>
        @if($content->description)
        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ $content->description }}</p>
        @endif

        @if($content->external_url)
        <div class="mt-5">
            <a href="{{ $content->external_url }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-sm font-medium hover:bg-teal-700 transition-colors">
                🔗 Buka Konten Eksternal
            </a>
        </div>
        @endif

        @if($content->file_path)
        <div class="mt-5">
            <a href="{{ Storage::url($content->file_path) }}" target="_blank"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">
                📥 Unduh File
            </a>
        </div>
        @endif
    </div>
    <a href="{{ route('education.index') }}" class="block text-center px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
        ← Kembali
    </a>
</div>
@endsection
