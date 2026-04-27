@extends('layouts.app')
@section('title', $employee->name)
@section('page-title', 'Detail Pegawai')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Profile --}}
    <div class="space-y-5">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 text-center">
            <div class="w-20 h-20 rounded-full bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center text-3xl font-bold text-teal-700 dark:text-teal-400 mx-auto mb-4">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <h2 class="font-bold text-lg">{{ $employee->name }}</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $employee->jabatan ?? 'Pegawai' }}</p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                {{ $employee->role->display_name }}
            </span>
        </div>

        {{-- Informasi Dasar --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 space-y-3 text-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Informasi Dasar</p>
            <div class="flex justify-between"><span class="text-slate-500">NIP</span><span class="font-medium">{{ $employee->nip ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Unit</span><span class="font-medium">{{ $employee->unit }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Email</span><span class="font-medium text-xs">{{ $employee->email }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Telepon</span><span class="font-medium">{{ $employee->phone ?? '—' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Gender</span>
                <span class="font-medium">
                    {{ match($employee->gender) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => '—'
                    } }}
                </span>
            </div>
            <div class="flex justify-between"><span class="text-slate-500">Bergabung</span><span class="font-medium">{{ $employee->created_at->format('M Y') }}</span></div>
        </div>

        {{-- Data Personal --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 space-y-3 text-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Data Personal</p>
            <div class="flex justify-between">
                <span class="text-slate-500">Usia</span>
                <span class="font-medium">{{ $employee->usia ? $employee->usia . ' tahun' : '—' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status Pernikahan</span>
                <span class="font-medium">
                    {{ match($employee->status_pernikahan) {
                        'belum_menikah' => 'Belum Menikah',
                        'menikah'       => 'Menikah',
                        'cerai_hidup'   => 'Cerai Hidup',
                        'cerai_mati'    => 'Cerai Mati',
                        default         => '—'
                    } }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Pendidikan</span>
                <span class="font-medium">{{ $employee->pendidikan ?? '—' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Lama Bekerja</span>
                <span class="font-medium">
                    @if($employee->lama_kerja_tahun !== null || $employee->lama_kerja_bulan !== null)
                        {{ $employee->lama_kerja_tahun ?? 0 }} thn {{ $employee->lama_kerja_bulan ?? 0 }} bln
                    @else
                        —
                    @endif
                </span>
            </div>
        </div>

        {{-- Riwayat Kesehatan --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 space-y-3 text-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Riwayat Kesehatan</p>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Masalah Kesehatan</span>
                @if($employee->has_health_issue)
                    <span class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Ada kondisi</span>
                @else
                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Tidak ada</span>
                @endif
            </div>
            @if($employee->has_health_issue && $employee->health_issue_detail)
            <p class="text-xs text-slate-500 bg-slate-50 dark:bg-slate-700/50 rounded-xl p-3 leading-relaxed">
                {{ $employee->health_issue_detail }}
            </p>
            @endif
        </div>

        <div class="flex flex-col gap-2">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.employees.edit', $employee) }}"
               class="block text-center px-4 py-2.5 bg-teal-600 text-white rounded-xl text-sm font-medium hover:bg-teal-700 transition-colors">
                Edit Data
            </a>
            @endif
            <a href="{{ route('admin.employees.index') }}"
               class="block text-center px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- Right: History --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Screening Stats --}}
        @php
            $latest = $screeningHistory->first();
            $riskColors = ['normal'=>'text-green-600','ringan'=>'text-yellow-600','sedang'=>'text-orange-600','tinggi'=>'text-red-600'];
            $riskBg = ['normal'=>'bg-green-100 dark:bg-green-900/30','ringan'=>'bg-yellow-100 dark:bg-yellow-900/30','sedang'=>'bg-orange-100 dark:bg-orange-900/30','tinggi'=>'bg-red-100 dark:bg-red-900/30'];
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <div class="text-2xl font-bold text-teal-600">{{ $screeningHistory->count() }}</div>
                <div class="text-xs text-slate-400 mt-1">Total Skrining</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <div class="text-2xl font-bold {{ $riskColors[$latest?->risk_level ?? 'normal'] }}">{{ $latest?->total_score ?? '—' }}</div>
                <div class="text-xs text-slate-400 mt-1">Skor Terakhir</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                <div class="text-sm font-bold {{ $riskColors[$latest?->risk_level ?? 'normal'] }}">{{ ucfirst($latest?->risk_level ?? '—') }}</div>
                <div class="text-xs text-slate-400 mt-1">Risiko Terakhir</div>
            </div>
        </div>

        {{-- Screening History --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-semibold text-sm">Riwayat Skrining</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-slate-500 bg-slate-50 dark:bg-slate-700/50">
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Kuesioner</th>
                            <th class="px-4 py-3 text-left">Skor</th>
                            <th class="px-4 py-3 text-left">Risiko</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($screeningHistory as $s)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-4 py-3 text-slate-500">{{ $s->completed_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-xs">{{ $s->questionnaire->name }}</td>
                            <td class="px-4 py-3 font-bold">{{ $s->total_score }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $riskBg[$s->risk_level] ?? '' }} {{ $riskColors[$s->risk_level] ?? '' }}">
                                    {{ ucfirst($s->risk_level) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400 text-xs">Belum ada skrining</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Active Cases --}}
        @if($activeCases->count() > 0)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-semibold text-sm">Kasus Aktif ({{ $activeCases->count() }})</h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($activeCases as $case)
                <div class="px-5 py-4 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-medium text-orange-600">{{ str_replace('_', ' ', ucfirst($case->status)) }}</span>
                        <div class="text-xs text-slate-400 mt-0.5">{{ $case->interventions->count() }} intervensi</div>
                    </div>
                    <a href="{{ route('cases.show', $case) }}" class="text-xs text-teal-600 hover:underline">Detail →</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection