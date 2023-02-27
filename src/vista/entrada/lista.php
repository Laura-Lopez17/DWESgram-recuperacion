<?php

if (!empty($datosParaVista['datos'])) {
    foreach ($datosParaVista['datos'] as $entrada) {
        $id = $entrada->id;
        $texto = $entrada->texto;
        $imagen = $entrada->imagen;
        $autor = $entrada->getUsuario()->getId();
        $numeroMegusta = $entrada->getNumeroMegusta();

        // Mostrar la entrada
        echo <<<HTML
            <p class="alert alert-dark" role="alert">$texto</p>
            <img src='$imagen' height='200px' width='auto';/>
            <a href="index.php?controlador=entrada&accion=detalle&id=$id" class="btn btn-primary my-3">Ver</a>&nbsp;
        HTML;

        // Si hay una sesión y el usuario es el autor de la entrada, mostrar botón para eliminar
        if ($sesion->haySesion() && $sesion->getId() == $autor) {
            echo "<a href='index.php?controlador=entrada&accion=eliminar&id=$id' class='btn btn-danger my-3'>Eliminar</a>";
        }

        // Si hay una sesión y el usuario no es el autor ni ha dado "me gusta", mostrar botón para dar "me gusta"
        if ($sesion->haySesion() && !$sesion->mismoUsuario($autor) && !$entrada->darMegusta($sesion->getId())) {
            echo <<<HTML
                &nbsp;
                <a href="index.php?controlador=megusta&accion=megusta&entrada={$entrada->getId()}&usuario={$sesion->getId()}" class="card-link">
                <i class="bi bi-heart"></i>   
               </a>
                ($numeroMegusta)
            HTML;
        } else {
            echo "&nbsp;<i class='bi bi-heart-fill'></i>&nbsp;$numeroMegusta";
        }
    }
} else {
    echo "<h3 class='text-center'>No hay publicaciones</h3>";
}
