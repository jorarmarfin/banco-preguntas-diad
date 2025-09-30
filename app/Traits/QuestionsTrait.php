<?php

namespace App\Traits;

use App\Models\Question;
use App\Models\Bank;
use App\Enums\QuestionStatus;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

trait QuestionsTrait
{
    /**
     * Retorna la cantidad de preguntas con estado 'sorteada' en un banco
     */
    public function drawCount(int $bankId): int
    {
        return Question::where('bank_id', $bankId)
            ->where('status', QuestionStatus::DRAWN->value)
            ->count();
    }

    /**
     * Preparar datos para la confirmación de archivado
     */
    public function prepareArchiveConfirmation(int $bankId): array
    {
        $bank = Bank::find($bankId);
        if (!$bank) {
            throw new Exception('Banco no encontrado');
        }

        // Antes llamaba a archivedCount() (no definida). Usamos drawCount() para
        // devolver la cantidad de preguntas con estado 'Sorteada'.
        $count = $this->drawCount($bankId);

        return [
            'bank' => $bank,
            'count' => $count,
        ];
    }

    /**
     * Archivar preguntas de un banco: mover archivos y actualizar path en BD
     * Retorna array con moved, skipped, errors
     */
    public function archiveQuestionsForBank(int $bankId): array
    {
        $bank = Bank::find($bankId);
        if (!$bank) {
            throw new Exception('Banco no encontrado');
        }

        $banksBasePath = Setting::where('key', 'path_banks')->value('value') ?? 'private/banks';
        $archivedBasePath = Setting::where('key', 'path_archived')->value('value') ?? 'private/archived';

        $questions = Question::with(['bank', 'subject'])
            ->where('bank_id', $bank->id)
            ->where('status', QuestionStatus::DRAWN->value)
            ->get();

        $moved = 0;
        $skipped = 0;
        $errors = [];

        foreach ($questions as $question) {
            $srcPath = $question->path;
            try {
                if (!$srcPath || !Storage::exists($srcPath)) {
                    $errors[] = "Código {$question->code}: carpeta origen no encontrada ({$srcPath})";
                    $skipped++;
                    continue;
                }

                $subjectName = $question->subject?->name ?? 'subject-' . $question->subject_id;
                $subjectSlug = Str::slug($subjectName);
                $destPath = trim("{$archivedBasePath}/{$bank->folder_slug}/{$subjectSlug}/{$question->code}", '/');

                if (!Storage::exists($destPath)) {
                    Storage::makeDirectory($destPath);
                }

                $files = Storage::allFiles($srcPath);
                if (empty($files)) {
                    $errors[] = "Código {$question->code}: no hay archivos en origen ({$srcPath})";
                    $skipped++;
                    continue;
                }

                foreach ($files as $file) {
                    $fileName = basename($file);
                    $destFile = "{$destPath}/{$fileName}";
                    if (Storage::exists($destFile)) {
                        $errors[] = "Código {$question->code}: archivo ya existe en destino ({$destFile})";
                        continue;
                    }

                    if (!Storage::move($file, $destFile)) {
                        $errors[] = "Código {$question->code}: error moviendo {$file} -> {$destFile}";
                    }
                }

                // intentar eliminar si vacío
                try {
                    $remaining = Storage::allFiles($srcPath);
                    if (empty($remaining)) {
                        Storage::deleteDirectory($srcPath);
                    }
                } catch (Exception $e) {
                    Log::warning('archiveQuestionsForBank: no se pudo eliminar directorio origen', ['src' => $srcPath, 'exception' => $e->getMessage()]);
                }

                $question->path = $destPath;
                $question->save();

                $moved++;

            } catch (Exception $e) {
                $errors[] = "Código {$question->code}: excepción - " . $e->getMessage();
                Log::error('archiveQuestionsForBank error', ['question' => $question->id, 'exception' => $e->getMessage()]);
            }
        }

        return [
            'moved' => $moved,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }
}
