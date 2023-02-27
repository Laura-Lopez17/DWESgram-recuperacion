<?php
namespace dwesgram\controlador;

use dwesgram\utilidades\Sesion;

abstract class Controlador
{
    protected string|null $vista = null;

    public function getVista(): string
    {
        if ($this->vista !== null) {
            return $this->vista;
        } else {
            return "errores/500";
        }
    }

    public function autenticado(): bool
    {
        $sesion = new Sesion();
        if (!$sesion->haySesion()) {
            $this->vista = 'errores/403';
            return true;
        }
        return false;
    }
}
