@extends('layouts.app')
@section('title', 'Jadwal & Reminder')
@section('page-title', 'Jadwal & Reminder')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Upcoming Schedules --}}
    <div class="lg:col-span-2 space-y-5">
        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-200">Jadwal Mendatang</h3>

        @forelse($upcoming as $schedule)
        @php
            $typeColors = ['konseling'=>'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400','program'=>'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400','reminder'=>'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400','webinar'=>'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'];
            $typeIcons = ['konseling'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>','program'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>','reminder'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>','webinar'=>'<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>'];
        @endphp
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5 flex gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $typeColors[$schedule->type] ?? 'bg-slate-100 text-slate-600' }}">
                {!! $typeIcons[$schedule->type] ?? '<svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' !!}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap justify-between gap-2">
                    <h4 class="font-semibold text-sm">{{ $schedule->title }}</h4>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $typeColors[$schedule->type] ?? '' }}">{{ ucfirst($schedule->type) }}</span>
                </div>
                <p class="text-xs text-teal-600 dark:text-teal-400 mt-1 font-medium">
                    <svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ $schedule->start_at->format('D, d M Y · H:i') }}
                    @if($schedule->end_at) — {{ $schedule->end_at->format('H:i') }} @endif
                </p>
                @if($schedule->description)
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $schedule->description }}</p>
                @endif
                <div class="flex flex-wrap gap-3 mt-2 text-xs text-slate-400">
                    @if($schedule->location) <span>📍 {{ $schedule->location }}</span> @endif
                    @if($schedule->meeting_link) <a href="{{ $schedule->meeting_link }}" target="_blank" class="text-blue-500 hover:underline">🔗 Link</a> @endif
                    @if($schedule->targetUser) <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> {{ $schedule->targetUser->name }}</span> @else <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Semua Pegawai</span> @endif
                    <span>oleh {{ $schedule->creator->name }}</span>
                </div>
            </div>
            @if(auth()->user()->canManageCases() && (auth()->user()->isAdmin() || $schedule->created_by === auth()->id()))
            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="flex-shrink-0">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Hapus jadwal ini?')"
                        class="text-slate-300 hover:text-red-500 transition-colors p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </form>
            @endif
        </div>
        @empty
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 p-10 text-center">
            <div class="text-4xl mb-2"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
            <p class="text-sm text-slate-500">Tidak ada jadwal mendatang</p>
        </div>
        @endforelse

        {{-- Past --}}
        @if($past->count())
        <div>
            <h3 class="font-semibold text-sm text-slate-500 dark:text-slate-400 mb-3">Jadwal Lalu</h3>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($past as $schedule)
                <div class="px-5 py-3 flex justify-between items-center opacity-60">
                    <div>
                        <span class="text-sm font-medium">{{ $schedule->title }}</span>
                        <span class="text-xs text-slate-400 ml-2">{{ $schedule->start_at->format('d M Y') }}</span>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500">{{ ucfirst($schedule->type) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Add Schedule Form --}}
    @if(auth()->user()->canManageCases())
    <div>
        <h3 class="font-semibold text-sm text-slate-700 dark:text-slate-200 mb-4">Tambah Jadwal</h3>
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-5">
            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium mb-1.5">Judul <span class="text-red-500">*</span></label>
                    <input type="text" name="title" placeholder="Judul jadwal..." required
                           class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Tipe <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="konseling"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg> Konseling</option>
                        <option value="program">Program</option>
                        <option value="reminder"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg> Reminder</option>
                        <option value="webinar"><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg> Webinar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_at" required
                           class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Selesai</label>
                    <input type="datetime-local" name="end_at"
                           class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="2"
                              class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Lokasi</label>
                    <input type="text" name="location" placeholder="Ruang meeting / online..."
                           class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Link Meeting</label>
                    <input type="url" name="meeting_link" placeholder="https://..."
                           class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5">Target Pegawai (kosong = semua)</label>
                    <select name="user_id" class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value=""><svg class="w-4 h-4 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Semua Pegawai</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold transition-colors">
                    + Tambah Jadwal
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
