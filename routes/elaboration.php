<?php

Route::middleware(['auth'])->group(function () {

    Route::get('/professors', [\App\Http\Controllers\Professor\ProfessorController::class, 'index'])->name('professors.index');
    Route::get('/proposed', [\App\Http\Controllers\Banks\QuestionProposedController::class, 'index'])->name('proposed.index');

});

