<?php

Route::middleware(['auth'])->group(function () {
    Route::get('/subject-categories', [\App\Http\Controllers\SubjectCategoriesController::class,'index'])
        ->name('subject.categories.index');

    Route::get('/subject', [\App\Http\Controllers\SubjectController::class,'index'])
        ->name('subject.index');

    Route::get('/subject-chapters/{id}', [\App\Http\Controllers\SubjectChaptersController::class,'index'])
        ->name('subject.chapters.index');

});

