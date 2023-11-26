
<?php
require '../files/functions.php';
//iniciamos sesion y guardamos el nombre de susuario qeu tenemos en la sesion en una variable una vz filtrado
session_start();


//varible booleana para guardar si hay errores a la hora de acceder a la pagina
$error = false;
$rol = 1;
//$dni = '123456789A';
//$dni = '04555666G';
$dni = '987654321';
$lastVisit = isset($_COOKIE[$dni]) ? $_COOKIE[$dni] : null;
if (isset($_SESSION['user']) && isset($_SESSION['rol']) && isset($_SESSION['dni'])) {
    $user = htmlspecialchars($_POST['user']);
    $rol = htmlspecialchars($_POST['rol']);
    $dni = htmlspecialchars($_POST['dni']);
}

//condicional para saber si existe esta cookie, si ha expirado o el la primera vex que entra a la página
////DESBLOQUEAT ESTE CODIGO PARA LA REALIZACION DE COOKIES DE SEXPIRACION DE SESION

//$sessionCookie = isset($_COOKIE['seesionCookie']) ? $_COOKIE['sessionCookie'] : null; 

//Condicinal para saber si se ha pulsado el boton de cerrar sesión
if (isset($_POST['logout'])) { //falta meter en la condicin un or para cuando no existe ls seesion cookie
    echo 'entro en el condicinal';
    require 'logOut.php';
}
//condicional para saber si la cookie de sesion ha expirado y preguntar al usuario si quiere ectender su sesión
if (!isset($sessionCookie)) {
    //TENEMOS QUE LLAMAR A UN FIHCERO QUE NOS GENERE UN FORMULARIO DE EXTENSION DE SESION PARA GENERAR UNA NUEVA COOCKIE CON UNA DURACCION DE UN MINUTO
}
//DESBLOQUEAR PARA QUE REDIRIGA AL INDEX EN EL CASO DE QUE NO SE CUMPLAN CON LOS REQUISITOS
//else{
//    header('Location: ../index.php?error');
//}
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
            if ($rol === 0) {
                require '../pages/crudRol0.php';
            } else {
                if ($rol === 1) {
                    require '../pages/crudRol1.php';
                }
            }
            ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        </div>
    </body>
</html>