<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Question;
use App\Models\Bank;

echo "Distinct statuses and counts:\n";
$statuses = Question::selectRaw('status, COUNT(*) as cnt')->groupBy('status')->orderBy('cnt', 'desc')->get();
foreach ($statuses as $s) {
    echo "{$s->status}: {$s->cnt}\n";
}

echo "\nCounts by bank and status:\n";
$rows = Question::selectRaw('bank_id, status, COUNT(*) as cnt')->groupBy('bank_id','status')->orderBy('bank_id')->get();
foreach ($rows as $r) {
    $bank = Bank::find($r->bank_id);
    $bankName = $bank ? "{$bank->id}-{$bank->name}" : "bank_id={$r->bank_id}";
    echo "{$bankName} | {$r->status}: {$r->cnt}\n";
}

echo "\nSample questions with non-standard statuses:\n";
$non = Question::whereNotIn('status', ['draft','approved','drawn','archived'])->take(20)->get();
foreach ($non as $q) {
    echo "{$q->id} | {$q->code} | status={$q->status} | bank_id={$q->bank_id}\n";
}

