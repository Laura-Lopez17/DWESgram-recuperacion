<?php

$ranking = $datosParaVista['datos'];
    echo "<h1>Usuarios con mas publicaciones:</h1>";

foreach ($ranking as $RankingUsuarios){
    
    echo "<p><b>El usuario: </b>". $RankingUsuarios->getUsuario()->getNombre() . "</p>";
    echo "<p><b>Ha publicado: </b>" . $RankingUsuarios->getNumEntradas() . "</p>";
}