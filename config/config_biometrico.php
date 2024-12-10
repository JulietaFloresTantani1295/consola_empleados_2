<?php
	define('DB_HOST_', 'localhost');
	define('DB_USER_', 'root');
	define('DB_PASS_', '123456789-');
	define('DB_NAME_', 'biometrico');
	$con_=@mysqli_connect(DB_HOST_, DB_USER_, DB_PASS_, DB_NAME_);
    if(!$con_){
        @die("<h2 style='text-align:center'>Imposible conectarse a la base de datos biometrico s! </h2>".mysqli_error($con_));
    }
    if (@mysqli_connect_errno()) {
        @die("Conexión falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
?>