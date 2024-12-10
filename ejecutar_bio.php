
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


$students3= query("SELECT cc.Codigo AS codigo_empleado, cc.Estado AS estado,
    cc.Ci AS ci, cc.Nombre, cc.NO_User, cc.Tipo_Documento AS tipo_documento, cc.Extension AS extension, 
    cc.FechaIngreso AS fecha_ingreso, cc.FechaSalida AS fecha_salida, 
    cc.SueldoBasico AS sueldo_basico, 
    cc.TipoContrato AS tipo_contrato, pp.CargoTH5 AS cargo,  pp.AreaTH5 AS area,
    cc.TipoEmpleado AS tipo_empleado, oo.RTH5  AS sucursal, if(ISNULL(cc.jefeId), 0, cc.jefeId) as jefeId 
    FROM cempleados cc 
    INNER JOIN cargo pp ON pp.IdCargo= cc.CargoId
    INNER JOIN oficina oo ON oo.IdOficina= cc.IdSucursal
    WHERE cc.Estado=1   AND cc.TipoEmpleado='Regular' ORDER BY cc.NO_User  asc");
  
  
  
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
      $codigo_empleado_nombre= $students3[$ilc]['Nombre'];
      echo $codigo_empleado;
      echo '</br>';
      echo '</br>';
      echo $codigo_empleado_nombre;
      echo '</br>';
      echo '</br>';


      $fdc_aa=queryth5("SELECT
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
      where aa.fecha_ingreso IS NOT NULL and  
      aa.fecha_salida IS NOT NULL and
      aa.cargo is not null  and aa.estado=1 and aa.codigo_empleado=  $codigo_empleado
      ))TT  
      ORDER BY  TT.codigo_empleado  ASC");




