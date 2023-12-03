<?php
require '../lib/allowControl.php';
$serializesValues = base64_encode(serialize($_POST));
$action = '';
if (isset($postValues['delete'])) {
    echo '<h2>¿Quieres borrar los datos realmente?</h2>';
    $action = 'delete';
}
if(isset($postValues['commit'])) {
    echo '<h2>¿Quieres modificar los datos realmente?</h2>';
    $action = 'commit';
}
if(isset($postValues['insert'])){
    echo '<h2>¿Quieres insertar los datos realmente?</h2>';
    $action = 'insert';
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <?php
    if(isset($postValues['mascotas'])){//Condidional para saber sobre que tabla se va a hacer referencia (mascotas) si existe un post con ese name
        createInput('mascotas', htmlspecialchars($postValues['mascotas']), 'text', false, true,'','',0);
    }
    if(isset($postValues['users'])){//En este caso se haria referencia a la tabla de ususarios
        createInput('users', htmlspecialchars($postValues['users']), 'text', false, true,'','',0);
    }
    createInput('array', $serializesValues, 'hidden', false, true, '', '',0);
    createButton($action, 'yes', 'Si');
    createButton($action, 'no', 'No');
    ?>

</form>