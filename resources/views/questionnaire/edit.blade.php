{{-- resources/views/questionnaire/edit.blade.php --}}
{{-- Edit dilakukan secara inline di halaman show, jadi redirect ke sana --}}
@php
    // Edit questionnaire dilakukan langsung di halaman show (inline editing)
    // Blade ini hanya sebagai fallback redirect
@endphp
@extends('layouts.app')
@section('title', 'Edit Kuesioner')
@section('page-title', 'Edit Kuesioner')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
        <h2 class="font-bold text-lg mb-1">Edit Kuesioner</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Ubah nama, deskripsi, dan ambang batas risiko kuesioner.</p>

        <form action="{{ route('admin.questionnaire.update', $questionnaire) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Kuesioner --}}
            <div>
                <label class="block text-sm font-medium mb-1.5">Nama Kuesioner <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $questionnaire->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                       placeholder="Contoh: PHQ-9, GAD-7">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                          placeholder="Deskripsi singkat tujuan kuesioner ini...">{{ old('description', $questionnaire->description) }}</textarea>
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                <div>
                    <p class="text-sm font-medium">Status Kuesioner</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kuesioner aktif dapat digunakan untuk skrining pegawai</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                           {{ old('is_active', $questionnaire->is_active) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                </label>
            </div>

            {{-- Ambang Batas Risiko --}}
            <div>
                <label class="block text-sm font-medium mb-3">Ambang Batas Risiko</label>
                <div class="space-y-3">
                    @php
                        $levels = [
                            'normal'  => ['label'=>'Normal',  'color'=>'#22c55e', 'placeholder'=>'0–10'],
                            'ringan'  => ['label'=>'Ringan',  'color'=>'#eab308', 'placeholder'=>'11–20'],
                            'sedang'  => ['label'=>'Sedang',  'color'=>'#f97316', 'placeholder'=>'21–30'],
                            'tinggi'  => ['label'=>'Tinggi',  'color'=>'#ef4444', 'placeholder'=>'31–40'],
                        ];
                        $thresholds = $questionnaire->riskThresholds->keyBy('level');
                    @endphp
                    @foreach($levels as $level => $conf)
                    @php $t = $thresholds[$level] ?? null; @endphp
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:{{ $conf['color'] }}"></span>
                        <span class="text-sm font-medium w-16 flex-shrink-0" style="color:{{ $conf['color'] }}">{{ $conf['label'] }}</span>
                        <div class="flex items-center gap-2 flex-1">
                            <input type="number" name="thresholds[{{ $level }}][min]"
                                   value="{{ old("thresholds.$level.min", $t?->score_min ?? '') }}"
                                   min="0" placeholder="Min"
                                   class="w-20 px-3 py-1.5 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <span class="text-slate-400 text-sm">–</span>
                            <input type="number" name="thresholds[{{ $level }}][max]"
                                   value="{{ old("thresholds.$level.max", $t?->score_max ?? '') }}"
                                   min="0" placeholder="Maks"
                                   class="w-20 px-3 py-1.5 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <input type="text" name="thresholds[{{ $level }}][description]"
                               value="{{ old("thresholds.$level.description", $t?->description ?? '') }}"
                               placeholder="Deskripsi (opsional)"
                               class="flex-1 px-3 py-1.5 text-sm rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.questionnaire.show', $questionnaire) }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    ← Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
