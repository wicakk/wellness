@extends('layouts.app')
@section('title', $questionnaire->name)
@section('page-title', 'Kelola Soal Kuesioner')

@section('content')
<div class="space-y-6" x-data="questionManager()">

    {{-- Header Info Kuesioner --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h2 class="font-bold text-lg">{{ $questionnaire->name }}</h2>
                    @if($questionnaire->is_active)
                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 font-medium">● Aktif</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-500 font-medium">○ Nonaktif</span>
                    @endif
                </div>
                @if($questionnaire->description)
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $questionnaire->description }}</p>
                @endif
                <div class="flex flex-wrap gap-4 mt-3 text-xs text-slate-500 dark:text-slate-400">
                    <span>
                        <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        {{ $questionnaire->questions->count() }} soal
                    </span>
                    <span>{{ $screeningCount }} skrining selesai</span>
                    <span>
                        <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Diperbarui {{ $questionnaire->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('admin.questionnaire.edit', $questionnaire) }}"
                   class="px-3 py-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    ⚙️ Edit Info
                </a>
                <a href="{{ route('admin.questionnaire.index') }}"
                   class="px-3 py-2 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- Risk Thresholds --}}
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
            <p class="text-xs font-medium text-slate-400 mb-2 uppercase tracking-wider">Kategori Risiko</p>
            <div class="flex flex-wrap gap-3">
                @forelse($questionnaire->riskThresholds->sortBy('score_min') as $t)
                @php $color = $t->color_code ?? '#64748b'; @endphp
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs border"
                     style="border-color:{{ $color }}33; background:{{ $color }}11;">
                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $color }}"></span>
                    <span class="font-semibold" style="color:{{ $color }}">{{ $t->label ?? ucfirst($t->level) }}</span>
                    <span class="text-slate-500">{{ $t->score_min }}–{{ $t->score_max }}</span>
                    @if($t->description)
                        <span class="text-slate-400 hidden sm:inline">• {{ $t->description }}</span>
                    @endif
                </div>
                @empty
                <span class="text-xs text-slate-400 italic">Belum ada kategori risiko.</span>
                @endforelse

                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs bg-slate-50 dark:bg-slate-700 text-slate-500">
                    Max total skor: <strong class="text-slate-700 dark:text-slate-200 ml-1">
                        {{ $questionnaire->questions->sum('max_score') }}
                    </strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Soal + Tambah Soal --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

        {{-- ── DAFTAR SOAL (kiri, lebar) ──────────────────────────── --}}
        <div class="lg:col-span-3 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm">Daftar Soal</h3>
                <span class="text-xs text-slate-400">Drag untuk ubah urutan</span>
            </div>

            @if($questionnaire->questions->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 p-8 text-center">
                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <p class="text-sm text-slate-500">Belum ada soal. Tambahkan soal pertama →</p>
            </div>
            @else
            <div id="sortable-questions" class="space-y-3">
                @foreach($questionnaire->questions as $question)
                <div class="question-item bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden group"
                     data-id="{{ $question->id }}" x-data="{ editing: false }">

                    {{-- View Mode --}}
                    <div class="p-4" x-show="!editing">
                        <div class="flex items-start gap-3">
                            {{-- Drag handle --}}
                            <div class="drag-handle flex-shrink-0 w-8 h-8 flex items-center justify-center cursor-grab text-slate-300 hover:text-slate-500 dark:text-slate-600 dark:hover:text-slate-400 transition-colors mt-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="8" cy="6" r="1.5"/><circle cx="16" cy="6" r="1.5"/>
                                    <circle cx="8" cy="12" r="1.5"/><circle cx="16" cy="12" r="1.5"/>
                                    <circle cx="8" cy="18" r="1.5"/><circle cx="16" cy="18" r="1.5"/>
                                </svg>
                            </div>

                            {{-- Question number --}}
                            <span class="flex-shrink-0 w-7 h-7 bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 rounded-full flex items-center justify-center text-xs font-bold mt-0.5">
                                {{ $question->order }}
                            </span>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium leading-relaxed">{{ $question->question_text }}</p>

                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    @php
                                    $typeConfig = [
                                        'scale'     => ['label' => 'Skala',      'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
                                        'boolean'   => ['label' => 'Ya/Tidak',   'color' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'],
                                        'open_text' => ['label' => 'Teks Bebas', 'color' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'],
                                    ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $typeConfig[$question->type]['color'] ?? '' }}">
                                        {{ $typeConfig[$question->type]['label'] ?? $question->type }}
                                    </span>

                                    @if($question->max_score > 0)
                                    <span class="text-xs text-slate-400">Maks {{ $question->max_score }} poin</span>
                                    @endif

                                    @if($question->options)
                                    <div class="flex gap-1.5 flex-wrap">
                                        @foreach($question->options as $val => $label)
                                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 rounded text-[10px] text-slate-500">
                                            {{ $val }}: {{ $label }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="flex items-center gap-1 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="editing = true"
                                        class="p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-500 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <form action="{{ route('admin.questionnaire.questions.duplicate', [$questionnaire, $question]) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-400 transition-colors" title="Duplikasi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </form>

                                <form action="{{ route('admin.questionnaire.questions.destroy', [$questionnaire, $question]) }}" method="POST"
                                      onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-400 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Edit Mode (inline) --}}
                    <div class="p-4 bg-blue-50/50 dark:bg-blue-900/10 border-t-2 border-blue-200 dark:border-blue-700" x-show="editing" x-cloak>
                        <form action="{{ route('admin.questionnaire.questions.update', [$questionnaire, $question]) }}" method="POST"
                              @submit="editing = false">
                            @csrf @method('PUT')
                            @include('questionnaire.partials.question-fields', ['q' => $question, 'prefix' => 'edit-'.$question->id])
                            <div class="flex gap-2 mt-4">
                                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg text-xs font-semibold hover:bg-teal-700 transition-colors flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                    Simpan
                                </button>
                                <button type="button" @click="editing = false"
                                        class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-xs hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ── TAMBAH SOAL (kanan) ──────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 sticky top-6">
                <h3 class="font-semibold text-sm mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 bg-teal-100 dark:bg-teal-900/30 text-teal-600 rounded-lg flex items-center justify-center text-xs">+</span>
                    Tambah Soal Baru
                </h3>

                <form action="{{ route('admin.questionnaire.questions.store', $questionnaire) }}" method="POST" class="space-y-4">
                    @csrf
                    @include('questionnaire.partials.question-fields', ['q' => null, 'prefix' => 'new'])
                    <button type="submit"
                            class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">
                        + Tambah Soal
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Max Score Summary --}}
    @if($questionnaire->questions->isNotEmpty())
    <div class="bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-700 rounded-2xl p-4 flex flex-wrap gap-6 text-sm">
        <div>
            <span class="text-teal-600 dark:text-teal-400 font-medium">Total soal:</span>
            <span class="font-bold ml-1">{{ $questionnaire->questions->count() }}</span>
        </div>
        <div>
            <span class="text-teal-600 dark:text-teal-400 font-medium">Total skor maksimum:</span>
            <span class="font-bold ml-1">{{ $questionnaire->questions->sum('max_score') }}</span>
        </div>
        <div>
            <span class="text-teal-600 dark:text-teal-400 font-medium">Soal ber-skor:</span>
            <span class="font-bold ml-1">{{ $questionnaire->questions->where('type', '!=', 'open_text')->count() }}</span>
        </div>
        <div>
            <span class="text-teal-600 dark:text-teal-400 font-medium">Teks bebas:</span>
            <span class="font-bold ml-1">{{ $questionnaire->questions->where('type', 'open_text')->count() }}</span>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function questionManager() {
    return {
        init() {
            const el = document.getElementById('sortable-questions');
            if (!el) return;

            Sortable.create(el, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'opacity-40',
                chosenClass: 'ring-2 ring-teal-400 rounded-2xl',
                onEnd: async () => {
                    const items = el.querySelectorAll('.question-item');
                    const order = Array.from(items).map(i => i.dataset.id);

                    await fetch('{{ route('admin.questionnaire.questions.reorder', $questionnaire) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order })
                    });

                    items.forEach((item, i) => {
                        const badge = item.querySelector('span.rounded-full');
                        if (badge) badge.textContent = i + 1;
                    });
                }
            });
        }
    }
}

