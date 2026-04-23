@extends('layouts.app')
@section('title', 'Detail Kasus')
@section('page-title', 'Detail Kasus')

@section('content')
@php
    $riskColors   = ['normal'=>'text-green-700 bg-green-100','ringan'=>'text-yellow-700 bg-yellow-100','sedang'=>'text-orange-700 bg-orange-100','tinggi'=>'text-red-700 bg-red-100'];
    $statusColors = ['belum_ditindaklanjuti'=>'text-slate-700 bg-slate-100','diproses'=>'text-blue-700 bg-blue-100','selesai'=>'text-green-700 bg-green-100'];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left Column --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Case Header --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
            <div class="flex flex-wrap gap-3 items-start justify-between">
                <div>
                    <h2 class="font-bold text-lg">{{ $case->user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $case->user->jabatan ?? 'Pegawai' }} — {{ $case->user->unit }}</p>
                    <p class="text-xs text-slate-400 mt-1">NIP: {{ $case->user->nip ?? '-' }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $riskColors[$case->screening->risk_level ?? 'normal'] ?? '' }}">
                        Risiko {{ ucfirst($case->screening->risk_level ?? '-') }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$case->status] ?? '' }}">
                        {{ str_replace('_', ' ', ucfirst($case->status)) }}
                    </span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs text-slate-500">
                <div><span class="font-medium block">Skor Skrining</span>{{ $case->screening->total_score ?? '-' }}</div>
                <div><span class="font-medium block">Skrining Tanggal</span>{{ $case->screening?->completed_at?->format('d M Y') ?? '-' }}</div>
                <div><span class="font-medium block">PIC</span>{{ $case->assignedTo?->name ?? '—' }}</div>
                <div><span class="font-medium block">Prioritas</span>{{ ucfirst($case->priority) }}</div>
                <div><span class="font-medium block">Target Selesai</span>{{ $case->target_completion?->format('d M Y') ?? '—' }}</div>
                <div><span class="font-medium block">Dibuka</span>{{ $case->created_at->format('d M Y') }}</div>
            </div>

            {{-- Assign PIC --}}
            @if(auth()->user()->canManageCases())
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-wrap gap-3 items-center">
                <form action="{{ route('cases.assign', $case) }}" method="POST" class="flex gap-2 items-center flex-wrap">
                    @csrf @method('PATCH')
                    <select name="assigned_to" class="text-sm px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">— Pilih PIC —</option>
                        @foreach($staff as $s)
                        <option value="{{ $s->id }}" {{ $case->assigned_to == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->role->display_name }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-teal-600 text-white text-xs rounded-xl hover:bg-teal-700 transition-colors">Tetapkan PIC</button>
                </form>

                {{-- Status Update --}}
                <form action="{{ route('cases.status.update', $case) }}" method="POST" class="flex gap-2 items-center">
                    @csrf @method('PATCH')
                    <select name="status" class="text-sm px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="belum_ditindaklanjuti" {{ $case->status === 'belum_ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                        <option value="diproses" {{ $case->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $case->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-xl hover:bg-blue-700 transition-colors">Update Status</button>
                </form>
            </div>
            @endif
        </div>

        {{-- Intervention History --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-semibold text-sm">Riwayat Intervensi ({{ $case->interventions->count() }})</h3>
            </div>

            @forelse($case->interventions->sortByDesc('intervention_date') as $iv)
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 last:border-0">
                <div class="flex flex-wrap gap-2 justify-between items-start">
                    <div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400">
                            {{ ucfirst($iv->type) }}
                        </span>
                        <span class="text-xs text-slate-400 ml-2">{{ $iv->intervention_date->format('d M Y') }}</span>
                    </div>
                    <span class="text-xs text-slate-400">oleh {{ $iv->performedBy?->name ?? '-' }}</span>
                </div>
                <p class="text-sm mt-2 text-slate-700 dark:text-slate-300">{{ $iv->notes }}</p>
                @if($iv->outcome)
                <p class="text-xs mt-1 text-slate-500"><strong>Hasil:</strong> {{ $iv->outcome }}</p>
                @endif
                @if($iv->next_follow_up)
                <p class="text-xs mt-1 text-blue-600 dark:text-blue-400"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Follow-up: {{ $iv->next_follow_up->format('d M Y') }}</p>
                @endif
            </div>
            @empty
            <div class="px-5 py-8 text-center text-slate-400 text-sm">Belum ada intervensi tercatat.</div>
            @endforelse
        </div>

        {{-- Add Intervention Form --}}
        @if(auth()->user()->canManageCases() && $case->status !== 'selesai')
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
            <h3 class="font-semibold text-sm mb-4">Tambah Catatan Intervensi</h3>
            <form action="{{ route('cases.interventions.add', $case) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Jenis Intervensi <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                            <option value="">Pilih jenis</option>
                            <option value="konseling">Konseling</option>
                            <option value="rujukan">Rujukan</option>
                            <option value="edukasi">Edukasi</option>
                            <option value="pendampingan">Pendampingan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="intervention_date" value="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Catatan <span class="text-red-500">*</span></label>
                    <textarea name="notes" rows="3" placeholder="Deskripsi intervensi yang dilakukan..."
                              class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none" required></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Hasil Intervensi</label>
                        <input type="text" name="outcome" placeholder="Kondisi setelah intervensi..."
                               class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Follow-up Berikutnya</label>
                        <input type="date" name="next_follow_up"
                               class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <div class="flex gap-3 items-center">
                    <div>
                        <label class="block text-xs font-medium mb-1.5">Perbarui Status Kasus</label>
                        <select name="status" class="px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="">— Tidak berubah —</option>
                            <option value="diproses">Diproses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="mt-5 px-5 py-2 bg-teal-600 text-white text-sm font-medium rounded-xl hover:bg-teal-700 transition-colors">
                        <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Simpan Intervensi
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

    {{-- Right Column --}}
    <div class="space-y-5">

        {{-- Screening History --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-semibold text-sm">Riwayat Skrining</h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($case->user->screenings->where('status','completed')->sortByDesc('completed_at')->take(5) as $s)
                @php $rc = ['normal'=>'text-green-600','ringan'=>'text-yellow-600','sedang'=>'text-orange-600','tinggi'=>'text-red-600']; @endphp
                <div class="px-4 py-3 flex justify-between items-center">
                    <div>
                        <div class="text-xs font-medium {{ $rc[$s->risk_level] ?? '' }}">{{ ucfirst($s->risk_level) }}</div>
                        <div class="text-xs text-slate-400">{{ $s->completed_at->format('d M Y') }}</div>
                    </div>
                    <div class="text-lg font-bold text-slate-700 dark:text-slate-200">{{ $s->total_score }}</div>
                </div>
                @empty
                <div class="px-4 py-6 text-center text-xs text-slate-400">Belum ada riwayat</div>
                @endforelse
            </div>
        </div>

        {{-- Back Link --}}
        <a href="{{ route('cases.index') }}"
           class="block text-center px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            ← Kembali ke Daftar Kasus
        </a>
    </div>
</div>
@endsection
