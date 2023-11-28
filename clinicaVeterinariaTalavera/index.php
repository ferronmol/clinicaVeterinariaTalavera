<?php
require './files/functions.php';
//Lamada a función para crear la base de datos sino existe, usamos dentro de un condicional porque nos devolvra true si la base de datos no existe y a sido creada
//para generar las tablas con los datos para tener una carga inicial
if(createDataBase('prueba')){
    try{
        //nos conectamos a la base de detos creada
    $bd = connectionBBDD('mysql:dbname=prueba;host=127.0.0.1', 'root', '');
    //Vamos a generar una consulta masiva con el metodo que nos devuleve dicho script sql ya creado y hacemos una consulta con ello
    $bd->query(getCreateScript());
    } catch (Exception $ex) {
        displayError('Vaya, parece que estamos en labores de mantenimiento, intentelo de nuevo más tarde');
    }
    
}
//Llamada a función para generar las tablas con los datos
//NOS TENEMOS QUE CONECTAR A LA BASE DE DATOS PRUEBA QUE CREO QUE LO HACE POR DEFECTO, Y LLAMAR A LA FUNCION stattement PARA CREAR UNA CONSUTLA PASANDO COMO PARAMETRO LA FUNCION
//getCreatScript QUE NOS PROPORCIONA EL SCRIPT ENTERO PARA CREAR LA BASE DE DATOS ENTERA, TODO ESTO EN EL CASO DE QUE NO ECXISTA PARA NO MACHCAR LOS VALORES QUE YA AHAYA GURDADOS
session_start();
//$dni = '123456789A';
//$dni = '04555666G';
$dni = '987654321B';
$rol = 1;
$user = 'Laura';
$_SESSION['dni'] = $dni;
$_SESSION['rol'] = $rol;
$_SESSION['user'] = $user;
//Creación de cookie de seesion con 1o minutos de duración usando la función hash() para encriptar los datos sensibles
$content = hash('sha256',session_id().$dni);
setcookie($content,'session',time() + 1 * 600,'/');
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/css/style.css">
        <title>Clinica Veterinaria Talavera</title>
    </head>
    <body>
        <a href="./pages/crud.php">Ir a crud</a>
        <header class= "info">
            <h1 class="title">aplicación creada por:</h1>
            <div class="personal_data">
                <p>Nombre: Juan Ferrón Paterna</p>
                <p>Nombre: Javier Rocha </p>
                <p>Curso: 2º DAW</p>
                <p>Módulo: Desarrollo web en entorno servidor (DWES)</p>
            </div>
        </header>

        <div>
          
        </div>                       
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>
