@extends('layouts.app')
@section('title', 'Manajemen Kuesioner')
@section('page-title', 'Manajemen Kuesioner')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Kelola kuesioner skrining beserta soal-soalnya
            </p>
        </div>
        <a href="{{ route('admin.questionnaire.create') }}"
           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Kuesioner Baru
        </a>
    </div>

    {{-- Cards --}}
    @forelse($questionnaires as $q)
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-5 flex flex-col sm:flex-row gap-4">

            {{-- Icon + Info --}}
            <div class="flex items-start gap-4 flex-1">
                <div class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center text-2xl
                    {{ $q->is_active ? 'bg-teal-50 dark:bg-teal-900/30' : 'bg-slate-100 dark:bg-slate-700' }}">
                    <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-semibold text-base">{{ $q->name }}</h3>
                        @if($q->is_active)
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                ● Aktif
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                                ○ Nonaktif
                            </span>
                        @endif
                    </div>
                    @if($q->description)
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">{{ $q->description }}</p>
                    @endif
                    <div class="flex flex-wrap gap-4 mt-3 text-xs text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-1">
                            <span class="text-teal-500">●</span>
                            {{ $q->questions_count }} soal
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="text-blue-500">●</span>
                            {{ $q->screenings()->where('status','completed')->count() }} skrining selesai
                        </span>
                        <span>Dibuat {{ $q->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2 flex-shrink-0" x-data="{ confirm: false }">
                <a href="{{ route('admin.questionnaire.show', $q) }}"
                   class="px-3 py-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <svg class="flex w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg> Kelola Soal
                </a>
                <a href="{{ route('admin.questionnaire.edit', $q) }}"
                   class="px-3 py-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <svg class="flex w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Edit
                </a>

                {{-- Toggle aktif --}}
                <form action="{{ route('admin.questionnaire.toggle', $q) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="px-3 py-2 text-xs font-medium rounded-lg transition-colors
                                {{ $q->is_active
                                   ? 'border border-yellow-200 text-yellow-700 hover:bg-yellow-50 dark:border-yellow-700 dark:text-yellow-400'
                                   : 'border border-green-200 text-green-700 hover:bg-green-50 dark:border-green-700 dark:text-green-400' }}">
                        <span class="flex items-center gap-1.5">
                                        @if($q->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Nonaktifkan
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Aktifkan
                                        @endif
                                        </span>
                    </button>
                </form>

                {{-- Delete --}}
                <div x-data="{ open: false }">
                    <button @click="open = true"
                            class="px-3 py-2 text-xs font-medium rounded-lg border border-red-200 text-red-600 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                        Hapus
                    </button>
                    <div x-show="open" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" @click.self="open = false">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full shadow-2xl">
                            <h4 class="font-semibold text-base mb-2">Hapus Kuesioner?</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
                                Kuesioner <strong>{{ $q->name }}</strong> dan semua soalnya akan dihapus permanen.
                                Kuesioner yang sudah punya data skrining tidak dapat dihapus.
                            </p>
                            <div class="flex gap-3">
                                <button @click="open = false"
                                        class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    Batal
                                </button>
                                <form action="{{ route('admin.questionnaire.destroy', $q) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-4 py-2 rounded-xl bg-red-600 text-white text-sm hover:bg-red-700 transition-colors">
                                        Ya, Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Risk threshold preview --}}
        <div class="border-t border-slate-100 dark:border-slate-700 px-5 py-3 flex flex-wrap gap-3">
            @foreach($q->riskThresholds()->orderBy('score_min')->get() as $t)
            <div class="flex items-center gap-1.5 text-xs">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $t->color_code }}"></span>
                <span class="text-slate-600 dark:text-slate-300 font-medium">{{ ucfirst($t->level) }}</span>
                <span class="text-slate-400">{{ $t->score_min }}–{{ $t->score_max }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 p-12 text-center">
        <div class="text-5xl mb-3"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
        <h3 class="font-semibold text-slate-600 dark:text-slate-300">Belum ada kuesioner</h3>
        <p class="text-sm text-slate-400 mt-1 mb-5">Buat kuesioner pertama untuk mulai melakukan skrining pegawai.</p>
        <a href="{{ route('admin.questionnaire.create') }}"
           class="inline-flex items-center gap-2 bg-teal-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-teal-700 transition-colors">
            + Buat Kuesioner
        </a>
    </div>
    @endforelse

    {{ $questionnaires->links() }}
</div>
@endsection
