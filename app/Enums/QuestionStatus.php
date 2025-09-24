<?php

namespace App\Enums;

enum QuestionStatus: string
{
    case DRAFT = 'draft';
    case APPROVED = 'approved';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Borrador',
            self::APPROVED => 'Aprobada',
            self::ARCHIVED => 'Archivada',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'bg-gray-100 text-gray-800',
            self::APPROVED => 'bg-green-100 text-green-800',
            self::ARCHIVED => 'bg-red-100 text-red-800',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($status) {
            return [$status->value => $status->label()];
        })->toArray();
    }
}
