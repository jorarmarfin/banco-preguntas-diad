<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Livewire\Banks\QuestionsLive;
use App\Models\Bank;

$component = new QuestionsLive();

$banks = Bank::orderBy('id')->get();
if ($banks->isEmpty()){
    echo "No banks\n";
    exit;
}

foreach ($banks as $bank) {
    $component->selectedBank = (string)$bank->id;
    try {
        $count = $component->getArchivedCountProperty();
        echo "Bank {$bank->id} ({$bank->name}) -> archivedCount={$count}\n";
        $data = $component->prepareArchiveConfirmation((int)$bank->id);
        echo "  prepareArchiveConfirmation -> count={$data['count']}\n";
    } catch (Exception $e) {
        echo "Exception for bank {$bank->id}: " . $e->getMessage() . "\n";
    }
}

