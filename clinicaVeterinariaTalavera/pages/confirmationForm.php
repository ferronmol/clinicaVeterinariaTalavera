<?php
$serializesValues = base64_encode(serialize($_POST));
$action = '';
if (isset($delete)) {
    echo '<h2>¿Quieres borrar los datos realmente?</h2>';
    $action = 'delete';
}
if(isset($commit)) {
    echo '<h2>¿Quieres modificar los datos realmente?</h2>';
    $action = 'commit';
}
if(isset($insert)){
    echo '<h2>¿Quieres insertar los datos realmente?</h2>';
    $action = 'insert';
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <?php
    if(isset($_POST['mascotas'])){//Condidional para saber sobre que tabla se va a hacer referencia (mascotas) si existe un post con ese name
        createInput('mascotas', htmlspecialchars($_POST['mascotas']), 'text', false, true,'','',0);
    }
    if(isset($_POST['users'])){//En este caso se haria referencia a la tabla de ususarios
        createInput('users', htmlspecialchars($_POST['users']), 'text', false, true,'','',0);
    }
    createInput('array', $serializesValues, 'hidden', false, true, '', '',0);
    createButton($action, 'yes', 'Si');
    createButton($action, 'no', 'No');
    ?>

</form>