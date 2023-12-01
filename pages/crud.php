<?php
//requerimineot del fichero de funciones
require '../files/functions.php';
//iniciamos sesion y guardamos el nombre de susuario qeu tenemos en la sesion en una variable una vz filtrado
session_start();
//Condicional para examnimar si las varibales de seesion obligatorias existen 
if (isset($_SESSION['user']) && isset($_SESSION['rol']) && isset($_SESSION['dni'])) {
    $user = htmlspecialchars($_SESSION['user']);
    $rol = htmlspecialchars($_SESSION['rol']);
    $dni = htmlspecialchars($_SESSION['dni']);
} else {//Se redirige el usuario al index por que ha intentado entrar a la url sin estar identificado
    header('Location: ./login.php?error');
    exit;
}
//manejo del formulario de estensión de sesión
//Condicional para saber si existe la respuesta al formulario de extensión de sesión
if (isset($_POST['cookieExtend'])) {
    if ($_POST['cookieExtend'] === 'yes') {//Condicional para ssaber si la repuesta es positiva para crear una nueva cookie con una duración de un minuto
        setcookie(nameSessionCookie(session_id(), $dni), 'session', time() + 1 * 600); //Utilizaciñon del la funcion hash para encriptar la información
        //Redirecionamos a la mims página para que se cree la cookie
        header('Location:./crud.php');
        exit;
    }
    if ($_POST['cookieExtend'] === 'no') {
        require 'logOut.php';
        exit;
    }
}
//Cookies management*******************************************************************************************************************

if (isset($_COOKIE[nameSessionCookie(session_id(), $dni)])) {
    setcookie(nameSessionCookie(session_id(), $dni), 'session', time() + 1 * 600, '/');
}
$sessionCookie = isset($_COOKIE[nameSessionCookie(session_id(), $dni)]) ? $_COOKIE[nameSessionCookie(session_id(), $dni)] : null;
$lastVisit = isset($_COOKIE[nameSessionCookie('', $dni)]) ? $_COOKIE[nameSessionCookie('', $dni)] : null;

//condicional para saber si la cookie de sesion ha expirado y preguntar al usuario si quiere ectender su sesión
if (!isset($sessionCookie)) {
    header('Location: ./cookieExtension.php');
    exit;
}
//************************************************************************************************************************************
//Condicinal para saber si se ha pulsado el boton de cerrar sesión
if (isset($_POST['logout'])) {
    require 'logOut.php';
    exit;
}


////Condicinal para saber sobre que tabla se esta realizando la consulta
if (isset($user)) {
    //Función para saber sobre que tabla se esta haciendo referencia
    $puppies = getRefenceTable(filter_input_array(INPUT_POST), $rol);

//declaramos una varible para poder controlar cuando un cliente o una mascota
//esta bajo una acción del ususario (modificacón o eliminación)
    $matches = false;
//con esta variable vamos a controlar si el usuario esta en un update para poner a disabled 
//todos los elementos de la pantalla menos los elementos afectados por el update
    $updating = false;
//Declaramos una variable para saber cuando solo tenemos que cargar el formulario de confirmacion
    $confirmationActive = false;
    $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
//Condicinal para saber si nos llega información desde post y filtramos el formulario que nos viene
    if (filter_input_array(INPUT_POST)) {
        //Condicional para saber si se estan en la zona de mascotas o usuarios,
        // para hacer la consulta sobre la tabla corresta
        if ($puppies) {
            $table = 'mascotas';
        } else {
            $table = 'personas';
        }
        if (isset($_POST['update'])) { //condicional para cuando recibimos una peticion de modificación
            $update = htmlspecialchars($_POST['update']);
            echo 'esto es lo que vale la varibale update cuando entro con mascota ' . $update;
            $updating = true;
        }
        if (isset($_POST['delete'])) { //condicional para cuando recibimos una peticion de eliminación
            $delete = htmlspecialchars($_POST['delete']);
            managePost($delete, 'createDeleteStatement', $confirmationActive, $bd, $_POST, $table);
        }
        if (isset($_POST['commit'])) { //condicional para cuando recibimos una peticion de confirmación
            $commit = htmlspecialchars($_POST['commit']);
            managePost($commit, 'createUpdateStatement', $confirmationActive, $bd, $_POST, $table);
        }
        if (isset($_POST['insert'])) { //condicional para cuando recibimos una peticion de insercción
            $insert = htmlspecialchars($_POST['insert']);
            //Condicional para detectar si se quiere insertar una mascotas
            managePost($insert, 'createInsertStatement', $confirmationActive, $bd, $_POST, $table);
        }
    }
    if ($puppies) {//Conditional to check if user pushed puppies button to make statement about puppies
        $sql = "select dni,codigo_animal,nombre,edad,peso,codigo_consulta FROM mascotas WHERE dni = ?";
        //Condicional para saber pasar dirtintas palabras claves en función del rol del usuario
        if ($rol == 0) {
            $mascotas = selectValues($bd, $sql, array($dni));
        } else {
            $mascotas = selectValues($bd, $sql, array(htmlspecialchars($_POST['mascotas'])));
        }
    } else {
        //rezlimaos la consulta para sacar por pantalla los nombres de todos los usuarios de la veterinaria
        $sql = "select dni,nombre,apellido,telefono,direccion FROM personas WHERE rol = ?";
        $clients = selectValues($bd, $sql, array(0));
    }
}
//Cerramos la conexión con la base de datos 
$bd = null;
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/stylesJv.css">
        <title>CRUD</title>
    </head>
    <body>
        <div class='container'>
            <header class="header">
                <?php
//Condicional para saber si la cookie de ultima visita existe y mostrarle dicha indormación al usuario
                if (isset($lastVisit)) {
                    ?>
                    <p>Su última visita fue <?= $lastVisit ?></p>
                    <?php
                }
                ?>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                    <button class="btn bg-primary" name="logout" type="submit" value="logout">Log out </button>
                </form>

            </header>
            <?php
            if ($confirmationActive) {//condicinal para saber cuando se ha iniciado una petición de confirmación para la edición o modificación de datos de la BBDD
                //Cargamos el fichero de coanfirmacionForm.php
                require './confirmationForm.php';
            } else {
                if ($puppies) {
                    require '../pages/puppies.php';
                } else {
                    if (!$puppies) {
                        require '../pages/users.php';
                    }
                }
            }
            ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        </div>
    </body>
</html>
