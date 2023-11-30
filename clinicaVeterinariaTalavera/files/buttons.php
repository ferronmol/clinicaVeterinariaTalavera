<td class='td'>

    <?php
    if($puppies){//Condicinal para saber a que tabla se esta haciendo referencia 
        //para tratar a los botones en función de la concurrencia con el campo necesario
        $keyValue = $mascota['nombre'];
    }else{
        $keyValue = $client['dni'];
    }
    //condicinal para controlar que input se va a generar dependiendo de si se ha encontrado el cliente a modificar

    if ($matches) {
        //Llamamos a la funcion para generar un input mandandole parametros especificos
        createInput('dni', $keyValue, 'text', true, true, 'hidden', '', '', getMaxLength($value));
        //Llamamos a la funcion para generar un boton de confirmar
        createButton('commit', $keyValue, 'Confirmar', 'bg-success');
    } else {
        //Llamamos la afuncion para crear un boton para modificar
        createButton('update', $keyValue, 'Modificar', 'bg-warning');
    }
    //Llamamos a una funcion para generar el boton de eliminar
    createButton('delete', $keyValue, 'Eliminar', 'bg-danger', $updating);
    if(!$puppies){
        createButton('mascotas', $keyValue, 'Macotas', 'bg-primary', $updating);
    }
    
    ?>
</td>