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
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('name') border-red-400 @enderror"
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

            {{-- Ambang Batas Risiko – Dynamic --}}
            <div x-data="thresholdManager()">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium">Ambang Batas Risiko</label>
                    <button type="button" @click="addThreshold"
                            class="flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Kategori
                    </button>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    Tentukan rentang skor untuk setiap kategori risiko. Pastikan tidak ada rentang yang tumpang tindih.
                </p>

                <div class="space-y-3">
                    <template x-for="(threshold, index) in thresholds" :key="threshold.id">
                        <div class="border rounded-xl p-4 transition-all"
                             :style="`border-color: ${threshold.color}44; background: ${threshold.color}0d;`">

                            {{-- Row 1: Warna + Label + Hapus --}}
                            <div class="flex items-center gap-3 mb-3">
                                <input type="color"
                                       :name="`thresholds[${index}][color]`"
                                       x-model="threshold.color"
                                       class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer p-0.5 bg-white dark:bg-slate-700 flex-shrink-0"
                                       title="Pilih warna kategori">

                                <input type="text"
                                       :name="`thresholds[${index}][label]`"
                                       x-model="threshold.label"
                                       placeholder="Nama kategori, contoh: Normal"
                                       class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500"
                                       :style="`color: ${threshold.color}`">

                                <input type="hidden" :name="`thresholds[${index}][level]`" x-model="threshold.level">

                                <button type="button" @click="removeThreshold(index)"
                                        x-show="thresholds.length > 1"
                                        class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-400 hover:text-red-600 transition-colors flex-shrink-0"
                                        title="Hapus kategori ini">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Row 2: Skor Min & Max --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium mb-1 opacity-70">Skor Minimum</label>
                                    <input type="number"
                                           :name="`thresholds[${index}][score_min]`"
                                           x-model="threshold.score_min"
                                           min="0"
                                           class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium mb-1 opacity-70">Skor Maksimum</label>
                                    <input type="number"
                                           :name="`thresholds[${index}][score_max]`"
                                           x-model="threshold.score_max"
                                           min="0"
                                           class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                                </div>
                            </div>

                            {{-- Row 3: Keterangan --}}
                            <div class="mt-3">
                                <label class="block text-xs font-medium mb-1 opacity-70">Keterangan (opsional)</label>
                                <input type="text"
                                       :name="`thresholds[${index}][description]`"
                                       x-model="threshold.description"
                                       placeholder="Contoh: Tidak perlu intervensi"
                                       class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Preview ringkasan --}}
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <p class="text-xs text-slate-400 mb-2 font-medium uppercase tracking-wider">Preview Kategori</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="t in thresholds" :key="t.id">
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs border"
                                 :style="`border-color: ${t.color}44; background: ${t.color}11; color: ${t.color}`">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" :style="`background: ${t.color}`"></span>
                                <span class="font-semibold" x-text="t.label || '(belum diisi)'"></span>
                                <span class="opacity-60" x-text="`${t.score_min}–${t.score_max}`"></span>
                            </div>
                        </template>
                    </div>
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

@push('scripts')
@php
$existingThresholds = $questionnaire->riskThresholds->map(fn($t) => [
    'id'          => $t->id,
    'level'       => $t->level,
    'label'       => $t->label ?? ucfirst($t->level),
    'color'       => $t->color_code ?? '#22c55e',
    'score_min'   => $t->score_min,
    'score_max'   => $t->score_max,
    'description' => $t->description ?? '',
])->values()->toArray();
@endphp
<script>
function thresholdManager() {
    const existing = @json($existingThresholds);

    const defaults = [
        { id: 1, level: 'normal', label: 'Normal',        color: '#22c55e', score_min: 0,  score_max: 10, description: '' },
        { id: 2, level: 'ringan', label: 'Risiko Ringan', color: '#eab308', score_min: 11, score_max: 20, description: '' },
        { id: 3, level: 'sedang', label: 'Risiko Sedang', color: '#f97316', score_min: 21, score_max: 30, description: '' },
        { id: 4, level: 'tinggi', label: 'Risiko Tinggi', color: '#ef4444', score_min: 31, score_max: 40, description: '' },
    ];

    return {
        thresholds: existing.length > 0 ? existing : defaults,
        nextId: 100,

        addThreshold() {
            const last = this.thresholds[this.thresholds.length - 1];
            const newMin = last ? (parseInt(last.score_max) + 1) : 0;
            this.thresholds.push({
                id:          this.nextId++,
                level:       'custom_' + this.nextId,
                label:       '',
                color:       '#6366f1',
                score_min:   newMin,
                score_max:   newMin + 10,
                description: '',
            });
        },

        removeThreshold(index) {
            if (this.thresholds.length <= 1) return;
            this.thresholds.splice(index, 1);
        },
    };
}
</script>
@endpush
@endsection