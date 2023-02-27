<?php
namespace dwesgram\controlador;

use dwesgram\modelo\Comentario;
use dwesgram\modelo\ComentarioBd;
use dwesgram\modelo\Entrada;
use dwesgram\modelo\EntradaBd;

class ComentarioControlador extends Controlador
{
    public function nuevo(): Entrada|null
    {
        if ($this->autenticado()) {
            return null;
        }

        $this->vista = 'entrada/detalle';

        $idEntrada = $_GET && isset($_GET['entrada']) ? htmlspecialchars($_GET['entrada']) : null;
        $idUsuario = $_GET && isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : null;
        $entrada = EntradaBd::getEntrada($idEntrada);
        if ($entrada === null || $idUsuario === null) {
            return null;
        }

        if (!$_POST || !isset($_POST['comentario'])) {
            return $entrada;
        }

        //Comentarios en la entrada
        foreach (ComentarioBd::getComentarios($idEntrada) as $comentario) {
            $entrada->añadirComentario($comentario);
        }


        $texto = htmlspecialchars(trim($_POST['comentario']));
        $comentario = new Comentario(
            comentario: $texto,
            entrada: $idEntrada,
            usuario: $idUsuario
        );
        if ($comentario->esValido()) {
            $resultado = ComentarioBd::insertar($comentario);
            if ($resultado !== null) {
                $comentario->setId($resultado);
                $entrada->añadirComentario($comentario);
            }
        }

        return $entrada;
    }
}
