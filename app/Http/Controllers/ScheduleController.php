<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $upcoming = Schedule::with(['creator', 'targetUser'])
            ->where(fn($q) => $q->whereNull('user_id')->orWhere('user_id', Auth::id()))
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->get();

        $past = Schedule::with(['creator', 'targetUser'])
            ->where(fn($q) => $q->whereNull('user_id')->orWhere('user_id', Auth::id()))
            ->where('start_at', '<', now())
            ->orderByDesc('start_at')
            ->take(10)
            ->get();

        $employees = User::whereHas('role', fn($q) => $q->where('name', 'pegawai'))
            ->where('is_active', true)->get();

        return view('schedules.index', compact('upcoming', 'past', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:konseling,program,reminder,webinar',
            'start_at'     => 'required|date',
            'end_at'       => 'nullable|date|after:start_at',
            'description'  => 'nullable|string',
            'location'     => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
            'user_id'      => 'nullable|exists:users,id',
            'is_reminder'  => 'boolean',
        ]);

        $schedule = Schedule::create([
            ...$request->only(['title', 'type', 'start_at', 'end_at', 'description', 'location', 'meeting_link', 'user_id', 'is_reminder']),
            'created_by' => Auth::id(),
        ]);

        \App\Models\AuditLog::record('schedule_created', 'Schedule', $schedule->id);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
