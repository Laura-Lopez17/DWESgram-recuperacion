<?php

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\Usuario;
use dwesgram\modelo\MegustaBd;

class EntradaBd
{
    use BaseDatos;

    public static function getEntrada(int $id): Entrada|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $query = <<<END
                SELECT
                e.id AS entrada_id,
                e.texto AS entrada_texto,
                e.imagen AS entrada_imagen,
                u.id AS usuario_id,
                u.nombre AS usuario_nombre,
                u.avatar AS usuario_avatar
            FROM
                entrada e
                JOIN usuario u ON e.autor = u.id
            WHERE
                e.id = ?
            END;
            $sentencia = $conexion->prepare($query);
            $sentencia->bind_param('i', $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            if ($resultado === false) {
                return null;
            }

            $fila = $resultado->fetch_assoc();
            $usuario = new Usuario(
                id: $fila['usuario_id'],
                nombre: $fila['usuario_nombre'],
                avatar: $fila['usuario_avatar']
            );
            $entrada = new Entrada(
                id: $fila['entrada_id'],
                texto: $fila['entrada_texto'],
                imagen: $fila['entrada_imagen'],
                usuario: $usuario,
                UsuariosMeGusta: MegustaBd::getUsuariosId($fila['entrada_id'])

            );
            return $entrada;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }


    public static function getEntradas(): array
    {
        try {
            $conexion = BaseDatos::getConexion();
            $query = <<<END
                SELECT e.id AS entrada_id,
                    e.texto AS entrada_texto,
                    e.imagen AS entrada_imagen,
                    u.id AS usuario_id,
                    u.nombre AS usuario_nombre,
                    u.avatar AS usuario_avatar
                FROM entrada e
                JOIN usuario u ON e.autor = u.id
                ORDER BY e.creado DESC;
     
            END;
            $resultado = $conexion->query($query);
            $entradas = [];
            while (($fila = $resultado->fetch_assoc()) !== null) {
                $usuario = new Usuario(
                    id: $fila['usuario_id'],
                    nombre: $fila['usuario_nombre'],
                    avatar: $fila['usuario_avatar']
                );
                $entrada = new Entrada(
                    id: $fila['entrada_id'],
                    texto: $fila['entrada_texto'],
                    imagen: $fila['entrada_imagen'],
                    usuario: $usuario,
                    UsuariosMeGusta: MegustaBd::getUsuariosId($fila['entrada_id'])
                );
                $entradas[] = $entrada;
            }
            return $entradas;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }


    public static function eliminar(int $id): bool
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("DELETE FROM entrada WHERE id=?");
            $sentencia->bind_param("i", $id);
            return $sentencia->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }


    public static function insertar(Entrada $entrada): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $query = <<<END
                insert into entrada (texto, imagen, autor)
                values (?, ?, ?)
            END;
            $sentencia = $conexion->prepare($query);
            $texto = substr($entrada->getTexto(), 0, 128);
            $imagen = $entrada->getImagen();
            $usuario = $entrada->getUsuario() !== null ? $entrada->getUsuario()->getId() : null;
            $sentencia->bind_param("ssi", $texto, $imagen, $usuario);
            $resultado = $sentencia->execute();
            if ($resultado) {
                return $conexion->insert_id;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function buscarPorId(int $id): ?Entrada
    {
        try {
            $conexion = BaseDatos::getConexion();
            $query = "SELECT * FROM entrada WHERE id = ?";
            $sentencia = $conexion->prepare($query);
            $sentencia->bind_param("i", $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $entrada = $resultado->fetch_object(Entrada::class);
            return $entrada ?: null;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
