<?php

namespace App\Http\Controllers;

use App\Models\EducationContent;
use App\Models\Notification;
use App\Models\Screening;
use Illuminate\Support\Facades\Auth;

class PegawaiDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $latestScreening  = $user->screenings()->where('status', 'completed')->latest('completed_at')->first();
        $screeningHistory = $user->screenings()->where('status', 'completed')->latest('completed_at')->take(10)->get();
        $notifications    = $user->notifications()->where('is_read', false)->latest()->take(5)->get();
        $educations       = EducationContent::where('is_published', true)->latest()->take(4)->get();

        $chartData = $user->screenings()
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subMonths(6))
            ->orderBy('completed_at')
            ->get(['total_score', 'risk_level', 'completed_at']);

        return view('dashboard.pegawai', compact(
            'user', 'latestScreening', 'screeningHistory', 'notifications', 'educations', 'chartData'
        ));
    }
}
