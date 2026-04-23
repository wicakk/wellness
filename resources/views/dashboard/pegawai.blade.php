@extends('layouts.app')
@section('title', 'Dashboard Saya')
@section('page-title', 'Dashboard Pegawai')

@section('content')
<div class="space-y-6">

    {{-- ── GREETING + STATUS ────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-br from-teal-600 to-teal-800 dark:from-teal-800 dark:to-slate-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg viewBox="0 0 400 200" fill="currentColor" class="w-full h-full">
                <circle cx="300" cy="50" r="150" opacity="0.4"/>
                <circle cx="350" cy="180" r="80" opacity="0.3"/>
            </svg>
        </div>
        <div class="relative">
            <p class="text-teal-100 text-sm">Selamat datang,</p>
            <h2 class="text-2xl font-bold mt-1">{{ auth()->user()->name }}</h2>
            <p class="text-teal-200 text-sm mt-1">{{ auth()->user()->jabatan ?? 'Pegawai' }} — {{ auth()->user()->unit }}</p>

            @if($latestScreening)
            <div class="mt-4 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                <span class="w-3 h-3 rounded-full" style="background:{{ $latestScreening->risk_color }}"></span>
                <span class="text-sm font-medium">Status: Risiko {{ ucfirst($latestScreening->risk_level) }}</span>
                <span class="text-teal-200 text-xs ml-2">{{ $latestScreening->completed_at->diffForHumans() }}</span>
            </div>
            @else
            <div class="mt-4 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                <span class="text-sm font-medium">Belum ada skrining</span>
            </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('screening.create') }}"
                   class="inline-flex items-center gap-2 bg-white text-teal-700 font-semibold text-sm px-5 py-2.5 rounded-xl hover:bg-teal-50 transition-colors shadow">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg> Mulai Skrining
                </a>
            </div>
        </div>
    </div>

    {{-- ── QUICK STATS ──────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
            <div class="text-3xl font-bold text-teal-600">{{ $screeningHistory->count() }}</div>
            <div class="text-xs text-slate-400 mt-1">Total Skrining</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $latestScreening?->total_score ?? '—' }}</div>
            <div class="text-xs text-slate-400 mt-1">Skor Terakhir</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
            @php
                $trend = null;
                if ($screeningHistory->count() >= 2) {
                    $diff = $screeningHistory->first()->total_score - $screeningHistory->skip(1)->first()->total_score;
                    $trend = $diff < 0 ? '↓' : ($diff > 0 ? '↑' : '→');
                }
            @endphp
            <div class="text-3xl font-bold {{ $trend === '↓' ? 'text-green-500' : ($trend === '↑' ? 'text-red-500' : 'text-slate-400') }}">
                {{ $trend ?? '—' }}
            </div>
            <div class="text-xs text-slate-400 mt-1">Tren</div>
        </div>
    </div>

    {{-- ── SCORE CHART ──────────────────────────────────────────────────── --}}
    @if($chartData->count() > 1)
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <h3 class="font-semibold text-sm mb-4">Riwayat Skor Skrining</h3>
        <div class="h-48">
            <canvas id="scoreChart"></canvas>
        </div>
    </div>
    @endif

    {{-- ── SCREENING HISTORY TABLE ───────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-semibold text-sm">Riwayat Skrining</h3>
            <a href="{{ route('screening.create') }}" class="text-xs bg-teal-600 text-white px-3 py-1.5 rounded-lg hover:bg-teal-700 transition-colors">+ Skrining Baru</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Kuesioner</th>
                        <th class="px-5 py-3 text-left">Skor</th>
                        <th class="px-5 py-3 text-left">Tingkat Risiko</th>
                        <th class="px-5 py-3 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($screeningHistory as $s)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $s->completed_at->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-xs">{{ $s->questionnaire->name }}</td>
                        <td class="px-5 py-3 font-bold">{{ $s->total_score }}</td>
                        <td class="px-5 py-3">
                            @php $riskColors = ['normal'=>'text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400','ringan'=>'text-yellow-700 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400','sedang'=>'text-orange-700 bg-orange-100 dark:bg-orange-900/30 dark:text-orange-400','tinggi'=>'text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-400']; @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $riskColors[$s->risk_level] ?? '' }}">
                                {{ ucfirst($s->risk_level) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('screening.result', $s) }}" class="text-xs text-teal-600 hover:underline">Lihat →</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                            <div class="mb-2 flex justify-center"><svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                            <div>Belum ada riwayat skrining.</div>
                            <a href="{{ route('screening.create') }}" class="text-teal-600 hover:underline mt-1 inline-block">Mulai sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── EDUCATION ─────────────────────────────────────────────────────── --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-sm">Artikel & Edukasi Terbaru</h3>
            <a href="{{ route('education.index') }}" class="text-xs text-teal-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($educations as $edu)
            <a href="{{ route('education.show', $edu) }}"
               class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md hover:-translate-y-0.5 transition-all">
                <div class="text-2xl mb-2">
                    {{ match($edu->type) { 'artikel' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>', 'video' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>', 'infografis' => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>', default => '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' } }}
                </div>
                <div class="font-medium text-sm leading-tight line-clamp-2">{{ $edu->title }}</div>
                <div class="text-xs text-slate-400 mt-2">{{ $edu->category }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $edu->view_count }} views</div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── BANTUAN / KONTAK ──────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-blue-50 to-teal-50 dark:from-blue-900/20 dark:to-teal-900/20 border border-blue-200 dark:border-blue-700 rounded-2xl p-5">
        <div class="flex items-start gap-4">
            <div class="text-3xl">🆘</div>
            <div>
                <h4 class="font-semibold text-blue-800 dark:text-blue-300">Butuh Bantuan?</h4>
                <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                    Jika kamu merasa membutuhkan dukungan segera, jangan ragu untuk menghubungi tim Wellness atau Psikolog.
                </p>
                <div class="flex gap-3 mt-3">
                    <a href="mailto:wellness@company.com" class="text-xs bg-teal-600 text-white px-3 py-1.5 rounded-lg hover:bg-teal-700 transition-colors">
                        📧 Hubungi Wellness
                    </a>
                    <a href="#" class="text-xs border border-blue-400 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> Hotline
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
@if($chartData->count() > 1)
<script>
const isDark = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)';
const textColor = isDark ? '#94a3b8' : '#64748b';

new Chart(document.getElementById('scoreChart'), {
    type: 'line',
    data: {
        labels: {!! $chartData->map(fn($s) => $s->completed_at->format('d M'))->toJson() !!},
        datasets: [{
            label: 'Skor Skrining',
            data: {!! $chartData->pluck('total_score')->toJson() !!},
            borderColor: '#14b8a6',
            backgroundColor: 'rgba(20,184,166,0.15)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: {!! $chartData->map(fn($s) => match($s->risk_level) {
                'normal' => '#22c55e', 'ringan' => '#eab308', 'sedang' => '#f97316', default => '#ef4444'
            })->toJson() !!},
            pointRadius: 6,
            pointHoverRadius: 9,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { grid: { color: gridColor }, ticks: { color: textColor } },
            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, precision: 0 } }
        },
        plugins: { legend: { display: false } },
        animation: { duration: 800 }
    }
});
</script>
@endif
@endpush