if(count($fdc_aa)>0){


  $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
  VALUES ('ejecutar_codigo_empleado_salario_1_validado_registro_1.php','$fecha', '$codigo_empleado')";
  $ejec_ser=query($ej_servicio);
  for($ilcdd=0; $ilcdd<count($fdc_aa); $ilcdd++){

    $codigo_empleado= $fdc_aa[$ilcdd]['codigo_empleado'];
    $codigo_empleado_is=0;
    $o_nivel=0;
    echo '<br>';
    echo $codigo_empleado;
    echo '<br>';
    $estadoth5= $fdc_aa[$ilcdd]['estado'];
    $nombre=$fdc_aa[$ilcdd]['apellido_paterno'].' '.$fdc_aa[$ilcdd]['apellido_materno'].' '.$fdc_aa[$ilcdd]['nombres'];
    $cii= $fdc_aa[$ilcdd]['ci'];
    $cargo_th5= $fdc_aa[$ilcdd]['cargo'];
    $tipo_empleado_th5= $fdc_aa[$ilcdd]['tipo_empleado'];
    $nom_cargo_th5= $fdc_aa[$ilcdd]['nom_cargo'];
    $area_th5= $fdc_aa[$ilcdd]['area'];
    $nom_area_th5= $fdc_aa[$ilcdd]['nom_area'];
    $sucursal_th5= $fdc_aa[$ilcdd]['sucursal'];
    $jefe_id_th5= $fdc_aa[$ilcdd]['jefeid'];
    $tipo_documento= $fdc_aa[$ilcdd]['tipo_documento'];
    $extension= $fdc_aa[$ilcdd]['extension'];
    $fecha_ingreso= $fdc_aa[$ilcdd]['fecha_ingreso']->format('Y-m-d');
    $fecha_salida= $fdc_aa[$ilcdd]['fecha_salida']->format('Y-m-d');
    echo '<br>';
    echo $fecha_ingreso;
    echo '<br>';
    echo $fecha_salida;
    echo '<br>';
    $sueldo_basico= $fdc_aa[$ilcdd]['sueldo_basico'];
    $tipo_contrato= $fdc_aa[$ilcdd]['tipo_contrato'];

    $pre1 = query("SELECT  cc.Id,  cc.Codigo, cc.Estado FROM  cempleados cc  WHERE cc.Codigo=$codigo_empleado");
    if(count($pre1)>0){
       if(count($pre1)==1){

        $pre2 = query("SELECT  cc.Id,   cc.Codigo, cc.Estado FROM  cempleados cc  WHERE cc.Codigo=$codigo_empleado");
         
          if(count($pre2)==1 && $estadoth5==1){
            echo '<br>';
            echo '<br>';
            echo 2;
            echo '<br>';
            echo '<br>';
            $iddc= $pre2[0]['Id'];
        

              if($tipo_empleado_th5=='Regular'){





                if($cargo_th5!=0){
                  $dato123_cargo= query("SELECT * FROM cargo cc WHERE cc.CargoTH5=$cargo_th5");
                  if(count($dato123_cargo)>0){
                      $cargo123=  $dato123_cargo[0]['IdCargo'];
                      echo $codigo_empleado."<br>";
                      echo "<br>";
                      echo $nom_cargo_th5."<br>";
                      echo "<br>";
      
                      $dato123_codeq= query("SELECT cc.IdUsuario , cc.Estado FROM usuario cc WHERE cc.CodigoE=$codigo_empleado");
                      if(count($dato123_codeq)>0){
                      $dato123_=query("update usuario set IdCargo= $cargo123 WHERE CodigoE=$codigo_empleado ");
                      }
                  }
                }



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



                if($jefe_id_th5!=0){
                  $datos_is14=queryth5("SELECT cc.codigo_empleado AS INSUP, cc.estado from empleado cc where cc.cargo=$jefe_id_th5");
    
                  if(count($datos_is14)>0){
                    $codigo_empleado_is= $datos_is14[0]['INSUP'];
                    $dato123_insuperior= query("SELECT cc.IdUsuario , cc.Estado FROM usuario cc WHERE cc.CodigoE=$codigo_empleado_is");
                    if(count($dato123_insuperior)>0){
                      $inmsuperior_123=  $dato123_insuperior[0]['IdUsuario'];
                      echo $codigo_empleado."<br>";
                      echo "<br>";
                      $dato123_=query("update usuario set ISuperiorIdUsuarioMarcacion= $inmsuperior_123 WHERE CodigoE=$codigo_empleado");
                      $dato123_=query("update usuario set ISuperiorIdUsuario= $inmsuperior_123 WHERE CodigoE=$codigo_empleado");
                    }
    
                  }
               
                }
               
                $fdc_aaGG=queryth5("SELECT * FROM usuario where  codigo_empleado=  $codigo_empleado");
                if(count($fdc_aaGG)==0){
                  $dato123_=query("update usuario set SinUsuarioTH5=1 WHERE CodigoE=$codigo_empleado");
                  $dato123_=query("update cempleados cc set cc.SinUsuarioTH5=1 WHERE cc.Codigo=$codigo_empleado ");
                }else{
                  $dato123_=query("update usuario set SinUsuarioTH5=0 WHERE CodigoE=$codigo_empleado");
                  $dato123_=query("update cempleados cc set cc.SinUsuarioTH5=0 WHERE cc.Codigo=$codigo_empleado ");
                }
    


                $busca=query("SELECT * FROM datos_salario_empleados sl WHERE sl.codigo_e=$codigo_empleado");
                if(count($busca)==0){
                 $query_salario="insert into datos_salario_empleados (codigo_e, salario, estado, idusuario, fecharegistro, idusuariom, fechamodificacion, IdCodigo) values 
                 ($codigo_empleado,  $sueldo_basico, 1,1,'$fecha', 1,'$fecha',$iddc )";
                 $ejec= query($query_salario);
                }else{
                  $pre4=query("update datos_salario_empleados cc SET  cc.IdCodigo=$iddc,  cc.Estado=1,  cc.salario=$sueldo_basico,cc.FechaModificacion='$fecha' WHERE cc.codigo_e=$codigo_empleado ");
                } 
    
    
                 
                  $pre3="update cempleados cc SET cc.Estado=1, cc.jefeId= $jefe_id_th5, cc.FechaModificacion='$fecha' , cc.IdUsuarioModificante=1 , cc.ISuperior=$codigo_empleado_is , 
                  cc.Tipo_Documento='$tipo_documento', cc.Extension='$extension', cc.FechaIngreso='$fecha_ingreso', 
                  cc.FechaSalida='$fecha_salida', cc.SueldoBasico='$sueldo_basico', cc.CI='$cii',
                  cc.TipoContrato='$tipo_contrato' , cc.TipoEmpleado ='$tipo_empleado_th5' ,
                  cc.IdSucursal=$sucursal123_su, cc.nivel=$o_nivel  ,   cc.Nombre='$nombre',
                  cc.CargoId=$cargo123 WHERE cc.Codigo=$codigo_empleado "; 
    
                  echo $pre3."<br>";
                  echo "<br>";
    
                  $pre3= query($pre3);



                echo "SELECT CC.IdUsuario, CC.NombreUsuario, CC.Usuario, CC.Estado, CC.CodigoE FROM usuario CC WHERE CC.NombreUsuario LIKE '%$codigo_empleado_nombre%'";
                echo '<br>';
                echo '<br>';
                $USAR_ =query("SELECT CC.IdUsuario, CC.NombreUsuario, CC.Usuario, CC.Estado, CC.CodigoE FROM usuario CC WHERE CC.NombreUsuario LIKE '%$codigo_empleado_nombre%'");
                if(count($USAR_)==1){
                 $fgg= $USAR_[0]['IdUsuario'];
                 echo $fgg;
                 echo '<br>';
                 echo '<br>';
                 echo $USAR_[0]['CodigoE'];
                 echo '<br>';
                 $prew="update usuario cc SET cc.CodigoE=$codigo_empleado , cc.Estado=1,cc.FechaCCU='$fecha'  WHERE cc.IdUsuario=$fgg "; 
                 $prew= query($prew);
                 $prew="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.RTU=$fgg WHERE cc.Codigo=$codigo_empleado  "; 
                 $prew= query($prew);
                 $prew="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.NO_User=1 WHERE cc.Codigo=$codigo_empleado  "; 
                 $prew= query($prew);
                }else{

                  if(count($USAR_)>0){
                    $prew="update usuario cc SET cc.Estado=0  WHERE cc.NombreUsuario LIKE '%$codigo_empleado_nombre%' "; 
                    $prew= query($prew);
                    $preww="SELECT CC.IdUsuario, CC.NombreUsuario, CC.Usuario, CC.Estado,
                    CC.CodigoE, CC.Estado, CC.FechaCreacion  FROM usuario CC 
                    WHERE CC.NombreUsuario LIKE '%$codigo_empleado_nombre%' ORDER BY CC.FechaCreacion DESC LIMIT 1"; 
                    $preww= query($preww);
                     

                    $fgg= $preww[0]['IdUsuario'];
                    echo $fgg;
                    echo '<br>';
                    echo '<br>';
                    echo $preww[0]['CodigoE'];
                    echo '<br>';
                    $prew="update usuario cc SET cc.CodigoE=$codigo_empleado , cc.Estado=1,cc.FechaCCU='$fecha'  WHERE cc.IdUsuario=$fgg "; 
                    $prew= query($prew);
                    $prew="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.RTU=$fgg WHERE cc.Codigo=$codigo_empleado  "; 
                    $prew= query($prew);
                    $prew="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.NO_User=1 WHERE cc.Codigo=$codigo_empleado  "; 
                    $prew= query($prew);



                  }else{
                    $prew="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.NO_User=0 WHERE cc.Codigo=$codigo_empleado  "; 
                    $prew= query($prew);
                  }
                 
                }





                $cdch='';
                $dchj=query("SELECT bb.IdUser, bb.Nombre, bb.Fecha  
                FROM biometrico.control_marcaciones bb WHERE bb.IdUser =$codigo_empleado
                and bb.Fecha >='2022-08-07'
                ORDER BY bb.Fecha DESC LIMIT 0,1");
                if(count($dchj)>0){
                  $cdch= $dchj[0]['Nombre'];
                  $datos_lis= "SELECT cc.* FROM lista_cb cc WHERE cc.CodigoE=$codigo_empleado AND cc.CodigoBio=$codigo_empleado  and cc.CI='$cii'";
                  $datos_lis= query($datos_lis);




                  if(count($datos_lis)==0){
                    $cdc= query("insert into lista_cb (codigoe, codigobio, fecharegistro, fechamodificacion, estado, idusuarior, Nombre,CI )
                    VALUES ($codigo_empleado,$codigo_empleado,'$fecha','$fecha',1,1,'$cdch' , '$cii')");
            
            
                    $pre3="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.NombreBioUltimo='$cdch', ECB=1 WHERE cc.Codigo=$codigo_empleado  "; 
                    $pre3= query($pre3);

            

                       
                    $dddc= query("SELECT cc.* FROM lista_cb cc WHERE cc.CI='$cii'");
                    if(count($dddc)>0){
                           $cdch=$dddc[0]['Nombre'];
                           if($cdch!=''){
                            $ghj="SELECT ff.IdUser, ff.Nombre FROM biometrico.datos_user ff WHERE  ff.Nombre LIKE '%$cdch%' ORDER BY ff.Nombre";
                                                    echo $ghj;
                                                    $cam_ids=query($ghj);
                      
                                                    if(count($cam_ids)>0){
                                                      for($chh=0; $chh<count($cam_ids); $chh++)
                                                      {
                                                        $cobio= $cam_ids[$chh]['IdUser'];
                                                        $cobio_nom= $cam_ids[$chh]['Nombre'];
                                                        $datos_lis= "SELECT cc.* FROM lista_cb cc WHERE cc.Ci='$cii' and cc.Nombre='$cobio_nom'  AND cc.CodigoBio=$cobio";
                                                        echo $datos_lis;
                                                        $datos_lis= query($datos_lis);
                                                         if(count($datos_lis)==0){
                                                         echo 'vacio';
                                                            $cdc=query("insert into lista_cb (codigoe, codigobio, fecharegistro, fechamodificacion, estado, idusuarior, Nombre, CI)
                                                            VALUES (0,$cobio,'$fecha','$fecha',1,1, '$cobio_nom', '$cii')");
                                                         }
                                                      }
                                                    }
                                
                                                
                           }
                      
                    }

                  }else{
            
                    $cdc= query("insert into log_nbio_ce (CodigoE, FechaRegistro, NombreBio, CodigoBio)
                    VALUES ($codigo_empleado,'$fecha','$cdch' , $codigo_empleado)");
            
                    $cdc=query("update lista_cb cc SET cc.FechaModificacion='$fecha' , cc.Nombre='$cdch' WHERE cc.CodigoE=$codigo_empleado AND cc.CodigoBio=$codigo_empleado  AND cc.CI='$cii'   and cc.Estado=1"); 
                    $pre3="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.NombreBioUltimo='$cdch', ECB=1 WHERE cc.Codigo=$codigo_empleado and cc.Estado=1 "; 
                                $pre3= query($pre3);


                                $dddc= query("SELECT cc.* FROM lista_cb cc WHERE cc.CI='$cii'");
                                if(count($dddc)>0){
                                       $cdch=$dddc[0]['Nombre'];
                                       if($cdch!=''){
                                        $ghj="SELECT ff.IdUser, ff.Nombre FROM biometrico.datos_user ff WHERE  ff.Nombre LIKE '%$cdch%' ORDER BY ff.Nombre";
                                                                echo $ghj;
                                                                $cam_ids=query($ghj);
                                  
                                                                if(count($cam_ids)>0){
                                                                  for($chh=0; $chh<count($cam_ids); $chh++)
                                                                  {
                                                                    $cobio= $cam_ids[$chh]['IdUser'];
                                                                    $cobio_nom= $cam_ids[$chh]['Nombre'];
                                                                    $datos_lis= "SELECT cc.* FROM lista_cb cc WHERE cc.Ci='$cii' and cc.Nombre='$cobio_nom'  AND cc.CodigoBio=$cobio";
                                                                    echo $datos_lis;
                                                                    $datos_lis= query($datos_lis);
                                                                     if(count($datos_lis)==0){
                                                                     echo 'vacio';
                                                                        $cdc=query("insert into lista_cb (codigoe, codigobio, fecharegistro, fechamodificacion, estado, idusuarior, Nombre, CI)
                                                                        VALUES (0,$cobio,'$fecha','$fecha',1,1, '$cobio_nom', '$cii')");
                                                                     }
                                                                  }
                                                                }
                                            
                                                            
                                       }
                                  
                                }
          

            
            
            
                  }
                }else{
                  $datos_lis= "SELECT cc.* FROM lista_cb cc WHERE cc.CodigoE=$codigo_empleado AND cc.CodigoBio=$codigo_empleado  and cc.CI='$cii'   AND cc.Estado=1";
                  $datos_lis= query($datos_lis);
                  if(count($datos_lis)==0){
                    $cdc= query("insert into lista_cb (codigoe, codigobio, fecharegistro, fechamodificacion, estado, idusuarior, Nombre, CI)
                    VALUES ($codigo_empleado,$codigo_empleado,'$fecha','$fecha',1,1,'' , '$cii')");
            
            
                    $pre3="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.NombreBioUltimo='' ,  ECB=0 WHERE cc.Codigo=$codigo_empleado and cc.Estado=1 "; 
                    $pre3= query($pre3);
            
            
                  }else{
            
                    $cdc= query("insert into log_nbio_ce (CodigoE, FechaRegistro, NombreBio, CodigoBio)
                    VALUES ($codigo_empleado,'$fecha','' , $codigo_empleado)");
            
                    $cdc=query("update lista_cb cc SET cc.FechaModificacion='$fecha' , cc.Nombre='' WHERE cc.CodigoE=$codigo_empleado AND cc.CodigoBio=$codigo_empleado  and cc.CI='$cii' and cc.Estado=1");  
            
                    $pre3="update cempleados cc SET cc.FechaModificacion='$fecha' , cc.NombreBioUltimo='', ECB=0 WHERE cc.Codigo=$codigo_empleado and cc.Estado=1 "; 
                                $pre3= query($pre3);
            
                  }
                }
            
          
          
              }









          }

 
       }else{
             $cdc=count($pre1);
             $guardar_inactivo=query("insert into ceduplicado(CodigoE, Cantidad, FechaRegistro) values ($codigo_empleado,$cdc,'$fecha')");
       }
     
    }








  }




}




    }
  }else{
    $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
    VALUES ('ejecutar_codigo_empleado_salario_1_validado_registro_1.php','$fecha', 'No hay datos array')";
    $ejec_ser=query($ej_servicio);
  
  }
  

 

?>
