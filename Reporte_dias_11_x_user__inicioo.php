
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

$sql2='';
$coit=0;
$totalf=0;$detgfg='';
$diferencia='';


$fecha_inicio_mes= date('Y-m-01').' 00:00:00';
$fechaActual_mes = date('Y-m-d').' 23:59:59';



$fechaActual0=0;

// $fdc=query("SELECT  aa.Codigo as codigo_empleado ,
// aa.Estado as estado,
//  aa.Nombre, aa.Ci as ci, 
//  aa.Tipo_Documento as  tipo_documento, 
//  aa.Extension as extension,
//   aa.FechaIngreso as fecha_ingreso,
//   aa.FechaSalida as fecha_salida,
//    aa.SueldoBasico as sueldo_basico,
//   aa.TipoContrato as tipo_contrato, aa.IdSucursal, 
//   cc.Nombre AS oficina, aa.Parametro
//   FROM cempleados  aa 
//   INNER JOIN oficina cc ON cc.IdOficina= aa.IdSucursal
//   WHERE aa.FechaIngreso IS NOT NULL AND  aa.FechaSalida 
//   IS NOT NULL AND aa.Estado=1 AND aa.TipoEmpleado='Regular' AND aa.Horario!=0 AND aa.Ci!='' 
//   AND aa.FechaRegistro BETWEEN '$fecha_inicio_mes' AND '$fechaActual_mes' AND  ISNULL(aa.FechaRelacion)
//   ORDER BY aa.IdSucursal, aa.Codigo asc");
  
$fdc=query("CALL `lista_registrar_marcacion_primera_vez`('$fecha_inicio_mes', '$fechaActual_mes')");





