<?php
$bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
//comprobamos si existe usuario desde ela rchivo requerido anterior se maneja esta posibilidad y se guarda en una variable
if (isset($dni)) {
    //rezlimaos la consulta para sacar por pantalla los nombres de las mascotas del usuario para qeu pueda eleir una de ellas
    $sql = "select nombre,especie,raza,edad,fechaNacimiento,peso from mascotas where dni in (SELECT dni from personas where dni = ?)";
    $mascotas = selectValues($bd, $sql,array($dni));
}
//Cerramos la conexión con la base de datos 
$bd = null;
?>
<main class="main">
    <?php
        ?>
        <h2>Estas son las mascotas de <?= $user ?></h2>
        <table border="1">
            <thead>
                <tr
                    <th class="td">Nombre</th>
                    <th class="td">Especie</th>
                    <th class="td">Raza</th>
                    <th class="td">Edad</th>
                    <th class="td">Nacimiento</th>
                    <th class="td">Peso</th>
                    <th class="td">Vacunas</th>
                    <th class="td">Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //recorremos al arrya de los registros que de la consulta y creamos un tr para cada mascota
                foreach ($mascotas as $mascota) {
                    //en cada pasada del bucle controlamos que la varibale de coinicidencia $matches este a false cada vez que se itera un cliente
                    $matches = false;
                    ?>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                    <tr>
                        <?php
                        //recorremos el array para cada registro en este caso para cada mascota

                        foreach ($mascota as $key => $value) {
                            //creamos un td para imprimir cada uno de los valores de las columnas
                            if(isset($update)){//condicional para saber si existe la variable de update si se ha pulsado el boton de modificar
                                if($update)
                                enabledInput($key,$value,$mascota['nombre'],$update,$matches);
                            }else{
                               enabledInput($key,$value,$mascota['nombre'],null,$matches);  
                            }
                            //Input hidden para saber sobre que tabla hacemos referencia
                            createInput('mascotas', 'mascotas', '', false, true,'','','');
                        }
                        //Realizamos una conexión a la base de datos para obtener el objeto con el que realizaremos la consulta
                        $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
                        //generamos una consulta para las vacunas de cada mascota en la iteración del array
                        //guardo en una variable el nombre del animal en cada iteracion
                        $animal_name = $mascota['nombre'];
                        $sql = "SELECT nombre_vacuna, fecha_vacunacion FROM vacunas_perro WHERE codigo_animal in (SELECT codigo_animal FROM mascotas WHERE nombre = ?)";
                        $vacunas = selectValues($bd,$sql,array($animal_name));
                        //Cerramos la conexión con la base de datos
                        $bd = null;
                        if (isset($vacunas)) {
                            //recoremos el array con los registros de las vacunas de la mascota
                            //creamos un select para guardad todas las vaucnas como optiions
                            ?>
                            <td class="td">
                                <select class="select" name="vaccines">
                                    <?php
                                createVaccines($vacunas);
                                    //cerramos la etiqueta select
                                    ?>
                                </select>
                            </td>
                            <?php
                        }
                        if($rol == 1){
                            require '../files/buttons.php';
                        }
                        
                        
                        ?>
                    </tr>

        </form>
        <!--final del formualrio-->
                    <?php
                }
                ?>
            </tbody>
        </table>
        <!--button to go back to users if user is allowed by his rol-->
        <?php
        if($rol == 1){
            ?>
        <a class="btn bg-primary" href="./crud.php">Volver a los usuarios</a>
        <?php
        }
    ?>

</main>
<footer class="footer">

</footer>

