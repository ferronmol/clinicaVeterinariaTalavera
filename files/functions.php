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
        displayError('La aplicación esta en labores de mantenimiento');
        exit();
    }
}

/**
 * funtion to get values by select statement and return it
 * 
 * @param PDO $bd
 * @param string $sql
 * @return type PDO
 */
function getSelectStatementValues($bd, $sql, $array) {
    try {
        $values = $bd->prepare($sql);
        $values->execute($array);
//realizamos ese código apra solo coger el array asociativo de la consulta
         // Verifica si hay al menos una fila de resultados
         if ($values->rowCount() > 0) {
            // Realizamos ese código para solo coger el array asociativo de la consulta
            $values->setFetchMode(PDO::FETCH_ASSOC);
            return $values;
        } else {
            // No hay resultados, puedes manejar esta situación según tus necesidades
            return false;
        }
    } catch (Exception $exc) {
        displayError('La aplicación está en labores de mantenimiento');
        // Puedes agregar más detalles si es necesario
        echo '<p class="error">Detalles: ' . $exc->getMessage() . '</p>';
        // Puedes registrar el error en un archivo de registro o en otro sistema de seguimiento de errores si es necesario
    }
}

/**
 * function to make a statement given as paramaeter into data base given as parameter
 * 
 * @param PDO $bd
 * @param string $sql
 */
function statement($bd, $sql) {
    try {
        return $bd->query($sql);
    } catch (Exception $ex) {
        $errorCode = $ex->getCode();

        if ($errorCode == 23000) {//this error is to manage duplicate values as primary key
            displayError('Vaya, parece que ya hay un registro en la tabla con esa identificación');
            displayError('O si estas tratando de agragar una mascota cerciorate que el duenio esta registrado como cliente');
        }
        if ($errorCode == '21S01') {
            displayError('Alguno de tus datos excede el numero de caracteres, echa un vistazo a los valores permitidos');
        }
        if ($errorCode === 23503) {//this error is to manage foreign key violation
            displayError('Vaya, la mascota que estas intentando agregar no corredponde a ningún duenio registrado');
        }
    }
}

/**
 * function to return keyword based in array key matches from values
 * 
 * @param type $array array of values
 * @return string keyWord to use in a statement as conditional
 */
function getKeyWord($array) {
    if (isset($array['mascotas'])) {
        return 'nombre';
    }
    if (isset($array['users'])) {
        return 'dni';
    }
}

/**
 * funtion to craeate a update statement by taking values from variables given as parameter
 * 
 * @param string $table
 * @param Array $values
 * @return string
 */
