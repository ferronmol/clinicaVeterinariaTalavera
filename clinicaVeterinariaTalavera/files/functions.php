<?php
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
    } catch (Exception $ex) {
        'La conexión no se pudo realizar correctamente, intentlo más tarde';
    }
    return $bd;
}
/**
 * funtion to get vvalues by select statement and return it
 * 
 * @param PDO $bd
 * @param string $sql
 * @return type PDO
 */
function selectValues($bd, $sql) {
    $values = $bd->query($sql);
//realizamos ese código apra solo coger el array asociativo de la consulta
    $values->setFetchMode(PDO::FETCH_ASSOC);
    return $values;
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
//guardamos el dni para luego usarlo como condicion del where
    $dni = htmlspecialchars($values['dni']);
//guardamos en un array nuevo solo los valores que vamos a necesitar
    $newValues = array_slice($values, 1, 4);
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
}
/**
 * function to create a sentence depending of type of value variable
 * 
 * @param PDO $bd
 * @param string $table
 * @param string or number $value
 */
function createDeleteStatement($bd, $table, $value) {
    $sql;
    if (is_numeric($value))
        $sql = "DELETE FROM $table WHERE dni = $value";
    else
        $sql = "DELETE FROM $table WHERE dni = '$value'";
    
    return $sql;
}
/**
 * function to create input element snd set values given as parameters into it
 * 
 * @param string $name
 * @param string $value
 * @param boolean $disabled
 * @param boolean $hidden
 */
function createInput($name, $value, $disabled = false, $hidden = false) {
    $disabled = ($disabled) ? 'disabled' : '';
    $hidden = ($hidden) ? 'hidden' : '';
    ?>

    <td class="td">
        <input class='form__input' name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?> <?= $hidden ?>>
    </td>

    <?php
}
/**
 * function to generate as many options element as length array be
 * 
 * @param Array $mascotas
 */
function generatePuppies($mascotas) {
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
