<?php
require '../lib/allowControl.php';
?>

<main class="main">
    <h2 class="title-user">Estas son las mascotas de <?= $user ?></h2>
    <table border="1">
        <thead>
            <tr class='tr'>
                <th class="td">Código</th>
                <th class="td">Nombre</th>
                <th class="td">Raza</th>
                <th class="td">Edad</th>
                <th class="td">Peso</th>
                <th class="td">Vacunas</th>
                <?php
                if ($rol == 1) {
                    echo '<th class="td">Options</th>';
                }
                ?>

            </tr>
        </thead>
        <tbody>
            <?php
            //recorremos al arrya de los registros que de la consulta y creamos un tr para cada mascota
            //Condicinal para saber si la varibale masxotas es un array con valores
            if (is_array($mascotas)) {
                foreach ($mascotas as $mascota) {
                    //en cada pasada del bucle controlamos que la varibale de coinicidencia $matches este a false cada vez que se itera un cliente
                    $isMatchingToKey = false;
                    ?>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                    <tr>
                        <?php
                        //recorremos el array para cada registro en este caso para cada mascota
                        $keyUpdatingElement = isset($keyUpdatingElement) ? $keyUpdatingElement : null; //Conditional to set value to update
                        foreach ($mascota as $key => $value) {
                            enabledInput($key, $value, $mascota['nombre'], $keyUpdatingElement, $isMatchingToKey);
                            //Input hidden para saber sobre que tabla hacemos referencia
                        }
                        //Creamos un input hidden con el valor del nombre de la tabla
                        createInput('mascotas', 'mascotas', '', false, true, '', '', '');
                        //Create statement 
                        $sql = "SELECT nombre_vacuna, fecha_vacunacion FROM vacunas_perro WHERE codigo_animal = ?";
                        //Calling function to get opotions values
                        $options = getOptionsValues($mascota['codigo_animal'], $sql);
                        if (isset($options)) {
                            //recoremos el array con los registros de las vacunas de la mascota
                            //creamos un select para guardad todas las vaucnas como optiions
                            ?>
                            <td class="td">
                                <select class="select" name="vaccines">
                                    <?php
                                    createVaccines($options);
                                    //cerramos la etiqueta select
                                    ?>
                                </select>
                            </td>
                            <?php
                        }
                        if ($rol == 1) {
                            require '../files/buttons.php';
                        }
                        ?>
                    </tr>

                </form>
                <!--final del formualrio-->
                <?php
            }//Final del for each para mascotas
        }//Final del condicional para saber si mascotas es un array
        ?>
        </tbody>
    </table>
    <!--button to go back to users if user is allowed by his rol-->
    <?php
    if ($rol == 1) {
        ?>
        <a class="btn bg-primary" href="./crud.php">Volver a los usuarios</a>
        <?php
    }
    ?>

</main>
<footer class="footer">

</footer>