if(count($fdc)>0){

	$ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
	VALUES ('Reporte_dias_11_x_user__inicio.php','$fecha', 'Hay datos array')";
	$ejec_ser=query($ej_servicio);



	for($ilc=0; $ilc<count($fdc); $ilc++)
    {
		$codigo_empleado= $fdc[$ilc]['codigo_empleado'];

		$ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
		VALUES ('Reporte_dias_11_x_user__inicio.php','$fecha', $codigo_empleado)";
		$ejec_ser=query($ej_servicio);

		 echo $codigo_empleado."<br>";
		$estadoth5= $fdc[$ilc]['estado'];
		 echo $estadoth5."<br>";
		$nombre=$fdc[$ilc]['Nombre'];
		 echo $nombre ."<br>";
		$tipo_contrato= $fdc[$ilc]['tipo_contrato'];
		 echo $tipo_contrato ."<br>";
    	$cii= $fdc[$ilc]['ci'];
		 echo $cii."<br>";
	

		 $fecha_ingreso= $fdc[$ilc]['fecha_ingreso'];
		 echo 'Fecha Ingreso'."<br>";
		 echo $fecha_ingreso."<br>";
		$fecha_salida= $fdc[$ilc]['fecha_salida'];
		 echo 'Fecha Salida'."<br>";
		 echo $fecha_salida."<br>";
	

		 $differenceFormat = '%a';
		 $fechaActual = date('Y-m-d');
		 echo 'Fecha Actual'."<br>";
		 echo $fechaActual."<br>";
		 $fechaActual0=0;
		 $diff=0;


		 $fecha_ingresox = date_create($fecha_ingreso);
		 $fechaActualAx = date_create($fechaActual);
		 $diff = date_diff($fecha_ingresox, $fechaActualAx);
		 $diff = $diff->format($differenceFormat);
		 echo "diferencia _diff"."<br>";
		 echo $diff."<br>"."<br>"."<br>"."<br>"."<br>";

		 $validar_relacion= query("update cempleados rr set  rr.Parametro= $diff , rr.FechaRelacion='$fecha', rr.ControlA=1  where rr.Codigo=$codigo_empleado" );



	    $validar_inicio_contrato= query("SELECT cc.Fecha FROM reportedias_nomarcados cc WHERE cc.codigo_e=$codigo_empleado ORDER BY cc.Fecha ASC LIMIT 1");
         if(count($validar_inicio_contrato)==0){

	
			$fechaActual0= $fecha_ingreso;
			$dias_se= query("SELECT cc.IdS, cc.Nombre, cc.Dias_M FROM dias_semana cc WHERE cc.IdS>1");
			if(count($dias_se)>0){
		
		
			   for($ilcg=0; $ilcg<count($dias_se); $ilcg++)
			   {
				   $diasf_ =$dias_se[$ilcg]['IdS'];
				   $diasf_n =$dias_se[$ilcg]['Nombre'];
		
				   $ejui=query("SELECT date_field 
				   FROM
				   (
				   SELECT
				   MAKEDATE(YEAR('$fechaActual0'),1) +
				   INTERVAL (MONTH('$fechaActual0')-1) MONTH +
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
				   WHERE MONTH(date_field) = MONTH('$fechaActual0')  AND  DAYOFWEEK(date_field)=$diasf_ and date_field>='$fechaActual0'   AND date_field<='$fechaActual';");
		
		
		
		
		$ejuii=query("SELECT date_field 
		FROM
		(
		SELECT
		MAKEDATE(YEAR('$fechaActual'),1) +
		INTERVAL (MONTH('$fechaActual')-1) MONTH +
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
		WHERE MONTH(date_field) = MONTH('$fechaActual')  AND  DAYOFWEEK(date_field)=$diasf_  and date_field<='$fechaActual'  and date_field>='$fechaActual0';");
				   
		
			$resultado = array_merge($ejui, $ejuii);
		
			$dias_registrados_t = array();
			for($ilct=0; $ilct<count($resultado); $ilct++)
			{
			 $diost=$resultado[$ilct]['date_field'];
			 array_push($dias_registrados_t,$diost);
			}
			echo 'total'."<br>";
			print_r($dias_registrados_t);
			echo "<br>";
		
		
			$dias_registrados_t = array_unique($dias_registrados_t);
			echo 'totalf'."<br>";
			print_r($dias_registrados_t);
			echo "<br>";
		
		
			$dias_re= query("SELECT cc.Fecha AS  date_field FROM reportedias_nomarcados cc
				WHERE  cc.CI='$cii'  AND cc.dias_cat=$diasf_ AND cc.Fecha BETWEEN '$fechaActual0' AND '$fechaActual'
				GROUP BY cc.Fecha
				ORDER BY cc.Fecha desc");
		
		
			  $dias_registrados_a = array();
			  if(count($dias_re)>0){
			   for($ilcr=0; $ilcr<count($dias_re); $ilcr++)
			   {
				$dioss=$dias_re[$ilcr]['date_field'];
				array_push($dias_registrados_a,$dioss);
			   }
			  }
		
			   $dias_rt=$dias_registrados_a;
			   echo 'total_base'."<br>";
			   print_r($dias_rt);
			   echo "<br>";
		
			   $dias_registrados_t = array_merge($dias_registrados_t, $dias_registrados_a);
			   $dias_registrados_t = array_unique($dias_registrados_t);
			   $dias_rt= $dias_registrados_t;
			   echo 'Diferencia'."<br>";
			   print_r($dias_rt);
			   echo "<br>";
			   echo $diasf_n."<br>";
			   echo $diasf_."<br>";
		
			   echo "<br>";
			   echo "<br>";
		
		
	
	
	
	
	//if($diasf_==3){
	
	
	
	
	
	
	
		if(count($dias_rt)>0){
			foreach($dias_rt as $key => $valo){
					echo $valo."<br>";
					$prik= $valo.' 00:00:00';
					echo $prik."<br>";
					$endfin = $valo.' 23:59:59';
					echo $endfin."<br>";

	
	
	
		echo 'validar_noexiste'."<br>";
			print_r($valo);
			echo "<br>";
	
		$dcd="SELECT  hh.Idh, dd.Posit,tt.Detalle AS detalle_turno , ft.IdP, ft.Nombre as nombre_er ,dd.Posis,  ss.Nombre AS dias_semana,
		ss.NombreIngles, lhh.Detalle  AS horario ,hh.Validar,lhh.Detalle AS detalle_,lhh.Desde, lhh.Hasta,
		  rr.Nombre AS descripcion , ce.Codigo,ce.CodigoBio,ce.NombreBio,ce.Nombre, rr.IdR, '$valo' as Fecha,lhh.FechaI, lhh.FechaF,
		   lhh.Detalle, lhh.Desde AS desdee, lhh.Hasta AS hastaa
		 FROM detalleturnosemana dd 
		 INNER JOIN turno tt ON tt.lugar=dd.Posit AND tt.Estado=1
		 INNER JOIN dias_semana ss ON ss.IdS=dd.Posis AND ss.Estado=1
		 INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
		 INNER JOIN regional rr ON rr.IdR=hh.IdR AND rr.Estado=1
		 INNER JOIN posicionturno ft ON ft.IdP=hh.Posicion AND ft.Estado=1
		 INNER JOIN cempleados ce ON ce.Horario=rr.IdR
		 INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
	 WHERE   ce.Ci='$cii'  and ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$valo') 
		 AND '$valo' BETWEEN lhh.FechaI AND lhh.FechaF 
		 AND NOT EXISTS(SELECT ww.*	FROM reportedias_nomarcados ww 
		 WHERE ww.CI='$cii' AND ww.IdT=dd.Posit AND ww.IdP=ft.IdP
	AND ww.Fecha ='$valo' )
		 GROUP BY hh.Idh";
	
	
	echo $dcd."<br>";
	echo "<br>";
	
	$dcd=query($dcd);

	
	
	if(count($dcd)>0){
	for($itty=0; $itty<count($dcd); $itty++){
	$nombre_usuarioo=$dcd[$itty]['Nombre'];
	$nombree=$dcd[$itty]['dias_semana'];
	$posit_aux=$dcd[$itty]['Posit'];
	$idtt=$posit_aux;
	$detalle_turno_aux=$dcd[$itty]['detalle_turno'];
	$idp_aux=$dcd[$itty]['IdP'];
	$nombre_re_aux=$dcd[$itty]['nombre_er'];
	$horario_aux=$dcd[$itty]['horario'];
	$idrr=$dcd[$itty]['IdR'];
	$Detalle__=$dcd[$itty]['Detalle'];
	$dias_catt=$dcd[$itty]['Posis'];
	$codg=$dcd[$itty]['Posis'];
	$idpp=$dcd[$itty]['IdP'];
	$idp=$dcd[$itty]['IdP'];
	
	$desde=$dcd[$itty]['desdee'];
	$hasta=$dcd[$itty]['hastaa'];
	
	
	
	$ejecutar=("insert into reportedias_nomarcados (codigo_e, NombreUsuario,
	dias_cat, Nombre, IdT, Detalle, IdP, Nombre_,IdR, Detalle_, Estado, FechaModificacion,
	IdUsuarioR, detalle_r, Fecha , Est_TV,posicion,CI, FechaUCI, FechaN, contador) values 
	(
	".$codigo_empleado.",
	'".$nombre_usuarioo."',
	".$dias_catt.",
	'".$nombree."',
	".$posit_aux.",
	'".$detalle_turno_aux."',
	".$idp_aux.",
	'".$nombre_re_aux."',
	".$idrr.",
	'".$horario_aux."',
	1,
	'$fecha',
	1,
	'registro  fin','$valo',0, '1','$cii', '$fecha', null, 0.0)");
	
	
	$ejecutar = query($ejecutar);
	
	
	
	}
	}
	
	
	 
			}
		}
	


	
	//}
	

	
	
		
				 
			 }
		   }



		 }






	}
}else{
	$ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
	VALUES ('Reporte_dias_11_x_user__inicio.php','$fecha', 'No Hay datos array')";
	$ejec_ser=query($ej_servicio);

}



?>
