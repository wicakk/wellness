<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Cases::class);

        $query = Cases::with(['user', 'screening', 'assignedTo', 'interventions']);

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('unit'))     $query->whereHas('user', fn($q) => $q->where('unit', $request->unit));
        if ($request->filled('search'))   $query->whereHas('user', fn($q) => $q->where('name', 'like', '%'.$request->search.'%'));

        $cases = $query->latest()->paginate(15)->withQueryString();
        $units = User::distinct()->pluck('unit');
        $staff = User::whereHas('role', fn($q) => $q->whereIn('name', ['admin', 'wellness_warrior', 'psikolog']))->get();

        return view('cases.index', compact('cases', 'units', 'staff'));
    }

    public function show(Cases $case)
    {
        $this->authorize('view', $case);
        $case->load(['user.screenings', 'screening', 'assignedTo', 'interventions.performedBy']);
        $staff = User::whereHas('role', fn($q) => $q->whereIn('name', ['admin', 'wellness_warrior', 'psikolog']))->get();
        return view('cases.show', compact('case', 'staff'));
    }

    public function addIntervention(Request $request, Cases $case)
    {
        $this->authorize('update', $case);

        $request->validate([
            'type'              => 'required|string',
            'intervention_date' => 'required|date',
            'notes'             => 'required|string',
            'outcome'           => 'nullable|string',
            'follow_up_status'  => 'nullable|string',
            'next_follow_up'    => 'nullable|date',
        ]);

        $case->interventions()->create([
            ...$request->only(['type', 'intervention_date', 'notes', 'outcome', 'follow_up_status', 'next_follow_up']),
            'performed_by' => Auth::id(),
        ]);

        if ($request->filled('status')) {
            $case->update(['status' => $request->status]);
        }

        \App\Models\AuditLog::record('intervention_added', 'Cases', $case->id);

        return back()->with('success', 'Intervensi berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, Cases $case)
    {
        $this->authorize('update', $case);

        $request->validate(['status' => 'required|in:belum_ditindaklanjuti,diproses,selesai']);

        $old = $case->status;
        $case->update([
            'status'    => $request->status,
            'closed_at' => $request->status === 'selesai' ? now() : null,
        ]);

        \App\Models\AuditLog::record('case_status_updated', 'Cases', $case->id, ['status' => $old], ['status' => $request->status]);

        return back()->with('success', 'Status kasus diperbarui.');
    }

    public function assign(Request $request, Cases $case)
    {
        $this->authorize('update', $case);
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $case->update(['assigned_to' => $request->assigned_to]);
        return back()->with('success', 'PIC berhasil diperbarui.');
    }
}
