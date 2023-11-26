<?php

//function about CRUD************************************************************************************************
/**
 * funtion to connect to data base
 * 
 * @param string $cadena
 * @param strig $user
 * @param string $password
 * @return \PDO
 */
function connectionBBDD($cadena, $user = 0, $password = 0) {
    try {
        $bd = new PDO($cadena, $user, $password);
        return $bd;
    } catch (Exception $ex) {
        displayError('La apicación esta en labores de mantenimiento');
    }
    
}

/**
 * funtion to get values by select statement and return it
 * 
 * @param PDO $bd
 * @param string $sql
 * @return type PDO
 */
function selectValues($bd, $sql) {
    try {
          $values = $bd->query($sql);
//realizamos ese código apra solo coger el array asociativo de la consulta
    $values->setFetchMode(PDO::FETCH_ASSOC);
    return $values;
    } catch (Exception $exc) {
        displayError('La aplicación esta en labores de mantenimiento');
    }
}

/**
 * function to make a statement given as paramaeter into data base given as parameter
 * 
 * @param PDO $bd
 * @param string $sql
 */
function statement($bd, $sql) {
    $bd->query($sql);
}

/**
 * funtion to craeate a update statement by taking values from variables given as parameter
 * 
 * @param string $table
 * @param Array $values
 * @return string
 */
function createUpdateStatement($bd,$table, $values) {
    try {
        //guardamos el dni para luego usarlo como condicion del where
        $dni = htmlspecialchars($values['commit']);
//guardamos en un array nuevo solo los valores que vamos a necesitar
        $newValues = array_slice($values, 0, 4);
//inicializamos la varible sql para ir generando la consulta acumulando los string
        $sql = "UPDATE $table SET ";
        foreach ($newValues as $key => $value) {
            $value = htmlspecialchars($value);
//Condicinal para saber si el valor es numerico o no
            if (is_numeric($value)) {
                $sql .= "$key = $value, ";
            } else {
                $sql .= "$key = '$value', ";
            }
        }
//Llamamos a etsa funcion para poder quitar la ultima coma y el espacio sel ultimo parametro de la consulta
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE  dni = '$dni';";
        return $sql;
    } catch (Exception $exc) {
        displayError('Los datos de la modificacion no son correctos, revisa que los campos cumplan con los requisitos especificados');
    }
}

/**
 * function to create a sentence depending of type of value variable
 * 
 * @param PDO $bd
 * @param string $table
 * @param string or number $value
 */
function createDeleteStatement($bd, $table, $value) {
    try {
        $dni = $value['delete'];
        $sql = "DELETE FROM $table WHERE dni = '$dni'";
        return $sql;
    } catch (Exception $exc) {
        displayError('La eliminación no se ha podido realizar correctamente, intentalo de nuevo');
    }
}

/**
 * function to create a insert statement by fill frilds taking values from array given as parameter
 * 
 * @param string $table
 * @param Array $values
 * @return string
 */
function createInsertStatement($bd,$table, $values) {
    //Realizamos esta operacion para quitarle el ultimo elemento del array de values que nos pasan como parametro 
    $values = array_slice($values, 0,9);
    try {
        $sql = "INSERT INTO $table VALUES (";
        foreach ($values as $value) {
            if ($value != 'insert') {
                if($value === 'rol')
                    $sql .= "$value, ";
                else
                    $sql .= "'$value', ";
            }
        }
        //Hacemos este metodo para eliminar la coma y el espacio del ultimo valor de la consulta
        $sql = substr($sql, 0, -2);
        $sql .= ");";
        return $sql;
    } catch (Exception $exc) {
        displayError('Los datos de la insercción no son corerctos, revisa que los campos cumplan con los requisitos especificados');
    }
}

//funtions about create element****************************************************************************************************************************************
/**
 * function to create input element snd set values given as parameters into it
 * 
 * @param string $name
 * @param string $value
 * @param boolean $disabled
 * @param boolean $hidden
 */
