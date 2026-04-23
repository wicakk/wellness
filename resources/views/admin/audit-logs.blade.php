@extends('layouts.app')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log Aktivitas')

@section('content')
<div class="space-y-5">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-semibold text-sm">Log Aktivitas Sistem <span class="text-slate-400 font-normal">({{ $logs->total() }})</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                        <th class="px-4 py-3 text-left">Model</th>
                        <th class="px-4 py-3 text-left">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($logs as $log)
                    @php
                        $actionColors = [
                            'page_view'         => 'bg-slate-100 text-slate-600',
                            'screening_completed'=> 'bg-blue-100 text-blue-700',
                            'intervention_added' => 'bg-teal-100 text-teal-700',
                            'case_status_updated'=> 'bg-yellow-100 text-yellow-700',
                            'employee_created'   => 'bg-green-100 text-green-700',
                            'employee_deleted'   => 'bg-red-100 text-red-700',
                        ];
                        $color = $actionColors[$log->action] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-xs">{{ $log->user?->name ?? 'Sistem' }}</div>
                            <div class="text-xs text-slate-400">{{ $log->user?->role->display_name ?? '' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $color }}">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $log->model ?? '—' }}
                            @if($log->model_id) <span class="text-slate-400">#{{ $log->model_id }}</span> @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-400 font-mono">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada log aktivitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
