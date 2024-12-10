
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
$fdccc= "SELECT cc.Codigo  FROM cempleados cc WHERE cc.TipoContrato='INDEFINIDO'  AND cc.Estado=1  ORDER BY cc.Codigo desc";
echo $fdccc;
echo '</br>';
echo '</br>';
$fdccc=query($fdccc);
if(count($fdccc)>0){
$students1 = array();
$students2 = array();
$students3 = array();
$students4 = array();
    for($i1ac=0; $i1ac<count($fdccc); $i1ac++)
    {
        $codigo_empleado = $fdccc[$i1ac]['Codigo'];  
        $codigo_empleado = (int)$codigo_empleado;
        $students1[] = array(
            'codigo_empleado'=> $codigo_empleado,
        );
    }
$datos_aqui1= query("SELECT cc.Codigo FROM datos_vacacion_cth5 cc  WHERE cc.Estado=1 ORDER BY cc.Codigo desc");
if(count($datos_aqui1)>0){
    for($i1ac=0; $i1ac<count($datos_aqui1); $i1ac++)
    {
        $codigo_empleado = $datos_aqui1[$i1ac]['Codigo'];  
        $codigo_empleado = (int)$codigo_empleado;
        $students2[] = array(
            'codigo_empleado'=> $codigo_empleado,
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
    $new_array = array_filter($students1, function ($obj)  use ($objeto1) { 
        return $obj == $objeto1 ;
    });
    if(count($new_array)==0){
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

    if(count($students3)>0){
     for($i1accc=0; $i1accc<count($students3); $i1accc++)
     {
      $valo= $students3[$i1accc]['codigo_empleado'];
      $fgg= query("SELECT cc.* FROM datos_vacacion_cth5  cc  WHERE cc.Estado=1 AND cc.Codigo=$valo ORDER BY cc.Codigo desc");
       if(count($fgg)>0){
        $fgg1= query("update datos_vacacion_cth5  set Estado=0  ,FechaModificacion='$fecha'   where  Codigo=$valo");
       }
     }
    }
}
}





?>
