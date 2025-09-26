<?php

namespace App\Enums;

enum QuestionDifficulty: string
{
    case EASY = 'easy';
    case MEDIUM = 'normal';
    case HARD = 'hard';

    /**
     * Get the label for the difficulty
     */
    public function label(): string
    {
        return match($this) {
            self::EASY => 'Fácil',
            self::MEDIUM => 'Medio',
            self::HARD => 'Difícil',
        };
    }

    /**
     * Get all difficulties as array for dropdowns
     */
    public static function toArray(): array
    {
        return [
            self::EASY->value => self::EASY->label(),
            self::MEDIUM->value => self::MEDIUM->label(),
            self::HARD->value => self::HARD->label(),
        ];
    }

    /**
     * Map string values to enum cases
     */
    public static function fromString(string $difficulty): self
    {
        $difficulties = [
            // Letras del CSV
            'f' => self::EASY,
            'F' => self::EASY,
            'd' => self::HARD,
            'D' => self::HARD,
            'n' => self::MEDIUM,
            'N' => self::MEDIUM,
            // Palabras completas
            'easy' => self::EASY,
            'facil' => self::EASY,
            'fácil' => self::EASY,
            'normal' => self::MEDIUM,
            'normal' => self::MEDIUM,
            'normal' => self::MEDIUM,
            'intermedio' => self::MEDIUM,
            'hard' => self::HARD,
            'dificil' => self::HARD,
            'difícil' => self::HARD
        ];

        return $difficulties[$difficulty] ?? self::MEDIUM;
    }
}
