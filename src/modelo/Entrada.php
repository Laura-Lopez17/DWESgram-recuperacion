<?php

namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;
use dwesgram\utilidades\subirImagenes;
use dwesgram\modelo\Usuario;
use dwesgram\modelo\UsuarioBd;
use DateTime;


class Entrada extends Modelo
{
    private array $errores = [];

    public function __construct(
        public string|null $texto,
        public int|null $id = null,
        public string|null|bool $imagen = null,
        private Usuario|null $usuario = null,
        private array $UsuariosMeGusta = [],
        private array $listaComentarios = []   


    ) {
        $this->errores = [
            'texto' => $texto === null || empty($texto) ? '<div class="alert alert-danger" role="alert"><i class="fa-solid fa-circle-xmark"></i>&nbsp;El texto no puede estar vacío</div>' : null,
            'imagen' => null
        ];
    }

    public static function crearEntradaDesdePost(array $post): Entrada|bool|null
    {
        $texto = $post && isset($post['texto']) ? htmlspecialchars(trim($post['texto'])) : null;
        $resultado = subirImagenes::subirImagen($_FILES['imagen'], CARPETA_IMAGENES);
        $usuario = $post && isset($post['autor']) ? UsuarioBd::getUsuarioPorId(htmlspecialchars($post['autor'])) : null;

        $entrada = new Entrada(
            texto: $texto,
            imagen: $resultado,
            usuario: $usuario
        );

        if ($resultado === false) {
            $entrada->errores['imagen'] = '<div class="alert alert-danger" role="alert"><i class="fa-solid fa-circle-xmark"></i>&nbsp;Los ficheros tienen que ser imágenes PNG o JPEG</div>';
        } else {
            $entrada->imagen = $resultado;
        }

        return $entrada;
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTexto(): string
    {
        return $this->texto ? $this->texto : '';
    }

    public function getImagen(): string|null
    {
        return $this->imagen;
    }

    public function esValido(): bool //si todos los elementos del array errores son null devuelve true
    {

        if ($this->errores === null) {
            return true;
        } else {

            $arrayErrores = array_filter($this->errores, function ($v1) {
                return $v1 !== null;
            });
            if (count($arrayErrores) === 0) {
                return true;
            }
        }

        return false;
    }

    public function getErrores(): array
    {
        return $this->errores;
    }

    public function getUsuario(): Usuario|null
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getIdUsuario(): int|null
    {
        return $this->usuario !== null ? $this->usuario->getId() : null;
    }

    public function getNumeroMegusta(): int
    {
        return count($this->UsuariosMeGusta);
    }

    public function darMegusta(int $usuarioId): bool
    {
        return in_array($usuarioId, $this->UsuariosMeGusta);
    }

    public function getComentarios(): array
    {
        return $this->listaComentarios;
    }

    public function añadirComentario(Comentario $comentario): void
    {
        $this->listaComentarios[] = $comentario;
    }
}

