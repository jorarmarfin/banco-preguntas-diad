<?php

Route::middleware(['auth'])->group(function () {
    Route::get('/subject', [\App\Http\Controllers\SubjectCategoriesController::class,'index'])
        ->name('subject.categories.index');

});

