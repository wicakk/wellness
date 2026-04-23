@extends('layouts.app')
@section('title', 'Skrining Mandiri')
@section('page-title', 'Skrining Mandiri')

@section('content')

@if($questionnaire->questions->isEmpty())
<div class="max-w-xl mx-auto text-center py-16">
    <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <h2 class="text-xl font-bold mb-2">Kuesioner belum siap</h2>
    <p class="text-slate-500 dark:text-slate-400 mb-6">Kuesioner aktif belum memiliki soal. Hubungi admin untuk menambahkan soal.</p>
    <a href="{{ route('pegawai.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-sm font-medium hover:bg-teal-700 transition-colors">
        ← Kembali ke Dashboard
    </a>
</div>
@else
<div class="max-w-3xl mx-auto" x-data="screening()">

    {{-- Progress --}}
    <div class="mb-6">
        <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400 mb-2">
            <span>Pertanyaan <span class="font-bold text-teal-600" x-text="current + 1"></span> dari {{ $questionnaire->questions->count() }}</span>
            <span x-text="Math.round(((current + 1) / {{ $questionnaire->questions->count() }}) * 100) + '%'"></span>
        </div>
        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-teal-500 rounded-full transition-all duration-500"
                 :style="'width:' + ((current + 1) / {{ $questionnaire->questions->count() }}) * 100 + '%'"></div>
        </div>
    </div>

    <form action="{{ route('screening.store', $screening) }}" method="POST" id="screeningForm">
        @csrf

        {{-- Questions --}}
        @foreach($questionnaire->questions as $i => $question)
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700"
             x-show="current === {{ $i }}" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">

            <div class="flex items-start gap-3 mb-6">
                <span class="flex-shrink-0 w-8 h-8 bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 rounded-full flex items-center justify-center text-sm font-bold">
                    {{ $i + 1 }}
                </span>
                <p class="text-base font-medium leading-relaxed pt-0.5">{{ $question->question_text }}</p>
            </div>

            @if($question->type === 'scale')
            <div class="space-y-3">
                @foreach($question->options as $value => $label)
                <label class="flex items-center gap-4 p-4 rounded-xl border-2 border-slate-100 dark:border-slate-700 hover:border-teal-400 dark:hover:border-teal-500 cursor-pointer transition-all has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50 dark:has-[:checked]:bg-teal-900/20 group">
                    <input type="radio"
                           name="answers[{{ $i }}][score]"
                           value="{{ $value }}"
                           class="w-4 h-4 text-teal-600 focus:ring-teal-500"
                           required
                           @change="if (current < {{ $questionnaire->questions->count() - 1 }}) setTimeout(() => current++, 300)">
                    <input type="hidden" name="answers[{{ $i }}][question_id]" value="{{ $question->id }}">
                    <span class="text-sm">{{ $label }}</span>
                </label>
                @endforeach
            </div>

            @elseif($question->type === 'open_text')
            <textarea name="answers[{{ $i }}][answer_text]"
                      placeholder="Tuliskan jawaban kamu di sini..."
                      rows="4"
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
            <input type="hidden" name="answers[{{ $i }}][question_id]" value="{{ $question->id }}">
            @endif
        </div>
        @endforeach

        {{-- Open Notes --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 mt-4"
             x-show="current === {{ $questionnaire->questions->count() }}"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">

            <h3 class="font-semibold mb-2">Catatan Tambahan (Opsional)</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Apakah ada hal lain yang ingin kamu sampaikan?</p>
            <textarea name="open_notes" rows="5" placeholder="Tulis di sini..."
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
        </div>

        {{-- Navigation Buttons --}}
        <div class="flex justify-between mt-6 gap-4">
            <button type="button"
                    @click="current > 0 && current--"
                    :class="current === 0 ? 'invisible' : ''"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                ← Sebelumnya
            </button>

            <template x-if="current < {{ $questionnaire->questions->count() - 1 }}">
                <button type="button" @click="nextQuestion()"
                        class="flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-sm font-medium hover:bg-teal-700 transition-colors">
                    Selanjutnya →
                </button>
            </template>

            <template x-if="current === {{ $questionnaire->questions->count() - 1 }}">
                <button type="button" @click="current++"
                        class="flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-sm font-medium hover:bg-teal-700 transition-colors">
                    Tambah Catatan →
                </button>
            </template>

            <template x-if="current === {{ $questionnaire->questions->count() }}">
                <button type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition-colors shadow-lg">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Kirim Skrining
                </button>
            </template>
        </div>
    </form>
</div>
</div>{{-- end @else --}}
@endif
@endsection

@push('scripts')
<script>
function screening() {
    return {
        current: 0,
        answers: {},
        nextQuestion() {
            // Validate current has been answered (for scale)
            const radios = document.querySelectorAll(`input[name="answers[${this.current}][score]"]`);
            if (radios.length > 0 && !document.querySelector(`input[name="answers[${this.current}][score]"]:checked`)) {
                alert('Silakan pilih salah satu jawaban sebelum melanjutkan.');
                return;
            }
            if (this.current < {{ $questionnaire->questions->count() }}) this.current++;
        }
    }
}
</script>
@endpush
