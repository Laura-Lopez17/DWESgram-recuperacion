<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Modelo;
use dwesgram\modelo\UsuarioBd;
use dwesgram\modelo\Usuario as UsuarioModelo;

class UsuarioControlador extends Controlador
{

    public function login(): array|string|null
    {
        if (!$this->autenticado()) {
            header('Location: index.php');
            return null;
        }

        if (!$_POST) {
            $this->vista = 'usuario/login';
            return null;
        }

        $nombre = $_POST && isset($_POST['nombre']) ? htmlentities(trim($_POST['nombre'])) : '';
        $clave = $_POST && isset($_POST['clave']) ? htmlentities(trim($_POST['clave'])) : '';
        $usuario = UsuarioBd::getUsuarioPorNombre($nombre);
        if ($usuario !== null && password_verify($clave, $usuario->clave)) {
            $this->vista = 'usuario/mensaje';
            $_SESSION['usuario'] = [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre
            ];
            header('Location: index.php');
            return null;
        }

        $this->vista = 'usuario/login';
        return [
            'nombre' => $nombre,
            'error' => 'Usuario y/o contraseña no válidos.'
        ];
    }


    public function registro(): UsuarioModelo|string|null
    {

        if (!$this->autenticado()) {
            header('Location: index.php');
            return null;
        }
        
        if (!$_POST) {
            $this->vista = 'usuario/registro';
            return null;
        }

        $usuario = UsuarioModelo::crearUsuarioDesdePost($_POST);

        if ($usuario->esValido()) {
            $usuario->setId(UsuarioBd::insertar($usuario));
            $this->vista = 'usuario/mensaje';

            return 'Te has registrado correctamente, ya puedes iniciar sesión';
        } else {
            $this->vista = 'usuario/registro';
            return $usuario;
        }

        $id = UsuarioBd::insertar($usuario);
        if ($id !== null) {
            $this->vista = 'usuario/mensaje';
            return 'Te has registrado correctamente, ya puedes iniciar sesión';
        } else {
            $this->vista = 'usuario/mensaje';
            return 'No se ha podido llevar a cabo el registro, prueba mas tarde';
        }

        
    }


    public function logout(): void
    {
        session_destroy();
        header('Location: index.php');
        $this->vista = "home/home";
    }

}
