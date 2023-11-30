
<main class="main">
    <?php?>
        <h2>Usuarios de la Clínica</h2>
        <table border="1">
            <thead>
                <tr class='tr'>
                        <th class="td">Nombre</th>
                        <th class="td">Apellidos</th>
                        <th class="td">Teléfono</th>
                        <th class="td">Dirección</th>
                        <th class="td">Mascotas</th>
                        <th class="td">Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //recorremos al arrya de los registros que de la consulta y creamos un tr para cada mascota
                foreach ($clients as $client) {
                    //en cada pasada del bucle controlamos que la varibale de coinicidencia $matches este a false cada vez que se itera un cliente
                    $matches = false;
                    //Creamos un formulario para guardar los inputs qeu generamos para cada cliente
                    ?>
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                    <tr class='tr'>
                        <?php
                        //recorremos el array para cada registro en este caso para cada mascota
                        foreach ($client as $key => $value) {
                           //creamos un td para imprimir cada uno de los valores de las columnas
                            if(isset($update)){//condicional para saber si existe la variable de update si se ha pulsado el boton de modificar
                                if($update)
                                enabledInput($key,$value,$client['dni'],$update,$matches);
                            }else{
                               enabledInput($key,$value,$client['dni'],null,$matches);  
                            }
                            //Input hidden para saber sobre que tabla hacemos referencia
                            createInput('users', 'users', '', false, true,'','','');
                        }
                        //Realizamos una conexión a la base de datos para obtener el objeto con el que realizaremos la consulta
                        $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
                            //generamos una consulta para las masscotas de cada cliente
                            //guardo en una variable el nombre del cliente sobre el que se genera la consulta
                            $condition = $client['nombre'];
                            $options = selectValues($bd, "SELECT nombre FROM mascotas WHERE dni in (SELECT dni FROM personas WHERE nombre = ?)", array($condition));
                        //Cerramos la conexión con la base de datos
                        $bd = null;
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

