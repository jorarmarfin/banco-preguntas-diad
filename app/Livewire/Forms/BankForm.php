<?php

namespace App\Livewire\Forms;

use App\Models\Bank;
use App\Traits\BankTrait;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BankForm extends Form
{
    use BankTrait;

    public ?Bank $bank;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:500')]
    public $description = '';

    #[Validate('boolean')]
    public $active = true;

    public $folder_slug = '';

    public function setBank(Bank $bank)
    {
        $this->bank = $bank;
        $this->name = $bank->name;
        $this->description = $bank->description;
        $this->active = $bank->active;
        $this->folder_slug = $bank->folder_slug ?? '';
    }

    public function store()
    {
        $this->validate();

        // Crear la carpeta del banco
        $folderSlug = $this->createBankFolder($this->name);

        $data = $this->only(['name', 'description', 'active']);
        $data['folder_slug'] = $folderSlug;

        $this->createBank($data);

        $this->reset();

        return true;
    }

    public function update()
    {
        $this->validate();

        // Actualizar la carpeta si el nombre cambió
        $newFolderSlug = $this->updateBankFolder($this->folder_slug, $this->name);

        $data = $this->only(['name', 'description', 'active']);
        $data['folder_slug'] = $newFolderSlug;

        $this->updateBank($this->bank, $data);

        return true;
    }

    public function reset(...$properties)
    {
        $this->name = '';
        $this->description = '';
        $this->active = true;
        $this->folder_slug = '';
        $this->bank = null;

        parent::reset(...$properties);
    }
}
