@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div class="flex justify-between items-center">
        <p class="text-sm text-slate-500">{{ $notifications->total() }} notifikasi</p>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-teal-600 hover:underline">Tandai semua dibaca</button>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden divide-y divide-slate-100 dark:divide-slate-700">
        @forelse($notifications as $notif)
        @php
            $typeIcons = ['risiko_tinggi'=>'<svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>','skrining_reminder'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>','follow_up'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>','jadwal'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'];
            $icon = $typeIcons[$notif->type] ?? '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>';
        @endphp
        <div class="px-5 py-4 flex gap-4 {{ $notif->is_read ? 'opacity-60' : 'bg-teal-50/40 dark:bg-teal-900/10' }}">
            <div class="flex-shrink-0 text-xl mt-0.5">{!! $icon !!}</div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between gap-2 flex-wrap">
                    <p class="font-medium text-sm">{{ $notif->title }}</p>
                    <span class="text-xs text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-0.5">{{ $notif->message }}</p>
                @if(!$notif->is_read)
                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="mt-2">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs text-teal-600 hover:underline">Tandai sudah dibaca</button>
                </form>
                @endif
            </div>
            @if(!$notif->is_read)
            <div class="flex-shrink-0 mt-2">
                <div class="w-2 h-2 rounded-full bg-teal-500"></div>
            </div>
            @endif
        </div>
        @empty
        <div class="px-5 py-12 text-center">
            <div class="text-5xl mb-3"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>
            <p class="text-slate-500 text-sm">Tidak ada notifikasi</p>
        </div>
        @endforelse
    </div>

    {{ $notifications->links() }}
</div>
@endsection
