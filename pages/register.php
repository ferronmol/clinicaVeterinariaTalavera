<?php 
session_start();
include ('../files/conexion.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Registro</title>   
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/styleJv.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    
    <div class="header">
        <h2>Registro</h2>
    </div>

    <form class="form" action="register_db.php" method="post">
        <?php include('../files/errors.php'); ?>
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error">
                <h3>
                    <?php 
                        echo $_SESSION['error'];
                        //elimino la variable error una vez mostrado
                        unset($_SESSION['error']);
                    ?>
                </h3>
            </div>
        <?php endif ?>
         <div class="input-group">
            <label for="dni">DNI</label>
            <input type="text" name="dni" required>
        </div>
        <div class="input-group">
            <label for="username">Nombre</label>
            <input type="text" name="username" required>
        </div>
        <div class="input-group">
            <label for="usersubname">Apellidos</label>
            <input type="text" name="usersubname" required>
        </div>
         <div class="input-group">
            <label for="">Fecha de Nacimiento</label>
            <input type="date" name="fnacimiento">
        </div>
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono">
        </div>
        <div class="input-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion">
        </div>
        <div class="rol input-group">
            <label for="rol"></label>
            <input type="text" name="rol">
        </div>
        <div class="input-group">
            <label for="password_1">Contraseña</label>
            <input type="password" name="password_1" required>
        </div>
        <div class="input-group">
            <label for="password_2">Confirmar contraseña</label>
            <input type="password" name="password_2" required>
        </div>
        <div class="input-group">
            <button type="submit" name="reg_user" class="btn">Registrarse</button>
        </div>
        <p>Si ya estás registrado <a href="login.php">Iniciar Sesión</a></p>
    </form>
</body>
</html>