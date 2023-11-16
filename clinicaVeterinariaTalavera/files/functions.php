<?php
function connectionBBDD($cadena,$user = 0,$password = 0){
    try{
        $bd = new PDO($cadena,$user,$password);
    } catch (Exception $ex) {
        'La conexión no se pudo realizar correctamente, intentlo más tarde';
    }
    return $bd;
}

function selectValues($bd,$sql){
//     and dni_propietario in (SELECT dni from personas where nombre = "Juan")
    $values = $bd->query($sql);
//realizamos ese código apra solo coger el array asociativo de la consulta
    $values->setFetchMode(PDO::FETCH_ASSOC);
    return $values;
    
}

