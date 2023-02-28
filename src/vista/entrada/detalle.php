<?php

if (!empty($datosParaVista['datos']) && $datosParaVista['datos'] != null) {
    $entrada = $datosParaVista['datos'];
    $id = $entrada->id;
    $text = $entrada->texto;
    $img = $entrada->imagen;
    $numeroMegusta = $entrada->getNumeroMegusta();
    $autor = $entrada->getUsuario()->getId();


    if ($entrada->getUsuario() !== null && $sesion->haySesion() && $sesion->getId() == $entrada->getUsuario()->getId()) {
        echo "<a href='index.php?controlador=entrada&accion=eliminar&id=$id' class='btn btn-danger my-3'>Eliminar</a>";
    }

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

    if ($entrada->getUsuario() !== null) {
        echo  "<h3>" . $entrada->getUsuario()->getNombre() . " escribió:</h3>";
    }

    echo <<<END
         <p class="alert alert-info" role="alert">$text</p>
        <img src='$img' height='500px' width='auto'/>
        <br>
    END;
} else {
    echo "<p>No existe esta entrada</p>";
}

/**********************************************************************************************************************
 * Formulario y comentarios
 */
if ($sesion->haySesion()) {
    echo <<<END
        </div>
        <div class="row mt-4 justify-content-center">
            <div class="col-8">
                <h2>Comentarios</h2>
                <form action="index.php?controlador=comentario&accion=nuevo&entrada={$entrada->getId()}&usuario={$sesion->getId()}" method="post">
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Escribe tu comentario:</label>
                        <textarea class="form-control" id="comentario" name="comentario"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir</button>
                </form>
            </div>
        </div class="row">
    END;
}
    echo <<<END
        <div class="row justify-content-center mt-4">
            <div class="col-8">
                <div class="list-group">
    END;
    $num = 1;
    foreach ($entrada->getComentarios() as $comentario) {
        $usuario = $comentario->getUsuario();
        echo <<<END
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{$usuario->getNombre()} comentó:</h5>
                    <small>#$num</small>
                </div>
                <p class="mb-1">{$comentario->getComentario()}</p>
            </div>
        END;
        $num++;
    }
    echo <<<END
                </div>
            </div>
        </div>
    END;
