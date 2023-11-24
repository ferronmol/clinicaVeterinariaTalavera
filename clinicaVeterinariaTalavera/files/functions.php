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
function createUpdateStatement($table, $values) {
    try {
        //guardamos el dni para luego usarlo como condicion del where
        $dni = htmlspecialchars($values['dni']);
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
        echo $sql;
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
        $sql;
        if (is_numeric($value))
            $sql = "DELETE FROM $table WHERE dni = $value";
        else
            $sql = "DELETE FROM $table WHERE dni = '$value'";

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
function createInsertStatement($table, $values) {
    try {
        $sql = "INSERT INTO $table VALUES (";
        foreach ($values as $value) {
            if ($value != 'insert') {
                if (is_numeric($value)) {
                    $sql .= "$value, ";
                } else {
                    $sql .= "'$value', ";
                }
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
        <input class='form__input <?= $class ?>'  placeholder='<?= $placeholder ?>'  name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?> <?= $hidden ?>>
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
function createButton($name, $value, $content, $class = false) {
    $class = ($class) ? $class : "";
    ?>
    <button class='<?= $class ?> button' type="submit" name="<?= $name ?>" value="<?= $value ?>"><?= $content ?></button>
    <?php
}

function toGetType($value){
    if($value === 'email')
        return 'email';
    if($value === 'fechaNacimiento')
        return 'date';
    if($value === 'clave')
        return 'password';
    
    return 'text';
}

function createFormInsert() {
    $fields = ['dni', 'nombre', 'apellido', 'fechaNacimiento', 'email', 'telefono', 'direccion', 'rol', 'clave'];
    $type = '';
    ?>
    <tr class="tr">
        <?php
        foreach ($fields as $value) {
            if($value === 'rol'){
                createInput($value, '',toGetType($value), true, false, '', 0);
            }else if($value === 'clave'){
                //hay que hacer un metodo para cifrar la contraseña y mostrar axteriscos, a su vez esas claves tienen que meterse en un array o algo para tenerlas
                createInput($value, '',toGetType($value), false, false, '', $value);
            }else {
                createInput($value, '',toGetType($value), false, false, '', $value);
            }
                
            
            
                
        }
        ?>
        <td class="td">
            <?php
            createButton('insert', 'insert', 'Insertar', 'bg-primary')
            ?>
    </tr>
    <?php
}

//funtions about errors

function displayError($content) {
    ?>
    <p class='error'><?= $content ?></p>
    <?php
}
