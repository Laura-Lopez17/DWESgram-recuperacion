<?php

namespace dwesgram\modelo;

use dwesgram\modelo\BaseDatos;
use dwesgram\utilidades\subirImagenes;

class UsuarioBd
{
    use BaseDatos;

    public static function getUsuarioPorNombre(string $nombre): Usuario | null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select id, nombre, clave, email, avatar from usuario where nombre = ?");
            $sentencia->bind_param('s', $nombre);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();
            if ($fila == null) {
                return null;
            } else {
                return new Usuario(
                    id: $fila['id'],
                    nombre: $fila['nombre'],
                    clave: $fila['clave'],
                    email: $fila['email'],
                    avatar: $fila['avatar']
                );
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public static function getUsuarioPorId(int $id): Usuario|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select id, nombre, clave, email, avatar from usuario where id = ?");
            $sentencia->bind_param('i', $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();
            if ($fila == null) {
                return null;
            } else {
                return new Usuario(
                    id: $fila['id'],
                    nombre: $fila['nombre'],
                    email: $fila['email'],
                    clave: $fila['clave'],
                    avatar: $fila['avatar']
                );
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }


    public static function eliminar(int $id): bool
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("delete from item where id=?");
            $sentencia->bind_param('i', $id);
            return $sentencia->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function insertar(Usuario $usuario): int|null
    {
        try {
            $conexion = BaseDatos::getConexion();

            $ruta_imagen = subirImagenes::subirImagen($_FILES['avatar'], "imagenes/avatares");
            $usuario->avatar = $ruta_imagen;


            $sentencia = $conexion->prepare("insert usuario (nombre,clave,email,avatar) values (?,?,?,?)");
            $nombre = $usuario->nombre;
            $clave = password_hash($usuario->clave, PASSWORD_BCRYPT);
            $email = $usuario->email;
            $avatar = $usuario->avatar;
            $sentencia->bind_param('ssss', $nombre, $clave, $email, $avatar);
            $sentencia->execute();

            return $conexion->insert_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    

    public static function getRutaAvatar(int $id): string|null
    {
        try {
            $conexion = BaseDatos::getConexion();
            $sentencia = $conexion->prepare("select avatar from usuario where id = ?");
            $sentencia->bind_param('i', $id);
            $sentencia->execute();
            $resultado = $sentencia->get_result();
            $fila = $resultado->fetch_assoc();

            return $fila['avatar'];
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
