<?php
if (isset($user)) {
//declaramos una varible para poder controlar cuando un cliente esta bajo una accion del ususario
    $matches = false;
//con esta variable vamos a controlar si el usuario esta en un update para poner a disabled todos los elementos de la pantalla menos los elementos afectados por el update
    $updating = false;
//Declaramos una variable para saber cuando solo tenemos que cargar el formulario de confirmacion
    $confirmationActive = false;
    $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
//comprobamos si existe usuario desde ela rchivo requerido anterior se maneja esta posibilidad y se guarda en una variablee
//filtramos el formulario que nos viene por post
    if (filter_input_array(INPUT_POST)) {
        if (isset($_POST['update'])) { //condicional para cuando recibimos una peticion de modificación
            $update = htmlspecialchars($_POST['update']);
            $updating = true;
        }
        if (isset($_POST['delete'])) { //condicional para cuando recibimos una peticion de eliminación
            $delete = htmlspecialchars($_POST['delete']);
            managePost($delete, 'createDeleteStatement', $confirmationActive, $bd, $_POST,'personas');
        }
        if (isset($_POST['commit'])) { //condicional para cuando recibimos una peticion de confirmación
            $commit = htmlspecialchars($_POST['commit']);
            managePost($commit, 'createUpdateStatement', $confirmationActive, $bd, $_POST,'personas');
        }
        if (isset($_POST['insert'])) { //condicional para cuando recibimos una peticion de insercción
            var_dump($_POST);
            $insert = htmlspecialchars($_POST['insert']);
            //Condicional para detectar si se quiere insertar una mascotas
            managePost($insert, 'createInsertStatement', $confirmationActive, $bd, $_POST,'');
        }
    }
    //rezlimaos la consulta para sacar por pantalla los nombres de todos los usuarios de la veterinaria
    $sql = "select dni,nombre,apellido,telefono,direccion FROM personas WHERE rol = ?";
    $clients = selectValues($bd, $sql, 0);
}
//Cerramos la conexión con la base de datos 
$bd = null;
?>
<main class="main">
    <?php
    if ($confirmationActive) {//condicinal para saber cuando se a iniciado una petición de confirmación para la edición o modificación de datos de la BBDD
        //Cargamos el fichero de coanfirmacionForm.php
        require './confirmationForm.php';
    } else {//Condicional para cargar la página con las tablas si la petición de cofirmación no existe
        ?>
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
                            //controlamos con este  if que el dni del cliente no se muestre por pantalla
                            if ($key != 'dni') {
                                //creamos un td para imprimir cada uno de los valores de las columnas, dentro metremos un input para hacer el campo editable
                                //Controlamos si existe la consulta si a devulto algo para poder controlar los errores,
                                if (isset($update)) {
                                    if ($update === $client['dni']) {
                                        //le damos a la varibale matches el valor true para mas abajo generar un input confirmas cambios en lugar de modificar
                                        $matches = true;
                                        //Llamamos a una funcion para generar un elemento pasandole valores para name,value y disabled
                                        createInput($key, $value, toGetType($value),false,false,'','',getMaxLength($value));
                                    } else {
                                        //este condicional nos va a controlar que los input se generen disabled para no poder modificarlo y lo creamos llamando ala funcion para crear el elemento
                                        createInput($key, $value, toGetType($value), true,false,'','',getMaxLength($value));
                                    }
                                } else {
                                    //este condicional nos va a controlar que los input se generen disabled para no poder modificarlo
                                    createInput('name_cliente', $value, toGetType($value), true,false,'','',getMaxLength($value));
                                }
//                                function createInput($name, $value, $type, $disabled = false, $hidden = false, $class = '', $placeholder = '',$maxlength)
                            }
                        }
                        //Realizamos una conexión a la base de datos para obtener el objeto con el que realizaremos la consulta
                        $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
                        //generamos una consulta para las masscotas de cada cliente
                        //guardo en una variable el nombre del cliente sobre el que se genera la consulta
                        $client_name = $client['nombre'];
                        $mascotas = selectValues($bd, "SELECT nombre FROM mascotas WHERE dni_propietario in (SELECT dni FROM personas WHERE nombre = ?)", $client_name);
                        //Cerramos la conexión con la base de datos
                        $bd = null;
                        if (isset($mascotas)) {
                            //recoremos el array con los registros de las mascotas de cada cliente
                            //creamos un select para guardad todas los nombres de las mascotas en options
                            ?>
                            <td class="td">
                                <select class="select" name="puppies">
                                    <!--Llamamos a la función para generar todas las mascotas de cada uno de los clientes-->
                                    <?php
                                    createOption($mascotas);
                                    //cerramos la etiqueta select
                                    ?>
                                </select>
                            </td>
                            <?php
                            //Creamos dos etiquetas <td> para guardar lso botones de editar o borrar y dentro meteremos un formulario
                            ?>
                            <td class='td'>

                                <?php
                                //condicinal para controlar que input se va a generar dependiendo de si se ha encontrado el cliente a modificar

                                if ($matches) {
                                    //Llamamos a la funcion para generar un input mandandole parametros especificos
                                    createInput('dni', $client['dni'], 'text', true, true, 'hidden','','',getMaxLength($value));
                                    //Llamamos a la funcion para generar un boton de confirmar
                                    createButton('commit', $client['dni'], 'Confirmar', 'bg-success');
                                } else {
                                    //Llamamos la afuncion para crear un boton para modificar
                                    createButton('update', $client['dni'], 'Modificar', 'bg-warning');
                                }
                                //Llamamos a una funcion para generar el boton de eliminar
                                createButton('delete', $client['dni'], 'Eliminar', 'bg-danger', $updating);
                                ?>
                            </td>
                            <?php
                        }
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
                createFormInsert($updating,'personas');
                ?>
            </form>
        </table>
        <h2>Inserción de una nueva msacota</h2>
        <table border='1'>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <?php
                createFormInsert($updating,'mascotas');
                ?>
            </form>
        </table>
        <?php
    }//Cierre del else principal que nos carga la pagina cuanso no hay formulario de confirmacion
    ?>
</main>
<footer class="footer">

</footer>

