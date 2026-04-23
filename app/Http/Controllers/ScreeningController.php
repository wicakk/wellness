<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Notification;
use App\Models\Questionnaire;
use App\Models\Screening;
use App\Models\ScreeningAnswer;
use App\Models\RiskThreshold;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreeningController extends Controller
{
    public function index()
    {
        $screenings = Auth::user()->screenings()->with('questionnaire')->latest()->paginate(15);
        return view('screening.index', compact('screenings'));
    }

    public function create()
    {
        $questionnaire = Questionnaire::where('is_active', true)
            ->with('questions')
            ->first();

        if (!$questionnaire) {
            return redirect()->route('screening.index')
                ->with('error', 'Belum ada kuesioner skrining yang aktif. Hubungi admin.');
        }

        if ($questionnaire->questions->isEmpty()) {
            return redirect()->route('screening.index')
                ->with('error', 'Kuesioner aktif belum memiliki soal. Hubungi admin untuk menambahkan soal.');
        }

        $existing = Screening::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->where('questionnaire_id', $questionnaire->id)
            ->first();

        if (!$existing) {
            $existing = Screening::create([
                'user_id'          => Auth::id(),
                'questionnaire_id' => $questionnaire->id,
                'status'           => 'draft',
            ]);
        }

        return view('screening.create', [
            'questionnaire' => $questionnaire,
            'screening'     => $existing,
        ]);
    }

    public function store(Request $request, Screening $screening)
    {
        $this->authorize('update', $screening);

        $request->validate([
            'answers'                   => 'required|array',
            'answers.*.question_id'     => 'required|exists:questions,id',
            'answers.*.score'           => 'nullable|integer|min:0|max:10',
            'open_notes'                => 'nullable|string|max:1000',
        ]);

        foreach ($request->answers as $answer) {
            ScreeningAnswer::updateOrCreate(
                ['screening_id' => $screening->id, 'question_id' => $answer['question_id']],
                ['score' => $answer['score'] ?? null, 'answer_text' => $answer['answer_text'] ?? null]
            );
        }

        $totalScore = $screening->answers()->sum('score');
        $riskLevel  = RiskThreshold::classify($totalScore, $screening->questionnaire_id);

        $screening->update([
            'total_score'  => $totalScore,
            'risk_level'   => $riskLevel,
            'open_notes'   => $request->open_notes,
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        if (in_array($riskLevel, ['sedang', 'tinggi'])) {
            Cases::firstOrCreate(
                ['screening_id' => $screening->id],
                [
                    'user_id'  => Auth::id(),
                    'priority' => $riskLevel === 'tinggi' ? 'kritis' : 'sedang',
                    'status'   => 'belum_ditindaklanjuti',
                ]
            );

            $admins = User::whereHas('role', fn($q) => $q->whereIn('name', ['admin', 'wellness_warrior']))->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type'    => 'risiko_tinggi',
                    'title'   => 'Kasus Baru: Risiko ' . ucfirst($riskLevel),
                    'message' => Auth::user()->name . ' memiliki hasil skrining risiko ' . $riskLevel,
                    'data'    => ['screening_id' => $screening->id, 'user_id' => Auth::id()],
                ]);
            }
        }

        \App\Models\AuditLog::record('screening_completed', 'Screening', $screening->id);

        return redirect()->route('screening.result', $screening)->with('success', 'Skrining berhasil disimpan!');
    }

    public function result(Screening $screening)
    {
        $this->authorize('view', $screening);
        $screening->load(['questionnaire', 'answers.question']);
        return view('screening.result', compact('screening'));
    }
}
