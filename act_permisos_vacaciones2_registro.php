
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


  
$datos_aqui1= query("SELECT   cc.codigo_e as codigo_empleado,  cc.documento , cc.estado , cc.fechainicio, cc.fechafin FROM tablapv cc WHERE cc.estado in (0,1,2,3) ORDER BY cc.fechasolicitud desc");



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



// for($i1accc=0; $i1accc<count($students3); $i1accc++)
// {
//     $cdf=count($students3)-1-$i1accc;
//     $objeto11 = $students3[$cdf];
//     array_push($students4, $objeto11);
// }











if(count($students3)>0){



  $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
  VALUES ('act_permisos_vacaciones2_registro.php','$fecha', 'Hay datos array')";

  $ejec_ser=query($ej_servicio);

  for($ilc=0; $ilc<count($students3); $ilc++)
  {
 
   echo json_encode($students3[$ilc]);
   echo '--------------'."<br>";
 
 $codigo_empleado= $students3[$ilc]['codigo_empleado'];
 $documento=$students3[$ilc]['documento'];
 $estado=$students3[$ilc]['estado'];
 $fecha_inii=$students3[$ilc]['fecha_ini'];
 $fecha_finn=$students3[$ilc]['fecha_fin'];





 
 
 echo $codigo_empleado."<br>";
 echo $documento."<br>";
 echo $estado."<br>";
 
 
 $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_empleado and  tt.documento='$documento'  and tt.estado=$estado AND tt.fechainicio ='$fecha_inii' AND tt.fechafin='$fecha_finn' ";
 $pre= query($pre);
 echo count($pre)."<br>";
 
 








 if(count($pre)==0){
   echo "No existe"."<br>";
   echo '--------------'."<br>";
 
 
 
   $p_v= "SELECT
   T.codigo_empleado,
   T.documento,
   T.vacacion,
   T.fechasolicitud,
   T.detalle,
   T.motivo,
   T.numeroDias,
   T.fechaInicio,
   T.fechaFin,
   T.estado,
   T.fechaAprobado,
   T.fechaCancelado,
   T.aprobador,
   T.rechazador
 
  
 FROM 	(( 
 SELECT
 
 
   T.codigo_empleado,
   T.documento,
   ccc.vacacion,
   ccc.fechasolicitud,
   ccc.detalle,
   ccc.motivo,
   ccc.numeroDias,
   ccc.fechaInicio,
   ccc.fechaFin,
   ccc.estado,
   ccc.fechaAprobado,
   ccc.fechaCancelado,
   (SELECT CONCAT(apellido_paterno,' ',apellido_materno,' ',nombres) from vi_usuarioEmpleado where vi_usuarioEmpleado.codigo_usuario=ccc.usrAprobado) AS aprobador,
   (SELECT CONCAT(apellido_paterno,' ',apellido_materno,' ',nombres) from vi_usuarioEmpleado where vi_usuarioEmpleado.codigo_usuario=ccc.ursCancelado) AS rechazador
  
 FROM 	(( 
 SELECT cc.codigo_empleado  ,  cc.documento , max(cc.fechasolicitud) as fecha
 FROM empleado_vacacion cc
 INNER JOIN empleado ee ON cc.codigo_empleado = ee.codigo_empleado
 LEFT JOIN empleado_cargo ccargo ON cc.codigo_empleado = ccargo.codigo_empleado
 INNER JOIN dbo.cargo gh ON ccargo.cargo = gh.codigo
 where ccargo.estado=1  and cc.codigo_empleado= $codigo_empleado  and  cc.documento='$documento' 
 group by cc.codigo_empleado, cc.documento 
 
 ))T  inner join empleado_vacacion ccc on ccc.codigo_empleado= T.codigo_empleado and ccc.documento= T.documento and ccc.fechasolicitud= T.fecha
 where ccc.estado=$estado and ccc.fechaInicio ='$fecha_inii' and ccc.fechaFin='$fecha_finn'
 
 ))T ";
 
 
 $p_v=queryth5($p_v);
 
 $codigo_e=$codigo_empleado;
 $vacacion=$p_v[0]['vacacion'];
 $fechas=$p_v[0]['fechasolicitud']->format('Y-m-d H:i:s');
 $detalle=$p_v[0]['detalle'];
 $motivo=$p_v[0]['motivo'];
 $numeroDias=$p_v[0]['numeroDias'];
 $fi_pv=$p_v[0]['fechaInicio']->format('Y-m-d');
 $ff_pv=$p_v[0]['fechaFin']->format('Y-m-d');
 $estau=$p_v[0]['estado'];   
 



 echo $estau."<br>";


 print_r($p_v)."<br>";

 echo 'Fin ----' ."<br><br><br><br><br><br><br>";
 
 



 if($estau==2){  //aprobado
 $fechaAprobado=$p_v[0]['fechaAprobado']->format('Y-m-d H:i:s'); 
 $aprobador=$p_v[0]['aprobador'];  
 $query_salario="insert into tablapv (codigo_e, documento, vacacion, fechasolicitud, Detalle, motivo, dias, fechainicio, fechafin, estado, fechaaprobado, aprobador, fechaM)
 VALUES ($codigo_e,'$documento', '$vacacion', '$fechas', '$detalle', '$motivo', '$numeroDias','$fi_pv', '$ff_pv', $estau,'$fechaAprobado', '$aprobador', '$fecha')";
 $ejec= query($query_salario);
 
 
 
 
 
 
 $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento' and tt.estado=$estado ";
 $pre= query($pre);
 
 
 if(count($pre)>0){
 
   $ID__=$pre[0]['Id'];
   echo "<br>";
   echo "<br>";
   echo $ID__;
   echo "<br>";
   echo "<br>";
   echo $codigo_empleado;
   echo "<br>";
   echo "<br>";
   echo '--------------'."<br>";
 
 
 
 
 
  //  $tolerancia_th5= query("SELECT date_field 
  //  FROM
  //  (
  //  SELECT
  //  MAKEDATE(YEAR('$fi_pv'),1) +
  //  INTERVAL (MONTH('$fi_pv')-1) MONTH +
  //  INTERVAL daynum DAY date_field
  //  FROM
  //  (
  //  SELECT t*10+u daynum
  //  FROM
  //  (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
  //  (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
  //  UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
  //  UNION SELECT 8 UNION SELECT 9) B
  //  ORDER BY daynum
  //  ) AA
  //  ) AAA
  //  WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'");
 
 $tolerancia_th5=query("SELECT date_field 
FROM
(
   SELECT
      MAKEDATE(YEAR('$fi_pv'), 1) +
      INTERVAL daynum DAY AS date_field
   FROM
   (
      SELECT t*100 + u*10 + v AS daynum
      FROM
      (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4) A,
      (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) B,
      (SELECT 0 v UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) C
      ORDER BY daynum
   ) AA
) AAA
WHERE date_field >= '$fi_pv' AND date_field <= '$ff_pv'
ORDER BY date_field ASC");  



    if(count($tolerancia_th5)>0){
 
      for($top=0; $top<count($tolerancia_th5); $top++)
      {
       $fecho= $tolerancia_th5[$top]['date_field'];
 
 
         $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
         ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
         FROM detalleturnosemana dd 
         INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
         INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
         INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
         INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
         INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
         INNER JOIN cempleados ce ON ce.Horario=rr.IdR
         INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
         WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
         AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
         GROUP BY hh.Idh");
 
         if(count($gyo1)>0){
          for($topt=0; $topt<count($gyo1); $topt++)
          {
            $dias_cat_tb = $gyo1[$topt]['Posis'];
            $IdT_tb=  $gyo1[$topt]['Posit'];
            $IdP_tb= $gyo1[$topt]['IdP'];
            $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
            
               $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
               if(count($buscar_l)>0){

               $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
               where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
               echo $yuii."<br>";
               echo "<br>";
                   $actualizar_fecha_r=query($yuii);


               }
          } 
         }
 
  
 
 
      }
 
    }
 
 
 }
 
 
 
 
 
 
 
 
 
 }else{
 
 if($estau==1){  //pendiente
   $query_salario="insert into tablapv (codigo_e, documento, vacacion, fechasolicitud, Detalle, motivo, dias, fechainicio, fechafin, estado, fechaaprobado, aprobador, fechaM)
   VALUES ($codigo_e,'$documento', '$vacacion', '$fechas', '$detalle', '$motivo', '$numeroDias','$fi_pv', '$ff_pv', $estau,NULL, '', '$fecha')";
   $ejec=query($query_salario);
 
 
 
 
 
   $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento' and tt.estado=$estado ";
   $pre= query($pre);
   
   
   if(count($pre)>0){
   
     $ID__=$pre[0]['Id'];
     echo "<br>";
     echo "<br>";
     echo $ID__;
     echo "<br>";
     echo "<br>";
     echo $codigo_empleado;
     echo "<br>";
     echo "<br>";
     echo '--------------'."<br>";
   
 
 
 
 
     $tolerancia_th5= query("SELECT date_field 
     FROM
     (
     SELECT
     MAKEDATE(YEAR('$fi_pv'),1) +
     INTERVAL (MONTH('$fi_pv')-1) MONTH +
     INTERVAL daynum DAY date_field
     FROM
     (
     SELECT t*10+u daynum
     FROM
     (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
     (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
     UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
     UNION SELECT 8 UNION SELECT 9) B
     ORDER BY daynum
     ) AA
     ) AAA
     WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'");
   
     
      if(count($tolerancia_th5)>0){
   
        for($top=0; $top<count($tolerancia_th5); $top++)
        {
         $fecho= $tolerancia_th5[$top]['date_field'];
   
   
           $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
           ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
           FROM detalleturnosemana dd 
           INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
           INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
           INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
           INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
           INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
           INNER JOIN cempleados ce ON ce.Horario=rr.IdR
           INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
           WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
           AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
           GROUP BY hh.Idh");
   
 
 if(count($gyo1)>0){

  for($topt=0; $topt<count($gyo1); $topt++)
        {

          
 
   $dias_cat_tb = $gyo1[$topt]['Posis'];
   $IdT_tb=  $gyo1[$topt]['Posit'];
   $IdP_tb= $gyo1[$topt]['IdP'];
   $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
   
      $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
      if(count($buscar_l)>0){
      $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
      where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
      echo $yuii."<br>";
      echo "<br>";
          $actualizar_fecha_r=query($yuii);
      }
        }
 }
 
        
   
   
        }
   
      }
 
 
 
 
     
   
   
   }
   
 
 
 
 
 
 }else{
   if($estau==3 ){ //rechazado


    echo 'rechazado';
    echo "<br>";
    echo "<br>";
     $fecharechazado=$p_v[0]['fechaCancelado']->format('Y-m-d H:i:s'); 
     $rechazador=$p_v[0]['rechazador'];  
     $query_salario="insert into tablapv (codigo_e, documento, vacacion, fechasolicitud, Detalle, motivo, dias, fechainicio, fechafin, estado, fechaaprobado, aprobador, fechaM)
     VALUES ($codigo_e,'$documento', '$vacacion', '$fechas', '$detalle', '$motivo', '$numeroDias','$fi_pv', '$ff_pv', $estau,'$fecharechazado', '$rechazador', '$fecha')";
     $ejec=query($query_salario);
 
 
 
 
 
     $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento' and tt.estado=$estado ";
     $pre= query($pre);
     
     
     if(count($pre)>0){
     
       $ID__=$pre[0]['Id'];
       echo "<br>";
       echo "<br>";
       echo $ID__;
       echo "<br>";
       echo "<br>";
       echo $codigo_empleado;
       echo "<br>";
       echo "<br>";
       echo '--------------'."<br>";
 
 
 
 
 
       $tolerancia_th5= query("SELECT date_field 
       FROM
       (
       SELECT
       MAKEDATE(YEAR('$fi_pv'),1) +
       INTERVAL (MONTH('$fi_pv')-1) MONTH +
       INTERVAL daynum DAY date_field
       FROM
       (
       SELECT t*10+u daynum
       FROM
       (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
       (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
       UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
       UNION SELECT 8 UNION SELECT 9) B
       ORDER BY daynum
       ) AA
       ) AAA
       WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'");
     
       
        if(count($tolerancia_th5)>0){
     
          for($top=0; $top<count($tolerancia_th5); $top++)
          {
           $fecho= $tolerancia_th5[$top]['date_field'];
     
     
             $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
             ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
             FROM detalleturnosemana dd 
             INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
             INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
             INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
             INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
             INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
             INNER JOIN cempleados ce ON ce.Horario=rr.IdR
             INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
             WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
             AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
             GROUP BY hh.Idh");
     
 
         if(count($gyo1)>0){

          for($topt=0; $topt<count($gyo1); $topt++)
          {

            $dias_cat_tb = $gyo1[$topt]['Posis'];
            $IdT_tb=  $gyo1[$topt]['Posit'];
            $IdP_tb= $gyo1[$topt]['IdP'];
            $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
            
               $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
               if(count($buscar_l)>0){
               $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
               where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
               echo $yuii."<br>";
               echo "<br>";
                   $actualizar_fecha_r=query($yuii);
               }

          }  

 
         }
 
 
      
     
     
          }
     
        }
 
 
 
 
 
 
     
      
     }
     
 
 
 
   }else{
     if($estau==0){  //anulado
       $query_salario="insert into tablapv (codigo_e, documento, vacacion, fechasolicitud, Detalle, motivo, dias, fechainicio, fechafin, estado, fechaaprobado, aprobador, fechaM)
       VALUES ($codigo_e,'$documento', '$vacacion', '$fechas', '$detalle', '$motivo', '$numeroDias','$fi_pv', '$ff_pv', $estau,NULL, '', '$fecha')";
       $ejec=query($query_salario);
 
 
 
       $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento' and tt.estado=$estado ";
       $pre= query($pre);
       
       
       if(count($pre)>0){
       
         $ID__=$pre[0]['Id'];
         echo "<br>";
         echo "<br>";
         echo $ID__;
         echo "<br>";
         echo "<br>";
         echo $codigo_empleado;
         echo "<br>";
         echo "<br>";
         echo '--------------'."<br>";
 
 
 
 
         $tolerancia_th5= query("SELECT date_field 
   FROM
   (
   SELECT
   MAKEDATE(YEAR('$fi_pv'),1) +
   INTERVAL (MONTH('$fi_pv')-1) MONTH +
   INTERVAL daynum DAY date_field
   FROM
   (
   SELECT t*10+u daynum
   FROM
   (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
   (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
   UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
   UNION SELECT 8 UNION SELECT 9) B
   ORDER BY daynum
   ) AA
   ) AAA
   WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'");
 
   
    if(count($tolerancia_th5)>0){
 
      for($top=0; $top<count($tolerancia_th5); $top++)
      {
       $fecho= $tolerancia_th5[$top]['date_field'];
 
 
         $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
         ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
         FROM detalleturnosemana dd 
         INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
         INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
         INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
         INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
         INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
         INNER JOIN cempleados ce ON ce.Horario=rr.IdR
         INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
         WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
         AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
         GROUP BY hh.Idh");
 
 
 if(count($gyo1)>0){


  for($topt=0; $topt<count($gyo1); $topt++)
  {
    $dias_cat_tb = $gyo1[$topt]['Posis'];
    $IdT_tb=  $gyo1[$topt]['Posit'];
    $IdP_tb= $gyo1[$topt]['IdP'];
    $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
    
       $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
       if(count($buscar_l)>0){
       $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
       where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
       echo $yuii."<br>";
       echo "<br>";
           $actualizar_fecha_r=query($yuii);
       }
  
  }





 }
 
       
 
 
      }
 
    }
       
         
       
       
       }
       
 
 
 
 
     }
 
   }
 
 
 }
 }
 
 
  }


else{
 
  
   echo "existe"."<br>";
   echo '--------------'."<br>";
 
 
 
 
 
 
 
   $p_v= "SELECT
 T.codigo_empleado,
   T.documento,
   T.vacacion,
   T.fechasolicitud,
   T.detalle,
   T.motivo,
   T.numeroDias,
   T.fechaInicio,
   T.fechaFin,
   T.estado,
   T.fechaAprobado,
   T.fechaCancelado,
   T.aprobador,
   T.rechazador
 
  
 FROM 	(( 
 
 SELECT
 
 
   T.codigo_empleado,
   T.documento,
   ccc.vacacion,
   ccc.fechasolicitud,
   ccc.detalle,
   ccc.motivo,
   ccc.numeroDias,
   ccc.fechaInicio,
   ccc.fechaFin,
   ccc.estado,
   ccc.fechaAprobado,
   ccc.fechaCancelado,
   (SELECT CONCAT(apellido_paterno,' ',apellido_materno,' ',nombres) from vi_usuarioEmpleado where vi_usuarioEmpleado.codigo_usuario=ccc.usrAprobado) AS aprobador,
   (SELECT CONCAT(apellido_paterno,' ',apellido_materno,' ',nombres) from vi_usuarioEmpleado where vi_usuarioEmpleado.codigo_usuario=ccc.ursCancelado) AS rechazador
  
 FROM 	(( 
 SELECT cc.codigo_empleado  ,  cc.documento , max(cc.fechasolicitud) as fecha
 FROM empleado_vacacion cc
 INNER JOIN empleado ee ON cc.codigo_empleado = ee.codigo_empleado
 LEFT JOIN empleado_cargo ccargo ON cc.codigo_empleado = ccargo.codigo_empleado
 INNER JOIN dbo.cargo gh ON ccargo.cargo = gh.codigo
 where ccargo.estado=1  and cc.codigo_empleado= $codigo_empleado  and  cc.documento='$documento'
 group by cc.codigo_empleado, cc.documento 
 
 ))T  inner join empleado_vacacion ccc on ccc.codigo_empleado= T.codigo_empleado and ccc.documento= T.documento and ccc.fechasolicitud= T.fecha
 
 ))T ";
 
 
 
 $p_v=queryth5($p_v);
 
 
 
 
 $codigo_e=$codigo_empleado;
 $vacacion=$p_v[0]['vacacion'];
 $fechas=$p_v[0]['fechasolicitud']->format('Y-m-d H:i:s');
 $detalle=$p_v[0]['detalle'];
 $motivo=$p_v[0]['motivo'];
 $numeroDias=$p_v[0]['numeroDias'];
 $fi_pv=$p_v[0]['fechaInicio']->format('Y-m-d');
 $ff_pv=$p_v[0]['fechaFin']->format('Y-m-d');
 $estau=$p_v[0]['estado'];   
 
 echo "<br>";
 echo $codigo_e;
 echo "<br>";
 echo $estau;
 echo "<br>";
 echo "<br>";
 echo "<br>";
 
 if($estau==2){ //aprobado
 $fechaAprobado=$p_v[0]['fechaAprobado']->format('Y-m-d H:i:s');  
 $aprobador=$p_v[0]['aprobador'];  
 $sdd="update tablapv tt SET  tt.fechaM='$fecha', tt.Aprobador='$aprobador', tt.fechaaprobado='$fechaAprobado' , tt.estado=$estau WHERE tt.documento='$documento' and tt.codigo_e=$codigo_e ";
 $ejec= query($sdd);
 
 
 
 echo $sdd;
 echo "<br>";
 
 
 
 $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento' and tt.estado=$estado ";
 $pre= query($pre);
 
 
 if(count($pre)>0){
 
   $ID__=$pre[0]['Id'];
   echo "<br>";
   echo "<br>";
   echo $ID__;
   echo "<br>";
   echo "<br>";
   echo $codigo_empleado;
   echo "<br>";
   echo "<br>";
   echo '--------------'."<br>";
   echo $ff_pv;
   echo "<br>";
   echo $fi_pv;
   echo "<br>";
 
 
  $dfgt="SELECT date_field 
   FROM
   (
   SELECT
   MAKEDATE(YEAR('$fi_pv'),1) +
   INTERVAL (MONTH('$fi_pv')-1) MONTH +
   INTERVAL daynum DAY date_field
   FROM
   (
  SELECT t*10+u daynum
   FROM
   (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
   (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
   UNION SELECT 8 UNION SELECT 9) B
  ORDER BY daynum
   ) AA
  ) AAA
   WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'";
   echo $dfgt;
   echo "<br>";
 
   $tolerancia_th5= query($dfgt);
 
   
     if(count($tolerancia_th5)>0){
 
      for($top=0; $top<count($tolerancia_th5); $top++)
      {
       $fecho= $tolerancia_th5[$top]['date_field'];
       echo $fecho;
       echo "<br>";
         $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
         ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
         FROM detalleturnosemana dd 
         INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
         INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
         INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
         INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
         INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
         INNER JOIN cempleados ce ON ce.Horario=rr.IdR
         INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
         WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
         AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
         GROUP BY hh.Idh");
 
           if(count($gyo1)>0){

            for($topt=0; $topt<count($gyo1); $topt++)
            {
              $dias_cat_tb = $gyo1[$topt]['Posis'];
              $IdT_tb=  $gyo1[$topt]['Posit'];
              $IdP_tb= $gyo1[$topt]['IdP'];
              $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
  
              $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
              if(count($buscar_l)>0){
    
              $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
              where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
              echo $yuii."<br>";
              echo "<br>";
              $actualizar_fecha_r=query($yuii);
              echo  $actualizar_fecha_r."<br>";
              echo "<br>";
    
    
               }

            }
        


 
           }
 
       
        
      
 
 
       }
 
     }
 
 
 
 
 
 
 }
 
 
 
 
 }else{
  if($estau==3){ //rechazado
   $fecharechazado=$p_v[0]['fechaCancelado']->format('Y-m-d H:i:s'); 
   $rechazador=$p_v[0]['rechazador']; 
   $sdd="update tablapv tt SET  tt.fechaM='$fecha', tt.Aprobador='$rechazador', tt.fechaaprobado='$fecharechazado' , tt.estado=$estau WHERE tt.documento='$documento'  and tt.codigo_e=$codigo_e";
   $ejec= query($sdd);
 
 
 
 
   $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento'  and tt.estado=$estado ";
   $pre= query($pre);
   
   
   if(count($pre)>0){
   
     $ID__=$pre[0]['Id'];
     echo "<br>";
     echo "<br>";
     echo $ID__;
     echo "<br>";
     echo "<br>";
     echo $codigo_empleado;
     echo "<br>";
     echo "<br>";
     echo '--------------'."<br>";
   
     $dfgt="SELECT date_field 
     FROM
     (
     SELECT
     MAKEDATE(YEAR('$fi_pv'),1) +
     INTERVAL (MONTH('$fi_pv')-1) MONTH +
     INTERVAL daynum DAY date_field
     FROM
     (
    SELECT t*10+u daynum
     FROM
     (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
     (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
   UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
     UNION SELECT 8 UNION SELECT 9) B
    ORDER BY daynum
     ) AA
    ) AAA
     WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'";
     echo $dfgt;
     echo "<br>";
   
     $tolerancia_th5= query($dfgt);
   
     
       if(count($tolerancia_th5)>0){
   
        for($top=0; $top<count($tolerancia_th5); $top++)
        {
         $fecho= $tolerancia_th5[$top]['date_field'];
         echo $fecho;
         echo "<br>";
           $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
           ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
           FROM detalleturnosemana dd 
           INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
           INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
           INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
           INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
           INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
           INNER JOIN cempleados ce ON ce.Horario=rr.IdR
           INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
           WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
           AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
           GROUP BY hh.Idh");
   
 
           if(count($gyo1)>0){


            for($topt=0; $topt<count($gyo1); $topt++)
            {

              $dias_cat_tb = $gyo1[$topt]['Posis'];
              $IdT_tb=  $gyo1[$topt]['Posit'];
              $IdP_tb= $gyo1[$topt]['IdP'];
              $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
              
                 $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
                 if(count($buscar_l)>0){
       
                 $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
                 where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
                 echo $yuii."<br>";
                 echo "<br>";
                 $actualizar_fecha_r=query($yuii);
                 echo  $actualizar_fecha_r."<br>";
                 echo "<br>";
       
       
                  }
            }
 





             }
         }
   
       }
 
 
   
   
   }
   
 
 
 
  }else{
    if($estau==0){ //anulado
     $sdd="update tablapv tt SET  tt.fechaM='$fecha',  tt.estado=$estau WHERE tt.documento='$documento'  and tt.codigo_e=$codigo_e  ";
     $ejec= query($sdd);
 
 
 
     $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento' and tt.estado=$estado ";
     $pre= query($pre);
     
     
     if(count($pre)>0){
     
       $ID__=$pre[0]['Id'];
       echo "<br>";
       echo "<br>";
       echo $ID__;
       echo "<br>";
       echo "<br>";
       echo $codigo_empleado;
       echo "<br>";
       echo "<br>";
       echo '--------------'."<br>";
     
    
       $dfgt="SELECT date_field 
       FROM
       (
       SELECT
       MAKEDATE(YEAR('$fi_pv'),1) +
       INTERVAL (MONTH('$fi_pv')-1) MONTH +
       INTERVAL daynum DAY date_field
       FROM
       (
      SELECT t*10+u daynum
       FROM
       (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
       (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
     UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
       UNION SELECT 8 UNION SELECT 9) B
      ORDER BY daynum
       ) AA
      ) AAA
       WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'";
       echo $dfgt;
       echo "<br>";
     
       $tolerancia_th5= query($dfgt);
     
       
         if(count($tolerancia_th5)>0){
     
          for($top=0; $top<count($tolerancia_th5); $top++)
          {
           $fecho= $tolerancia_th5[$top]['date_field'];
           echo $fecho;
           echo "<br>";
             $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
             ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
             FROM detalleturnosemana dd 
             INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
             INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
             INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
             INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
             INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
             INNER JOIN cempleados ce ON ce.Horario=rr.IdR
             INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
             WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
             AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
             GROUP BY hh.Idh");
     
 
        if(count($gyo1)>0){


          for($topt=0; $topt<count($gyo1); $topt++)
          {
            $dias_cat_tb = $gyo1[$topt]['Posis'];
            $IdT_tb=  $gyo1[$topt]['Posit'];
            $IdP_tb= $gyo1[$topt]['IdP'];
            $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
            
               $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
               if(count($buscar_l)>0){
     
               $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
               where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
               echo $yuii."<br>";
               echo "<br>";
               $actualizar_fecha_r=query($yuii);
               echo  $actualizar_fecha_r."<br>";
               echo "<br>";
     
     
                }
          }
        
              }
     
           }
     
         }
     
 
 
     
     }
     
 
 
 
 
    }else{
       if($estau==1){ //pendiente
        $fechaAprobado=$fecha;
        $aprobador=$p_v[0]['aprobador'];  
        $sdd="update tablapv tt SET  tt.fechaM='$fecha', tt.Aprobador='', tt.fechaaprobado='$fechaAprobado' , tt.estado=$estau WHERE tt.documento='$documento' and tt.codigo_e=$codigo_e ";
        $ejec= query($sdd);
 
 
 
 
        $pre= "SELECT * FROM tablapv tt WHERE tt.codigo_e=$codigo_e and  tt.documento='$documento'  and tt.estado=$estado";
        $pre= query($pre);
        
        
        if(count($pre)>0){
        
          $ID__=$pre[0]['Id'];
          echo "<br>";
          echo "<br>";
          echo $ID__;
          echo "<br>";
          echo "<br>";
          echo $codigo_empleado;
          echo "<br>";
          echo "<br>";
          echo '--------------'."<br>";
        
 
 
 
 
 
 
 
          $dfgt="SELECT date_field 
          FROM
          (
          SELECT
          MAKEDATE(YEAR('$fi_pv'),1) +
          INTERVAL (MONTH('$fi_pv')-1) MONTH +
          INTERVAL daynum DAY date_field
          FROM
          (
         SELECT t*10+u daynum
          FROM
          (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
          (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
        UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
          UNION SELECT 8 UNION SELECT 9) B
         ORDER BY daynum
          ) AA
         ) AAA
          WHERE MONTH(date_field) = MONTH('$fi_pv')   and date_field<='$ff_pv'  and date_field>='$fi_pv'";
          echo $dfgt;
          echo "<br>";
        
          $tolerancia_th5= query($dfgt);
        
          
            if(count($tolerancia_th5)>0){
        
             for($top=0; $top<count($tolerancia_th5); $top++)
             {
              $fecho= $tolerancia_th5[$top]['date_field'];
              echo $fecho;
              echo "<br>";
                $gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
                ss.Nombre AS dias_semana, if(dd.Posit=3,'0.50','0.25') AS cant_marcaciones
                FROM detalleturnosemana dd 
                INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
                INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
                INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
                INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
                INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
                INNER JOIN cempleados ce ON ce.Horario=rr.IdR
                INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
                WHERE   ce.Codigo =$codigo_empleado   AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$fecho') 
                AND '$fecho' BETWEEN lhh.FechaI AND lhh.FechaF 
                GROUP BY hh.Idh");
          if(count($gyo1)>0){


            for($topt=0; $topt<count($gyo1); $topt++)
            {
              $dias_cat_tb = $gyo1[$topt]['Posis'];
              $IdT_tb=  $gyo1[$topt]['Posit'];
              $IdP_tb= $gyo1[$topt]['IdP'];
              $marcacion_tb= $gyo1[$topt]['cant_marcaciones'];
              
                 $buscar_l=query("SELECT * FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado AND cc.Fecha= '$fecho' and cc.dias_cat=$dias_cat_tb  AND cc.IdT=$IdT_tb AND cc.IdP=$IdP_tb");
                 if(count($buscar_l)>0){
       
                 $yuii="update reportedias_nomarcados set  FechaModificacionVP='$fecha',  contador=$marcacion_tb , estado=0 , idtab = $ID__
                 where codigo_e=$codigo_empleado AND Fecha= '$fecho'  and dias_cat=$dias_cat_tb   AND IdT=$IdT_tb AND IdP=$IdP_tb";
                 echo $yuii."<br>";
                 echo "<br>";
                 $actualizar_fecha_r=query($yuii);
                 echo  $actualizar_fecha_r."<br>";
                 echo "<br>";
       
       
                  }
            }
               




                 }
        
              }
        
            }
 
 
 
     
        
        }
        
 
 
 
 
       }
    }
 
  }
 }
 
 
 
 
 
 
 
 }
 
 
 
 
 
 
 
 
  }
 
 






}else{
  $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
  VALUES ('act_permisos_vacaciones2_registro.php','$fecha', 'No datos array')";

  $ejec_ser=query($ej_servicio);
}








}else{
  $ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion,Detalle)
  VALUES ('act_permisos_vacaciones2.php','$fecha', 'No hay datos')";

  $ejec_ser=query($ej_servicio);
}





?>
