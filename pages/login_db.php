<?php
session_start();
$errors = array();
require '../files/functions.php';
//Declaring varibale ann storage a bd conecction in it
$bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');

if (isset($_POST['login_user'])) {
    $dni = strtoupper(htmlspecialchars($_POST['dni']));
//    $password = htmlspecialchars($_POST['password']);
    $password = hash('sha256',$_POST['password']);
    try {
        //Primero miramos si existe algun usuario con ese dni
        $sql = "SELECT dni FROM personas WHERE DNI = ?";
        $results = getSelectStatementValues($bd, $sql, array($dni));
        //Condicional para saber si el usuario existe
        if($results->rowCount() == 1){
            $sql = "SELECT dni,rol,nombre FROM personas WHERE DNI = ? AND Clave = ?";
            $results = getSelectStatementValues($bd, $sql, array($dni,$password));
            //Condicional para saber si la consulta nos devulve valores
            if($results->rowCount() == 1){
                //Cookie session creation
                setcookie(nameSessionCookie(session_id(), $dni),'session',time() + 1 * 600,'/');
                foreach ($results as $result) {
                    //Fill session values
                $_SESSION['dni'] = $result['dni'];
                $_SESSION['rol'] = $result['rol'];
                $_SESSION['user'] = $result['nombre'];
                }
                
                //Redirection to crud url
                header('Location: crud.php');
            }else{
                header('Location: ./login.php?errorNotMatch');
            }
        }else{
            header('Location: ./login.php?errorNotFound');
        }
  
    } catch (PDOException $e) {
        array_push($errors, "Error en la conexión a la base de datos: " . $e->getMessage());
        $_SESSION['error'] = "Error en la conexión a la base de datos";
        header("location: login.php");
        exit();
    }
}
?>
