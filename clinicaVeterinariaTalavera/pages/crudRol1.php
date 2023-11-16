<?php
require '../files/functions.php';
$user = 'Laura';
$bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
//comprobamos si existe usuario desde ela rchivo requerido anterior se maneja esta posibilidad y se guarda en una variable
if (isset($user)) {
    //rezlimaos la consulta para sacar por pantalla los nombres de todos los usuarios de la veterinaria
    $sql = "select nombre,apellido,telefono,direccion FROM personas";
    $clients = selectValues($bd, $sql);
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
        <link rel="stylesheet" href="../css/styles.css">
        <title>Clinica Veterinaria Talavera</title>
    </head>
    <body>
        <header class= "info">
            <h1 class="title">Aplicación creada por:</h1>
            <div class="personal_data">
                <p>Nombre: Juan Ferrón Paterna</p>
                <p>Nombre: Javier Rocha </p>
                <p>Curso: 2º DAW</p>
                <p>Módulo: Desarrollo web en entorno servidor (DWES)</p>
            </div>
        </header>
        <div>
            <main class="main">
                <?php
                //Controlamos si existe la consulta si a devulto algo para poder controlar los errores
                if (isset($clients)) {
                    ?>
                    <h2>Estas son tus mascotas <?= $user ?></h2>
                    <table border="1">
                        <thead>
                            <tr>
                                <th class="td">Nombre</th>
                                <th class="td">Apellidos</th>
                                <th class="td">Teléfono</th>
                                <th class="td">Dirección</th>
                                <th class="td">Mascotas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //recorremos al arrya de los registros que de la consulta y creamos un tr para cada mascota
                            foreach ($clients as $client) {
                                ?>
                                <tr>
                                    <?php
                                    //recorremos el array para cada registro en este caso para cada mascota

                                    foreach ($client as $key => $value) {
                                        //creamos un td para imprimir cada uno de los valores de las columnas, dentro metremos un input para hacer el campo editable
                                        ?>

                                        <td class="td">
                                            <input name="name_cliente" disabled value="<?= $value ?>">
                                        </td>

                                        <?php
                                    }
                                    //Realizamos una conexión a la base de datos para obtener el objeto con el que realizaremos la consulta
                                    $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
                                    //generamos una consulta para las masscotas de cada cliente
                                    //guardo en una variable el nombre del cliente sobre el que se genera la consulta
                                    $client_name = $client['nombre'];
                                    $mascotas = selectValues($bd, "SELECT nombre FROM mascotas WHERE dni_propietario in (SELECT dni FROM personas WHERE nombre = '$client_name')");
                                    //Cerramos la conexión con la base de datos
                                    $bd = null;
                                    if (isset($mascotas)) {
                                        //recoremos el array con los registros de las mascotas de cada cliente
                                        //creamos un select para guardad todas los nombres de las mascotas en options
                                        ?>
                                        <td class="td">
                                            <select class="select" name="puppies">
                                                <?php
                                                foreach ($mascotas as $mascota) {
                                                    //para cada creamos una etiqueta option y la rellenamos con el nombre de la mascota del cliente
                                                    ?>
                                                    <option class="option" name="puppy" value="<?= $mascota['nombre'] ?>"><?= $mascota['nombre'] ?></option>
                                                    <?php
                                                }
                                                //cerramos la etiqueta select
                                                ?>
                                            </select>
                                        </td>
                                        <?php
                                        //Creamos dos etiquetas <td> para guardar lso botones de editar o borrar y dentro meteremos un formulario
                                        ?>
                                        <td>
                                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                                <button type="submit" name="update" value="<?= $client['nombre'] ?>">Modificar</button>
                                                <button type="submit" name="delete" value="<?= $client['nombre'] ?>">Eliminar</button>
                                            </form>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
    <!--<td class="td">Hola</th>-->

                        </tbody>
                    </table>
                    <?php
                }//final if
                ?>

            </main>
            <footer class="footer">

            </footer>
        </div>                       
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>

