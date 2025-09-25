<?php

Route::middleware(['auth'])->group(function () {
    Route::get('/subject-categories', [\App\Http\Controllers\SubjectCategoriesController::class,'index'])
        ->name('subject.categories.index');

    Route::get('/subject', [\App\Http\Controllers\SubjectController::class,'index'])
        ->name('subject.index');

    Route::get('/subject-chapters/{id}', [\App\Http\Controllers\SubjectChaptersController::class,'index'])
        ->name('subject.chapters.index');

    Route::get('/subject-topics/{id}', [\App\Http\Controllers\SubjectTopicsController::class,'index'])
        ->name('subject.topics.index');

    Route::get('/subject-questions/{id}', [\App\Http\Controllers\SubjectQuestionController::class,'index'])
        ->name('subject.questions.index');

    Route::get('/import-questions', [\App\Http\Controllers\ImportQuestionsController::class,'index'])
        ->name('import.questions.index');

    Route::get('/questions', [\App\Http\Controllers\Banks\QuestionsController::class,'index'])
        ->name('questions.index');

});

