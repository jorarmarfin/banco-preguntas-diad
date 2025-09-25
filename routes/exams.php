<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Exams\ExamLive;
use App\Livewire\Exams\ExamQuestionsLive;

Route::middleware(['auth'])->prefix('exams')->name('exams.')->group(function () {
    Route::get('/', ExamLive::class)->name('index');
    Route::get('/{examId}/questions', ExamQuestionsLive::class)->name('questions');
});
