<?php
// ── TAMBAHKAN INI KE DALAM BLOK middleware admin di routes/web.php ──────────
// Letakkan di dalam: Route::middleware('role:admin,wellness_warrior')->group(...)

// Questionnaire Management
Route::prefix('questionnaire')->name('questionnaire.')->group(function () {
    Route::get('/',                                    [QuestionnaireController::class, 'index'])->name('index');
    Route::get('/create',                              [QuestionnaireController::class, 'create'])->name('create');
    Route::post('/',                                   [QuestionnaireController::class, 'store'])->name('store');
    Route::get('/{questionnaire}',                     [QuestionnaireController::class, 'show'])->name('show');
    Route::get('/{questionnaire}/edit',                [QuestionnaireController::class, 'edit'])->name('edit');
    Route::put('/{questionnaire}',                     [QuestionnaireController::class, 'update'])->name('update');
    Route::delete('/{questionnaire}',                  [QuestionnaireController::class, 'destroy'])->name('destroy');
    Route::patch('/{questionnaire}/toggle',            [QuestionnaireController::class, 'toggleActive'])->name('toggle');

    // Question CRUD (nested)
    Route::post('/{questionnaire}/questions',                        [QuestionnaireController::class, 'storeQuestion'])->name('questions.store');
    Route::put('/{questionnaire}/questions/{question}',              [QuestionnaireController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/{questionnaire}/questions/{question}',           [QuestionnaireController::class, 'destroyQuestion'])->name('questions.destroy');
    Route::post('/{questionnaire}/questions/reorder',                [QuestionnaireController::class, 'reorderQuestions'])->name('questions.reorder');
    Route::post('/{questionnaire}/questions/{question}/duplicate',   [QuestionnaireController::class, 'duplicateQuestion'])->name('questions.duplicate');
});
