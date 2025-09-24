<?php

namespace App\Traits;

use App\Models\Term;

trait ActiveTermTrait
{
    /**
     * Obtener el período activo
     */
    public function getActiveTerm()
    {
        return Term::where('is_active', true)->first();
    }

    /**
     * Obtener el ID del período activo
     */
    public function getActiveTermId()
    {
        $activeTerm = $this->getActiveTerm();
        return $activeTerm ? $activeTerm->id : null;
    }

    /**
     * Verificar si existe un período activo
     */
    public function hasActiveTerm()
    {
        return $this->getActiveTerm() !== null;
    }
}
