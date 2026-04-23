@extends('layouts.app')
@section('title', isset($questionnaire) ? 'Edit Kuesioner' : 'Buat Kuesioner')
@section('page-title', isset($questionnaire) ? 'Edit Kuesioner' : 'Buat Kuesioner Baru')

@section('content')
<div class="max-w-2xl mx-auto">

    <form action="{{ isset($questionnaire) ? route('admin.questionnaire.update', $questionnaire) : route('admin.questionnaire.store') }}"
          method="POST" class="space-y-6">
        @csrf
        @if(isset($questionnaire)) @method('PUT') @endif

        {{-- Info Dasar --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 space-y-5">
            <h3 class="font-semibold text-sm text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                Informasi Kuesioner
            </h3>

            <div>
                <label class="block text-sm font-medium mb-1.5">Nama Kuesioner <span class="text-red-500">*</span></label>
                <input type="text" name="name"
                       value="{{ old('name', $questionnaire->name ?? '') }}"
                       placeholder="Contoh: PHQ-9, SRQ-20, Skrining Umum..."
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                          placeholder="Jelaskan tujuan dan cara pengisian kuesioner ini..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('description', $questionnaire->description ?? '') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                           {{ old('is_active', $questionnaire->is_active ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-teal-600 transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
                <span class="text-sm font-medium">Aktifkan kuesioner ini (dapat digunakan untuk skrining)</span>
            </div>
        </div>

        {{-- Risk Thresholds --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
            <h3 class="font-semibold text-sm text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">
                Ambang Batas Risiko
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-5">
                Tentukan rentang skor untuk setiap kategori risiko. Pastikan tidak ada rentang yang tumpang tindih.
            </p>

            @php
                $defaultThresholds = [
                    ['level'=>'normal', 'label'=>'Normal',       'color'=>'text-green-600 bg-green-50 dark:bg-green-900/20 border-green-200',  'score_min'=>0,  'score_max'=>10],
                    ['level'=>'ringan', 'label'=>'Risiko Ringan','color'=>'text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200','score_min'=>11, 'score_max'=>20],
                    ['level'=>'sedang', 'label'=>'Risiko Sedang','color'=>'text-orange-600 bg-orange-50 dark:bg-orange-900/20 border-orange-200','score_min'=>21, 'score_max'=>30],
                    ['level'=>'tinggi', 'label'=>'Risiko Tinggi','color'=>'text-red-600 bg-red-50 dark:bg-red-900/20 border-red-200',           'score_min'=>31, 'score_max'=>40],
                ];
                $existingThresholds = isset($questionnaire) ? $questionnaire->riskThresholds->keyBy('level') : collect();
            @endphp

            <div class="space-y-4">
                @foreach($defaultThresholds as $i => $def)
                @php $existing = $existingThresholds[$def['level']] ?? null; @endphp
                <div class="border rounded-xl p-4 {{ $def['color'] }}">
                    <input type="hidden" name="thresholds[{{ $i }}][level]" value="{{ $def['level'] }}">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="font-semibold text-sm">{{ $def['label'] }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium mb-1 opacity-80">Skor Minimum</label>
                            <input type="number" name="thresholds[{{ $i }}][score_min]"
                                   value="{{ old("thresholds.$i.score_min", $existing?->score_min ?? $def['score_min']) }}"
                                   min="0" class="w-full px-3 py-2 rounded-lg border border-current/20 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1 opacity-80">Skor Maksimum</label>
                            <input type="number" name="thresholds[{{ $i }}][score_max]"
                                   value="{{ old("thresholds.$i.score_max", $existing?->score_max ?? $def['score_max']) }}"
                                   min="0" class="w-full px-3 py-2 rounded-lg border border-current/20 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-medium mb-1 opacity-80">Keterangan (opsional)</label>
                        <input type="text" name="thresholds[{{ $i }}][description]"
                               value="{{ old("thresholds.$i.description", $existing?->description ?? '') }}"
                               placeholder="Contoh: Tidak perlu intervensi"
                               class="w-full px-3 py-2 rounded-lg border border-current/20 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-between gap-4">
            <a href="{{ route('admin.questionnaire.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                ← Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">
                {!!  isset($questionnaire) ? '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg> Simpan Perubahan' : '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Buat Kuesioner'  !!}
            </button>
        </div>
    </form>
</div>
@endsection
