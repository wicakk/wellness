@extends('layouts.app')
@section('title', isset($content) ? 'Edit Konten' : 'Upload Konten Edukasi')
@section('page-title', isset($content) ? 'Edit Konten' : 'Upload Konten Edukasi')

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ isset($content) ? route('education.update', $content) : route('education.store') }}"
          method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @if(isset($content)) @method('PUT') @endif

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-5">

            <div>
                <label class="block text-sm font-medium mb-1.5">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $content->title ?? '') }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('title') border-red-400 @enderror">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Deskripsi</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('description', $content->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Tipe <span class="text-red-500">*</span></label>
                    <select name="type" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach(['artikel'=>'<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg> Artikel','video'=>'<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg> Video','infografis'=>'<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg> Infografis','panduan'=>'<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Panduan'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type', $content->type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category', $content->category ?? '') }}"
                           placeholder="stres, kecemasan, mindfulness..." required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">URL Eksternal</label>
                <input type="url" name="external_url" value="{{ old('external_url', $content->external_url ?? '') }}"
                       placeholder="https://..."
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Upload File (max 50MB)</label>
                <input type="file" name="file"
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                @if(isset($content) && $content->file_path)
                <p class="text-xs text-slate-400 mt-1">File saat ini: {{ basename($content->file_path) }}</p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" class="sr-only peer"
                           {{ old('is_published', $content->is_published ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-teal-600 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
                <span class="text-sm">Publikasikan (langsung tampil ke pegawai)</span>
            </div>
        </div>

        <div class="flex justify-between gap-4">
            <a href="{{ route('education.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">← Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors">
                {!!  isset($content) ? '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg> Simpan' : '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> Upload'  !!}
            </button>
        </div>
    </form>
</div>
@endsection
