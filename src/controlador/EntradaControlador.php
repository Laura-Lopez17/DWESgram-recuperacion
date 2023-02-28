<?php

namespace dwesgram\controlador;

use dwesgram\controlador\Controlador;
use dwesgram\modelo\Entrada as ModeloEntrada;
use dwesgram\modelo\EntradaBd;
use dwesgram\utilidades\Sesion;
use dwesgram\modelo\ComentarioBd;

//devolver vistas de cada funcion

class EntradaControlador extends Controlador
{


    public function lista(): array
    {
        if ($_POST && isset($_POST['search'])) {
            $search = htmlspecialchars($_POST['search']);
            $this->vista = 'entrada/lista';
            return EntradaBd::getEntradasByName($search); // Buscamos por nombre
        }
        $this->vista = "entrada/lista";
        return EntradaBd::getEntradas();
    }

    public function detalle(): ModeloEntrada|null //ver
    {
        $this->vista = 'entrada/detalle';
        $id = $_GET && isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
        if ($id !== null) {
            $entrada = EntradaBd::getEntrada($id);
            if ($entrada === null) {
                return null;
            }

            $comentarios = ComentarioBd::getComentarios($id);
            foreach ($comentarios as $comentario) {
                $entrada->añadirComentario($comentario);
            }

            return $entrada;
        } else {
            return null;
        }
    }

    public function nuevo(): ModeloEntrada|null
    {
        if ($this->autenticado()) {
            header('Location: index.php');
            return null;
        }

        //Si no hay POST cargo el formulario vacío
        if (!$_POST) {
            $this->vista = 'entrada/nuevo';
            return null;
        }

        //Si hay POST creo del modelo desde el POST

        $entrada = ModeloEntrada::crearEntradaDesdePost($_POST);

        if ($entrada->esValido()) {
            $this->vista = 'entrada/detalle';

            $entrada->id = EntradaBd::insertar($entrada);

            return $entrada;
        } else {
            $this->vista = 'entrada/nuevo';
            return $entrada;
        }
    }
    public function eliminar(): bool|null
    {
        // Obtener la sesión del usuario
        $sesion = new Sesion();
    
        // Comprobar que el usuario está autenticado
        if (!$sesion->haySesion()) {
            return null;
        }
    
        $this->vista = 'entrada/eliminar';
        if (!$_GET || !isset($_GET['id'])) {
            return false;
        }
    
        // Obtener la entrada a eliminar
        $entrada = EntradaBd::getEntrada(htmlspecialchars($_GET['id']));
    
        // Comprobar que la entrada existe
        if (!$entrada) {
            return false;
        }
    
        // Comprobar que el usuario es el mismo que creó la entrada
        if (!$sesion->mismoUsuario($entrada->getIdUsuario())) {
            return false;
        }
    
        // Eliminar la entrada
        return EntradaBd::eliminar($entrada->getId());
    }
    }



