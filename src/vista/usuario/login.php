<?php
$datos = $datosParaVista['datos'];
$error = $datos && isset($datos['error']) ? $datos['error'] : null;
$nombre = $datos && isset($datos['nombre']) ? $datos['nombre'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>Inicia sesión</h1>

        <?= $error ? "<div class='alert alert-danger' role='alert'>$error</div>" : '' ?>


        <form action="index.php?controlador=usuario&accion=login" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de usuario</label><br>
                <input type="text" id="nombre" name="nombre">
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label">Contraseña</label><br>
                <input type="password" id="clave" name="clave">
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</body>

</html>