/**
 * questionFields(config)
 * Dipakai oleh semua instance form (tambah & edit)
 */
const DEFAULT_LABELS = [
    'Tidak pernah','Jarang','Kadang-kadang','Sering','Selalu',
    'Sangat sering','Hampir selalu','Selalu'
];

function questionFields(cfg) {
    return {
        type:     cfg.type     || 'scale',
        maxScore: cfg.maxScore || 5,
        options:  cfg.options  && cfg.options.length
                    ? [...cfg.options]
                    : DEFAULT_LABELS.slice(0, (cfg.maxScore || 5) + 1),

        get computedMaxScore() {
            if (this.type === 'boolean')   return 1;
            if (this.type === 'open_text') return 0;
            return this.options.length - 1;
        },

        init() {
            if (this.type === 'scale') this._syncOptionsToMax();
        },

        onTypeChange() {
            if (this.type === 'boolean') {
                this.options = ['Tidak', 'Ya'];
            } else if (this.type === 'open_text') {
                this.options = [];
            } else {
                this._syncOptionsToMax();
            }
        },

        syncMaxScore() {
            this._syncOptionsToMax();
        },

        _syncOptionsToMax() {
            const target = parseInt(this.maxScore) + 1;
            while (this.options.length < target) {
                this.options.push(DEFAULT_LABELS[this.options.length] || '');
            }
            if (this.options.length > target) {
                this.options = this.options.slice(0, target);
            }
        },

        addOption() {
            this.options.push(DEFAULT_LABELS[this.options.length] || '');
            this.maxScore = this.options.length - 1;
        },

        removeOption(idx) {
            if (this.options.length <= 1) return;
            this.options.splice(idx, 1);
            this.maxScore = this.options.length - 1;
        },
    };
}
</script>
@endpush