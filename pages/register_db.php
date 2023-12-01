<?php
session_start();
include('conexion.php');
$errors = array();

if (isset($_POST['reg_user'])) {
    $dni = strtoupper($_POST['dni']);
    // Validacion del formato del DNI
    if (!preg_match('/^[0-9]{8}[a-zA-Z]$/', $dni)) {
        array_push($errors, "El DNI debe tener 8 números y una letra");
        $_SESSION['error'] = "El DNI debe tener 8 números y una letra";
    }
    $username = ucwords(strtolower($_POST['username']));
    $usersubname = ucwords(strtolower($_POST['usersubname']));
    $fnacimiento = $_POST['fnacimiento'];
    $email = strtolower($_POST['email']);
    $telefono = $_POST['telefono'];
    $direccion = ucwords(strtolower($_POST['direccion']));
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];
    //$fecha = date("d/m/y");

    if (empty($dni) || empty($username) || empty($usersubname) || empty($email) || empty($password_1) || empty($password_2)) {
        array_push($errors, "Todos los campos son requeridos");
        $_SESSION['error'] = "Todos los campos son requeridos";
    }

    if ($password_1 != $password_2) {
        array_push($errors, "Las contraseñas no coinciden");
        $_SESSION['error'] = "Las contraseñas no coinciden";
    }

    $user_check_query = "SELECT * FROM personas WHERE dni = '$dni' OR email = '$email' LIMIT 1";
    $query = $bd->query($user_check_query);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) { // Si el usuario existe
        if ($result['dni'] === $dni) {
            array_push($errors, "El usuario ya existe");
        }
        if ($result['email'] === $email) {
            array_push($errors, "Email ya existe");
        }
    }

    if (count($errors) == 0) {
        $clave = hash('sha256', $password_1);

        //insertar en la base de datos
        try {
            //uso consulta preparada
            $stmt = $bd->prepare("INSERT INTO personas (dni, Nombre, Apellido, FechaNacimiento, Email, Telefono, Direccion, Rol, Clave)
            VALUES (:dni, :username, :usersubname, :fnacimiento, :email, :telefono, :direccion, 0, :clave)");
            // Asignar valores a los parámetros de la consulta
            $stmt->bindParam(':dni', $dni);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':usersubname', $usersubname);
            $stmt->bindParam(':fnacimiento', $fnacimiento);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':clave', $clave);

            if ($stmt->execute()) {
                $_SESSION['dni'] = $dni;
                $_SESSION['success'] = "Registrado correctamente.Ahora estás logueado como $dni.";
                header('location: ../index.php');
                exit();
            } else {
                array_push($errors, "Error al insertar en la base de datos");
                $_SESSION['error'] = "Error al insertar en la base de datos";
                header("location: register.php");
                exit();
            }
        } catch (PDOException $e) {
            if ($e->getCode() == "23000") {
                //codigo 23000 indica que es una clave duplicada
                if ($e->getCode() == 23000) {
                    // Código 23000 indica una clave duplicada
                    if (strpos($e->getMessage(), 'PRIMARY') !== false) {
                        array_push($errors, "El usuario con ese Dni ya existe");
                        $_SESSION['error'] = "El usuario con ese Dni ya existe";
                    } elseif (strpos($e->getMessage(), 'email') !== false) {
                        array_push($errors, "El correo electrónico $email ya está registrado");
                        $_SESSION['error'] = "El correo electrónico $email ya está registrado";
                    } else {
                        array_push($errors, "Error al insertar en la base de datos");
                        $_SESSION['error'] = "Error al insertar en la base de datos";
                    }
                } else {
                    array_push($errors, "Error en la conexión a la base de datos: " . $e->getMessage());
                    $_SESSION['error'] = "Error en la conexión a la base de datos";
                }
                header("location: register.php");
            }
        }
    } else {
        header("location:register.php");
    }
}
