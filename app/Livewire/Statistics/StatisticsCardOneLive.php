<?php

namespace App\Livewire\Statistics;

use Livewire\Component;
use App\Traits\StatisticsTrait;

class StatisticsCardOneLive extends Component
{
    use StatisticsTrait;

    public string $title = 'Total preguntas';
    public string $subtitle = 'Todas las preguntas del banco activo';
    public string $metric = 'total'; // total|approved|review|draft|archived|drawn
    public ?int $bankId = null;

    public function mount(string $metric = 'total', ?string $title = null, ?string $subtitle = null, ?int $bankId = null): void
    {
        $this->metric = $metric;
        if ($title !== null) $this->title = $title;
        if ($subtitle !== null) $this->subtitle = $subtitle;
        $this->bankId = $bankId;
    }

    public function getValueProperty(): int
    {
        return match ($this->metric) {
            'approved' => $this->countApproved($this->bankId),
            'review' => $this->countReview($this->bankId),
            'draft' => $this->countDraft($this->bankId),
            'archived' => $this->countArchived($this->bankId),
            'drawn' => $this->countDrawn($this->bankId),
            default => $this->countTotal($this->bankId),
        };
    }

    public function render()
    {
        return view('livewire.statistics.statistics-card-one-live');
    }
}