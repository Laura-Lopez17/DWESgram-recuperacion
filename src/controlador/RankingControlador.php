<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Ranking;
use dwesgram\modelo\RankingBd;
use dwesgram\modelo\UsuarioBd;
use dwesgram\modelo\EntradaBd;

class RankingControlador extends Controlador
{
    public function lista(): array
    {
        $topUsuarios = RankingBd::getTopTresUsuariosConMasPublicaciones();
        $ranking = [];
        if (!empty($topUsuarios)) {
            foreach ($topUsuarios as $usuario) {
                $usuarioId = $usuario['autor'];
                $num_publicaciones = $usuario['num_publicaciones'];
                $usuario = UsuarioBd::getUsuarioPorId($usuarioId);

                $estadistica = new Ranking(
                    usuario: $usuario,
                    numEntradas: $num_publicaciones,
                );
                array_push($ranking, $estadistica);

            }
                $this->vista = 'ranking/ranking';
                return $ranking;
        }

    }
}
