<?php

namespace App\Livewire\Statistics;

use Livewire\Component;
use App\Traits\DdlTrait;
use App\Traits\StatisticsTrait;

class DistributionBySubjectLive extends Component
{
    use DdlTrait;
    use StatisticsTrait;

    public ?int $subjectId = null;
    public array $subjects = [];
    public array $difficultyCounts = ['easy' => 0, 'normal' => 0, 'hard' => 0];
    public int $approvedCount = 0;
    public int $totalCount = 0;

    public function mount(): void
    {
        // Dropdown de asignaturas (id => name)
        $this->subjects = $this->DdlSubjects()->toArray();
    }

    public function updatedSubjectId($value): void
    {
        $this->recalculate();
    }

    protected function recalculate(): void
    {
        if (!$this->subjectId) {
            $this->difficultyCounts = ['easy' => 0, 'normal' => 0, 'hard' => 0];
            $this->approvedCount = 0;
            $this->totalCount = 0;
            return;
        }
        $bankId = $this->getActiveBankId();
        $this->difficultyCounts = $this->countsByDifficultyForSubject($this->subjectId, $bankId);
        $this->approvedCount = $this->countApprovedForSubject($this->subjectId, $bankId);
        $this->totalCount = $this->countTotalForSubject($this->subjectId, $bankId);
    }

    public function render()
    {
        return view('livewire.statistics.distribution-by-subject-live');
    }
}
