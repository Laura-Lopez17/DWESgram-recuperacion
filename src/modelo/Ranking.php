<?php
namespace dwesgram\modelo;

use dwesgram\modelo\Usuario;
use dwesgram\modelo\Entrada;

class Ranking 
{


    public function __construct(
   
        private Usuario $usuario,
        private int $numEntradas,
        )
     {}


     public function getUsuario(): Usuario
     {
         return $this->usuario;
     }
     
     public function setUsuario(Usuario $usuario): void
     {
         $this->usuario = $usuario;
     }
     
     public function getNumEntradas(): int
     {
         return $this->numEntradas;
     }
     
     public function setNumEntradas(int $numEntradas): void
     {
         $this->numEntradas = $numEntradas;
     }
     


}
