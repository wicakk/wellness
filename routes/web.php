<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminDashboardController,
    PegawaiDashboardController,
    ScreeningController,
    CaseController,
    EducationController,
    ReportController,
    EmployeeController,
    ScheduleController,
    ProfileController,
    QuestionnaireController,
    RoomController,
};

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'theme'])->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role->name ?? null;
        return match (true) {
            in_array($role, ['admin', 'wellness_warrior', 'psikolog']) => redirect()->route('admin.dashboard'),
            $role === 'pegawai' => redirect()->route('pegawai.dashboard'),
            default => abort(403, 'Role tidak dikenali: ' . $role),
        };
    })->name('dashboard');

    // Pegawai
    Route::prefix('pegawai')->name('pegawai.')->middleware('role:pegawai')->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
    });

    // Admin / Wellness Warrior / Psikolog
    Route::prefix('admin')->name('admin.')->middleware('role:admin,wellness_warrior,psikolog')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:admin,wellness_warrior')->group(function () {

            // Employees
            Route::prefix('employees')->name('employees.')->group(function () {
                Route::get('/',                  [EmployeeController::class, 'index'])->name('index');
                Route::get('/create',            [EmployeeController::class, 'create'])->name('create');
                Route::post('/',                 [EmployeeController::class, 'store'])->name('store');
                Route::get('/{employee}',        [EmployeeController::class, 'show'])->name('show');
                Route::get('/{employee}/edit',   [EmployeeController::class, 'edit'])->name('edit');
                Route::put('/{employee}',        [EmployeeController::class, 'update'])->name('update');
                Route::patch('/{employee}',      [EmployeeController::class, 'update']);
                Route::delete('/{employee}',     [EmployeeController::class, 'destroy'])->name('destroy');
            });

            // Questionnaire
            Route::prefix('questionnaire')->name('questionnaire.')->group(function () {
                Route::get('/',                                                [QuestionnaireController::class, 'index'])->name('index');
                Route::get('/create',                                          [QuestionnaireController::class, 'create'])->name('create');
                Route::post('/',                                               [QuestionnaireController::class, 'store'])->name('store');
                Route::get('/{questionnaire}',                                 [QuestionnaireController::class, 'show'])->name('show');
                Route::get('/{questionnaire}/edit',                            [QuestionnaireController::class, 'edit'])->name('edit');
                Route::put('/{questionnaire}',                                 [QuestionnaireController::class, 'update'])->name('update');
                Route::delete('/{questionnaire}',                              [QuestionnaireController::class, 'destroy'])->name('destroy');
                Route::patch('/{questionnaire}/toggle',                        [QuestionnaireController::class, 'toggleActive'])->name('toggle');
                Route::post('/{questionnaire}/questions',                      [QuestionnaireController::class, 'storeQuestion'])->name('questions.store');
                Route::put('/{questionnaire}/questions/{question}',            [QuestionnaireController::class, 'updateQuestion'])->name('questions.update');
                Route::delete('/{questionnaire}/questions/{question}',         [QuestionnaireController::class, 'destroyQuestion'])->name('questions.destroy');
                Route::post('/{questionnaire}/questions/reorder',              [QuestionnaireController::class, 'reorderQuestions'])->name('questions.reorder');
                Route::post('/{questionnaire}/questions/{question}/duplicate', [QuestionnaireController::class, 'duplicateQuestion'])->name('questions.duplicate');
            });

            // Master Ruangan
            Route::prefix('rooms')->name('rooms.')->group(function () {
                Route::get('/',                [RoomController::class, 'index'])->name('index');
                Route::get('/create',          [RoomController::class, 'create'])->name('create');
                Route::post('/',               [RoomController::class, 'store'])->name('store');
                Route::get('/{room}',          [RoomController::class, 'show'])->name('show');
                Route::get('/{room}/edit',     [RoomController::class, 'edit'])->name('edit');
                Route::put('/{room}',          [RoomController::class, 'update'])->name('update');
                Route::patch('/{room}',        [RoomController::class, 'update']);
                Route::delete('/{room}',       [RoomController::class, 'destroy'])->name('destroy');
                Route::patch('/{room}/toggle', [RoomController::class, 'toggleActive'])->name('toggle');
            });
        });

        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

        Route::get('/audit-logs', function () {
            return view('admin.audit-logs', [
                'logs' => \App\Models\AuditLog::with('user')->latest()->paginate(50),
            ]);
        })->middleware('role:admin')->name('audit.logs');
    });

    // Skrining
    Route::prefix('screening')->name('screening.')->group(function () {
        Route::get('/',                    [ScreeningController::class, 'index'])->name('index');
        Route::get('/create',              [ScreeningController::class, 'create'])->name('create');
        Route::post('/submit/{screening}', [ScreeningController::class, 'store'])->name('store');
        Route::get('/{screening}/result',  [ScreeningController::class, 'result'])->name('result');
    });

    // Case Management
    Route::prefix('cases')->name('cases.')->middleware('role:admin,wellness_warrior,psikolog')->group(function () {
        Route::get('/',                      [CaseController::class, 'index'])->name('index');
        Route::get('/{case}',                [CaseController::class, 'show'])->name('show');
        Route::post('/{case}/interventions', [CaseController::class, 'addIntervention'])->name('interventions.add');
        Route::patch('/{case}/status',       [CaseController::class, 'updateStatus'])->name('status.update');
        Route::patch('/{case}/assign',       [CaseController::class, 'assign'])->name('assign');
    });

    // Edukasi
    Route::prefix('education')->name('education.')->group(function () {
        Route::get('/', [EducationController::class, 'index'])->name('index');

        Route::middleware('role:admin,wellness_warrior')->group(function () {
            Route::get('/create',         [EducationController::class, 'create'])->name('create');
            Route::post('/',              [EducationController::class, 'store'])->name('store');
            Route::get('/{content}/edit', [EducationController::class, 'edit'])->name('edit');
            Route::put('/{content}',      [EducationController::class, 'update'])->name('update');
            Route::delete('/{content}',   [EducationController::class, 'destroy'])->name('destroy');
        });

        Route::get('/{content}', [EducationController::class, 'show'])->name('show');
    });

    // Jadwal
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/',              [ScheduleController::class, 'index'])->name('index');
        Route::post('/',             [ScheduleController::class, 'store'])->middleware('role:admin,wellness_warrior,psikolog')->name('store');
        Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->middleware('role:admin,wellness_warrior')->name('destroy');
    });

    // Notifikasi
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', fn() => view('notifications.index', [
            'notifications' => auth()->user()->notifications()->latest()->paginate(20),
        ]))->name('index');

        Route::patch('/{id}/read', function ($id) {
            auth()->user()->notifications()->findOrFail($id)->update(['is_read' => true, 'read_at' => now()]);
            return back();
        })->name('read');

        Route::post('/read-all', function () {
            auth()->user()->notifications()->update(['is_read' => true, 'read_at' => now()]);
            return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
        })->name('read-all');
    });

    // Toggle Tema
    Route::post('/theme/toggle', function (\Illuminate\Http\Request $request) {
        $theme = $request->theme === 'dark' ? 'dark' : 'light';
        auth()->user()->update(['theme_preference' => $theme]);
        session(['theme' => $theme]);
        return response()->json(['theme' => $theme]);
    })->name('theme.toggle');

    // Profil
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});