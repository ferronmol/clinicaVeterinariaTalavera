<?php
//requerimineot del fichero de funciones
require '../files/functions.php';
//iniciamos sesion y guardamos el nombre de susuario qeu tenemos en la sesion en una variable una vz filtrado
session_start();
require '../lib/allowControl.php';

//Form extend session management*****************************************************************************************************

//Condicional para saber si existe la respuesta al formulario de extensión de sesión
if (isset($_POST['cookieExtend'])) {
    $postCookies = filter_input_array(INPUT_POST);
    if ($postCookies['cookieExtend'] === 'yes') { //Condicional para ssaber si la repuesta es positiva para crear una nueva cookie con una duración de un minuto
        setcookie(nameSessionCookie(session_id(), $dni), 'session', time() + 1 * 600); //Utilizaciñon del la funcion hash para encriptar la información
        //Redirecionamos a la mims página para que se cree la cookie
        header('Location:./crud.php');
        exit;
    }
    if ($postCookies['cookieExtend'] === 'no') {
        require 'logOut.php';
        exit;
    }
}
//Cookies management*******************************************************************************************************************
//Conditional to check if a cookie with this specific name exists
if (isset($_COOKIE[nameSessionCookie(session_id(), $dni)])) {
    //calling function to add more espiration time to session cookie
    setcookie(nameSessionCookie(session_id(), $dni), 'session', time() + 1 * 600, '/');
}
//Set value to seesionCookie if specific session cookie exists or nul if it does not
$sessionCookie = isset($_COOKIE[nameSessionCookie(session_id(), $dni)]) ? $_COOKIE[nameSessionCookie(session_id(), $dni)] : null;

//Set value to seesionCookie if specific las visit cookie exists or nul if it does not
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
    //Function to get reference table to make statement on
    $table = getReferenceTable(filter_input_array(INPUT_POST), $rol);

    //declaramos una varible para poder controlar cuando un cliente o una mascota
    //esta bajo una acción del ususario (modificacón o eliminación)
    $isMatchingToKey = false;
    //con esta variable vamos a controlar si el usuario esta en un update para poner a disabled 
    //todos los elementos de la pantalla menos los elementos afectados por el update
    $updating = false;
    //Declaramos una variable para saber cuando solo tenemos que cargar el formulario de confirmacion
    $confirmationActive = false;

    //Calling function to generate connection to a databse and storage the object ut returns into $bd variable
    $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
    //Conditional to check if exist info send by post and filtering at the same time
    if (filter_input_array(INPUT_POST)) {
        //Guardamos en una varibale lo que nos viene por post
        $postValues = filter_input_array(INPUT_POST);
        //Condicional para saber si se estan en la zona de mascotas o usuarios,
        // para hacer la consulta sobre la tabla corresta

        if (isset($postValues['update'])) { //condicional para cuando recibimos una peticion de modificación
            $keyUpdatingElement = $postValues['update'];
            $updating = true;
        }
        if (isset($postValues['delete']) || isset($postValues['insert']) || isset($postValues['commit'])) {
            managePost(getAction($postValues)[0], getAction($postValues)[1], $confirmationActive, $bd, $postValues, $table);
        }
    }
    if ($table == 'mascotas') { //Conditional to check if user pushed puppies button to make statement about puppies
        $sql = "select dni,codigo_animal,nombre,raza,edad,peso FROM mascotas WHERE dni = ?";
        //Condicional para saber pasar dirtintas palabras claves en función del rol del usuario
        if (isset($postValues['dni'])) {
            $dni = $postValues['dni'];
        }
        $mascotas = getSelectStatementValues($bd, $sql, array($dni));
    } else {
        //rezlimaos la consulta para sacar por pantalla los nombres de todos los usuarios de la veterinaria
        $sql = "select dni,nombre,apellido,telefono,direccion FROM personas WHERE rol = ?";
        $clients = getSelectStatementValues($bd, $sql, array(0));
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
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>CRUD</title>
</head>

<body>
    <header class="header_main header_short">
        <nav class="nav container" id="mininav">
            <h2 class="nav__logo">Tala<span class="char char--logo">V</span>et</h2>

            <ul class="nav__links">

                <li class="nav__item">
                    <a href="../index.php" class="nav__link"><span class="char">I</span>nicio</a>
                    <?php
                    //Condicional para saber si la cookie de ultima visita existe y mostrarle dicha indormación al usuario
                    if (isset($lastVisit)) {
                    ?>
                        <p>Su última visita fue <?= $lastVisit ?></p>
                    <?php
                    }
                    ?>
                </li>
                <li class="nav__item">
                    <a href="./login.php" class="nav__link"><span class="char">A</span>cceso</a>
                    <!-- Botón de Cerrar Sesión -->
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                        <button class="btn bg-primary btn-mb" name="logout" type="submit" value="logout">
                            <span class="bi bi-box-arrow-right "></span> Cerrar Sesión
                        </button>
                    </form>
                </li>
                <li class="nav__item">
                    <a href="#" class="nav__link"><span class="char">S</span>ervicios</a>
                </li>
                <li class="nav__item">
                    <a href="#" class="nav__link"><span class="char">C</span>ontacto</a>
                </li>

            </ul>

            <!--Tiene que ser un boton que haga referencia al id puesto en el nav-->
            <a href="#mininav" class="nav__hamburguer">
                <img src="../images/menu.svg" class="nav__icon">
            </a>

            <a href="" class="nav__close">
                <img src="../images/close.svg" class="nav__icon">
            </a>

        </nav>

    </header>
    <div class='container'>
        <?php
        if ($confirmationActive) { //condicinal para saber cuando se ha iniciado una petición de confirmación para la edición o modificación de datos de la BBDD
            //Cargamos el fichero de confirmacionForm.php
            require './confirmationForm.php';
        } else {
            if ($table == 'mascotas') {
                require '../pages/puppies.php';
            } else {
                if ($table == 'personas') {
                    require '../pages/users.php';
                }
            }
        }
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </div>
</body>
</html>