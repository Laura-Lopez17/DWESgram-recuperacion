<?php
if ($datosParaVista['datos']) { // returnea true o false, por el controlador y ItemDB.
    echo "<p><strong>Eliminado correctamente.</strong></p>";
} else {
    echo "<p><strong>No se ha eliminado.</strong></p>";
}

echo "<p><a href='index.php?controlador=entrada&accion=lista'>Volver</a></p>";
?>