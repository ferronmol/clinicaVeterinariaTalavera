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
function selectValues($bd, $sql, $value) {
    try {
        $values = $bd->prepare($sql);
        $values->execute(array($value));
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
    try {
        $bd->query($sql);
    } catch (Exception $ex) {
        $errorCode = $ex->getCode();
        echo $errorCode;
        echo '<br>';
        echo 'entro en la excepcion';

        if ($errorCode == 23000) {
            displayError('Vaya, parece que ya hay un registro en la tabla con esa identificación');
            displayError('O si estas tratando de agragar una mascota cerciorate que el duenio esta registrado como cliente');
        }
        if ($errorCode == '21S01') {
            displayError('Alguno de tus datos excede el numero de caracteres, echa un vistazo a los valores permitidos');
        }
        if ($errorCode === 1452) {
            displayError('Vaya, la mascota que estas intentando agregar no corredponde a ningún duenio registrado');
        }
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
                if ($value === 'rol')
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
function createInput($name, $value, $type, $disabled = false, $hidden = false, $class = '', $placeholder = '') {
    $disabled = ($disabled) ? 'disabled' : '';
    $hidden = ($hidden) ? 'hidden' : '';
    if ($hidden === 'hidden') {
        ?>
        <input class='form__input <?= $class ?>' required type='<?= $type ?>'  placeholder='<?= $placeholder ?>'  name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?> <?= $hidden ?>>
        <?php
    } else {
        ?>
        <td class="td">
            <input class='form__input' required type='<?= $type ?>' <?= $class ?>'  placeholder='<?= $placeholder ?>'  name="<?= $name ?>" value="<?= $value ?>" <?= $disabled ?>>
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

/**
 * funtion to create a form for a insert action
 * 
 * @param string $disabled
 */
function createFormInsert($disabled, $table) {
    $personFileds = ['dni', 'nombre', 'apellido', 'fechaNacimiento', 'email', 'telefono', 'direccion', 'rol', 'clave'];
    $animalFields = ['codigo_animal', 'nombre', 'especie', 'raza', 'edad', 'dni_propietario', 'fechaNacimiento', 'peso', 'codigo_consulta'];
    $buttonValue = 'insertPersona';
    if ($table === 'personas') {
        $fields = $personFileds;
    } else {
        $fields = $animalFields;
        $buttonValue = 'insertMascota';
    }


    $type = '';
    ?>
    <tr class="tr">
        <?php
        foreach ($fields as $value) {
            if ($value === 'rol') {
                //SI ESTE VALOR ES DISABLED NO SE ENVIA Y SI ES HIDDEN SE COLOCA EL PRIMERO Y DESCOLOCA TODO ,
                //SOLUCCION HACER UNA INSERCCION EN LA BASE DE DATOS REFERENCIADNO LOS CAMPOS,
                createInput($value, 0, toGetType($value), $disabled, true, '', 0);
            } else if ($value === 'clave') {
                //hay que hacer un metodo para cifrar la contraseña y mostrar axteriscos, a su vez esas claves tienen que meterse en un array o algo para tenerlas
                createInput($value, '', toGetType($value), $disabled, false, '', $value);
            } else {
                createInput($value, '', toGetType($value), $disabled, false, '', $value);
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
function managePost($action, $statement, &$confirmationActive, $bd, $values, $table) {
    //Llamamos a una funcion para borrar los datos

    if ($action === 'yes') {//condicional para saber si el usuario confirma la modificación de los datos
        //Deserialización del array que nos llega desde el formualrio de confirmación
        $clearValues = unserialize(base64_decode($values['array']));
        //Llamamos a la función para crear una consulta de delete y lo que nos devulve esa función se lo pasamos
        //como parametro a ala función padre apra generar la consulta a la base de datos
        echo 'esta es la consutla' . $statement($bd, $table, $clearValues);
        statement($bd, $statement($bd, $table, $clearValues));
    }
    if ($action !== 'yes' && $action !== 'no') {
        //Le damos el valor true a la varibale porque la confirmacion en estos momento esta activa
        $confirmationActive = true;
    }
}

function existsDataBase($name,$bd) {
    $statement = $bd->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
    $statement->execute(array($name));
    $result = $statement->fetch();
    return $result;
}

/**
 * Función que sirve para verificar si la base de datos con el nombre que nos pasan como parametro existe en nuestra base de datos y si no es así, se procede a crearla
 * 
 * @param string $name nombre de la base de datos que vamos a intentar crear 
 */
function createDataBase($name) {
    try {
        //Conexión al servidor de base de datos sin especidficar la base de datos
        $bd = connectionBBDD('mysql:host=127.0.0.1', 'root', '');
        //Condicional para saber si la base de datos que le pasamos como parametro existe sabiendo si la función devulve un valor que no es nulo
        if (!existsDataBase($name,$bd)) { //Llamada a función para verificar si la base de datos existe buscandola en el esquema de nuestro servidor
            // La base de datos no existe, crearla
            $bd->query("CREATE DATABASE IF NOT EXISTS $name");
            echo "La base de datos '$name' ha sido creada.";
        } else {
            echo "La base de datos '$name' ya existe.";
        }
    } catch (PDOException $e) {
        displayError('Vaya, parece que nuestra página esta en mantenimiento, intentelo de nuevo más tarde');
    }
}

function getCreateScript() {
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
    Clave VARCHAR(16) NOT NULL
);


CREATE TABLE IF NOT EXISTS Mascotas (
    Codigo_Animal INT NOT NULL,
    Nombre VARCHAR(50) NOT NULL,
    Especie VARCHAR(50),
    Raza VARCHAR(50),
    Edad INT NOT NULL,
    Dni_Propietario VARCHAR(10) NOT NULL,
    FechaNacimiento DATE,
    Peso DECIMAL(5, 2) NOT NULL,
    Codigo_Consulta INT,
    PRIMARY KEY (Codigo_Animal, Dni_Propietario),
    CONSTRAINT fk_Mascotas_Personas FOREIGN KEY (Dni_Propietario) REFERENCES personas (DNI)
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
    FOREIGN KEY (Codigo_Animal) REFERENCES Mascotas(Codigo_Animal)
);



-- Generar 7 registros con Rol 0
INSERT INTO Personas (DNI, Nombre, Apellido, FechaNacimiento, Email, Telefono, Direccion, Rol)
VALUES
  ('123456789A', 'Juan', 'Pérez', '1985-03-15', 'juan.perez@email.com', '123-456-7890', 'Calle A, Ciudad A', 0),
  ('987654321B', 'Ana', 'López', '1990-05-20', 'ana.lopez@email.com', '987-654-3210', 'Calle B, Ciudad B', 0),
  ('567890123C', 'María', 'García', '1980-12-10', 'maria.garcia@email.com', '567-890-1230', 'Calle C, Ciudad C', 0),
  ('345678901D', 'David', 'Martínez', '1988-08-25', 'david.martinez@email.com', '345-678-9010', 'Calle D, Ciudad D', 0),
  ('210987654E', 'Laura', 'Torres', '1992-04-05', 'laura.torres@email.com', '210-987-6540', 'Calle E, Ciudad E', 0),
  ('456789012F', 'Pedro', 'Sánchez', '1975-09-30', 'pedro.sanchez@email.com', '456-789-0120', 'Calle F, Ciudad F', 0),
  ('789012345G', 'Sofía', 'Ramírez', '1987-07-12', 'sofia.ramirez@email.com', '789-012-3450', 'Calle G, Ciudad G', 0);

-- Generar 3 registros con Rol 1
INSERT INTO Personas (DNI, Nombre, Apellido, FechaNacimiento, Email, Telefono, Direccion, Rol)
VALUES
  ('654321098H', 'Roberto', 'Hernández', '1983-01-05', 'roberto.hernandez@email.com', '654-321-0980', 'Calle H, Ciudad H', 1),
  ('890123456I', 'Isabel', 'Gómez', '1995-06-20', 'isabel.gomez@email.com', '890-123-4560', 'Calle I, Ciudad I', 1),
  ('234567890J', 'Luis', 'Rodríguez', '1982-11-15', 'luis.rodriguez@email.com', '234-567-8900', 'Calle J, Ciudad J', 1);



-- Generar 30 registros aleatorios de animales con propietarios con Rol 0
-- Insertar los datos con códigos de animal únicos
INSERT INTO Mascotas (Codigo_Animal, Nombre, Especie, Raza, Edad, Dni_Propietario, FechaNacimiento, Peso, Codigo_Consulta)
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
  (004,100030, 'CatSafe', 'Enfermedad4', '2 meses', 'Anual', '2023-04-05');
-- Continúa con más registros para otros animales si es necesario


UPDATE Personas SET clave = '0001' WHERE DNI = '123456789A';

UPDATE Personas SET clave = '0002' WHERE DNI = '987654321B';

UPDATE Personas SET clave = '0003' WHERE DNI = '567890123C';

UPDATE Personas SET clave = '0004' WHERE DNI = '345678901D';

UPDATE Personas SET clave = '0005' WHERE DNI = '210987654E';

UPDATE Personas SET clave = '0006' WHERE DNI = '456789012F';

UPDATE Personas SET clave = '0007' WHERE DNI = '789012345G';

UPDATE Personas SET clave = '0008' WHERE DNI = '654321098H';

UPDATE Personas SET clave = '0009' WHERE DNI = '890123456I';

UPDATE Personas SET clave = '0010' WHERE DNI = '234567890J';";
}
