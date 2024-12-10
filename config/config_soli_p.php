<?php

	define('DB_HOST_s', 'localhost');
	define('DB_USER_s', 'root');//Usuario de tu base de datos
	define('DB_PASS_s', '123456789-');//Contraseña del usuario de la base de datos
	define('DB_NAME_s', 'soli_p');//Nombre de la base de datos
	$con=@mysqli_connect(DB_HOST_s, DB_USER_s, DB_PASS_s, DB_NAME_s);
    if(!$con){
        @die("<h2 style='text-align:center'>Imposible conectarse a la base de datos  soli_p s! </h2>".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        @die("Conexión falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
?>