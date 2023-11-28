<?php
require '../files/functions.php';
//iniciamos sesion y guardamos el nombre de susuario qeu tenemos en la sesion en una variable una vz filtrado
session_start();
//Condicional para examnimar si las varibales de seesion obligatorias existen 
if (isset($_SESSION['user']) && isset($_SESSION['rol']) && isset($_SESSION['dni'])) {
    $user = htmlspecialchars($_SESSION['user']);
    $rol = htmlspecialchars($_SESSION['rol']);
    $dni = htmlspecialchars($_SESSION['dni']);
}
else{//Se redirige el usuario al index por que ha intentado entrar a la url sin estar identificado
    header('Location: ../index.php?error');
    exit;
}

//Condicional para saber si existe la respuesta al formulario de extensión de sesión
if(isset($_POST['cookieExtend'])){
    if($_POST['cookieExtend'] === 'yes'){//Condicional para ssaber si la repuesta es positiva para crear una nueva cookie con una duración de un minuto
        setcookie(hash('sha256',session_id().$dni),'session',time() + 1 * 600);//Utilizaciñon del la funcion hash para encriptar la información
        //Redirecionamos a la mims página para que se cree la cookie
        header('Location:./crud.php');
        exit;
    }
    if($_POST['cookieExtend'] === 'no'){
        require 'logOut.php';
        exit;
    }
}
//manejo de cookies
if(isset($_COOKIE[hash('sha256',session_id().$dni)])){
    setcookie(hash('sha256',session_id().$dni),'session',time() + 1 * 600,'/');
}
$sessionCookie = isset($_COOKIE[hash('sha256',session_id().$dni)]) ? $_COOKIE[hash('sha256',session_id().$dni)] : null;
$lastVisit = isset($_COOKIE[hash('sha256',$dni)]) ? $_COOKIE[hash('sha256',$dni)] : null;

//Condicinal para saber si se ha pulsado el boton de cerrar sesión
if (isset($_POST['logout'])) { //falta meter en la condicin un or para cuando no existe ls seesion cookie
    echo 'entro en el condicinal';
    require 'logOut.php';
    exit;
}
//condicional para saber si la cookie de sesion ha expirado y preguntar al usuario si quiere ectender su sesión
if (!isset($sessionCookie)) {//Condicional para saber si la cookie de sesión esta activa o no para llamar al formulario de extensión de sesión
    header('Location: ./cookieExtension.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/styles.css">
        <title>CRUD</title>
    </head>
    <body>
        <div class='container'>
            <header class="header">
                <?php
                //Condicional para saber si la cookie de ultima visita existe y mostrarle dicha indormación al usuario
                if (isset($lastVisit)) {
                    ?>
                    <p>Su ultima visita fue <?= $lastVisit ?></p>
                    <?php
                }
                ?>


                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                    <button class="btn bg-primary" name="logout" type="submit" value="logout">Log out </button>
                </form>

            </header>
            <?php
            if ($rol == 0) {
                require '../pages/crudRol0.php';
            } else {
                if ($rol == 1) {
                    require '../pages/crudRol1.php';
                }
            }
            ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        </div>
    </body>
</html>