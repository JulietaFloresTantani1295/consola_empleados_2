
<?php
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("America/La_Paz");
error_reporting(E_ALL);
ini_set('display_errors', 1);
$fecha=date('Y-m-d H:i:s');
include "config/config_soli_p.php";
include "config/config_biometrico.php";
include "config/con_query.php";
include "abc2.php";
include "server.php";


$lista_empresas= query("SELECT * FROM empresa cc WHERE cc.Estado=1  AND cc.IdEmpresa=1");
if(count($lista_empresas)>0){



  for($ilc=0; $ilc<count($lista_empresas); $ilc++)
    {
      $idem= $lista_empresas[$ilc]['IdEmpresa'];
      $servidor= $lista_empresas[$ilc]['servidor'];
      $dbn= $lista_empresas[$ilc]['dbn'];
      $usuario_s= $lista_empresas[$ilc]['usuario_s'];
      $contrasenia_s= $lista_empresas[$ilc]['contrasenia_s'];

        if(isset($servidor) && isset($dbn)  && isset($usuario_s)  && isset($contrasenia_s)  ){


          $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
          VALUES ('coneccion_th5G_SM.php','$fecha', 'Hay datos')";
          $ejec_ser=query($ej_servicio);
        


          // area 
          $lista_area_th5=queryth5t("select cc.id, cc.codigo, cc.nombre, cc.estado from area cc  order by cc.codigo asc", $servidor, $dbn, $usuario_s, $contrasenia_s);
          echo count($lista_area_th5);
          if(count($lista_area_th5)>0){
            for($i1a=0; $i1a<count($lista_area_th5); $i1a++)
            {
              $id_area_th5=$lista_area_th5[$i1a]['id'];
              $codigo_area_th5=$lista_area_th5[$i1a]['codigo'];
              $nombre_area_th5=$lista_area_th5[$i1a]['nombre'];
              $estado_area_th5=$lista_area_th5[$i1a]['estado'];
              echo $nombre_area_th5."<br>";
              echo "<br>";
               if($estado_area_th5!=1){
                $estado_area_th5=0;
               }
               $lista_area_sn=query("SELECT  *  FROM area aa WHERE aa.AreaTH5= $codigo_area_th5 AND aa.IdEmpresa=$idem ORDER BY aa.IdEmpresa desc");
               if(count($lista_area_sn)>0){
                   $actualizar_area=query("update area set  FechaModificacion='$fecha'  , NombreTH5='$nombre_area_th5', Nombre='$nombre_area_th5' , Estado= $estado_area_th5  where  AreaTH5= $codigo_area_th5 AND IdEmpresa=$idem");
               }else{
                $insertar_area =query("insert into area (IdArea, Nombre, Descripcion, Estado, FechaCreacion,  IdUsuarioCreacion,IdEmpresa, AreaTI, AreaTH5, NombreTH5, FechaModificacion ) 
                values (0,'$nombre_area_th5', '', $estado_area_th5 , '$fecha', 1, $idem, 0, $codigo_area_th5, '$nombre_area_th5', '$fecha')");
               }
            }
          }
          $actualizar_area_total=query("update area SET idarea = iddarea WHERE idarea!= 21 AND idarea!=1  ");

          $actualizar_area_total=query("update area cc SET cc.idarea = 0, cc.FechaModificacion='$fecha' WHERE cc.IddArea=105  ");








          //cargo
          $lista_cargo_th5=queryth5t("select cc.codigo, cc.descripcion, cc.descripcion2, cc.estado, cc.jefeid, cc.area from cargo cc  order by cc.area desc", $servidor, $dbn, $usuario_s, $contrasenia_s);
          echo count($lista_cargo_th5);
          if(count($lista_cargo_th5)>0){
            for($i1ac=0; $i1ac<count($lista_cargo_th5); $i1ac++)
            {
              $codigo_cargo_th5=$lista_cargo_th5[$i1ac]['codigo'];
              $descripcion_cargo_th5=$lista_cargo_th5[$i1ac]['descripcion'];
              $descripcion2_cargo_th5=$lista_cargo_th5[$i1ac]['descripcion2'];
              $estado_cargo_th5=$lista_cargo_th5[$i1ac]['estado'];
              $jefeid_cargo_th5=$lista_cargo_th5[$i1ac]['jefeid'];
              $area_cargo_th5=$lista_cargo_th5[$i1ac]['area'];
              
              echo $descripcion_cargo_th5."<br>";
              echo "<br>";
               if($estado_cargo_th5==2){
                $estado_cargo_th5=1;
               }

                          $lista_cargo_sn=query("SELECT  *  FROM cargo cc WHERE cc.CargoTH5=$codigo_cargo_th5  ");
                          if(count($lista_cargo_sn)>0){

                                 if(count($lista_cargo_sn)>1){
                                       $actualizar_area=query("delete  from cargo where  CargoTH5= $codigo_cargo_th5"); 
                                       $lista_area_snn=query("SELECT  *  FROM area aa WHERE aa.AreaTH5= $area_cargo_th5 AND aa.IdEmpresa=$idem ORDER BY aa.IdEmpresa desc");
                                       if(count($lista_area_snn)>0){
                                             $id_areaa=$lista_area_snn[0]['IdArea'];
                                             $insertar_cargo =query("insert into cargo (IdCargo,IdArea, Nombre, Descripcion, Estado, FechaCreacion,  IdUsuarioCreacion,Numb,posi, OAdmin, CargoTH5,AreaTH5, NombreTH5, FechaModificacion ) 
                                             values (0,$id_areaa,'$descripcion_cargo_th5', '$descripcion2_cargo_th5', $estado_cargo_th5 , '$fecha', 1, $jefeid_cargo_th5, 0,0, $codigo_cargo_th5, $area_cargo_th5,'$descripcion_cargo_th5', '$fecha')");     
                                       }
                                 }else{
                                  $lista_area_snn=query("SELECT  *  FROM area aa WHERE aa.AreaTH5= $area_cargo_th5 AND aa.IdEmpresa=$idem ORDER BY aa.IdEmpresa desc");
                                  if(count($lista_area_snn)>0){
                                        $id_areaa=$lista_area_snn[0]['IdArea'];
                                        $actualizar_area=query("update cargo set FechaModificacion='$fecha'  , NombreTH5='$descripcion_cargo_th5', Nombre='$descripcion_cargo_th5' , Estado= $estado_cargo_th5 ,
                                        AreaTH5=$area_cargo_th5, Descripcion='$descripcion2_cargo_th5', IdCargo=$codigo_cargo_th5, IdArea=$id_areaa, Numb=$jefeid_cargo_th5  where  CargoTH5= $codigo_cargo_th5");
                                  }
                                 }


                     
                          }else{
                           $lista_area_snn=query("SELECT  *  FROM area aa WHERE aa.AreaTH5= $area_cargo_th5 AND aa.IdEmpresa=$idem ORDER BY aa.IdEmpresa desc");
                           if(count($lista_area_snn)>0){
                                 $id_areaa=$lista_area_snn[0]['IdArea'];
                                 $insertar_cargo =query("insert into cargo (IdCargo,IdArea, Nombre, Descripcion, Estado, FechaCreacion,  IdUsuarioCreacion,Numb,posi, OAdmin, CargoTH5,AreaTH5, NombreTH5, FechaModificacion ) 
                                 values (0,$id_areaa,'$descripcion_cargo_th5', '$descripcion2_cargo_th5', $estado_cargo_th5 , '$fecha', 1, $jefeid_cargo_th5, 0,0, $codigo_cargo_th5, $area_cargo_th5,'$descripcion_cargo_th5', '$fecha')");     
                           }
                          }
                       
            }
          }
          $actualizar_cargo_total=query("update cargo SET idcargo = iddcargo  ");










          //sucursales
          $lista_sucursales_th5=queryth5t("select cc.idsucursal, cc.idciudad, cc.sucursal, cc.direccion, cc.estado, cc.telefono,
           cc.nro_patronal from sucursales cc order by cc.idsucursal desc", $servidor, $dbn, $usuario_s, $contrasenia_s);
          echo count($lista_sucursales_th5);
           if(count($lista_sucursales_th5)>0){
            for($i1=0; $i1<count($lista_sucursales_th5); $i1++)
            {
              $idsu_th5=$lista_sucursales_th5[$i1]['idsucursal'];
              $nombre_th5=$lista_sucursales_th5[$i1]['sucursal'];
              $direccion_th5=$lista_sucursales_th5[$i1]['direccion'];
              $estado_th5=$lista_sucursales_th5[$i1]['estado'];
              $idciudad_th5=$lista_sucursales_th5[$i1]['idciudad'];

              echo $nombre_th5."<br>";
              echo "<br>";
          
                  if($estado_th5!=1){
                      $estado_th5=0;
                  }

                  
                  $lista_ciudades_th5=queryth5t("select * from ciudades  cc where cc.idciudad=$idciudad_th5 order by cc.idciudad desc", $servidor, $dbn, $usuario_s, $contrasenia_s);
                   if(count($lista_ciudades_th5)>0){
                    $id_depart_th5=$lista_ciudades_th5[0]['iddepto'];
                    $lista_depto_th5=queryth5t("select *from departamentos  cc  where cc.iddepto=$id_depart_th5 order by cc.iddepto desc", $servidor, $dbn, $usuario_s, $contrasenia_s);
                    $nombre_depto_th5=$lista_depto_th5[0]['nombre'];    
                    $estado_depto_th5=$lista_depto_th5[0]['estado'];


                    if($estado_depto_th5!=1){
                      $estado_depto_th5=0;
                     }

                        $buscar2=query("SELECT *FROM ciudad cc WHERE cc.IdDeptoTH5=$id_depart_th5  and cc.IdEmpresa= $idem");
                        $Id_ciudad_ns=$buscar2[0]['IdCiudad'];
                        if(count($buscar2)>0){
                             $actualizar2=query("update ciudad set Nombre='$nombre_depto_th5' , Estado=$estado_depto_th5,
                                                   FechaModificacion='$fecha', Modificante='Automatico', IdEmpresa=$idem , IdPais=$idem  where IdDeptoTH5=$id_depart_th5 and IdEmpresa= $idem");

                        $ft456="SELECT  * FROM  oficina cc WHERE cc.IdEmpresa=$idem AND cc.RTH5=$idsu_th5";
                                                   echo $ft456."<br>";
                                                   echo "<br>";

                                  $pregunta1=query("SELECT  * FROM  oficina cc WHERE cc.IdEmpresa=$idem AND cc.RTH5=$idsu_th5");
                                  if(count($pregunta1)>0){
                                    $idoficina= $pregunta1[0]['IdOficina'];
                                    echo 'actaulizar'."<br>";
                                    echo "<br>";
                                           $actualizar1=query("update oficina set IdEmpresa= $idem,Nombre='$nombre_th5' , ubicacion='$direccion_th5', Estado=$estado_th5,
                                            FechaModificacion='$fecha', IdCiudad= $Id_ciudad_ns, Modificante='Automatico',IdCiudadTH5= $idciudad_th5 where RTH5=$idsu_th5");

                                          $vali_biometrico= query("SELECT* FROM soli_p.lista_biometricos oo  WHERE oo.IdSucursal=$idoficina ORDER BY oo.FechaRegistro DESC");
                                          if(count($vali_biometrico)>0){
                                            $estado_biometrico= $vali_biometrico[0]['Estado'];
                                            $aut_biometrico=query("update lista_biometricos cc SET cc.fechamodificacionaut='$fecha', cc.Estado=$estado_biometrico WHERE cc.idsucursal=$idoficina");
                                          }



                                  }else{
                                    echo 'registrar'."<br>";
                                    echo "<br>";
                                    $insertar1 =query("insert into oficina (IdEmpresa, Nombre, Descripcion, Ubicacion, FechaCreacion, Estado, IdUsuarioCreacion,
                                    IdCiudad, RTH5, FechaModificacion,Modificante, IdCiudadTH5 ) 
                                    values ($idem, '$nombre_th5', '', '$direccion_th5' ,'$fecha',$estado_th5,1,$Id_ciudad_ns,$idsu_th5, '$fecha',  'Registro Automatico', $idciudad_th5 )");



                                    $vali_biometrico= query("SELECT* FROM soli_p.lista_biometricos oo  WHERE oo.IdSucursal=$insertar1 ORDER BY oo.FechaRegistro DESC");
                                    if(count($vali_biometrico)>0){
                                      $idoficina= $vali_biometrico[0]['IdSucursal'];
                                      $estado_biometrico= $vali_biometrico[0]['Estado'];
                                      $aut_biometrico=query("update lista_biometricos cc SET cc.fechamodificacionaut='$fecha', cc.Estado=$estado_biometrico WHERE cc.idsucursal=$idoficina");
                                    }else{
                                      $insert_biometrico=query("insert into lista_biometricos (IdSucursal, IP, FechaRegistro, Estado, FechaModificacion, IdUsuarioM, Puerto)VALUES
                                       ($insertar1, '', '$fecha', 1,'$fecha', 1,4730)");

                                       $insert_estado_biometrico=querybio("insert into estado_biometrico ( IP, IdSucursal, Estado, FechaRegistro,FechaModificacion)VALUES
                                       ('', $insertar1, 1,'$fecha','$fecha')");

                                    }



                                  }
                        }else{
                               $insertar2 =query("insert into ciudad (Nombre, FechaRegistro, FechaModificacion, IdUsuario, IdEmpresa, Estado, IdPais, IdDeptoTH5, Modificante) 
                               values ('$nombre_depto_th5', '$fecha', '$fecha', 1,$idem, $estado_depto_th5,$idem,$id_depart_th5, 'Registro Automatico' )");

                               $buscar22=query("SELECT *FROM ciudad cc WHERE cc.IdDeptoTH5=$id_depart_th5");
                               $Id_ciudad_ns=$buscar22[0]['IdCiudad'];

                               $pregunta1=query("SELECT  * FROM  oficina cc WHERE cc.IdEmpresa=$idem AND cc.RTH5=$idsu_th5");
                               if(count($pregunta1)>0){
                                        $actualizar1=query("update oficina set IdEmpresa= $idem,Nombre='$nombre_th5' , ubicacion='$direccion_th5', Estado=$estado_th5,
                                         FechaModificacion='$fecha', IdCiudad= $Id_ciudad_ns, Modificante='Automatico',IdCiudadTH5= $idciudad_th5 where RTH5=$idsu_th5");
                               }else{

                                 $insertar1 =query("insert into oficina (IdEmpresa, Nombre, Descripcion, Ubicacion, FechaCreacion, Estado, IdUsuarioCreacion,
                                 IdCiudad, RTH5, FechaModificacion,Modificante, IdCiudadTH5 ) 
                                 values ($idem, '$nombre_th5', '', '$direccion_th5' ,'$fecha',$estado_th5,1,$Id_ciudad_ns,$idsu_th5, '$fecha',  'Registro Automatico', $idciudad_th5 )");

                                 $vali_biometrico= query("SELECT* FROM soli_p.lista_biometricos oo  WHERE oo.IdSucursal=$insertar1 ORDER BY oo.FechaRegistro DESC");
                                 if(count($vali_biometrico)>0){
                                   $idoficina= $vali_biometrico[0]['IdSucursal'];
                                   $estado_biometrico= $vali_biometrico[0]['Estado'];
                                   $aut_biometrico=query("update lista_biometricos cc SET cc.fechamodificacionaut='$fecha', cc.Estado=$estado_biometrico WHERE cc.idsucursal=$idoficina");
                                 }else{
                                   $insert_biometrico=query("insert into lista_biometricos (IdSucursal, IP, FechaRegistro, Estado, FechaModificacion, IdUsuarioM, Puerto)VALUES
                                    ($insertar1, '', '$fecha', 1,'$fecha', 1,4730)");

                                    $insert_estado_biometrico=querybio("insert into estado_biometrico ( IP, IdSucursal, Estado, FechaRegistro,FechaModificacion)VALUES
                                    ('', $insertar1, 1,'$fecha','$fecha')");
                                 }




                               }
                        }




                   }
            }

           }






















        }else{
          $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
          VALUES ('coneccion_th5G_SM.php','$fecha', 'No Hay datos')";
          $ejec_ser=query($ej_servicio);
        }
      
      
      
      
    }

}






?>
