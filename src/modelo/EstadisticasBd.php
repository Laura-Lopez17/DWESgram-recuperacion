<?php
namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Megusta;

class EstadisticasBd
{
    use BaseDatos;

    public static function topTresMasMg(): array
    {
        try {
            $conexion = BaseDatos::getConexion();
            $query = <<<END
                select entrada, count(*) as num_megusta
                from megusta
                group by entrada
                order by num_megusta desc
                limit 3
            END;
            $sentencia = $conexion->prepare($query);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $entradasId = [];
            while (($fila = $resultado->fetch_assoc()) !== null) {
                $entradasId[] = $fila['entrada'];
            }
            return $entradasId;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}