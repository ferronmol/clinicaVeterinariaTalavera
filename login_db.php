<?php
session_start();
$errors = array();

include('conexion.php'); // Asegúrate de que este archivo incluya la conexión PDO

if (isset($_POST['login_user'])) {
    $dni = strtoupper($_POST['dni']);
    $password = $_POST['password'];

    try {
        $clave = hash('sha256', $password);
        echo "Hash de la contraseña: $clave";
        echo "Consulta SQL: " . $stmt->queryString;

        // Uso de consulta preparada con PDO
        $stmt = $bd->prepare("SELECT * FROM personas WHERE DNI = :dni AND Clave = :clave");

        // Asignar valores a los parámetros de la consulta
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':clave', $clave);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Consulta SQL: " . $stmt->queryString;
            if ($result) {
                $_SESSION['dni'] = $result['DNI'];
                $_SESSION['success'] = "Sesión iniciada correctamente";
                header('location: index.php');
                exit();
            } else {
                array_push($errors, "Dni o contraseña incorrectos");
                $_SESSION['error'] = "Dni o contraseña incorrectos";
                echo "Mensaje de error establecido";
                header("location: login.php");
                exit();
            }
        } else {
            throw new PDOException("Error al ejecutar la consulta");          
        }
    } catch (PDOException $e) {
        array_push($errors, "Error en la conexión a la base de datos: " . $e->getMessage());
        $_SESSION['error'] = "Error en la conexión a la base de datos";
        header("location: login.php");
        exit();
    }
}
?>