function createUpdateStatement($bd, $table, $values) {
    try {
        //Declaramos una varibale para guardar la palabra clave, llamamos a la función para sonseguir esa palaba en función del array de valores que le mandamos
        $keyWord = getKeyWord($values);

        //guardamos el dni para luego usarlo como condicion del where
        $condition = htmlspecialchars($values['commit']);
//guardamos en un array nuevo solo los valores que vamos a necesitar
        $newValues = array_slice($values, 2, 4);
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
        $sql .= " WHERE  $keyWord = '$condition';";
        echo 'esta es la consulta ' . $sql;
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
function createDeleteStatement($bd, $table, $values) {
    try {
        //Declaramos una varibale para guardar la palabra clave, llamamos a la función para sonseguir esa palaba en función del array de valores que le mandamos
        $keyWord = getKeyWord($values);
        $key = $values['delete'];
        $sql = "DELETE FROM $table WHERE $keyWord = '$key'";
        echo 'consulta de bprrado ' . $sql;
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
function createInsertStatement($bd, $table, $values) {
    $table = 'personas';
    //Condicional para saber a que tabla se esta haciendo referncia, mirando que valor tiene el boton de submit del formulario de insercción
    if ($values['insert'] == 'insertMascota') {
        $table = 'mascotas';
    }
    //Realizamos esta operacion para quitarle el ultimo elemento del array de values que nos pasan como parametro 
    $values = array_slice($values, 0, 9);
    try {
        $sql = "INSERT INTO $table VALUES (";
        foreach ($values as $value) {
            if ($value != 'insert') {
                if ($value === 'rol') {
                    $sql .= "$value, ";
                } else if ($value == 'clave') {//NO ME ENTRA EN EL CONDICINAL CUANDO EL VALOR VALE CLAVE HAY QUE MIRAR QUE VALE EN LA ITERACION
                    $value = hash('sha256', $value);
                    $sql .= "'$value', ";
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

/**
 * function to return if refernce table is mascotas or personas
 * 
 * @param array $values values form $_POST
 * @param number $rol rol from a user
 * @return bool
 */
function getReferenceTable($values, $rol) {
    if (isset($values['mascotas']))
        return 'mascotas';
    if (isset($values['users']))
        return 'personas';
    if ($rol == 1)
        return 'personas';
    if ($rol == 0)
        return 'mascotas';
}

function getOptionsValues($keyword, $sql) {
    $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
    $options = getSelectStatementValues($bd, $sql, array($keyword));
    //Cerramos la conexión con la base de datos
    $bd = null;
    return $options;
}

function getAction($values){
    if(isset($values['delete'])){
        return [$values['delete'],'createDeleteStatement'];
    }
    if(isset($values['insert'])){
        return [$values['insert'],'createInsertStatement'];
    }
    if(isset($values['commit'])){
        return [$values['commit'],'createUpdateStatement'];
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
function createInput($name, $value, $type, $disabled = false, $hidden = false, $class = '', $placeholder = '', $maxlength = '') {
    $disabled = ($disabled) ? 'disabled' : '';
    $hidden = ($hidden) ? 'hidden' : '';
    if ($hidden === 'hidden') {
        ?>
        <input class='form__input <?= $class ?>' maxlength="<?= $maxlength ?>" required type='<?= $type ?>'  placeholder='<?= $placeholder ?>'  name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?> <?= $hidden ?>>
        <?php
    } else {
        ?>
        <td class="td">
            <input class='form__input' maxlength="<?= $maxlength ?>" required type='<?= $type ?>' <?= $class ?>'  placeholder='<?= $placeholder ?>'  name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?>>
        </td>
        <?php
    }
}

function createVaccines($vaccines) {
    foreach ($vaccines as $vacuna) {
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
function createButton($name, $value, $content, $class = false, $disabled = false) {
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
function toGetType($value) {
    if ($value === 'email')
        return 'email';
    if ($value === 'fechaNacimiento')
        return 'date';
    if ($value === 'clave')
        return 'password';

    return 'text';
}

function getMaxLength($value) {
    if ($value == 'codigo_animal' || $value == 'edad' || $value == 'codigo_consulta')
        return '11';
    if ($value == 'dni')
        return '10';
    if ($value == 'telefono')
        return '15';
    if ($value == 'clave')
        return '16';
    if ($value == 'direccion')
        return '100';
    if ($value == 'peso')
        return '7';

    return '50';
}

/**
 * funtion to create a form for a insert action
 * 
 * @param string $disabled
 */
function createFormInsert($disabled, $table) {
    $personFileds = ['dni', 'nombre', 'apellido', 'fechaNacimiento', 'email', 'telefono', 'direccion', 'rol', 'clave'];
    $animalFields = ['codigo_animal', 'nombre', 'especie', 'raza', 'edad', 'dni', 'fechaNacimiento', 'peso', 'codigo_consulta'];
    $buttonValue = 'insertPersona';
    if ($table === 'personas') {
        $fields = $personFileds;
    } else {
        $fields = $animalFields;
        $buttonValue = 'insertMascota';
    }
    //conditional to get maxLength for any input

    $type = '';
    ?>
    <tr class="tr">
        <?php
        foreach ($fields as $value) {
            if ($value === 'rol') {
                //SI ESTE VALOR ES DISABLED NO SE ENVIA Y SI ES HIDDEN SE COLOCA EL PRIMERO Y DESCOLOCA TODO ,
                //SOLUCCION HACER UNA INSERCCION EN LA BASE DE DATOS REFERENCIADNO LOS CAMPOS,
                createInput($value, 0, toGetType($value), $disabled, false, '', 0, getMaxLength($value));
            } else if ($value === 'clave') {
                //hay que hacer un metodo para cifrar la contraseña y mostrar axteriscos, a su vez esas claves tienen que meterse en un array o algo para tenerlas
                createInput($value, '', toGetType($value), $disabled, false, '', $value, getMaxLength($value));
            } else {
                createInput($value, '', toGetType($value), $disabled, false, '', $value, getMaxLength($value));
            }
        }
        ?>
        <td class="td">
    <?php
    createButton('insert', $buttonValue, 'Insertar', 'bg-primary', $disabled);
    ?>
    </tr>
    <?php
}

//funtions about errors
/**
 * funtion to show a error put in a p tag a explanation for the error given as parameter
 * 
 * @param string $content
 */
function displayError($content) {
    ?>
    <p class='error'><?= $content ?></p>
    <?php
}

//functions about cookies********************************************************************************************************************

/**
 * Function to create a seesion cookie based in seesion_id and dni given as parameter a return name of newly created cookie
 * 
 * @param string $sessionID
 * @param string $dni
 * @return string cookie name
 */
function nameSessionCookie($sessionID, $dni) {
    return hash('sha256', $sessionID . $dni);
}

/** function to callign other funtion to manage teh type of action user wants to complete
 * 
 * @param string $action
 * @param string $statement name of a function
 * @param bool $confirmationActive
 * @param PDO $bd database object
 * @param Array $values  $_POST global variable
 */
function managePost($action, $statement, &$confirmationActive, $bd, &$values, $table) {
    //Llamamos a una funcion para borrar los datos

    if ($action === 'yes') {//condicional para saber si el usuario confirma la modificación de los datos
        //Deserialización del array que nos llega desde el formualrio de confirmación
        $values = unserialize(base64_decode($values['array']));
        //Llamamos a la función para crear una consulta de delete y lo que nos devulve esa función se lo pasamos
        //como parametro a ala función padre apra generar la consulta a la base de datos
        statement($bd, $statement($bd, $table, $values));
    }
    if ($action !== 'yes' && $action !== 'no') {
        //Le damos el valor true a la varibale porque la confirmacion en estos momento esta activa
        $confirmationActive = true;
    }
}

function enabledInput($key, $value, $keyword, $update = null, &$matches) {
    //controlamos con este  if que el dni del cliente no se muestre por pantalla
    if ($key != 'dni') {
        //creamos un td para imprimir cada uno de los valores de las columnas, dentro metremos un input para hacer el campo editable
        //Controlamos si existe la consulta si a devulto algo para poder controlar los errores,
        if (isset($update)) {
            if ($update === $keyword) {
                //le damos a la varibale matches el valor true para mas abajo generar un input confirmas cambios en lugar de modificar
                $matches = true;
                //Llamamos a una funcion para generar un elemento pasandole valores para name,value y disabled
                createInput($key, $value, toGetType($value), false, false, '', '', getMaxLength($value));
            } else {
                //este condicional nos va a controlar que los input se generen disabled para no poder modificarlo y lo creamos llamando ala funcion para crear el elemento                createInput($key, $value, toGetType($value), true, false, '', '', getMaxLength($value));
                createInput('name_cliente', $value, toGetType($value), true, false, '', '', getMaxLength($value));
            }
        } else {
            //este condicional nos va a controlar que los input se generen disabled para no poder modificarlo
            createInput('name_cliente', $value, toGetType($value), true, false, '', '', getMaxLength($value));
        }
    } else {
        createInput($key, $value, '', false, true, '', '', '');
    }
}

/**
 * function to get schema from database server and return it
 * 
 * @param string $name
 * @param PDO $bd
 * @return type
 */
function existsDataBase($name, $bd) {
    $statement = $bd->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
    $statement->execute(array($name));
    $result = $statement->fetch();
    return $result;
}

/**
 * function to check if databse named with name given as parameter exists and creates if ti does not 
 * 
 * @param string $name nombre de la base de datos que vamos a intentar crear 
 * @return true if a database has been created and false if it already existed
 */
function createDataBase($name) {
    try {
        //Conexión al servidor de base de datos sin especidficar la base de datos
        $bd = connectionBBDD('mysql:host=127.0.0.1', 'root', '');
        //Condicional para saber si la base de datos que le pasamos como parametro existe sabiendo si la función devulve un valor que no es nulo
        if (!existsDataBase($name, $bd)) { //Llamada a función para verificar si la base de datos existe buscandola en el esquema de nuestro servidor
            // La base de datos no existe, crearla
            $bd->query("CREATE DATABASE IF NOT EXISTS $name");
            $bd = null;
            return true;
        } else {
            $bd = null;
            return false;
        }
    } catch (PDOException) {
        displayError('Vaya, parece que nuestra página esta en mantenimiento, intentelo de nuevo más tarde');
    }
}

/**
 * funnction to return a string as sql masive statement as initial load
 * 
 * @return string script de sql para crear tablas con contenido (carga de datos inicial)
 */
function getScript() {
    return "
CREATE TABLE IF NOT EXISTS Personas (
    DNI VARCHAR(10) PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    Apellido VARCHAR(50) NOT NULL,
    FechaNacimiento DATE,
    Email VARCHAR(100),
    Telefono VARCHAR(15),
    Direccion VARCHAR(100),
    Rol INT NOT NULL,
    Clave VARCHAR(100) NOT NULL
);


CREATE TABLE IF NOT EXISTS Mascotas (
    Codigo_Animal INT NOT NULL,
    Nombre VARCHAR(50) NOT NULL,
    Especie VARCHAR(50),
    Raza VARCHAR(50),
    Edad INT NOT NULL,
    Dni VARCHAR(10) NOT NULL,
    FechaNacimiento DATE,
    Peso DECIMAL(5, 2) NOT NULL,
    Codigo_Consulta INT,
    PRIMARY KEY (Codigo_Animal, Dni),
    FOREIGN KEY (Dni) REFERENCES personas (DNI) ON DELETE CASCADE
);



CREATE TABLE IF NOT EXISTS Vacunas_Perro (
    ID INT,
    Codigo_Animal INT,
    Nombre_Vacuna VARCHAR(50),
    Enfermedades_Prevenidas VARCHAR(100),
    Edad_de_Inicio VARCHAR(20),
    Frecuencia VARCHAR(20),
    Fecha_Vacunacion DATE, -- Nuevo campo de fecha
    PRIMARY KEY (ID, Codigo_Animal),
    FOREIGN KEY (Codigo_Animal) REFERENCES Mascotas(Codigo_Animal) ON DELETE CASCADE
);



-- Generar 7 registros con Rol 0
INSERT INTO Personas (DNI, Nombre, Apellido, FechaNacimiento, Email, Telefono, Direccion, Rol, Clave)
VALUES                                                                                                         
  ('123456789A', 'Juan', 'Pérez', '1985-03-15', 'juan.perez@email.com', '123-456-7890', 'Calle A, Ciudad A', 0,'9af15b336e6a9619928537df30b2e6a2376569fcf9d7e773eccede65606529a0'),
  ('987654321B', 'Ana', 'López', '1990-05-20', 'ana.lopez@email.com', '987-654-3210', 'Calle B, Ciudad B', 0,'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c'),
  ('567890123C', 'María', 'García', '1980-12-10', 'maria.garcia@email.com', '567-890-1230', 'Calle C, Ciudad C', 0,'edee29f882543b956620b26d0ee0e7e950399b1c4222f5de05e06425b4c995e9'),
  ('345678901D', 'David', 'Martínez', '1988-08-25', 'david.martinez@email.com', '345-678-9010', 'Calle D, Ciudad D', 0,'318aee3fed8c9d040d35a7fc1fa776fb31303833aa2de885354ddf3d44d8fb69'),
  ('210987654E', 'Laura', 'Torres', '1992-04-05', 'laura.torres@email.com', '210-987-6540', 'Calle E, Ciudad E', 0,'79f06f8fde333461739f220090a23cb2a79f6d714bee100d0e4b4af249294619'),
  ('456789012F', 'Pedro', 'Sánchez', '1975-09-30', 'pedro.sanchez@email.com', '456-789-0120', 'Calle F, Ciudad F', 0,'c1f330d0aff31c1c87403f1e4347bcc21aff7c179908723535f2b31723702525'),
  ('789012345G', 'Sofía', 'Ramírez', '1987-07-12', 'sofia.ramirez@email.com', '789-012-3450', 'Calle G, Ciudad G', 0,'d7697570462f7562b83e81258de0f1e41832e98072e44c36ec8efec46786e24e');

-- Generar 3 registros con Rol 1
INSERT INTO Personas (DNI, Nombre, Apellido, FechaNacimiento, Email, Telefono, Direccion, Rol,Clave)
VALUES
  ('654321098H', 'Roberto', 'Hernández', '1983-01-05', 'roberto.hernandez@email.com', '654-321-0980', 'Calle H, Ciudad H', 1,'fe91a760983d401d9b679fb092b689488d1f46d92f3af5e9e93363326f3e8aa4'),
  ('890123456I', 'Isabel', 'Gómez', '1995-06-20', 'isabel.gomez@email.com', '890-123-4560', 'Calle I, Ciudad I', 1,'b7e307660e1611cb42bcb28e4bb4a6465ccb5ec2e028ca4be8b84e8787929a38'),
  ('234567890J', 'Luis', 'Rodríguez', '1982-11-15', 'luis.rodriguez@email.com', '234-567-8900', 'Calle J, Ciudad J', 1,'793a84a351bd364d2f0323b67b39407711e54bc4748c439fb32734538ef8dd15');


-- Generar 30 registros aleatorios de animales con propietarios con Rol 0
-- Insertar los datos con códigos de animal únicos
INSERT INTO Mascotas (Codigo_Animal, Nombre, Especie, Raza, Edad, Dni, FechaNacimiento, Peso, Codigo_Consulta)
VALUES
  (100001, 'Fido', 'Perro', 'Labrador', 3, '123456789A', '2021-06-15', 25.5, NULL),
  (100002, 'Whiskers', 'Gato', 'Siames', 2, '987654321B', '2022-01-20', 8.2, NULL),
  (100003, 'Rex', 'Perro', 'Golden Retriever', 4, '567890123C', '2019-05-10', 30.0, NULL),
  (100004, 'Bella', 'Gato', 'Persa', 6, '345678901D', '2017-03-25', 12.3, NULL),
  (100005, 'Lucky', 'Perro', 'Poodle', 2, '210987654E', '2021-11-05', 10.8, NULL),
  (100006, 'Simba', 'Gato', 'Bengal', 4, '456789012F', '2018-09-30', 14.6, NULL),
  (100007, 'Rocky', 'Perro', 'Bulldog', 5, '789012345G', '2017-07-12', 28.3, NULL),
  (100008, 'Molly', 'Perro', 'Labrador', 2, '123456789A', '2022-03-01', 22.1, NULL),
  (100009, 'Oliver', 'Gato', 'Siames', 3, '987654321B', '2021-08-14', 9.7, NULL),
  (100010, 'Luna', 'Gato', 'Persa', 5, '567890123C', '2017-04-19', 11.5, NULL),
  (100011, 'Cooper', 'Perro', 'German Shepherd', 4, '345678901D', '2018-12-03', 32.5, NULL),
  (100012, 'Charlie', 'Perro', 'Bulldog', 1, '210987654E', '2023-02-10', 15.4, NULL),
  (100013, 'Lucy', 'Gato', 'Ragdoll', 4, '456789012F', '2018-07-25', 10.9, NULL),
  (100014, 'Bailey', 'Perro', 'Golden Retriever', 2, '789012345G', '2021-10-05', 29.8, NULL),
  (100015, 'Tiger', 'Gato', 'Maine Coon', 3, '123456789A', '2021-12-20', 13.6, NULL),
  (100016, 'Max', 'Perro', 'Labrador', 6, '987654321B', '2016-04-02', 34.2, NULL),
  (100017, 'Chloe', 'Gato', 'British Shorthair', 7, '567890123C', '2015-08-15', 11.0, NULL),
  (100018, 'Rocky', 'Perro', 'Boxer', 3, '345678901D', '2019-09-22', 27.7, NULL),
  (100019, 'Daisy', 'Perro', 'Beagle', 2, '210987654E', '2021-02-18', 17.9, NULL),
  (100020, 'Lily', 'Gato', 'Siames', 4, '456789012F', '2018-06-10', 9.5, NULL),
  (100021, 'Milo', 'Perro', 'Dachshund', 5, '789012345G', '2017-12-14', 13.8, NULL),
  (100022, 'Lola', 'Gato', 'Persa', 2, '123456789A', '2022-05-05', 8.7, NULL),
  (100023, 'Sophie', 'Gato', 'Maine Coon', 1, '987654321B', '2023-04-01', 12.4, NULL),
  (100024, 'Duke', 'Perro', 'Great Dane', 3, '567890123C', '2020-11-30', 40.2, NULL),
  (100025, 'Penny', 'Perro', 'Cocker Spaniel', 4, '345678901D', '2019-03-08', 19.6, NULL),
  (100026, 'Leo', 'Gato', 'Ragdoll', 6, '210987654E', '2017-10-22', 14.1, NULL),
  (100027, 'Zoe', 'Gato', 'British Shorthair', 3, '456789012F', '2020-12-02', 11.8, NULL),
  (100028, 'Koda', 'Perro', 'Siberian Husky', 2, '789012345G', '2022-08-10', 27.3, NULL),
  (100029, 'Mia', 'Gato', 'Siames', 3, '123456789A', '2021-09-19', 10.2, NULL),
  (100030, 'Benji', 'Perro', 'Shih Tzu', 5, '987654321B', '2017-05-15', 14.5, NULL);



INSERT INTO Vacunas_Perro (ID,Codigo_Animal, Nombre_Vacuna, Enfermedades_Prevenidas, Edad_de_Inicio, Frecuencia, Fecha_Vacunacion)
VALUES
  (001,100001, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100001, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100002, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100002, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100003, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100003, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100004, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100004, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100005, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100005, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100006, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100006, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100007, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100007, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100008, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100008, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100009, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100009, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100010, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100010, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05');
-- Continúa con más registros para otros animales si es necesario

INSERT INTO Vacunas_Perro (ID,Codigo_Animal, Nombre_Vacuna, Enfermedades_Prevenidas, Edad_de_Inicio, Frecuencia, Fecha_Vacunacion)
VALUES
  (001,100011, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100011, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100012, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100012, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100013, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100013, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100014, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100014, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100015, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100015, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100016, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100016, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100017, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100017, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100018, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100018, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100019, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100019, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100020, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100020, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05');
-- Continúa con más registros para otros animales si es necesario


INSERT INTO Vacunas_Perro (ID,Codigo_Animal, Nombre_Vacuna, Enfermedades_Prevenidas, Edad_de_Inicio, Frecuencia, Fecha_Vacunacion)
VALUES
  (001,100021, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100021, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100022, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100022, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100023, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100023, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100024, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100024, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100025, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100025, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100026, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100026, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05'),
  (001,100027, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100027, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100028, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100028, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05');
-- Continúa con más registros para otros animales si es necesario

INSERT INTO Vacunas_Perro (ID,Codigo_Animal, Nombre_Vacuna, Enfermedades_Prevenidas, Edad_de_Inicio, Frecuencia, Fecha_Vacunacion)
VALUES
  (001,100029, 'CaniGuard', 'Enfermedad1', '3 meses', 'Anual', '2023-01-15'),
  (002,100029, 'ProtectoPup', 'Enfermedad2', '2 meses', 'Semestral', '2023-02-10'),
  (003,100030, 'FelinoShield', 'Enfermedad3', '4 meses', 'Anual', '2023-03-20'),
  (004,100030, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05');";
}
