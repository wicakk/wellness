<div
    x-data="{
        type: '{{ $q->type ?? 'scale' }}',
        options: @js(array_values($q->options ?? ['Tidak pernah', 'Jarang', 'Kadang-kadang', 'Sering', 'Selalu'])),

        get maxScore() {
            return this.options.length - 1;
        },

        addOption() {
            if (this.options.length >= 11) return;
            this.options.push('');
        },

        removeOption(index) {
            if (this.options.length <= 2) return;
            this.options.splice(index, 1);
        }
    }"
    class="space-y-3"
>
    {{-- Teks Soal --}}
    <div>
        <label class="block text-xs font-medium mb-1">Teks Soal</label>
        <textarea name="question_text" rows="2" required
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ $q->question_text ?? '' }}</textarea>
    </div>

    {{-- Tipe Soal --}}
    <div>
        <label class="block text-xs font-medium mb-1">Tipe Soal</label>
        <div class="flex gap-2">
            @foreach(['scale' => 'Skala', 'boolean' => 'Ya/Tidak', 'open_text' => 'Teks'] as $val => $label)
            <label class="cursor-pointer">
                <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only peer">
                <div class="px-3 py-1.5 rounded-lg text-xs border border-slate-200 dark:border-slate-700
                            peer-checked:border-teal-500 peer-checked:bg-teal-50 dark:peer-checked:bg-teal-900/20
                            transition-all cursor-pointer font-medium select-none">
                    {{ $label }}
                </div>
            </label>
            @endforeach
        </div>
    </div>

    {{-- ── SCALE ─────────────────────────────────────────────────────── --}}
    <div x-show="type === 'scale'" x-transition class="space-y-2">

        <input type="hidden" name="max_score" :value="maxScore">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <label class="text-xs font-medium text-slate-600 dark:text-slate-400">Pilihan Jawaban</label>
            <span class="text-[10px] text-slate-400">
                Skor maks: <span class="font-semibold text-teal-600 dark:text-teal-400" x-text="maxScore"></span>
            </span>
        </div>

        {{-- Daftar opsi --}}
        <div class="space-y-1.5">
            <template x-for="(option, index) in options" :key="index">
                <div class="flex items-center gap-1.5">

                    {{-- Badge skor --}}
                    <span class="w-5 h-5 rounded bg-slate-100 dark:bg-slate-700 text-[10px] font-mono
                                 text-slate-500 flex items-center justify-center flex-shrink-0"
                          x-text="index"></span>

                    {{-- Input --}}
                    <input
                        type="text"
                        :name="`options[${index}]`"
                        x-model="options[index]"
                        :placeholder="`Label untuk ${index}`"
                        class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700
                               bg-white dark:bg-slate-900 text-xs focus:outline-none focus:ring-1
                               focus:ring-teal-500 transition"
                    >

                    {{-- Tombol hapus — selalu tampil, merah jika bisa dihapus --}}
                    <button
                        type="button"
                        @click="removeOption(index)"
                        :disabled="options.length <= 2"
                        class="w-6 h-6 flex-shrink-0 flex items-center justify-center rounded-md transition-all"
                        :class="options.length <= 2
                            ? 'text-slate-200 dark:text-slate-700 cursor-not-allowed'
                            : 'text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer'"
                        title="Hapus pilihan"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        {{-- Tombol tambah --}}
        <button
            type="button"
            @click="addOption()"
            :disabled="options.length >= 11"
            class="w-full flex items-center justify-center gap-1.5 py-1.5 rounded-lg
                   border border-dashed border-slate-300 dark:border-slate-600
                   text-xs text-slate-400
                   hover:border-teal-400 hover:text-teal-500 hover:bg-teal-50 dark:hover:bg-teal-900/10
                   disabled:opacity-40 disabled:cursor-not-allowed transition-all"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
            </svg>
            Tambah Pilihan
        </button>
    </div>

    {{-- ── BOOLEAN ──────────────────────────────────────────────────── --}}
    <div x-show="type === 'boolean'" x-transition>
        <p class="text-xs text-slate-400">Pilihan: Tidak (0) / Ya (1)</p>
        <input type="hidden" name="max_score" value="1">
        <input type="hidden" name="options[0]" value="Tidak">
        <input type="hidden" name="options[1]" value="Ya">
    </div>

    {{-- ── OPEN TEXT ─────────────────────────────────────────────────── --}}
    <div x-show="type === 'open_text'" x-transition>
        <p class="text-xs text-slate-400">Jawaban teks bebas — tidak ada skor</p>
        <input type="hidden" name="max_score" value="0">
    </div>

</div>