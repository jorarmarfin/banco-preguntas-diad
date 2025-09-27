<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Bank;
use App\Models\Question;
use App\Enums\QuestionStatus;

$banks = Bank::orderBy('id')->get();
if ($banks->isEmpty()) {
    echo "No banks found\n";
    exit(0);
}

foreach ($banks as $bank) {
    $drawn = Question::where('bank_id', $bank->id)->where('status', QuestionStatus::DRAWN->value)->count();
    $archived = Question::where('bank_id', $bank->id)->where('status', QuestionStatus::ARCHIVED->value)->count();
    echo "Bank {$bank->id} - {$bank->name}: drawn={$drawn}, archived={$archived}\n";
}

// Show a few sample questions that are archived or drawn
echo "\nSample questions (status, code, bank_id):\n";
$samples = Question::whereIn('status', [QuestionStatus::DRAWN->value, QuestionStatus::ARCHIVED->value])->take(20)->get();
foreach ($samples as $q) {
    echo "{$q->status} | {$q->code} | bank_id={$q->bank_id}\n";
}

