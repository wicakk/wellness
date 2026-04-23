@extends('layouts.app')
@section('title', 'Manajemen Kasus')
@section('page-title', 'Manajemen Kasus')

@section('content')
<div class="space-y-5">

    {{-- Filters --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-slate-500 mb-1">Cari Pegawai</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama pegawai..."
                       class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                <select name="status" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="belum_ditindaklanjuti" {{ request('status') === 'belum_ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Prioritas</label>
                <select name="priority" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Prioritas</option>
                    <option value="kritis" {{ request('priority') === 'kritis' ? 'selected' : '' }}>Kritis</option>
                    <option value="tinggi" {{ request('priority') === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="sedang" {{ request('priority') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Unit</label>
                <select name="unit" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit }}" {{ request('unit') === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white text-sm rounded-xl hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg> Filter
            </button>
            @if(request()->hasAny(['search','status','priority','unit']))
            <a href="{{ route('cases.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-sm rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-semibold text-sm">Daftar Kasus <span class="text-slate-400 font-normal">({{ $cases->total() }})</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-5 py-3 text-left">Pegawai</th>
                        <th class="px-5 py-3 text-left">Unit</th>
                        <th class="px-5 py-3 text-left">Risiko</th>
                        <th class="px-5 py-3 text-left">Prioritas</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">PIC</th>
                        <th class="px-5 py-3 text-left">Tgl Masuk</th>
                        <th class="px-5 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($cases as $case)
                    @php
                        $riskColors    = ['normal'=>'text-green-700 bg-green-100','ringan'=>'text-yellow-700 bg-yellow-100','sedang'=>'text-orange-700 bg-orange-100','tinggi'=>'text-red-700 bg-red-100'];
                        $statusColors  = ['belum_ditindaklanjuti'=>'text-slate-700 bg-slate-100','diproses'=>'text-blue-700 bg-blue-100','selesai'=>'text-green-700 bg-green-100'];
                        $prioColors    = ['kritis'=>'text-red-700 bg-red-100','tinggi'=>'text-orange-700 bg-orange-100','sedang'=>'text-yellow-700 bg-yellow-100','rendah'=>'text-slate-700 bg-slate-100'];
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-medium">{{ $case->user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $case->user->nip }}</div>
                        </td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-300 text-xs">{{ $case->user->unit }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $riskColors[$case->screening->risk_level] ?? '' }}">
                                {{ ucfirst($case->screening->risk_level ?? '-') }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $prioColors[$case->priority] ?? '' }}">
                                {{ ucfirst($case->priority) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$case->status] ?? '' }}">
                                {{ str_replace('_', ' ', ucfirst($case->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $case->assignedTo?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $case->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('cases.show', $case) }}"
                               class="text-xs font-medium text-teal-600 hover:text-teal-800 dark:hover:text-teal-400">Detail →</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-slate-400">
                            <div class="text-4xl mb-2"><svg class="w-8 h-8 inline flex-shrink-0 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
                            <div>Tidak ada kasus ditemukan</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cases->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $cases->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
