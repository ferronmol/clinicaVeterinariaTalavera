<?php
session_start();

$errors = array();

include('conexion.php');

if (isset($_POST['login_user'])) {
    //verificar la conexion

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        array_push($errors, "Todos los campos son requeridos");
        $_SESSION['error'] = "Todos los campos son requeridos";
    }

    if (count($errors) == 0) {
        $clave = hash('sha256', $password);

        // Uso de consulta preparada
        $stmt = $bd->prepare("SELECT * FROM personas WHERE Nombre=:username AND Clave=:clave");

        // Asignar valores a los parámetros de la consulta
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':clave', $clave);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $_SESSION['dni'] = $result['dni'];
                $_SESSION['success'] = "Sesión iniciada correctamente";
                header('location: index.php');
                exit();
            } else {
                array_push($errors, "Nombre de usuario o contraseña incorrectos");
                $_SESSION['error'] = "Nombre de usuario o contraseña incorrectos";
                echo "Mensaje de error establecido";
                header("location: login.php");
                exit();
            }
        } else {
            array_push($errors, "Error al ejecutar la consulta");
            $_SESSION['error'] = "Error al ejecutar la consulta";
            header("location: login.php");
        }
    } else {
        header("location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de Inicio de sesión</title>

    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <div class="header">
        <h2>Iniciar sesión</h2>
    </div>

    <form class="form" action="login_db.php" method="post">           
                <?php
                // Verificar si hay errores antes de mostrar el div
                if (isset($_SESSION['error'])): 
                ?>
                <div class="error">
                    <h3>
                        <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </h3>
                </div>
                <?php endif; ?>
        <div class="input-group">
            <label for="dni">DNI usuario</label>
            <input type="text" name="dni">
        </div>
        <div class="input-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password">
        </div>
        <div class="input-group">
            <button type="submit" name="login_user" class="btn">Iniciar sesión</button>

        </div>
        <p>Si no estás registrado <a href="register.php">Regístrate</a></p>
        <a href="index.php">Volver a la página principal</a>
    </form>   
</body>

</html>