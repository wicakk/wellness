<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\RiskThreshold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    // ── QUESTIONNAIRE CRUD ─────────────────────────────────────────────────

    public function index()
    {
        $questionnaires = Questionnaire::withCount('questions')
            ->latest()
            ->paginate(10);

        return view('questionnaire.index', compact('questionnaires'));
    }

    public function create()
    {
        return view('questionnaire.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                    => 'required|string|max:255|unique:questionnaires,name',
            'description'             => 'nullable|string|max:1000',
            'is_active'               => 'boolean',

            // Threshold dinamis: minimal 1, tidak ada batasan jumlah
            'thresholds'              => 'required|array|min:1',
            'thresholds.*.label'      => 'required|string|max:100',
            'thresholds.*.level'      => 'required|string|max:100',
            'thresholds.*.color'      => 'nullable|string|max:20',
            'thresholds.*.score_min'  => 'required|integer|min:0',
            'thresholds.*.score_max'  => 'required|integer|min:0',
            'thresholds.*.description'=> 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $questionnaire = Questionnaire::create([
                'name'        => $request->name,
                'description' => $request->description,
                'is_active'   => $request->boolean('is_active', true),
            ]);

            foreach ($request->thresholds as $t) {
                RiskThreshold::create([
                    'questionnaire_id' => $questionnaire->id,
                    'level'            => $t['level'],
                    'label'            => $t['label'],
                    'score_min'        => $t['score_min'],
                    'score_max'        => $t['score_max'],
                    'color_code'       => $t['color'] ?? '#64748b',
                    'description'      => $t['description'] ?? null,
                ]);
            }

            \App\Models\AuditLog::record('questionnaire_created', 'Questionnaire', $questionnaire->id);
        });

        return redirect()->route('admin.questionnaire.index')
            ->with('success', 'Kuesioner berhasil dibuat! Sekarang tambahkan soal-soalnya.');
    }

    public function show(Questionnaire $questionnaire)
    {
        $questionnaire->load(['questions' => fn($q) => $q->orderBy('order'), 'riskThresholds']);
        $screeningCount = $questionnaire->screenings()->where('status', 'completed')->count();

        return view('questionnaire.show', compact('questionnaire', 'screeningCount'));
    }

    public function edit(Questionnaire $questionnaire)
    {
        $questionnaire->load('riskThresholds');
        return view('questionnaire.edit', compact('questionnaire'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $request->validate([
            'name'                    => 'required|string|max:255|unique:questionnaires,name,' . $questionnaire->id,
            'description'             => 'nullable|string|max:1000',
            'is_active'               => 'boolean',

            // Threshold dinamis
            'thresholds'              => 'required|array|min:1',
            'thresholds.*.label'      => 'required|string|max:100',
            'thresholds.*.level'      => 'required|string|max:100',
            'thresholds.*.color'      => 'nullable|string|max:20',
            'thresholds.*.score_min'  => 'required|integer|min:0',
            'thresholds.*.score_max'  => 'required|integer|min:0',
            'thresholds.*.description'=> 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $questionnaire) {
            $questionnaire->update([
                'name'        => $request->name,
                'description' => $request->description,
                'is_active'   => $request->boolean('is_active'),
            ]);

            // Hapus semua threshold lama, ganti dengan yang baru
            $questionnaire->riskThresholds()->delete();

            foreach ($request->thresholds as $t) {
                RiskThreshold::create([
                    'questionnaire_id' => $questionnaire->id,
                    'level'            => $t['level'],
                    'label'            => $t['label'],
                    'score_min'        => $t['score_min'],
                    'score_max'        => $t['score_max'],
                    'color_code'       => $t['color'] ?? '#64748b',
                    'description'      => $t['description'] ?? null,
                ]);
            }

            \App\Models\AuditLog::record('questionnaire_updated', 'Questionnaire', $questionnaire->id);
        });

        return redirect()->route('admin.questionnaire.show', $questionnaire)
            ->with('success', 'Kuesioner berhasil diperbarui.');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        if ($questionnaire->screenings()->exists()) {
            return back()->with('error', 'Kuesioner tidak bisa dihapus karena sudah memiliki data skrining.');
        }

        $questionnaire->questions()->delete();
        $questionnaire->riskThresholds()->delete();
        $questionnaire->delete();

        \App\Models\AuditLog::record('questionnaire_deleted', 'Questionnaire', $questionnaire->id);

        return redirect()->route('admin.questionnaire.index')
            ->with('success', 'Kuesioner berhasil dihapus.');
    }

    public function toggleActive(Questionnaire $questionnaire)
    {
        $questionnaire->update(['is_active' => !$questionnaire->is_active]);
        $status = $questionnaire->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kuesioner berhasil {$status}.");
    }

    // ── QUESTION CRUD ──────────────────────────────────────────────────────

    public function storeQuestion(Request $request, Questionnaire $questionnaire)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'type'          => 'required|in:scale,boolean,open_text',
            'max_score'     => 'required_unless:type,open_text|integer|min:1|max:10',
            'options'       => 'required_unless:type,open_text|array|min:2',
            'options.*'     => 'required|string|max:100',
        ]);

        $nextOrder = $questionnaire->questions()->max('order') + 1;

        $options = null;
        if ($request->type === 'boolean') {
            // Boolean tetap 0/1
            $options  = ['0' => 'Tidak', '1' => 'Ya'];
            $maxScore = 1;
        } elseif ($request->type !== 'open_text') {
            // FIX: key dimulai dari 1 agar nilai yang dikirim sesuai dengan max_score
            $options  = collect($request->options)
                ->values()
                ->mapWithKeys(fn($label, $i) => [$i + 1 => $label])
                ->all();
            $maxScore = (int) $request->max_score;
        } else {
            $maxScore = 0;
        }

        $question = $questionnaire->questions()->create([
            'order'         => $nextOrder,
            'question_text' => $request->question_text,
            'type'          => $request->type,
            'options'       => $options,
            'max_score'     => $maxScore,
        ]);

        \App\Models\AuditLog::record('question_created', 'Question', $question->id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'question' => $question->load('questionnaire')]);
        }

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, Questionnaire $questionnaire, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'type'          => 'required|in:scale,boolean,open_text',
            'max_score'     => 'required_unless:type,open_text|integer|min:1|max:10',
            'options'       => 'required_unless:type,open_text|array|min:2',
            'options.*'     => 'required|string|max:100',
        ]);

        $options  = null;
        $maxScore = 0;

        if ($request->type === 'boolean') {
            // Boolean tetap 0/1
            $options  = ['0' => 'Tidak', '1' => 'Ya'];
            $maxScore = 1;
        } elseif ($request->type !== 'open_text') {
            // FIX: key dimulai dari 1 agar nilai yang dikirim sesuai dengan max_score
            $options  = collect($request->options)
                ->values()
                ->mapWithKeys(fn($label, $i) => [$i + 1 => $label])
                ->all();
            $maxScore = (int) $request->max_score;
        }

        $question->update([
            'question_text' => $request->question_text,
            'type'          => $request->type,
            'options'       => $options,
            'max_score'     => $maxScore,
        ]);

        \App\Models\AuditLog::record('question_updated', 'Question', $question->id);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'question' => $question->fresh()]);
        }

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroyQuestion(Questionnaire $questionnaire, Question $question)
    {
        if ($question->answers()->exists()) {
            return back()->with('error', 'Soal tidak bisa dihapus karena sudah ada jawaban.');
        }

        $question->delete();

        $questionnaire->questions()->orderBy('order')->each(function ($q, $i) {
            $q->update(['order' => $i + 1]);
        });

        \App\Models\AuditLog::record('question_deleted', 'Question', $question->id);

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function reorderQuestions(Request $request, Questionnaire $questionnaire)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:questions,id',
        ]);

        foreach ($request->order as $position => $questionId) {
            Question::where('id', $questionId)
                ->where('questionnaire_id', $questionnaire->id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function duplicateQuestion(Questionnaire $questionnaire, Question $question)
    {
        $newOrder = $questionnaire->questions()->max('order') + 1;

        $newQuestion = $question->replicate();
        $newQuestion->order         = $newOrder;
        $newQuestion->question_text = $question->question_text . ' (Salinan)';
        $newQuestion->save();

        return back()->with('success', 'Soal berhasil diduplikasi.');
    }
}