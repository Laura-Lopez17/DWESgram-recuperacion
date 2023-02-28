<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Megusta;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\MegustaBd;
use dwesgram\utilidades\Sesion;

class MegustaControlador extends Controlador
{
    public function megusta(): array|Entrada
    {
        $handle = $this->handleMegusta();
        $this->vista = $handle['vista'];
        return $handle['data'];
    }

    private function handleMegusta(): array //darMgusta
    {
        if (!$this->comprobacion()) {
            return [
                'vista' => 'entrada/lista',
                'data' => EntradaBd::getEntradas(),
            ];
        }

        $megusta = Megusta::newMegusta($_GET);

        if (!$megusta->esValido()) {
            return [
                'vista' => 'entrada/lista',
                'data' => EntradaBd::getEntradas(),
            ];
        }

        $entrada = EntradaBd::getEntrada($megusta->getEntrada());
        $sesion = new Sesion();

        if ($entrada->getUsuario()->getId() != $sesion->getId()) {
            $id = MegustaBd::insertar($megusta);

            if ($id !== null) {
                $megusta->setId($id);
            }

            $volver = $_GET && isset($_GET['volver']) ? htmlspecialchars($_GET['volver']) : 'lista';

            switch ($volver) {
                case 'detalle':
                    return [
                        'vista' => 'entrada/detalle',
                        'data' => EntradaBd::getEntrada($megusta->getEntrada()),
                    ];
                default:
                    return [
                        'vista' => 'entrada/lista',
                        'data' => EntradaBd::getEntradas(),
                    ];
            }
        }
        
        return [
            'vista' => 'entrada/lista',
            'data' => EntradaBd::getEntradas(),
        ];
    }

    private function comprobacion(): bool
    {
        if ($this->autenticado()) {
            return false;
        }

        $usuarioId = $_GET && isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : null;
        $entrada = EntradaBd::getEntrada($_GET && isset($_GET['entrada']) ? htmlspecialchars($_GET['entrada']) : null);

        return $usuarioId !== null && $entrada !== null && $usuarioId !== $entrada->getUsuario()->getId();
    }
}
