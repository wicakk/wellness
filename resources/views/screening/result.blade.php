
@extends('layouts.app')
@section('title', 'Hasil Skrining')
@section('page-title', 'Hasil Skrining')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Result Card --}}
    @php
        $riskConfig = [
            'normal'  => ['bg'=>'from-green-500 to-emerald-600',
                          'icon'=>'<svg class="w-14 h-14 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                        //   'msg'=>'Kondisi mental kamu baik! Pertahankan pola hidup sehat.'
                          ],
            'ringan'  => ['bg'=>'from-yellow-400 to-amber-500',
                          'icon'=>'<svg class="w-14 h-14 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>',
                        //   'msg'=>'Ada beberapa tanda yang perlu diperhatikan. Jaga keseimbangan hidupmu.'
                          ],
            'sedang'  => ['bg'=>'from-orange-500 to-amber-600',
                          'icon'=>'<svg class="w-14 h-14 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>',
                        //   'msg'=>'Kondisi kamu perlu perhatian lebih. Tim Wellness akan segera menghubungi.'
                          ],
            'tinggi'  => ['bg'=>'from-red-500 to-rose-600',
                          'icon'=>'<svg class="w-14 h-14 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>',
                        //   'msg'=>'Kamu membutuhkan bantuan profesional. Tim kami akan segera menghubungimu.'
                          ],
        ];
        $conf = $riskConfig[$screening->risk_level] ?? $riskConfig['normal'];
    @endphp

    <div class="bg-gradient-to-br {{ $conf['bg'] }} rounded-2xl p-8 text-white text-center shadow-xl">
        <div class="mb-3 flex justify-center text-white">{!! $conf['icon'] !!}</div>
        <h2 class="text-2xl font-bold">Risiko {{ ucfirst($screening->risk_level) }}</h2>
        <div class="text-5xl font-black mt-2 mb-1">{{ $screening->total_score }}</div>
        <div class="text-white/80 text-sm mb-4">Total Skor</div>
        {{-- <p class="text-white/90 text-sm max-w-sm mx-auto">{{ $conf['msg'] }}</p> --}}
    </div>

    {{-- Answer Summary --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-semibold text-sm">Ringkasan Jawaban</h3>
        </div>
        <div class="divide-y divide-slate-100 dark:divide-slate-700">
            @foreach($screening->answers->sortBy('question.order') as $answer)
            <div class="px-5 py-3 flex gap-4">
                <span class="flex-shrink-0 w-6 h-6 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-xs font-bold text-slate-500">
                    {{ $answer->question->order }}
                </span>
                <div class="flex-1">
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $answer->question->question_text }}</p>
                    @if($answer->score !== null)
                    <div class="flex items-center gap-2 mt-1">
                        @for($i = 0; $i <= 4; $i++)
                        <div class="w-3 h-3 rounded-full {{ $i <= $answer->score ? 'bg-teal-500' : 'bg-slate-200 dark:bg-slate-600' }}"></div>
                        @endfor
                        <span class="text-xs text-slate-400">{{ $answer->question->options[$answer->score] ?? $answer->score }}</span>
                    </div>
                    @else
                    <p class="text-xs text-slate-400 mt-1 italic">{{ $answer->answer_text }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Notes --}}
    @if($screening->open_notes)
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-2xl p-5">
        <h4 class="font-semibold text-sm text-blue-800 dark:text-blue-300 mb-2">Catatan Tambahan</h4>
        <p class="text-sm text-blue-700 dark:text-blue-400">{{ $screening->open_notes }}</p>
    </div>
    @endif

    <div class="flex gap-3">
        <a href="{{ route('pegawai.dashboard') }}" class="flex-1 text-center px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            ← Kembali
        </a>
        <a href="{{ route('screening.create') }}" class="flex-1 text-center px-4 py-3 bg-teal-600 text-white rounded-xl text-sm font-medium hover:bg-teal-700 transition-colors">
            Skrining Baru
        </a>
    </div>
</div>
@endsection
