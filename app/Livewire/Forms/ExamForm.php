<?php

namespace App\Livewire\Forms;

use App\Models\Exam;
use App\Traits\ExamTrait;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ExamForm extends Form
{
    use ExamTrait;

    public ?Exam $exam;

    #[Validate('required|string|max:50|unique:exams,code')]
    public $code = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    public $term_id = '';

    #[Validate('nullable|string|max:500')]
    public $path = '';

    public function setExam(Exam $exam)
    {
        $this->exam = $exam;
        $this->code = $exam->code;
        $this->name = $exam->name;
        $this->term_id = $exam->term_id;
        $this->path = $exam->path ?? '';
    }

    public function store()
    {
        // Generar código automáticamente si no se proporciona
        if (empty($this->code)) {
            $this->code = $this->generateExamCode();
        }

        // Obtener el ID del término activo automáticamente
        $activeTermId = $this->getActiveTermId();
        if (!$activeTermId) {
            throw new \Exception('No hay un período activo configurado.');
        }

        $this->term_id = $activeTermId;

        // Generar el path automáticamente basado en el período activo y código
        $this->path = $this->generateExamPath($this->code);

        $this->validate();

        $data = $this->only(['code', 'name', 'term_id', 'path']);

        $this->createExam($data);

        $this->reset();

        return true;
    }

    public function update()
    {
        // Obtener el ID del término activo automáticamente
        $activeTermId = $this->getActiveTermId();
        if (!$activeTermId) {
            throw new \Exception('No hay un período activo configurado.');
        }

        $this->term_id = $activeTermId;

        // Regenerar el path automáticamente basado en el período activo y código actual
        $this->path = $this->generateExamPath($this->code);

        // Actualizar las reglas de validación para excluir el examen actual
        $this->validate([
            'code' => 'required|string|max:50|unique:exams,code,' . $this->exam->id,
            'name' => 'required|string|max:255',
            'path' => 'nullable|string|max:500',
        ]);

        $data = $this->only(['code', 'name', 'term_id', 'path']);

        $this->updateExam($this->exam, $data);

        return true;
    }

    public function reset(...$properties)
    {
        $this->code = '';
        $this->name = '';
        $this->term_id = '';
        $this->path = '';
        $this->exam = null;

        parent::reset(...$properties);
    }
}
