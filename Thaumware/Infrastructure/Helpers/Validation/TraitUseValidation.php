<?php

namespace Thaumware\Core\Helpers\Validation;

trait TraitUseValidation
{

    protected $validation = new ValidationContext();

    public function getValidacion(): ValidationContext
    {
        return $this->validation;
    }

    public function setValidacion(ValidationContext $validacion): void
    {
        $this->validacion = $validacion;
    }
}