<?php
//Creamos una nueva cookie con la última fecha en este caso es la fecha en la que abandona su sesión
    //Llamada a función para generar la fecha actual en estos momentos de cuando el usuario accede
    setcookie(nameSessionCookie('', $dni), date("d-m-Y H:i:s"), time() + 24 * 3600); //No le indicamos todo el directorio ya que solo se quiere tener accesos a esta cookie dentro de páginas restringidas
    //Destruimos la sesión y borramos la cookie que nos genera dicha sesión
    setcookie(session_id(),'',time() - 24 * 3600);
    session_destroy();
    //Redireccionamos el usuario al index.php
    header('Location: ../index.php');