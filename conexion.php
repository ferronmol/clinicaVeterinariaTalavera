<?php

if (!function_exists('connectionBBDD')) {
    function connectionBBDD($cadena, $user = 'root', $password = '')
    {
        try {
            $bd = new PDO($cadena, $user, $password);
            return $bd;
        } catch (Exception $ex) {
            die('La conexión no se pudo realizar correctamente, intentalo más tarde');
        }
    }
}
$bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
