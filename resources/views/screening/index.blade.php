@extends('layouts.app')
@section('title', 'Riwayat Skrining')
@section('page-title', 'Riwayat Skrining')

@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-slate-500">Semua riwayat skrining kamu</p>
        <a href="{{ route('screening.create') }}"
           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Mulai Skrining Baru
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Kuesioner</th>
                        <th class="px-5 py-3 text-left">Skor</th>
                        <th class="px-5 py-3 text-left">Tingkat Risiko</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($screenings as $s)
                    @php $riskColors = ['normal'=>'text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400','ringan'=>'text-yellow-700 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-400','sedang'=>'text-orange-700 bg-orange-100 dark:bg-orange-900/30 dark:text-orange-400','tinggi'=>'text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-400']; @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-5 py-3 text-slate-500">{{ $s->completed_at?->format('d M Y H:i') ?? '-' }}</td>
                        <td class="px-5 py-3 text-xs">{{ $s->questionnaire->name }}</td>
                        <td class="px-5 py-3 font-bold">{{ $s->total_score ?? '-' }}</td>
                        <td class="px-5 py-3">
                            @if($s->risk_level)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $riskColors[$s->risk_level] ?? '' }}">
                                {{ ucfirst($s->risk_level) }}
                            </span>
                            @else <span class="text-slate-400 text-xs">—</span> @endif
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $s->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $s->status === 'completed' ? 'Selesai' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @if($s->status === 'completed')
                            <a href="{{ route('screening.result', $s) }}" class="text-xs text-teal-600 hover:underline">Lihat →</a>
                            @else
                            <a href="{{ route('screening.create') }}" class="text-xs text-orange-600 hover:underline">Lanjutkan →</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                            <div class="mb-2 flex justify-center"><svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                            <p>Belum ada riwayat skrining.</p>
                            <a href="{{ route('screening.create') }}" class="text-teal-600 hover:underline mt-1 inline-block">Mulai skrining pertama →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($screenings->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">{{ $screenings->links() }}</div>
        @endif
    </div>
</div>
@endsection
