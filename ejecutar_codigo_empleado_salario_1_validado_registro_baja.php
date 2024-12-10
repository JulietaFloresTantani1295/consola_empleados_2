
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
TT.estado
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
'' as nom_cargo,
'' as area ,
''  as nom_area,
aa.tipo_empleado,
aa.sucursal,
'' as jefeid

FROM empleado aa
where aa.fecha_ingreso IS NOT NULL and  aa.fecha_salida IS NOT NULL and   aa.cargo =0


))TT  
ORDER BY  TT.codigo_empleado  ASC");


if(count($fdc)>0){



  $datos_aqui1= query("SELECT cc.Codigo as codigo_empleado , cc.Estado as estado  FROM cempleados cc WHERE cc.Estado=1
  ORDER BY cc.Codigo asc");



$students1 = array();
$students2 = array();
$students3 = array();

for($i1ac=0; $i1ac<count($fdc); $i1ac++)
{
    $codigo_empleado = $fdc[$i1ac]['codigo_empleado'];  
    $codigo_empleado = (int)$codigo_empleado;
    $estado = $fdc[$i1ac]['estado'];

    $students1[] = array(
        'codigo_empleado'=> $codigo_empleado
    );
}

for($i1ac=0; $i1ac<count($datos_aqui1); $i1ac++)
{
    $codigo_empleado = $datos_aqui1[$i1ac]['codigo_empleado'];  
    $codigo_empleado = (int)$codigo_empleado;
    $estado = $datos_aqui1[$i1ac]['estado'];

    $students2[] = array(
        'codigo_empleado'=> $codigo_empleado
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
  
      if(count($new_array)!=0){
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
    for($ilc=0; $ilc<count($students3); $ilc++)
    {
      $codigo_empleado= $students3[$ilc]['codigo_empleado'];


      
$fdc_fg=queryth5("SELECT
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
'' as nom_cargo,
'' as area ,
''  as nom_area,
aa.tipo_empleado,
aa.sucursal,
'' as jefeid

FROM empleado aa
where aa.fecha_ingreso IS NOT NULL and  aa.fecha_salida IS NOT NULL and   aa.cargo =0
and aa.codigo_empleado=$codigo_empleado

))TT  
ORDER BY  TT.codigo_empleado  ASC");



if(count($fdc_fg)>0){


  


  for($ilchh=0; $ilchh<count($fdc_fg); $ilchh++)
  {
    $codigo_empleado= $fdc_fg[$ilchh]['codigo_empleado'];



    $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
    VALUES ('ejecutar_codigo_empleado_salario_1_validado_registro_baja.php','$fecha', '$codigo_empleado')";
    $ejec_ser=query($ej_servicio);


    $codigo_empleado_is=0;
    $o_nivel=0;
    echo '<br>';
    echo $codigo_empleado;
    echo '<br>';


    $estadoth5= $fdc_fg[$ilchh]['estado'];
    $nombre=$fdc_fg[$ilchh]['apellido_paterno'].' '.$fdc_fg[$ilchh]['apellido_materno'].' '.$fdc_fg[$ilchh]['nombres'];
    $cii= $fdc_fg[$ilchh]['ci'];


    $cargo_th5= $fdc_fg[$ilchh]['cargo'];
    $tipo_empleado_th5= $fdc_fg[$ilchh]['tipo_empleado'];
    $nom_cargo_th5= $fdc_fg[$ilchh]['nom_cargo'];
    $area_th5= $fdc_fg[$ilchh]['area'];
    $nom_area_th5= $fdc_fg[$ilchh]['nom_area'];
    $sucursal_th5= $fdc_fg[$ilchh]['sucursal'];
    $jefe_id_th5= $fdc_fg[$ilchh]['jefeid'];

   


    $tipo_documento= $fdc_fg[$ilchh]['tipo_documento'];
    $extension= $fdc_fg[$ilchh]['extension'];
    $fecha_ingreso= $fdc_fg[$ilchh]['fecha_ingreso']->format('Y-m-d');
    $fecha_salida= $fdc_fg[$ilchh]['fecha_salida']->format('Y-m-d');

    echo $fecha_ingreso;

    echo '<br>';
    echo $fecha_salida;
    echo '<br>';
    $sueldo_basico= $fdc_fg[$ilchh]['sueldo_basico'];
    $tipo_contrato= $fdc_fg[$ilchh]['tipo_contrato'];



    

   















    
    $pre1 = query("SELECT  cc.Id,  cc.Codigo, cc.Estado FROM  cempleados cc  WHERE cc.Codigo=$codigo_empleado");
    if(count($pre1)>0){


       if(count($pre1)==1){

        $pre2 = query("SELECT  cc.Id,   cc.Codigo, cc.Estado FROM  cempleados cc  WHERE cc.Codigo=$codigo_empleado and cc.Estado=1");

         if(count($pre2)>0){
          $iddc= $pre2[0]['Id'];
         }




          if(count($pre2)==1 && $estadoth5==0){
            echo '<br>';
            echo 1;
           echo '<br>';

 
            if($sucursal_th5!=0){
              $dato123_sucursal= query("SELECT * FROM oficina ss WHERE ss.RTH5=$sucursal_th5");
              if(count($dato123_sucursal)>0){
                  $sucursal123_su=  $dato123_sucursal[0]['IdOficina'];
                  echo $sucursal123_su."<br>";
                  echo "<br>";
  
                  $dato123_codeq= query("SELECT cc.IdUsuario , cc.Estado FROM usuario cc WHERE cc.CodigoE=$codigo_empleado");
                  if(count($dato123_codeq)>0){
                    $dato123_=query("update usuario set IdOficina= $sucursal123_su WHERE CodigoE=$codigo_empleado ");
                  }     
                
              }
  
            }



            if($cargo_th5==0){
                  $cargo123=  3106;
                  echo $codigo_empleado."<br>";
                  echo "<br>";
                 
                  $dato123_codeq= query("SELECT cc.IdUsuario , cc.Estado FROM usuario cc WHERE cc.CodigoE=$codigo_empleado");
                  if(count($dato123_codeq)>0){
                  $dato123_=query("update usuario set IdCargo= $cargo123 WHERE CodigoE=$codigo_empleado ");
                  }

                  $pre3=query("update cempleados cc SET cc.Estado=0,  cc.FechaModificacion='$fecha' , cc.IdUsuarioModificante=1 , cc.ISuperior=$codigo_empleado_is , 
                  cc.Tipo_Documento='$tipo_documento', cc.Extension='$extension', cc.FechaIngreso='$fecha_ingreso', 
                  cc.FechaSalida='$fecha_salida', cc.SueldoBasico='$sueldo_basico', cc.CI='$cii',
                  cc.TipoContrato='$tipo_contrato' , cc.TipoEmpleado ='$tipo_empleado_th5' ,cc.IdSucursal= $sucursal123_su, cc.nivel=$o_nivel, cc.CargoId=$cargo123 WHERE cc.Codigo=$codigo_empleado and cc.Estado=1 "); 
            }



          $busca=query("SELECT * FROM datos_salario_empleados sl WHERE sl.codigo_e=$codigo_empleado");
          if(count($busca)==0){
           $query_salario="insert into datos_salario_empleados (codigo_e, salario, estado, idusuario, fecharegistro, idusuariom, fechamodificacion, IdCodigo) values 
           ($codigo_empleado,  $sueldo_basico, 1,1,'$fecha', 1,'$fecha',$iddc )";
           $ejec= query($query_salario);
          }else{
            $pre4=query("update datos_salario_empleados cc SET   cc.IdCodigo=$iddc, cc.Estado=0 ,cc.salario=$sueldo_basico, cc.FechaModificacion='$fecha' WHERE cc.codigo_e=$codigo_empleado ");
          } 

       
          
          $pre_logcodigoe=query("SELECT cc.* FROM tabla_logcodigoempleadoinactivo cc WHERE cc.CE=$codigo_empleado");
          if(count($pre_logcodigoe)==0){
           $guardar_inactivo=query("insert into tabla_logcodigoempleadoinactivo (CE, CB, IdUsuario, FechaRegistro, Detalle) values ($codigo_empleado,0, 0, '$fecha', 'registro automatizado' )");
          }


         }




 
       }else{
             $cdc=count($pre1);
             $guardar_inactivo=query("insert into ceduplicado(CodigoE, Cantidad, FechaRegistro) values ($codigo_empleado,$cdc,'$fecha')");
       }
     
    }


  }
}else{
  $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
  VALUES ('ejecutar_codigo_empleado_salario_1_validado_registro_baja.php','$fecha', 'No Hay datos')";
  $ejec_ser=query($ej_servicio);

}










































































    }   










  }else{
    $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
    VALUES ('ejecutar_codigo_empleado_salario_1_validado_registro_baja.php','$fecha', 'No Hay datos array')";
    $ejec_ser=query($ej_servicio);
  }
  

}



?>
