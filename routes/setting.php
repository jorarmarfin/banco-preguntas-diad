<?php

Route::middleware(['auth'])->group(function () {
    Route::get('/setting', [\App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');
    Route::get('/term', [\App\Http\Controllers\TermController::class, 'index'])->name('term.index');
    Route::get('/banks', [\App\Http\Controllers\Banks\BankController::class, 'index'])->name('banks.index');
    Route::get('/professors', [\App\Http\Controllers\Professor\ProfessorController::class, 'index'])->name('professors.index');
});
