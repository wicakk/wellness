{{--
    Partial: questionnaire/partials/question-fields.blade.php
    Used in both: show.blade.php (inline edit)
    Variables: $q (Question model), $prefix (unique id prefix)
--}}
<div x-data="{ type: '{{ $q->type }}', optionCount: {{ count($q->options ?? []) ?: 5 }} }" class="space-y-3">

    <div>
        <label class="block text-xs font-medium mb-1">Teks Soal</label>
        <textarea name="question_text" rows="2" required
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ $q->question_text }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-medium mb-1">Tipe Soal</label>
        <div class="flex gap-2">
            @foreach(['scale' => 'Skala', 'boolean' => 'Ya/Tidak', 'open_text' => 'Teks'] as $val => $label)
            <label class="cursor-pointer">
                <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only peer">
                <div class="px-3 py-1.5 rounded-lg text-xs border border-slate-200 dark:border-slate-700 peer-checked:border-teal-500 peer-checked:bg-teal-50 dark:peer-checked:bg-teal-900/20 transition-all cursor-pointer font-medium">
                    {{ $label }}
                </div>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Scale options --}}
    <div x-show="type === 'scale'" class="space-y-2">
        <div class="flex items-center gap-2">
            <label class="text-xs font-medium">Skor Maks:</label>
            <input type="number" name="max_score" x-model="optionCount" min="1" max="10"
                   value="{{ $q->max_score }}"
                   class="w-16 px-2 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        <div class="space-y-1.5">
            <template x-for="i in (parseInt(optionCount) + 1)" :key="i">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 bg-slate-100 dark:bg-slate-700 rounded text-[10px] flex items-center justify-center font-mono flex-shrink-0"
                          x-text="i-1"></span>
                    <input type="text" :name="`options[${i-1}]`"
                           :placeholder="`Label ${i-1}`"
                           :value="[
                               @foreach($q->options ?? [] as $k => $v)'{{ addslashes($v) }}', @endforeach
                           ][i-1] || ''"
                           class="flex-1 px-2 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:outline-none focus:ring-1 focus:ring-teal-500">
                </div>
            </template>
        </div>
    </div>

    <div x-show="type === 'boolean'">
        <p class="text-xs text-slate-400">Pilihan: Tidak (0) / Ya (1)</p>
        <input type="hidden" name="max_score" value="1">
        <input type="hidden" name="options[0]" value="Tidak">
        <input type="hidden" name="options[1]" value="Ya">
    </div>

    <div x-show="type === 'open_text'">
        <p class="text-xs text-slate-400">Jawaban teks bebas — tidak ada skor</p>
        <input type="hidden" name="max_score" value="0">
    </div>
</div>
