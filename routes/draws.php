<?php

Route::middleware(['auth'])->group(function () {
    Route::get('/exams', [\App\Http\Controllers\Exams\ExamController::class,'index'])
        ->name('exams.index');


});

