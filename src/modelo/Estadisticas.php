<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Entrada;

class Estadisticas 
{
    public function __construct(
        private Entrada $entrada,
        private array $usuariosMeGusta,
        private int $meGusta
    )
    {}

    public function getEntrada(): Entrada
    {
        return $this->entrada;
    }

    public function getNumMeGusta(): int
    {
        return $this->meGusta;
    }

    public function getUsuariosMeGusta(): array
    {
        return $this->usuariosMeGusta;
    }

    public function setEntrada(Entrada $entrada): void
    {
        $this->entrada = $entrada;
    }

    public function setNumMeGusta(int $numMeGusta): void
    {
        $this->meGusta = $numMeGusta;
    }

    public function setUsuariosMeGusta(array $usuariosMeGusta): void
    {
        $this->usuariosMeGusta = $usuariosMeGusta;
    }
}