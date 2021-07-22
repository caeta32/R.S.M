<?php
    $params = 'accion=obtenerEstacionMasCercana&longitud=-55.97288&latitud=-34.73416';
    // Se abre conexión curl pasando url.
    $init = curl_init('http://localhost/sitio-cliente_JEE/R.S.M/Servicios%20R.S.M/?'.$params);
    // Se especifica si se quiere la respuesta de la petición.
    curl_setopt($init, CURLOPT_RETURNTRANSFER, true);
    // Se especifica si se quiere el header en la respuesta.
    curl_setopt($init, CURLOPT_HEADER, false);
    // Se ejecuta la petición HTTP y se almacena la respuesta.
    $data = curl_exec($init);
    // Se cierra la conexión cURL.
    curl_close($init);

    echo $data;
?>