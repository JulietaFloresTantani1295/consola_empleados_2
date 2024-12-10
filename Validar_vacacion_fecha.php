
<?php


header('Access-Control-Allow-Origin: *');
date_default_timezone_set("America/La_Paz");
error_reporting(E_ALL);
ini_set('display_errors', 1);
$fecha=date('Y-m-d H:i:s');
$fecha_info=date('Y-m-d');
include "config/config_biometrico.php";
include "config/config_soli_p.php";
include "config/con_query.php";
include "abc2.php";
include "server.php";

$sql2='';
$coit=0;
$totalf=0;$detgfg='';
$diferencia='';

  
$fdc1=query("CALL `permiso_r_t`()");
$fdc2=query("CALL `permiso_t`()");


$students1 = array();
$students2 = array();
$students3 = array();

for($i1ac=0; $i1ac<count($fdc1); $i1ac++)
{
    $codigo_empleado = $fdc1[$i1ac]['codigo_e'];  
    $codigo_empleado = (int)$codigo_empleado;

    $idtab = $fdc1[$i1ac]['idtab'];

    $students1[] = array(
        'codigo_empleado'=> $codigo_empleado,
        'idtab'=> $idtab
    );
}

for($i1ac=0; $i1ac<count($fdc2); $i1ac++)
{
    $codigo_empleado = $fdc2[$i1ac]['codigo_e'];  
    $codigo_empleado = (int)$codigo_empleado;

    $idtab = $fdc2[$i1ac]['idtab'];

    $students2[] = array(
        'codigo_empleado'=> $codigo_empleado,
        'idtab'=> $idtab
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
     echo json_encode($students3[$ilc]);
     echo '--------------'."<br>";

	 $codigo_empleado= $students3[$ilc]['codigo_empleado'];
	 $idtab=$students3[$ilc]['idtab'];

	 $prer= "SELECT * FROM tablapv tt WHERE tt.id= $idtab ";
	 $prer= query($prer);

	 $documento= $prer[0]['documento'];
	 

	 

	 
	if(count($prer)>0){
 
	   $fi_pv=$prer[0]['fechainicio'];   
	   $ff_pv=$prer[0]['fechafin'];   	
	   $ID__=$prer[0]['Id'];
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
	  ((
	 	 SELECT date_field 
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
	  WHERE MONTH(date_field) = MONTH('$fi_pv') AND  date_field>='$fi_pv'  and date_field<='$ff_pv' 

	  
	  UNION 
	  
	   SELECT date_field 
	  FROM
	  (
	  SELECT
	  MAKEDATE(YEAR('$ff_pv'),1) +
	  INTERVAL (MONTH('$ff_pv')-1) MONTH +
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
	  WHERE MONTH(date_field) = MONTH('$ff_pv')   and date_field<='$ff_pv' AND  date_field>='$fi_pv'

	
	  
	  )) ff ORDER BY ff.date_field ASc";

	   






	   echo $dfgt;
	   echo "<br>";
	 
	   $tolerancia_th5= query($dfgt);
	 
	   





		 if(count($tolerancia_th5)>0){
	 
		  for($top=0; $top<count($tolerancia_th5); $top++)
		  {
		   $fecho= $tolerancia_th5[$top]['date_field'];
		   echo $fecho;
		   echo "<br>";


		   if($fecho<=$fecha_info ){




			$gyo1=query("SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis, 
			ss.Nombre AS dias_semana,  tt.Contador AS cant_marcaciones
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
		   
					   echo "<br>";
					   echo "<br>";
					   echo $marcacion_tb;
					   echo "<br>";
					   echo "<br>";

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





?>
