<?php
$user = 'Laura';
$bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
//comprobamos si existe usuario desde ela rchivo requerido anterior se maneja esta posibilidad y se guarda en una variable
if (isset($dni)) {
    //rezlimaos la consulta para sacar por pantalla los nombres de las mascotas del usuario para qeu pueda eleir una de ellas
    $sql = "select nombre,especie,raza,edad,fechaNacimiento,peso from mascotas where dni_propietario in (SELECT dni from personas where dni = '$dni')";
    $mascotas = selectValues($bd, $sql);
}
//Cerramos la conexión con la base de datos 
$bd = null;
?>
<main class="main">
    <?php
    //Controlamos si existe la consulta si a devulto algo para poder controlar los errores
    if (isset($mascotas)) {
        ?>
        <h2>Estas son tus mascotas <?= $user ?></h2>
        <table border="1">
            <thead>
                <tr>
                    <th class="td">Nombre</th>
                    <th class="td">Especie</th>
                    <th class="td">Raza</th>
                    <th class="td">Edad</th>
                    <th class="td">Nacimiento</th>
                    <th class="td">Peso</th>
                    <th class="td">Vacunas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //recorremos al arrya de los registros que de la consulta y creamos un tr para cada mascota
                foreach ($mascotas as $mascota) {
                    ?>
                    <tr>
                        <?php
                        //recorremos el array para cada registro en este caso para cada mascota

                        foreach ($mascota as $key => $value) {
                            //creamos un td para imprimir cada uno de los valores de las columnas
                            ?>

                            <td class="td"><?= $value ?></td>

                            <?php
                        }
                        //Realizamos una conexión a la base de datos para obtener el objeto con el que realizaremos la consulta
                        $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
                        //generamos una consulta para las vacunas de cada mascota en la iteración del array
                        //guardo en una variable el nombre del animal en cada iteracion
                        $animal_name = $mascota['nombre'];
                        $vacunas = selectValues($bd, "SELECT nombre_vacuna, fecha_vacunacion FROM vacunas_perro WHERE codigo_animal in (SELECT codigo_animal FROM mascotas WHERE nombre = '$animal_name')");
                        //Cerramos la conexión con la base de datos
                        $bd = null;
                        if (isset($vacunas)) {
                            //recoremos el array con los registros de las vacunas de la mascota
                            //creamos un select para guardad todas las vaucnas como optiions
                            ?>
                            <td class="td">
                                <select class="select" name="vaccines">
                                    <?php
//                                                createOption($vacunas);
                                    foreach ($vacunas as $vacuna) {
                                        //para cada creamos una etiqueta option y la rellenamos con el nmbre de la vacuna
                                        ?>
                                        <option class="option" name="vaccine" value="">
                                            <?php
                                            //Utilizamos el bucle for each para rellenar el contenido del opotion
                                            foreach ($vacuna as $key => $value) {
                                                ?>
                                                <?= $value . ' ' ?>
                                                <?php
                                            }
                                            //Cerramos la etiqueta option
                                            ?>
                                        </option>
                                        <?php
                                    }
                                    //cerramos la etiqueta select
                                    ?>
                                </select>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }//final if
    else {
        ?>
        <p>Usted no tienes registradas mascotas aún dirijase a una de nuestras tiendas físicas para proceder al registro de tus mascotas</p>
        <?php
    }
    ?>

</main>
<footer class="footer">

</footer>

