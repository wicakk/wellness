<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $this->authorize('viewReports', User::class);
        $units = User::distinct()->pluck('unit');
        return view('reports.index', compact('units'));
    }

    public function generate(Request $request)
    {
        $this->authorize('viewReports', User::class);

        $request->validate([
            'type'      => 'required|in:unit,tren,individual',
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
            'format'    => 'required|in:pdf,excel',
            'unit'      => 'nullable|string',
        ]);

        $query = Screening::with('user')
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$request->date_from, $request->date_to]);

        if ($request->filled('unit')) {
            $query->whereHas('user', fn($q) => $q->where('unit', $request->unit));
        }

        $data = $query->get();

        if ($request->format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('data', 'request'));
            return $pdf->download('laporan-wellness-' . now()->format('Ymd') . '.pdf');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ScreeningExport($data),
            'laporan-wellness-' . now()->format('Ymd') . '.xlsx'
        );
    }
}
