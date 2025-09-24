<?php

Route::middleware(['auth'])->group(function () {
    Route::get('/setting', [\App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');

    Route::get('/term', [\App\Http\Controllers\TermController::class, 'index'])->name('term.index');


});
