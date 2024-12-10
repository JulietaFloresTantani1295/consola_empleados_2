
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




$fdc=queryth5("SELECT
TT.codigo_empleado,
TT.estado,
TT.apellido_materno,
TT.apellido_paterno,              
TT.nombres,
TT.ci,
TT.tipo_documento,
TT.extension,
TT.fecha_ingreso,
TT.fecha_salida,
TT.sueldo_basico,
TT.tipo_contrato,
TT.cargo,
TT.nom_cargo,
TT.area ,
TT.nom_area,
TT.tipo_empleado,
TT.sucursal,
TT.jefeid
FROM 	(( 
  


SELECT 
aa.codigo_empleado,
aa.estado,
aa.apellido_materno,
aa.apellido_paterno,              
aa.nombres,
aa.ci,
aa.tipo_documento,
aa.extension,
aa.fecha_ingreso,
aa.fecha_salida,
aa.sueldo_basico,
aa.tipo_contrato,
aa.cargo,
cc.descripcion as nom_cargo,
cc.area ,
ar.nombre  as nom_area,
aa.tipo_empleado,
aa.sucursal,
cc.jefeid

FROM empleado aa
inner join cargo cc on cc.codigo= aa.cargo
inner join area ar on ar.codigo= cc.area
where aa.fecha_ingreso IS NOT NULL and  aa.fecha_salida IS NOT NULL and aa.cargo is not null  and aa.estado=1


))TT  
ORDER BY  TT.codigo_empleado  ASC");





  if(count($fdc)>0){



  

    $datos_aqui1= query("SELECT cc.Codigo AS codigo_empleado, cc.Estado AS estado,
    cc.Ci AS ci, cc.Tipo_Documento AS tipo_documento, cc.Extension AS extension, 
    cc.FechaIngreso AS fecha_ingreso, cc.FechaSalida AS fecha_salida, 
    cc.SueldoBasico AS sueldo_basico, 
    cc.TipoContrato AS tipo_contrato, pp.CargoTH5 AS cargo,  pp.AreaTH5 AS area,
    cc.TipoEmpleado AS tipo_empleado, oo.RTH5  AS sucursal, if(ISNULL(cc.jefeId), 0, cc.jefeId) as jefeId 
      FROM cempleados cc 
      INNER JOIN cargo pp ON pp.IdCargo= cc.CargoId
      INNER JOIN oficina oo ON oo.IdOficina= cc.IdSucursal
     
      WHERE cc.Estado=1 ORDER BY cc.Codigo asc");
  


  $students1 = array();
  $students2 = array();
  $students3 = array();
  
  for($i1ac=0; $i1ac<count($fdc); $i1ac++)
  {
   
   

      $codigo_empleado = $fdc[$i1ac]['codigo_empleado'];  
      $codigo_empleado = (int)$codigo_empleado;
      $estado = $fdc[$i1ac]['estado'];
      $ci = $fdc[$i1ac]['ci'];
      $tipo_documento = $fdc[$i1ac]['tipo_documento'];
      $extension = $fdc[$i1ac]['extension'];
      $fecha_ingreso = ($fdc[$i1ac]['fecha_ingreso']->format('Y-m-d'));
      $fecha_salida = ($fdc[$i1ac]['fecha_salida']->format('Y-m-d'));
      $sueldo_basico = $fdc[$i1ac]['sueldo_basico'];
      $tipo_contrato = $fdc[$i1ac]['tipo_contrato'];
      $cargo = $fdc[$i1ac]['cargo'];
      $cargo = (int)$cargo;
      $area = $fdc[$i1ac]['area'];
      $area = (int)$area;
      $tipo_empleado = $fdc[$i1ac]['tipo_empleado'];
      $sucursal = $fdc[$i1ac]['sucursal'];
      $sucursal = (int)$sucursal;
      $jefeId = $fdc[$i1ac]['jefeid'];
      $jefeId = (int)$jefeId;

  
      $students1[] = array(
          'codigo_empleado'=> $codigo_empleado,
          'estado'=> $estado,
          'ci'=> $ci,
          'tipo_documento'=> $tipo_documento,
          'extension'=> $extension,
          'fecha_ingreso'=> $fecha_ingreso,
          'fecha_salida'=> $fecha_salida,
          'sueldo_basico'=> $sueldo_basico,
          'tipo_contrato'=> $tipo_contrato,
          'cargo'=> $cargo,
          'area'=> $area,
          'tipo_empleado'=> $tipo_empleado,
          'sucursal'=> $sucursal,
          'jefeId'=> $jefeId
      );
  }
  
  for($i1ac=0; $i1ac<count($datos_aqui1); $i1ac++)
  {
  
      $codigo_empleado = $datos_aqui1[$i1ac]['codigo_empleado'];  
      $codigo_empleado = (int)$codigo_empleado;
      $estado = $datos_aqui1[$i1ac]['estado'];
      $ci = $datos_aqui1[$i1ac]['ci'];
      $tipo_documento = $datos_aqui1[$i1ac]['tipo_documento'];
      $extension = $datos_aqui1[$i1ac]['extension'];
      $fecha_ingreso = $datos_aqui1[$i1ac]['fecha_ingreso'];
      $fecha_salida =  $datos_aqui1[$i1ac]['fecha_salida'];
      $sueldo_basico = $datos_aqui1[$i1ac]['sueldo_basico'];
      $tipo_contrato = $datos_aqui1[$i1ac]['tipo_contrato'];
      $cargo = $datos_aqui1[$i1ac]['cargo'];
      $cargo = (int)$cargo;
      $area = $datos_aqui1[$i1ac]['area'];
      $area = (int)$area;
      $tipo_empleado = $datos_aqui1[$i1ac]['tipo_empleado'];
      $sucursal = $datos_aqui1[$i1ac]['sucursal'];
      $sucursal = (int)$sucursal;
      $jefeId = $datos_aqui1[$i1ac]['jefeId'];
      $jefeId = (int)$jefeId;


      $students2[] = array(
        'codigo_empleado'=> $codigo_empleado,
        'estado'=> $estado,
        'ci'=> $ci,
        'tipo_documento'=> $tipo_documento,
        'extension'=> $extension,
         'fecha_ingreso'=> $fecha_ingreso,
        'fecha_salida'=> $fecha_salida,
        'sueldo_basico'=> $sueldo_basico,
        'tipo_contrato'=> $tipo_contrato,
        'cargo'=> $cargo,
        'area'=> $area,
        'tipo_empleado'=> $tipo_empleado,
        'sucursal'=> $sucursal,
        'jefeId'=> $jefeId
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
  


    
  for($i1acc=0; $i1acc<count($students1); $i1acc++)
  {
      $objeto1 = $students1[$i1acc];
      $new_array = array_filter($students2, function ($obj)  use ($objeto1) { 
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








  

  }
  

?>
