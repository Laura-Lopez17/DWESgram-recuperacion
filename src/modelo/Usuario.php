<?php
namespace dwesgram\modelo;

use dwesgram\modelo\Modelo;
use dwesgram\modelo\UsuarioBd;
use dwesgram\utilidades\subirImagenes;

class Usuario extends Modelo
{
    private array $errores = [];

    public function __construct(
        public string|null $nombre = null,
        public string|null $clave = null,
        public string|null $repiteClave = null,
        public string|null $email = null,
        public string|null|bool $avatar = null,
        public int|null $id = null
    ) {
        $this->errores = [
            'nombre' => $nombre === null || empty($nombre) ? 'El nombre no puede estar vacío' : null,
            'email' => $email === null || empty($email) ? 'El email no puede estar vacío' : null,
            'clave' => $clave === null || empty($clave) ? 'La contraseña no puede estar vacía' : null,
            'repiteclave' => $clave !== $repiteClave ? 'Las contraseñas deben coincidir' : null,
            'avatar' => null
        ];;
    }



    public static function crearUsuarioDesdePost(array $post): Usuario
    {

        $usuario = new Usuario(
        nombre: $post && isset($post['nombre']) ? htmlspecialchars(trim($post['nombre'])) : null,
        clave: $post && isset($post['clave']) ? htmlspecialchars(trim($post['clave'])) : null,
        email: $post && isset($post['email']) ? htmlspecialchars(trim($post['email'])) : null ,
        repiteClave: $post && isset($post['repiteclave']) ? htmlspecialchars(trim($post['repiteclave'])) : null,
        avatar: $post && isset($post['avatar']) ? htmlspecialchars(trim($post['avatar'])) : null
        );
       

        if (mb_strlen($usuario->clave) < 8) {
            $usuario->errores['clave'] = "La contraseña debe tener, al menos,8 carácteres";
        }

        if (mb_strlen($usuario->nombre) === 0){
            $usuario->errores['nombre'] = 'El nombre no puede estar vacío';
        }else{
            $otro = UsuarioBd::getUsuarioPorNombre($usuario->nombre);
            if ($otro !== null){
                $usuario->errores['nombre'] = "El nombre de usuario no está disponible";
            }
        }

        return $usuario;
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
    public function getNombre(): string
    {
        return $this->nombre ? $this->nombre : '';
    }
    
    public function setNombre(string $nombre): string
    {
        return $this->nombre ? $this->nombre : '';
    }
    
    public function getClave(): string
    {
        return $this->clave ? $this->clave : '';
    }
    
    public function setClave(string $clave): string
    {
        return $this->repiteClave ? $this->repiteClave : '';
    }
    
    public function getRepiteClave(): string
    {
        return $this->repiteClave ? $this->repiteClave : '';
    }
    
    public function setRepiteClave(string $repiteClave): string
    {
        return $this->repiteClave ? $this->repiteClave : '';
    }
    
    public function getEmail(): string
    {
        return $this->email ? $this->email : '';
    }
    
    public function setEmail(string $email): string
    {
        return $this->email ? $this->email : '';
    }
    
    public function getAvatar(): string|bool|null
    {
        return $this->avatar ? $this->avatar : '';
    }
    
    public function setAvatar(string|bool|null $avatar): string
    {
        return $this->avatar ? $this->avatar : '';
    }
    
    public function getId(): int|null
    {
        return $this->id;
    }
    
    public  function setId(int $id): void
    {
        $this->id = $id;
    }
    
}
