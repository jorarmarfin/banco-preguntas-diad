<?php

namespace App\Traits;

use App\Enums\QuestionStatus;
use App\Models\Bank;
use App\Models\Question;

trait StatisticsTrait
{
    protected function getActiveBankId(): ?int
    {
        $bank = Bank::where('active', true)->first();
        return $bank?->id;
    }

    public function countTotal(?int $bankId = null): int
    {
        $bankId = $bankId ?? $this->getActiveBankId();
        if (!$bankId) {
            return 0;
        }
        return Question::where('bank_id', $bankId)->count();
    }

    public function countByStatus(QuestionStatus $status, ?int $bankId = null): int
    {
        $bankId = $bankId ?? $this->getActiveBankId();
        if (!$bankId) {
            return 0;
        }
        return Question::where('bank_id', $bankId)
            ->where('status', $status->value)
            ->count();
    }

    public function countApproved(?int $bankId = null): int
    {
        return $this->countByStatus(QuestionStatus::APPROVED, $bankId);
    }

    public function countReview(?int $bankId = null): int
    {
        return $this->countByStatus(QuestionStatus::REVIEW, $bankId);
    }

    public function countDraft(?int $bankId = null): int
    {
        return $this->countByStatus(QuestionStatus::DRAFT, $bankId);
    }

    public function countArchived(?int $bankId = null): int
    {
        return $this->countByStatus(QuestionStatus::ARCHIVED, $bankId);
    }

    public function countDrawn(?int $bankId = null): int
    {
        return $this->countByStatus(QuestionStatus::DRAWN, $bankId);
    }

    public function countsByDifficulty(?int $bankId = null): array
    {
        $bankId = $bankId ?? $this->getActiveBankId();
        if (!$bankId) {
            return ['easy' => 0, 'normal' => 0, 'hard' => 0];
        }
        return Question::where('bank_id', $bankId)
            ->selectRaw("difficulty, COUNT(*) as total")
            ->groupBy('difficulty')
            ->pluck('total', 'difficulty')
            ->all() + ['easy' => 0, 'normal' => 0, 'hard' => 0];
    }

    // --- Subject-scoped statistics ---
    public function countTotalForSubject(int $subjectId, ?int $bankId = null): int
    {
        $bankId = $bankId ?? $this->getActiveBankId();
        if (!$bankId) {
            return 0;
        }
        return Question::where('bank_id', $bankId)
            ->where('subject_id', $subjectId)
            ->count();
    }

    public function countApprovedForSubject(int $subjectId, ?int $bankId = null): int
    {
        $bankId = $bankId ?? $this->getActiveBankId();
        if (!$bankId) {
            return 0;
        }
        return Question::where('bank_id', $bankId)
            ->where('subject_id', $subjectId)
            ->where('status', QuestionStatus::APPROVED->value)
            ->count();
    }

    public function countsByDifficultyForSubject(int $subjectId, ?int $bankId = null): array
    {
        $bankId = $bankId ?? $this->getActiveBankId();
        if (!$bankId) {
            return ['easy' => 0, 'normal' => 0, 'hard' => 0];
        }
        $result = Question::where('bank_id', $bankId)
            ->where('subject_id', $subjectId)
            ->selectRaw('difficulty, COUNT(*) as total')
            ->groupBy('difficulty')
            ->pluck('total', 'difficulty')
            ->all();

        return array_merge(['easy' => 0, 'normal' => 0, 'hard' => 0], $result);
    }
}


