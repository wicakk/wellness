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

    {{-- ===================== WELCOME SCREEN ===================== --}}
    <div x-show="phase === 'welcome'"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4">

        {{-- Animated Icon --}}
        <div class="relative mb-8">
            <div class="w-24 h-24 bg-teal-500 rounded-3xl flex items-center justify-center shadow-2xl shadow-teal-200 dark:shadow-teal-900/50 animate-bounce-slow">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            {{-- Ping rings --}}
            <span class="absolute inset-0 rounded-3xl bg-teal-400 opacity-30 animate-ping"></span>
        </div>

        {{-- Badge --}}
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 text-xs font-semibold rounded-full mb-4 uppercase tracking-widest">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Skrining Kesehatan Mental
        </span>

        {{-- Title --}}
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 dark:text-white mb-3 leading-tight">
            Selamat Datang! 👋
        </h1>
        <p class="text-lg text-slate-500 dark:text-slate-400 mb-2">Kamu akan mengerjakan</p>

        {{-- Questionnaire Name Box --}}
        <div class="my-4 px-6 py-4 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-900/30 dark:to-emerald-900/20 border-2 border-teal-200 dark:border-teal-700 rounded-2xl shadow-sm">
            <p class="text-xl md:text-2xl font-bold text-teal-700 dark:text-teal-300">
                Skrining {{ $questionnaire->name ?? 'Skrining Mandiri' }}
            </p>
            @if($questionnaire->description)
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $questionnaire->description }}</p>
            @endif
        </div>

        {{-- Info chips --}}
        <div class="flex flex-wrap justify-center gap-3 mb-8 mt-2">
            <span class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $questionnaire->questions->count() }} Pertanyaan
            </span>
            <span class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ±{{ ceil($questionnaire->questions->count() * 0.5) }} Menit
            </span>
            <span class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-3 py-1.5 rounded-full">
                <svg class="w-3.5 h-3.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Rahasia & Aman
            </span>
        </div>

        {{-- Start Button --}}
        <button @click="startScreening()"
                class="group relative inline-flex items-center gap-3 px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white text-base font-bold rounded-2xl shadow-lg shadow-teal-200 dark:shadow-teal-900/50 hover:shadow-xl hover:shadow-teal-300 dark:hover:shadow-teal-900 transition-all duration-300 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Mulai Skrining
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <p class="text-xs text-slate-400 dark:text-slate-500 mt-4">Jawab setiap pertanyaan dengan jujur untuk hasil yang akurat</p>
    </div>

    {{-- ===================== LOADING SCREEN ===================== --}}
    <div x-show="phase === 'loading'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4">

        {{-- Spinner --}}
        <div class="relative w-24 h-24 mb-8">
            {{-- Outer ring --}}
            <svg class="w-24 h-24 animate-spin" viewBox="0 0 96 96" fill="none">
                <circle cx="48" cy="48" r="40" stroke="#e2e8f0" stroke-width="8"/>
                <path d="M48 8a40 40 0 0 1 40 40" stroke="#0d9488" stroke-width="8" stroke-linecap="round"/>
            </svg>
            {{-- Inner pulsing dot --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Loading dots text --}}
        <h2 class="text-xl font-bold text-slate-700 dark:text-slate-200 mb-2" x-text="loadingText"></h2>
        <p class="text-sm text-slate-400 dark:text-slate-500">Mohon tunggu sebentar...</p>

        {{-- Animated bar --}}
        <div class="mt-6 w-48 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-teal-500 rounded-full animate-loading-bar"></div>
        </div>
    </div>

    {{-- ===================== SCREENING FORM ===================== --}}
    <div x-show="phase === 'form'"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Progress --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">Skrining {{ $questionnaire->name }}</h1>
            <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400 mb-2">
                {{-- FIX: gunakan Math.min agar tidak melebihi total soal saat di halaman catatan --}}
                <span>Pertanyaan <span class="font-bold text-teal-600" x-text="Math.min(current + 1, {{ $questionnaire->questions->count() }})"></span> dari {{ $questionnaire->questions->count() }}</span>
                <span x-text="Math.round((Math.min(current + 1, {{ $questionnaire->questions->count() }}) / {{ $questionnaire->questions->count() }}) * 100) + '%'"></span>
            </div>
            <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-teal-400 to-emerald-500 rounded-full transition-all duration-500"
                     :style="'width:' + (Math.min(current + 1, {{ $questionnaire->questions->count() }}) / {{ $questionnaire->questions->count() }}) * 100 + '%'"></div>
            </div>
        </div>

        <form action="{{ route('screening.store', $screening) }}" method="POST" id="screeningForm">
            @csrf

            {{-- Questions --}}
            @foreach($questionnaire->questions as $i => $question)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700"
                 x-show="current === {{ $i }}"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0">

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

                {{-- Inline error message --}}
                <p class="text-red-500 text-xs mt-3 hidden" id="err-{{ $i }}">
                    ⚠️ Pertanyaan ini wajib dijawab sebelum melanjutkan.
                </p>
            </div>
            @endforeach

            {{-- Open Notes --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 mt-4"
                 x-show="current === {{ $questionnaire->questions->count() }}"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0">

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
                    <button type="button" @click="nextQuestion()"
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

</div>
@endif
@endsection

@push('scripts')
<script>
function screening() {
    return {
        phase: 'welcome',   // 'welcome' | 'loading' | 'form'
        current: 0,
        loadingText: 'Menyiapkan pertanyaan...',
        loadingMessages: [
            'Menyiapkan pertanyaan...',
            'Memuat kuesioner...',
            'Hampir siap...',
            'Mempersiapkan formulir...'
        ],

        startScreening() {
            this.phase = 'loading';
            let i = 0;
            const interval = setInterval(() => {
                i++;
                if (i < this.loadingMessages.length) {
                    this.loadingText = this.loadingMessages[i];
                }
            }, 600);

            setTimeout(() => {
                clearInterval(interval);
                this.phase = 'form';
            }, 2500);
        },

        nextQuestion() {
            const totalQuestions = {{ $questionnaire->questions->count() }};

            // Cek validasi untuk soal scale (radio)
            const radios = document.querySelectorAll(`input[name="answers[${this.current}][score]"]`);
            if (radios.length > 0) {
                const checked = document.querySelector(`input[name="answers[${this.current}][score]"]:checked`);
                if (!checked) {
                    this.showError(this.current);
                    return;
                }
            }

            // Cek validasi untuk soal open_text (textarea)
            const textarea = document.querySelector(`textarea[name="answers[${this.current}][answer_text]"]`);
            if (textarea && textarea.value.trim() === '') {
                this.showError(this.current);
                return;
            }

            // Sembunyikan error jika ada
            this.hideError(this.current);

            if (this.current < totalQuestions) this.current++;
        },

        showError(index) {
            const err = document.getElementById(`err-${index}`);
            if (err) {
                err.classList.remove('hidden');
                // Shake animation
                const card = err.closest('.rounded-2xl');
                if (card) {
                    card.classList.add('animate-shake');
                    setTimeout(() => card.classList.remove('animate-shake'), 500);
                }
            }
        },

        hideError(index) {
            const err = document.getElementById(`err-${index}`);
            if (err) err.classList.add('hidden');
        }
    }
}
</script>

<style>
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.animate-bounce-slow { animation: bounce-slow 2.5s ease-in-out infinite; }

@keyframes loading-bar {
    0% { width: 0%; margin-left: 0; }
    50% { width: 80%; margin-left: 0; }
    100% { width: 0%; margin-left: 100%; }
}
.animate-loading-bar { animation: loading-bar 1.4s ease-in-out infinite; }

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-6px); }
    40% { transform: translateX(6px); }
    60% { transform: translateX(-4px); }
    80% { transform: translateX(4px); }
}
.animate-shake { animation: shake 0.4s ease-in-out; }
</style>
@endpush