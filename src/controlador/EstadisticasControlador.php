<?php
namespace dwesgram\controlador;

use dwesgram\modelo\Estadisticas;
use dwesgram\modelo\EstadisticasBd;
use dwesgram\modelo\EntradaBd;
use dwesgram\modelo\MegustaBd;
use dwesgram\modelo\UsuarioBd;

class EstadisticasControlador extends Controlador
{
    public function lista(): array
    {
        $topTres = [];
        $entradasId = EstadisticasBd::topTresMasMg();
        if (count($entradasId) > 0) {
            foreach ($entradasId as $entradaId) {
                $entrada = EntradaBd::getEntrada($entradaId);
                $numMeGusta = $entrada->getNumeroMegusta();
                $usuariosMeGusta = MegustaBd::getUsuariosId($entradaId);
                $usuarios = [];
                foreach ($usuariosMeGusta as $usuario) {
                    array_push($usuarios, UsuarioBd::getUsuarioPorId($usuario));
                }
                $estadisticas = new Estadisticas(
                    entrada: $entrada,
                    meGusta: $numMeGusta,
                    usuariosMeGusta: $usuarios
                );
                array_push($topTres, $estadisticas);
            }
            $this->vista = 'estadisticas/topTres';
            return $topTres;
        } else {
            $this->vista = 'estadisticas/topTres';
            return [];
        }
    }

}