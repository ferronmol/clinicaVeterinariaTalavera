<?php
require '../lib/allowControl.php';
?>
<main class="main">
    <?php ?>
    <h2>Usuarios de la Clínica</h2>
    <table border="1">
        <thead>
            <tr class='tr'>
                <th class="th">Nombre</th>
                <th class="th">Apellidos</th>
                <th class="th">Teléfono</th>
                <th class="th">Dirección</th>
                <th class="th">Mascotas</th>
                <th class="th">Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //recorremos al arrya de los registros que de la consulta y creamos un tr para cada mascota
            foreach ($clients as $client) {
                //en cada pasada del bucle controlamos que la varibale de coinicidencia $matches este a false cada vez que se itera un cliente
                $isMatchingToKey = false;
                //Creamos un formulario para guardar los inputs qeu generamos para cada cliente
                ?>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <tr class='tr'>
                    <?php
                    //recorremos el array para cada registro en este caso para cada mascota
                    $keyUpdatingElement = isset($keyUpdatingElement) ? $keyUpdatingElement : null;//Conditional to set value to update
                    foreach ($client as $key => $value) {
                        enabledInput($key, $value, $client['dni'], $keyUpdatingElement, $isMatchingToKey);
                    }
                    //Creamos un input hidden con el nombre de la tabla
                    createInput('users', 'users', '', false, true, '', '', '');
                    //Create statement 
                    $sql = "SELECT nombre FROM mascotas WHERE dni = ?";
                    //Calling function to get opotions values
                    $options = getOptionsValues($client['dni'], $sql);
                    
                    if (isset($options)) {
                        //recoremos el array con los registros de las mascotas de cada cliente
                        //creamos un select para guardad todas los nombres de las mascotas en options
                        ?>
                        <td class="td">
                            <select class="select" name="puppies">
                                <!--Llamamos a la función para generar todas las mascotas de cada uno de los clientes-->
                                <?php
                                createOption($options);
                                //cerramos la etiqueta select
                                ?>
                            </select>
                        </td>
                        <?php
                    }

                    require '../files/buttons.php';
                    ?>
                </tr>
            </form>

            <?php
        }
        ?>
        </tbody>
    </table>
    <!--final de la tabla de informacion de las cosnultas--> 
    <!--creación de tabla para la insercción de un nuevo cliente-->
    <h2>Insercción de un nuevo ususario</h2>
    <table border='1'>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <?php
            createFormInsert($updating, 'personas');
            ?>
        </form>
    </table>
    <h2>Inserción de una nueva msacota</h2>
    <table border='1'>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <?php
            createFormInsert($updating, 'mascotas');
            ?>
        </form>
    </table>
</main>
<footer class="footer">

</footer>

