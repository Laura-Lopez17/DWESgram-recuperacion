<?php
namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;

class RankingBd
{
    use BaseDatos; 

    public static function getTopTresUsuariosConMasPublicaciones(): array
    {
        try {
            $conexion = BaseDatos::getConexion();
            $query = <<<END
            select autor, count(*) AS num_publicaciones
            from entrada
            group by autor
            order by num_publicaciones desc
            limit 3
            END;
            $sentencia = $conexion->prepare($query);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $usuarios = [];
            while (($fila = $resultado->fetch_assoc()) !== null) {
                $usuarios[] = [
                    'autor' => $fila['autor'],
                    'num_publicaciones' => $fila['num_publicaciones']
                ];
            }
            return $usuarios;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}

