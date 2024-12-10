
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
$anio=date("Y");
$dff=query("SELECT cc.Codigo, cc.CI FROM datos_vacacion_cth5  cc  WHERE cc.Estado=1 AND cc.Gestion='$anio' ORDER BY cc.FechaRegistro desc");

if(count($dff)>=0){
  $students1 = array();
  $students2 = array();
  $students3 = array();
  $students4 = array();
  for($i1ac=0; $i1ac<count($dff); $i1ac++)
  {
      $codigo_empleado = $dff[$i1ac]['Codigo'];  
      $codigo_empleado = (int)$codigo_empleado;
      $students1[] = array(
          'codigo_empleado'=> $codigo_empleado,
      );
  }
  $lista_is=query("CALL `lista_vacaciones_presente_anio_repo`()");
  if(count($lista_is)>0){
    for($i1ac=0; $i1ac<count($lista_is); $i1ac++)
    {
        $codigo_empleado = $lista_is[$i1ac]['Codigo'];  
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
        for($ilc=0; $ilc<count($students3); $ilc++)
        {
          $codigoo= $students3[$ilc]['codigo_empleado'];
          $cdfg= query("CALL `lista_vacacion_presente_anio_codigo`('$codigoo')");
          if(count($cdfg)>0){
            $codigo_info= $cdfg[0]['Codigo'];
            $ci_info= $cdfg[0]['Ci'];
            $gestion= $cdfg[0]['gestion'];
            $dias_vacacion= $cdfg[0]['dias_vacacion'];
            $Anios_transcurridos= $cdfg[0]['años_transcurridos'];
            echo $codigo_info;
            echo "<br>";
            $validar_fecha_codigo= query("SELECT cc.* FROM datos_vacacion_cth5 cc WHERE cc.Codigo = $codigo_info AND cc.Gestion=$gestion ORDER BY cc.Gestion  asc");
            if(count($validar_fecha_codigo)==0){
              $insert_detalle= query("insert into datos_vacacion_cth5 (Codigo, CI, FechaRegistro, Gestion, Anios_transcurridos, Dias_Vacacion) 
              values ($codigo_info, '$ci_info', '$fecha', '$gestion', $Anios_transcurridos, $dias_vacacion)");
            }else{
              echo "ya esta registrado";
              echo "<br>";
            }
          }
        }


        $cdfg_1= query("CALL `infor_empleado_2`()");







    }
  }
}

?>


