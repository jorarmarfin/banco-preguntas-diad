<?php

namespace App\Livewire\Forms;

use App\Models\Setting;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SettingForm extends Form
{
    public ?Setting $setting;

    #[Validate('required|string|max:255')]
    public $key = '';

    #[Validate('required|string|max:255')]
    public $value = '';

    public array $fields = ['key', 'value'];
    public function setSetting(Setting $setting)
    {
        $this->setting = $setting;
        $this->key = $setting->key;
        $this->value = $setting->value;
    }

    public function store()
    {
        $this->validate();

        // Check if key already exists
        if (Setting::where('key', $this->key)->exists()) {
            $this->addError('key', 'Esta clave ya existe.');
            return false;
        }

        Setting::create($this->only(...$this->fields));

        $this->reset();
        return true;
    }

    public function update()
    {
        $this->validate();

        // Check if key already exists (excluding current record)
        $exists = Setting::where('key', $this->key)
            ->where('id', '!=', $this->setting->id)
            ->exists();

        if ($exists) {
            $this->addError('key', 'Esta clave ya existe.');
            return false;
        }

        $this->setting->update($this->only(...$this->fields));

        $this->reset();
        return true;
    }

    public function reset(...$properties)
    {
        parent::reset(...$properties);
        $this->setting = null;
    }
}
