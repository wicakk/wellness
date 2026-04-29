{{--
    Partial: questionnaire/partials/question-fields.blade.php
    Dipakai di:
      - Edit mode (inline) → $q = $question, $prefix = 'edit-{id}'
      - Tambah soal baru   → $q = null,       $prefix = 'new'

    Variabel:
      $q      – model Question atau null
      $prefix – string unik untuk id HTML agar tidak tabrakan
--}}

@php
    $isEdit     = isset($q) && $q !== null;
    $qType      = $isEdit ? $q->type      : 'scale';
    $qText      = $isEdit ? $q->question_text : '';
    $qMaxScore  = $isEdit ? $q->max_score  : 5;
    $qOptions   = $isEdit && $q->options ? $q->options : [];   // [value => label]
    $pfx        = $prefix ?? 'qf';
@endphp

<div
    x-data="questionFields({
        type:        '{{ $qType }}',
        maxScore:    {{ $qMaxScore }},
        options:     {{ json_encode(array_values($qOptions)) }},
        isBoolean:   {{ $qType === 'boolean' ? 'true' : 'false' }}
    })"
    x-init="init()"
    class="space-y-4"
>
    {{-- ── Teks Soal ─────────────────────────────────────────── --}}
    <div>
        <label class="block text-xs font-medium mb-1.5">
            Teks Soal <span class="text-red-500">*</span>
        </label>
        <textarea
            name="question_text"
            rows="3"
            required
            placeholder="Contoh: Saya merasa sulit berkonsentrasi..."
            class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700
                   bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2
                   focus:ring-teal-500 resize-none"
        >{{ $qText }}</textarea>
    </div>

    {{-- ── Tipe Soal ─────────────────────────────────────────── --}}
    <div>
        <label class="block text-xs font-medium mb-1.5">
            Tipe Soal <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-3 gap-2">

            {{-- Skala --}}
            <label class="cursor-pointer">
                <input type="radio" value="scale" x-model="type" class="sr-only peer"
                       @change="onTypeChange">
                <div class="text-center p-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700
                            peer-checked:border-teal-500 peer-checked:bg-teal-50 dark:peer-checked:bg-teal-900/20
                            transition-all cursor-pointer">
                    <div class="mb-0.5 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="text-xs font-medium">Skala</div>
                </div>
            </label>

            {{-- Ya/Tidak --}}
            <label class="cursor-pointer">
                <input type="radio" value="boolean" x-model="type" class="sr-only peer"
                       @change="onTypeChange">
                <div class="text-center p-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700
                            peer-checked:border-teal-500 peer-checked:bg-teal-50 dark:peer-checked:bg-teal-900/20
                            transition-all cursor-pointer">
                    <div class="mb-0.5 flex justify-center gap-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="text-xs font-medium">Ya/Tidak</div>
                </div>
            </label>

            {{-- Teks Bebas --}}
            <label class="cursor-pointer">
                <input type="radio" value="open_text" x-model="type" class="sr-only peer"
                       @change="onTypeChange">
                <div class="text-center p-2.5 rounded-xl border-2 border-slate-200 dark:border-slate-700
                            peer-checked:border-teal-500 peer-checked:bg-teal-50 dark:peer-checked:bg-teal-900/20
                            transition-all cursor-pointer">
                    <div class="mb-0.5 flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div class="text-xs font-medium">Teks</div>
                </div>
            </label>
        </div>

        {{-- hidden field type untuk submit --}}
        <input type="hidden" name="type" x-bind:value="type">
    </div>

    {{-- ── Skala: Skor Maks + Pilihan ────────────────────────── --}}
    <div x-show="type === 'scale'" x-cloak class="space-y-3">

        {{-- Skor Maksimum --}}
        <div>
            <label class="block text-xs font-medium mb-1.5">Skor Maksimum</label>
            <input
                type="number"
                x-model.number="maxScore"
                @input="syncMaxScore"
                min="1" max="20"
                class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700
                       bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
            >
        </div>

        {{-- Label Pilihan Jawaban --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-medium">Label Pilihan Jawaban</label>
                <span class="text-[10px] text-slate-400">
                    Skor maks: <strong x-text="maxScore"></strong>
                </span>
            </div>

            <div class="space-y-2" id="{{ $pfx }}-options-list">
                <template x-for="(opt, idx) in options" :key="idx">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 bg-slate-100 dark:bg-slate-700 rounded text-xs flex items-center justify-center font-mono flex-shrink-0"
                              x-text="idx + 1"></span>
                        <input
                            type="text"
                            :name="`options[${idx + 1}]`"
                            x-model="options[idx]"
                            :placeholder="`Label untuk nilai ${idx + 1}`"
                            class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700
                                   bg-slate-50 dark:bg-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500"
                        >
                        <button
                            type="button"
                            @click="removeOption(idx)"
                            x-show="options.length > 1"
                            class="p-1 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-400
                                   transition-colors flex-shrink-0"
                            title="Hapus pilihan"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            {{-- Tambah Pilihan --}}
            <button
                type="button"
                @click="addOption"
                class="mt-2 w-full py-2 border border-dashed border-slate-300 dark:border-slate-600
                       rounded-xl text-xs text-slate-400 hover:text-teal-600 hover:border-teal-400
                       dark:hover:text-teal-400 dark:hover:border-teal-600 transition-colors"
            >
                + Tambah Pilihan
            </button>
        </div>
    </div>

    {{-- ── Boolean: tampilan info ─────────────────────────────── --}}
    <div x-show="type === 'boolean'" x-cloak
         class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
        <p class="text-xs text-purple-600 dark:text-purple-400 font-medium mb-2">Pilihan otomatis:</p>
        <div class="flex gap-2">
            <span class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-purple-200 dark:border-purple-700 rounded-lg text-xs">0 — Tidak</span>
            <span class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-purple-200 dark:border-purple-700 rounded-lg text-xs">1 — Ya</span>
        </div>
        <input type="hidden" name="options[0]" value="Tidak">
        <input type="hidden" name="options[1]" value="Ya">
    </div>

    {{-- ── Open Text: info saja ──────────────────────────────── --}}
    <div x-show="type === 'open_text'" x-cloak
         class="p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Responden mengisi jawaban bebas. Tidak memiliki skor.
        </p>
    </div>

    {{-- Hidden max_score – ditulis ulang saat submit via JS di bawah --}}
    <input type="hidden" name="max_score" :value="computedMaxScore">

</div>

{{-- ── Alpine component (definisi sekali, dipakai semua instance) ── --}}
@once
@push('scripts')
<script>
/**
 * questionFields(config)
 * config = { type, maxScore, options: string[], isBoolean }
 *
 * Konvensi options:
 *   - scale   → array label per nilai, index = nilai (0 .. maxScore)
 *   - boolean → ['Tidak', 'Ya']
 *   - open_text → []
 *
 * computedMaxScore dipakai sebagai hidden max_score:
 *   scale     → options.length - 1
 *   boolean   → 1
 *   open_text → 0
 */
function questionFields(cfg) {
    const DEFAULT_LABELS = [
        'Tidak pernah','Jarang','Kadang-kadang','Sering','Selalu',
        'Sangat sering','Hampir selalu','Selalu'
    ];

    return {
        type:     cfg.type     || 'scale',
        maxScore: cfg.maxScore || 5,
        options:  cfg.options  && cfg.options.length
                    ? [...cfg.options]
                    : DEFAULT_LABELS.slice(0, cfg.maxScore || 4),

        get computedMaxScore() {
            if (this.type === 'boolean')   return 1;
            if (this.type === 'open_text') return 0;
            return this.options.length;   // scale: index mulai 1, jadi maks = jumlah option
        },

        init() {
            // pastikan jumlah options sesuai maxScore saat pertama load (scale)
            if (this.type === 'scale') this._syncOptionsToMax();
        },

        onTypeChange() {
            if (this.type === 'boolean') {
                this.options = ['Tidak', 'Ya'];
            } else if (this.type === 'open_text') {
                this.options = [];
            } else {
                // kembali ke scale – rebuild dari maxScore
                this._syncOptionsToMax();
            }
        },

        syncMaxScore() {
            // Dipanggil saat user ubah angka skor maks
            this._syncOptionsToMax();
        },

        _syncOptionsToMax() {
            const target = parseInt(this.maxScore); // 1-based: maxScore = jumlah pilihan
            while (this.options.length < target) {
                this.options.push(DEFAULT_LABELS[this.options.length] || '');
            }
            if (this.options.length > target) {
                this.options = this.options.slice(0, target);
            }
        },

        addOption() {
            this.options.push(DEFAULT_LABELS[this.options.length] || '');
            this.maxScore = this.options.length;
        },

        removeOption(idx) {
            if (this.options.length <= 1) return;
            this.options.splice(idx, 1);
            this.maxScore = this.options.length;
        },
    };
}
</script>
@endpush
@endonce