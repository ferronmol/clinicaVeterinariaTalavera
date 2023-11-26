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
    createInput('array', $serializesValues, 'hidden', false, true, '', '');
    createButton($action, 'yes', 'Si');
    createButton($action, 'no', 'No');
    ?>

</form>