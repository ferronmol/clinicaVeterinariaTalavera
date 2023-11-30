<?php
require '../files/functions.php';
//Llamada a función para crear la base de datos sino existe, usamos dentro de un condicional porque nos devolvra true si la base de datos no existe y a sido creada
//para generar las tablas con los datos para tener una carga inicial
if (createDataBase('exposicion')) {
    try {
        //nos conectamos a la base de detos creada
        $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
        //Vamos a generar una consulta masiva con el metodo que nos devuleve dicho script sql ya creado y hacemos una consulta con ello
        $bd->query(getScript());
        $bd = null;
    } catch (Exception $ex) {
        displayError('Vaya, parece que estamos en labores de mantenimiento, intentelo de nuevo más tarde');
    }
}
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pagina de Inicio de sesión</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/styleJv.css">
    </head>

    <body>

        <div class="header">
            <h2>Iniciar sesión</h2>
        </div>

        <form class="form" action="login_db.php" method="post">           
            <?php
            // Verificar si hay errores antes de mostrar el div
            if (isset($_GET['errorNotFound'])) {
                ?>
                <div class="error">
                    <h3>El usuario no se encuentra registrado</h3>
                </div>
            <?php }//fin del error de ususario no encontrado
            if (isset($_GET['errorNotMatch'])) {
                ?>
                <div class="error">
                    <h3>Usuario y/o contraseña no válidas</h3>
                </div>
            <?php }//Fin del error de credenciales no válidas
            ?>
            <div class="input-group">
                <label for="dni">DNI usuario</label>
                <input type="text" required name="dni">
            </div>
            <div class="input-group">
                <label for="password">Contraseña</label>
                <input type="password" required name="password">
            </div>
            <div class="input-group">
                <button type="submit" name="login_user" class="btn">Iniciar sesión</button>

            </div>
            <p>Si no estás registrado <a href="register.php">Regístrate</a></p>
            <a href="../index.php">Volver a la página principal</a>
        </form>   
    </body>

</html>