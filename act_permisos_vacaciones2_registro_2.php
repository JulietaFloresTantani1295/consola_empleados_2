
<?php
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("America/La_Paz");
error_reporting(E_ALL);
ini_set('display_errors', 1);
$fecha=date('Y-m-d H:i:s');
include "config/config_biometrico.php";
include "config/config_soli_p.php";
include "config/con_query.php";
include "abc2.php";
include "server.php";

$fechaActual = date('d/m/Y');
$fechaActuala = date('Y-m-d');




$fdccc= "SELECT
T.codigo_empleado  ,  T.documento , T.estado,T.fechaInicio, T.fechaFin

 
FROM 	(( 

SELECT
T.codigo_empleado  ,  T.documento ,T.fecha , ccc.estado ,ccc.fechaInicio,  ccc.fechaFin

 
FROM 	(( 
SELECT cc.codigo_empleado  ,  cc.documento , max(cc.fechasolicitud) as fecha
FROM empleado_vacacion cc
INNER JOIN empleado ee ON cc.codigo_empleado = ee.codigo_empleado
LEFT JOIN empleado_cargo ccargo ON cc.codigo_empleado = ccargo.codigo_empleado
INNER JOIN dbo.cargo gh ON ccargo.cargo = gh.codigo
where ccargo.estado=1 
group by cc.codigo_empleado, cc.documento 

))T  inner join empleado_vacacion ccc on ccc.codigo_empleado= T.codigo_empleado and ccc.documento= T.documento and ccc.fechasolicitud= T.fecha

))T ";


echo $fdccc;
echo '</br>';
echo '</br>';




$fdccc=queryth5($fdccc);




if(count($fdccc)>0){


  
$datos_aqui1= query("SELECT   cc.codigo_e as codigo_empleado,  cc.documento , cc.estado , cc.fechainicio, cc.fechafin FROM tablapv cc 
WHERE cc.estado in (0,1,2,3)  GROUP BY cc.fechainicio, cc.fechafin, cc.estado ,cc.codigo_e ORDER BY cc.fechasolicitud asc");



$students1 = array();
$students2 = array();
$students3 = array();
$students4 = array();

for($i1ac=0; $i1ac<count($fdccc); $i1ac++)
{
    $codigo_empleado = $fdccc[$i1ac]['codigo_empleado'];  
    $codigo_empleado = (int)$codigo_empleado;

    $documento = $fdccc[$i1ac]['documento'];
    $estado = $fdccc[$i1ac]['estado'];
    $fecha_ini = $fdccc[$i1ac]['fechaInicio']; 
    $fecha_ini = $fecha_ini->format('Y-m-d');

    $fecha_fin = $fdccc[$i1ac]['fechaFin'];
    $fecha_fin = $fecha_fin->format('Y-m-d');
    $students1[] = array(
        'codigo_empleado'=> $codigo_empleado,
        'documento'=> $documento,
        'estado'=> $estado,
        'fecha_ini'=> $fecha_ini,
        'fecha_fin'=> $fecha_fin
    );
}

for($i1ac=0; $i1ac<count($datos_aqui1); $i1ac++)
{
    $codigo_empleado = $datos_aqui1[$i1ac]['codigo_empleado'];  
    $codigo_empleado = (int)$codigo_empleado;

    $documento = $datos_aqui1[$i1ac]['documento'];
    $estado = $datos_aqui1[$i1ac]['estado'];
    $fecha_ini = $datos_aqui1[$i1ac]['fechainicio'];
    $fecha_fin = $datos_aqui1[$i1ac]['fechafin'];

    $students2[] = array(
        'codigo_empleado'=> $codigo_empleado,
        'documento'=> $documento,
        'estado'=> $estado,
        'fecha_ini'=> $fecha_ini,
        'fecha_fin'=> $fecha_fin
    );
}


echo '</br>';
echo 'version 1';
echo '</br>';
echo '</br>';
echo json_encode($students1);
echo '</br>';
echo '</br>';


echo '</br>';
echo 'version 2';
echo '</br>';
echo '</br>';
echo json_encode($students2);
echo '</br>';
echo '</br>';



for($i1acc=0; $i1acc<count($students2); $i1acc++)
{
    $objeto1 = $students2[$i1acc];
    $fechaActual = date("Y-m-d"); 
    if((!isset($objeto1['fecha_fin']) || $objeto1['fecha_fin'] >= $fechaActual) ){
      array_push($students3, $objeto1);
    }
}


echo '</br>';
echo 'version 4';
echo '</br>';
echo '</br>';
echo  count($students3);
echo '</br>';
echo '</br>';
echo  json_encode($students3);
echo '</br>';
echo '-----------------------------------------</br>';






}else{
  $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion,Detalle)
  VALUES ('act_permisos_vacaciones2.php','$fecha', 'No hay datos')";
  $ejec_ser=query($ej_servicio);
}





?>