function createInput($name, $value,$type, $disabled = false, $hidden = false, $class = '', $placeholder = '') {
    $disabled = ($disabled) ? 'disabled' : '';
    $hidden = ($hidden) ? 'hidden' : '';
    if ($hidden === 'hidden') {
        ?>
<input class='form__input <?= $class ?>' type='<?= $type ?>'  placeholder='<?= $placeholder ?>'  name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?> <?= $hidden ?>>
        <?php
    } else {
        ?>
        <td class="td">
            <input class='form__input' type='<?= $type ?>' <?= $class ?>'  placeholder='<?= $placeholder ?>'  name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?>>
        </td>
        <?php
    }
}

/**
 * function to generate as many options element as length array be
 * 
 * @param Array $mascotas
 */
function createOption($mascotas) {
    foreach ($mascotas as $mascota) {
        //para cada creamos una etiqueta option y la rellenamos con el nombre de la mascota del cliente
        ?>
        <option class="option" name="puppy" value="<?= $mascota['nombre'] ?>"><?= $mascota['nombre'] ?></option>
        <?php
    }
}

/**
 * function to create a button html element and set values given as parameter into it
 * 
 * @param string $name
 * @param string $value
 * @param string $content
 * @param string $class
 */
function createButton($name, $value, $content, $class = false,$disabled = false) {
    $class = ($class) ? $class : "";
    $disabled = ($disabled) ? 'disabled' : "";
    ?>
    <button class='<?= $class ?> button' type="submit" name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?>><?= $content ?></button>
    <?php
}
/**
 * funtion to get the correct type for a input based on s atrign given as paramenter
 * 
 * @param string $value
 * @return string
 */
function toGetType($value){
    if($value === 'email')
        return 'email';
    if($value === 'fechaNacimiento')
        return 'date';
    if($value === 'clave')
        return 'password';
    
    return 'text';
}
/**
 * funtion to create a form for a insert action
 * 
 * @param string $disabled
 */
function createFormInsert($disabled) {
    $fields = ['dni', 'nombre', 'apellido', 'fechaNacimiento', 'email', 'telefono', 'direccion', 'rol', 'clave'];
    $type = '';
    
    ?>
    <tr class="tr">
        <?php
        foreach ($fields as $value) {
            if($value === 'rol'){
                createInput($value, 0,toGetType($value), false, false, '', 0);
            }else if($value === 'clave'){
                //hay que hacer un metodo para cifrar la contraseña y mostrar axteriscos, a su vez esas claves tienen que meterse en un array o algo para tenerlas
                createInput($value, '',toGetType($value), $disabled, false, '', $value);
            }else {
                createInput($value, '',toGetType($value), $disabled, false, '', $value);
            }     
        }
        ?>
        <td class="td">
            <?php
            createButton('insert', 'insert', 'Insertar', 'bg-primary',$disabled);
            ?>
    </tr>
    <?php
}

//funtions about errors
/**
 * funtion to shoe a error put in a p tag a explanation for the error given as parameter
 * 
 * @param string $content
 */
function displayError($content) {
    ?>
    <p class='error'><?= $content ?></p>
    <?php
}
//functions about cookies

/** function to callign other funtion to manage teh type of action user wants to complete
 * 
 * @param string $action
 * @param string $statement name of a function
 * @param bool $confirmationActive
 * @param PDO $bd database object
 * @param Array $values  $_POST global variable
 */
function managePost($action,$statement,&$confirmationActive,$bd,$values){
            //Llamamos a una funcion para borrar los datos
            
             if ($action === 'yes') {//condicional para saber si el usuario confirma la modificación de los datos
                //Deserialización del array que nos llega desde el formualrio de confirmación
                $clearValues = unserialize(base64_decode($values['array']));
                //Llamamos a la función para crear una consulta de delete y lo que nos devulve esa función se lo pasamos
                //como parametro a ala función padre apra generar la consulta a la base de datos
                statement($bd, $statement($bd, 'personas', $clearValues));
            }
            if ($action !== 'yes' && $action !== 'no') {
                //Le damos el valor true a la varibale porque la confirmacion en estos momento esta activa
                $confirmationActive = true;
            }
    
}