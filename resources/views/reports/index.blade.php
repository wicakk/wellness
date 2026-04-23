@extends('layouts.app')
@section('title', 'Laporan & Analitik')
@section('page-title', 'Laporan & Analitik')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
        <h3 class="font-semibold text-sm mb-5">Generate Laporan</h3>
        <form action="{{ route('admin.reports.generate') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1.5">Tipe Laporan <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['unit'=>['<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>','Per Unit'],'tren'=>['<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>','Tren Waktu'],'individual'=>['<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>','Individual']] as $val => [$icon,$label])
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="{{ $val }}" class="sr-only peer" {{ old('type','unit') === $val ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-3 text-center transition-all peer-checked:border-teal-500 peer-checked:bg-teal-50 dark:peer-checked:bg-teal-900/20 border-slate-200 dark:border-slate-700 hover:border-teal-300">
                            <div class="text-xl mb-1">{!! $icon !!}</div>
                            <div class="text-xs font-medium">{{ $label }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Dari Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date_from" value="{{ old('date_from', now()->startOfMonth()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Sampai Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date_to" value="{{ old('date_to', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Filter Unit (opsional)</label>
                <select name="unit" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-3">Format Export <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="format" value="excel" checked class="text-teal-600 focus:ring-teal-500">
                        <span class="text-sm"><svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Excel (.xlsx)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="format" value="pdf" class="text-teal-600 focus:ring-teal-500">
                        <span class="text-sm"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> PDF</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">
                📥 Download Laporan
            </button>
        </form>
    </div>

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-2xl p-4">
        <p class="text-xs text-blue-700 dark:text-blue-400">
            <strong><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg> Catatan:</strong> Laporan Excel membutuhkan package <code>maatwebsite/excel</code>,
            laporan PDF membutuhkan <code>barryvdh/laravel-dompdf</code>. Pastikan keduanya sudah terinstall.
        </p>
    </div>
</div>
@endsection
