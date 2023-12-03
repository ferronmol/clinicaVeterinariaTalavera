<?php
//Condicional para examnimar si las varibales de seesion obligatorias existen 
if (isset($_SESSION['user']) && isset($_SESSION['rol']) && isset($_SESSION['dni'])) {
    $user = htmlspecialchars($_SESSION['user']);
    $rol = htmlspecialchars($_SESSION['rol']);
    $dni = htmlspecialchars($_SESSION['dni']);
} else {//Se redirige el usuario al index por que ha intentado entrar a la url sin estar identificado
    header('Location: ./login.php');
    exit;
}
