<?php
session_start();
include('conexion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = strtoupper($_POST['dni']);
    $password = $_POST['password'];

    try {
        // Consulta preparada para obtener la contraseña almacenada
        $stmt = $bd->prepare("SELECT dni, clave FROM personas WHERE dni = :dni");
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // El DNI no existe en la base de datos
            $_SESSION['error'] = "DNI  incorrectos";
            header("location: login.php");
            exit();
        }
        if ($result) {
            // Verificar la contraseña
           
            $hashedPassword = hash('sha256', $password);
            echo "Contraseña ingresada: " . $password . "<br>";
            echo "Contraseña cifrada en la base de datos: " . $result['clave'] . "<br>";
            echo "Contraseña cifrada para comparación: " . $hashedPassword . "<br>";

            if (hash('sha256', $password) === $result['clave']) {
                $_SESSION['dni'] = $result['dni'];
                $_SESSION['success'] = "Sesión iniciada correctamente";
                header('location: index.php');
                exit();
            } else {
                $_SESSION['error'] = " contraseña incorrectos.";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error en la conexión a la base de datos: " . $e->getMessage();
    }

    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio de sesión</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="header">
        <h2>Iniciar sesión</h2>
    </div>

    <form class="form" action="login.php" method="post">
        <?php
        if (isset($_SESSION['error'])) :
        ?>
            <div class="error">
                <h3><?php echo $_SESSION['error']; ?></h3>
            </div>
        <?php
            unset($_SESSION['error']);
        endif;
        ?>

        <div class="input-group">
            <label for="dni">DNI usuario</label>
            <input type="text" name="dni" required>
        </div>

        <div class="input-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <button type="submit" class="btn">Iniciar sesión</button>
        </div>

        <p>Si no estás registrado <a href="register.php">Regístrate</a></p>
        <a href="index.php">Volver a la página principal</a>
    </form>
</body>

</html>