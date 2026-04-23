<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Notification;
use App\Models\Screening;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $riskDistribution = Screening::where('status', 'completed')
            ->selectRaw('risk_level, count(*) as total')
            ->groupBy('risk_level')
            ->pluck('total', 'risk_level');

        $totalScreeningThisMonth = Screening::where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->count();

        $priorityCases = Cases::with(['user', 'screening', 'assignedTo'])
            ->whereIn('status', ['belum_ditindaklanjuti', 'diproses'])
            ->whereHas('screening', fn($q) => $q->whereIn('risk_level', ['sedang', 'tinggi']))
            ->orderByRaw("FIELD(status, 'belum_ditindaklanjuti', 'diproses')")
            ->latest()
            ->take(10)
            ->get();

        $screeningTrend = Screening::where('status', 'completed')
            ->where('completed_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(completed_at, '%Y-%m') as month, risk_level, count(*) as total")
            ->groupBy('month', 'risk_level')
            ->orderBy('month')
            ->get();

        $unitStats = User::where('is_active', true)
            ->selectRaw('unit, count(*) as total_pegawai')
            ->groupBy('unit')
            ->get()
            ->map(function ($unit) {
                $unit->screened = Screening::whereHas('user', fn($q) => $q->where('unit', $unit->unit))
                    ->where('status', 'completed')
                    ->whereMonth('completed_at', now()->month)
                    ->count();
                return $unit;
            });

        $adminNotifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'riskDistribution', 'totalScreeningThisMonth', 'priorityCases',
            'screeningTrend', 'unitStats', 'adminNotifications'
        ));
    }
}
