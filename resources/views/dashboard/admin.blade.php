@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    {{-- ── STAT CARDS ───────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Total Pegawai',      'value' => \App\Models\User::whereHas('role', fn($q) => $q->where('name','pegawai'))->count(), 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>', 'color' => 'teal'],
                ['label' => 'Skrining Bulan Ini', 'value' => $totalScreeningThisMonth, 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>', 'color' => 'blue'],
                ['label' => 'Kasus Aktif',        'value' => \App\Models\Cases::whereIn('status',['belum_ditindaklanjuti','diproses'])->count(), 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>', 'color' => 'orange'],
                ['label' => 'Risiko Tinggi',      'value' => $riskDistribution['tinggi'] ?? 0, 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 'color' => 'red'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="mb-2 text-teal-500">{!! $stat['icon'] !!}</div>
            <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ $stat['value'] }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ── RISK DISTRIBUTION + TREND ───────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Risk Donut --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="font-semibold text-sm mb-4">Distribusi Risiko</h3>
            <div class="relative h-48 flex items-center justify-center">
                <canvas id="riskChart"></canvas>
                <div class="absolute text-center pointer-events-none">
                    <div class="text-2xl font-bold">{{ array_sum($riskDistribution->toArray()) }}</div>
                    <div class="text-xs text-slate-400">Total</div>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                @foreach(['normal' => ['label'=>'Normal','color'=>'#22c55e'], 'ringan' => ['label'=>'Ringan','color'=>'#eab308'], 'sedang' => ['label'=>'Sedang','color'=>'#f97316'], 'tinggi' => ['label'=>'Tinggi','color'=>'#ef4444']] as $key => $conf)
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background:{{ $conf['color'] }}"></span>
                        <span class="text-slate-600 dark:text-slate-300">{{ $conf['label'] }}</span>
                    </div>
                    <span class="font-semibold">{{ $riskDistribution[$key] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Trend Chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="font-semibold text-sm mb-4">Tren Skrining 6 Bulan</h3>
            <div class="h-52">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── PRIORITY CASES ────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-semibold text-sm">Kasus Prioritas</h3>
            <a href="{{ route('cases.index') }}" class="text-xs text-teal-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-5 py-3 text-left">Pegawai</th>
                        <th class="px-5 py-3 text-left">Unit</th>
                        <th class="px-5 py-3 text-left">Risiko</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">PIC</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($priorityCases as $case)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-medium">{{ $case->user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $case->user->nip }}</div>
                        </td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $case->user->unit }}</td>
                        <td class="px-5 py-3">
                            @php $riskColors = ['normal'=>'text-green-600 bg-green-100','ringan'=>'text-yellow-600 bg-yellow-100','sedang'=>'text-orange-600 bg-orange-100','tinggi'=>'text-red-600 bg-red-100']; @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $riskColors[$case->screening->risk_level] ?? '' }}">
                                {{ ucfirst($case->screening->risk_level) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @php $statusColors = ['belum_ditindaklanjuti'=>'text-slate-600 bg-slate-100','diproses'=>'text-blue-600 bg-blue-100','selesai'=>'text-green-600 bg-green-100']; @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$case->status] ?? '' }}">
                                {{ str_replace('_', ' ', ucfirst($case->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300 text-xs">
                            {{ $case->assignedTo?->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('cases.show', $case) }}"
                               class="text-xs font-medium text-teal-600 hover:text-teal-800 dark:hover:text-teal-400">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                            <div class="mb-2 flex justify-center"><svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
                            <div class="text-sm">Tidak ada kasus prioritas saat ini</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── UNIT STATS ────────────────────────────────────────────────────── --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700">
        <h3 class="font-semibold text-sm mb-4">Partisipasi Skrining per Unit</h3>
        <div class="space-y-3">
            @foreach($unitStats as $unit)
            @php $pct = $unit->total_pegawai > 0 ? round(($unit->screened / $unit->total_pegawai) * 100) : 0; @endphp
            <div class="flex items-center gap-4">
                <div class="w-28 text-xs text-slate-600 dark:text-slate-300 truncate">{{ $unit->unit }}</div>
                <div class="flex-1 h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-teal-500 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                </div>
                <div class="text-xs font-semibold text-slate-500 w-16 text-right">{{ $unit->screened }}/{{ $unit->total_pegawai }}</div>
                <div class="text-xs text-teal-600 font-bold w-10 text-right">{{ $pct }}%</div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const isDark = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)';
const textColor = isDark ? '#94a3b8' : '#64748b';

// Risk Donut
new Chart(document.getElementById('riskChart'), {
    type: 'doughnut',
    data: {
        labels: ['Normal', 'Ringan', 'Sedang', 'Tinggi'],
        datasets: [{
            data: [
                {{ $riskDistribution['normal'] ?? 0 }},
                {{ $riskDistribution['ringan'] ?? 0 }},
                {{ $riskDistribution['sedang'] ?? 0 }},
                {{ $riskDistribution['tinggi'] ?? 0 }},
            ],
            backgroundColor: ['#22c55e', '#eab308', '#f97316', '#ef4444'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        cutout: '72%',
        plugins: { legend: { display: false }, tooltip: { callbacks: {
            label: ctx => ` ${ctx.label}: ${ctx.raw} pegawai`
        }}},
        animation: { animateRotate: true, duration: 900 }
    }
});

// Trend Line Chart
@php
    $months = $screeningTrend->pluck('month')->unique()->sort()->values();
    $levels = ['normal', 'ringan', 'sedang', 'tinggi'];
    $colors = ['#22c55e', '#eab308', '#f97316', '#ef4444'];
    $datasets = [];
    foreach ($levels as $i => $level) {
        $data = $months->map(fn($m) => $screeningTrend->where('month', $m)->where('risk_level', $level)->first()?->total ?? 0)->values();
        $datasets[] = ['label' => ucfirst($level), 'data' => $data, 'color' => $colors[$i]];
    }
@endphp

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: {!! $months->map(fn($m) => \Carbon\Carbon::parse($m)->translatedFormat('M Y'))->toJson() !!},
        datasets: [
            @foreach($datasets as $ds)
            {
                label: '{{ $ds['label'] }}',
                data: {!! json_encode($ds['data']->toArray()) !!},
                borderColor: '{{ $ds['color'] }}',
                backgroundColor: '{{ $ds['color'] }}22',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 7,
            },
            @endforeach
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 } } },
            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, precision: 0 } }
        },
        plugins: {
            legend: { labels: { color: textColor, usePointStyle: true, pointStyleWidth: 8, boxHeight: 6 } }
        },
        interaction: { mode: 'index', intersect: false },
        animation: { duration: 900 }
    }
});
</script>
@endpush
