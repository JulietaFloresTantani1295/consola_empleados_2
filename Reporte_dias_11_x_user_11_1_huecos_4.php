
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

$fechaActual0=0;



$fdc=query("SELECT  aa.Codigo as codigo_empleado ,
aa.Estado as estado,
 aa.Nombre, aa.Ci as ci, 
 aa.Tipo_Documento as  tipo_documento, 
 aa.Extension as extension,
  aa.FechaIngreso as fecha_ingreso,
  aa.FechaSalida as fecha_salida,
   aa.SueldoBasico as sueldo_basico,
  aa.TipoContrato as tipo_contrato, aa.IdSucursal, 
  cc.Nombre AS oficina, aa.Parametro, aa.FechaRelacion,aa.ControlA,  aa.FechaAB,  lec.FechaModificacion,
    lec.Estado AS estado_bio
  FROM cempleados  aa 
  INNER JOIN biometrico.estado_biometrico lec ON lec.IdSucursal=aa.IdSucursal 
  INNER JOIN oficina cc ON cc.IdOficina= aa.IdSucursal
  WHERE aa.FechaIngreso IS NOT NULL AND  aa.FechaSalida 
  IS NOT NULL AND aa.Estado=1 AND aa.TipoEmpleado='Regular' AND aa.Horario!=0 AND aa.Ci!='' AND lec.Estado IN (1,0)   
    AND aa.ControlAsistencia=0   AND cc.Posi=4
  ORDER BY aa.IdSucursal, aa.Codigo asc");


if(count($fdc)>0){

	
	for($ilc=0; $ilc<count($fdc); $ilc++)
    {   
	
    $cii= $fdc[$ilc]['ci'];
	echo $cii."<br>";
	$fdc_huecos=query("SELECT cc.Fecha, cc.Hora FROM reportedias_nomarcados cc 
	WHERE cc.CI='$cii' AND cc.Fecha BETWEEN '2024-10-21' AND '2024-11-20'
	  AND ISNULL(cc.Hora) AND ISNULL(cc.idtab)  ORDER BY cc.Fecha desc");

    if(count($fdc_huecos)>0){
		$ejecutar=query("insert into limpio_maracion (Ci, Fecha , Registro, desde, hasta, vacio) values ('$cii','','$fecha','2024-10-21','2024-11-20',0)");
		for($ilcv=0; $ilcv<count($fdc_huecos); $ilcv++)
		{
            $valo= $fdc_huecos[$ilcv]['Fecha'];
			$horaa_r= $fdc_huecos[$ilcv]['Hora'];
			echo "<br>";
			echo "<br>";
			echo $valo."<br>";
			$prik= $valo.' 00:00:00';
			echo $prik."<br>";
			$endfin = $valo.' 23:59:59';
		    echo $endfin."<br>";
		    echo "<br>";
		    echo "<br>";

            $diasf_hhh=query("SELECT  DAYOFWEEK('$valo') AS DiaSemana;");
            $diasf_=$diasf_hhh[0]['DiaSemana'];
		


	
						$validar_dia="SELECT ww.* FROM reportedias_nomarcados ww WHERE ww.CI='$cii' AND ww.Fecha ='$valo'";
						$validar_dia= query($validar_dia);
						if(count($validar_dia)==0){
						
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
						WHERE   ce.Ci='$cii' AND ce.Estado=1 AND dd.Estado=1 AND hh.Validar=1  AND dd.Posis=DAYOFWEEK('$valo') 
						AND '$valo' BETWEEN lhh.FechaI AND lhh.FechaF 
						GROUP BY hh.Idh";
						
			
						echo $dcd."<br>";
						echo "<br>";
						echo "<br>";
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
			
			
			
			
			
			
					   
			
			$lista_sin_registrar=query("SELECT  T.CodigoE,T.nom_bio,T.IdUser, T.cant, T.FechaRegistro , T.Fecha, T.Hora , T.diasdelasemana, T.NombreUsuario, 
			T.IP, T.dias_cat, T.Nombre, T.IdT, T.Detalle, T.IdP, T.nombre_, T.IdR, T.detalle_, T.Desde, T.Hasta, T.NombreIngles,T.ultimo_detalle, T.CI,T.Contador
			FROM 	((
			SELECT ll.CodigoE,cc.Nombre AS nom_bio,cc.IdUser,COUNT(cc.FechaRegistro) AS cant,min(cc.FechaRegistro) AS FechaRegistro, cc.Fecha,  min(cc.Hora) AS Hora ,
			 dss.Nombre AS diasdelasemana, rr.Nombre AS NombreUsuario,
			cc.IP,DAYOFWEEK(cc.FechaRegistro) AS dias_cat ,dss.Nombre,hh.IdT,tt.Detalle,dr.IdP,dr.Nombre AS nombre_, rrg.IdR,
			lhh.Detalle AS detalle_,lhh.Desde, lhh.Hasta,dss.NombreIngles ,lhh.Detalle AS ultimo_detalle, ll.CI,tt.Contador
			FROM biometrico.control_marcaciones cc
			INNER JOIN lista_cb  ll ON ll.CodigoBio= cc.IdUser and ll.Estado=1
			INNER JOIN cempleados rr ON rr.Ci= ll.CI
			INNER JOIN dias_semana dss ON dss.IdS=DAYOFWEEK(cc.FechaRegistro)
			INNER JOIN detalleturnosemana dd ON dd.Posis=dss.IdS AND dd.Estado=1
			INNER JOIN turno tt ON tt.lugar=dd.Posit AND dd.Estado=1
			INNER JOIN horariosturno hh ON hh.IdT=tt.lugar AND hh.Estado=1
			INNER JOIN regional rrg ON rrg.IdR=hh.IdR AND rrg.Estado=1 
			INNER JOIN posicionturno dr ON dr.IdP=hh.Posicion AND dr.Estado=1
			INNER JOIN log_horario lhh  ON lhh.Idh = hh.Idh  and lhh.Estado=1
													
			WHERE ll.CI='$cii' AND ll.CodigoBio= cc.IdUser AND ll.Nombre= cc.Nombre 
			and cc.FechaRegistro BETWEEN '$prik' AND '$endfin'   AND dss.IdS=$diasf_  AND rr.ControlAsistencia IN (0,1) AND  rrg.IdR=rr.Horario  AND hh.Validar=1  
			AND cc.Fecha BETWEEN lhh.FechaI AND lhh.FechaF 
			AND cc.Hora BETWEEN lhh.Desde AND lhh.Hasta 
			AND cc.Fecha BETWEEN  rr.FechaIngreso AND if(rr.FechaSalida='1960-01-01', '2222-01-01' ,rr.FechaSalida) 
			AND  cc.Hora >= lhh.Desde
			GROUP BY   cc.Fecha, hh.IdT, dr.IdP 
			))T WHERE 
			not EXISTS(SELECT w.*	FROM reportedias_nomarcados w WHERE w.CI=T.CI
			AND  w.IdT=T.IdT AND w.IdP=T.IdP  AND w.Detalle_ =  T.ultimo_detalle  AND  w.FechaN= T.FechaRegistro 
			AND  w.FechaRegistro BETWEEN '$prik' AND '$endfin'  )GROUP BY T.Fecha , T.IdT, T.IdP");
			
			
			
			if(count($lista_sin_registrar)>0){
			
			
			  for($ilf=0; $ilf<count($lista_sin_registrar); $ilf++)
			  {
			  
			  
			  
			  $nombre_usuarioo=$lista_sin_registrar[$ilf]['NombreUsuario']; 
			  
			  $dias_catt=$lista_sin_registrar[$ilf]['dias_cat'];
			
			  $nombree=$lista_sin_registrar[$ilf]['Nombre'];
			
			  $idtt=$lista_sin_registrar[$ilf]['IdT'];
			  
			  $Detallee=$lista_sin_registrar[$ilf]['Detalle'];
			  
			  $idpp=$lista_sin_registrar[$ilf]['IdP'];
			  $idp=$idpp;
			  $Nombre__=$lista_sin_registrar[$ilf]['nombre_'];
			  
			  $idrr=$lista_sin_registrar[$ilf]['IdR'];
			  
			  $Detalle__=$lista_sin_registrar[$ilf]['detalle_'];
			  
			  $desde=$lista_sin_registrar[$ilf]['Desde'];
			  
			  $hasta=$lista_sin_registrar[$ilf]['Hasta'];
			
			  $fechaa=$lista_sin_registrar[$ilf]['Fecha'];
			
			  $horaar=$lista_sin_registrar[$ilf]['Hora'];
			
			  $fecharegistror=$lista_sin_registrar[$ilf]['FechaRegistro'];
			
			  $contador_info=$lista_sin_registrar[$ilf]['Contador'];
			
			
			
			  if($idpp==1){
				if($fechaa == '2023-12-18'|| $fechaa =='2023-12-19'||  $fechaa =='2023-12-20' || 
				 $fechaa =='2023-12-21' || $fechaa =='2023-12-22'){
			
					$horaar=date('H:i:s', strtotime($horaar."- 30 minutes"));
					$fecharegistror=date('Y-m-d H:i:s', strtotime($fecharegistror."- 30 minutes"));
				}
			  }
				 
				  $de="SELECT rr.Estado, rr.Hora, rr.Est_TV FROM reportedias_nomarcados rr WHERE rr.CI='$cii' AND 
				   rr.dias_cat=$dias_catt AND  rr.IdT=$idtt AND rr.IdP=$idpp   and rr.Fecha='$fechaa'";
				 
			  
				  $validar_existe=query($de);
			   
			  
			
			
			
			   
				if(count($validar_existe)>0){
					  $est=$validar_existe[0]['Estado'];
					  $horay=$validar_existe[0]['Hora'];
					  $Est_TV=$validar_existe[0]['Est_TV'];
					
					  if($est==1 && $Est_TV!=0){
						$fer=$fechaa.' '.$Detalle__;
						$tyu="update reportedias_nomarcados rr set 
						rr.FechaN='$fecharegistror',
						rr.IdR=$idrr,
						rr.Detalle_='$Detalle__',
						rr.detalle_r='Sin Marcacion pero con Tolerancia',
						rr.Estado=0, 
						rr.FechaModificacion='$fecha' ,
						rr.Hora='$Detalle__' ,  
						rr.contador='$contador_info' ,
						rr.FechaRegistro='$fer' ,
						rr.Nombre='$nombree'
						
						WHERE rr.CI='$cii' AND 
							  rr.dias_cat=$dias_catt AND 
							  rr.IdT=$idtt AND 
							  rr.IdP=$idpp AND 
							  rr.Estado=1  and rr.Fecha='$fechaa'";
						$updatee= query($tyu);
					  }
			
			
					  if($est==1 &&  $Est_TV==0 ){
						$tyu="update reportedias_nomarcados rr set 
						rr.FechaN='$fecharegistror',
						rr.IdR=$idrr,
						rr.Detalle_='$Detalle__',
						rr.detalle_r='Sin Marcar, Sin Tolerancia',
						rr.Estado=0,
						rr.FechaModificacion='$fecha' ,
						rr.Hora='$horaar',  
						rr.contador='$contador_info' , 
						rr.FechaRegistro='$fecharegistror',
						rr.Nombre='$nombree'
			
					   WHERE rr.CI='$cii' AND 
							 rr.dias_cat=$dias_catt AND 
							 rr.IdT=$idtt AND 
							 rr.IdP=$idpp AND
							 rr.Estado=1 AND
							 rr.Est_TV=0 and rr.Fecha='$fechaa'";
					   $updatee= query($tyu);
					  }
			
			
					
					  if($est==0 && $Est_TV!=0 ){
						$fer=$fechaa.' '.$Detalle__;
						$tyu="update reportedias_nomarcados rr set 
						 rr.FechaN='$fecharegistror',
						 rr.IdR=$idrr,
						 rr.Detalle_='$Detalle__',
						 rr.detalle_r='Retraso pero con Tolerancia',
						 rr.Estado=0,
						 rr.FechaModificacion='$fecha' , 
						 rr.Hora='$Detalle__' ,
						 rr.contador='$contador_info',
						 rr.FechaRegistro='$fer' ,
						 rr.Nombre='$nombree'
			
						WHERE rr.CI='$cii' AND 
							  rr.dias_cat=$dias_catt AND 
							  rr.IdT=$idtt AND 
							  rr.IdP=$idpp AND
							  rr.Estado=0   and rr.Fecha='$fechaa'";
						$updatee= query($tyu);
					  }
			
					  if($est==0 &&  $Est_TV==0 ){
						$tyu="update reportedias_nomarcados rr set 
						rr.FechaN='$fecharegistror',
						 rr.IdR=$idrr,
						 rr.Detalle_='$Detalle__',
						 rr.detalle_r='Retraso',
						 rr.Estado=0,
						 rr.FechaModificacion='$fecha' ,
						 rr.Hora='$horaar',  
						 rr.contador='$contador_info' , 
						 rr.FechaRegistro='$fecharegistror',
						 rr.Nombre='$nombree'
			
						WHERE rr.CI='$cii' AND 
							  rr.dias_cat=$dias_catt AND 
							  rr.IdT=$idtt AND 
							  rr.IdP=$idpp AND
							  rr.Estado=0 AND
							  rr.Est_TV=0 and rr.Fecha='$fechaa'";
						$updatee= query($tyu);
					  }
			
			
			  
			  
			  
				 }
			  
			  
				   }
			  
			
			
			
			
			}
			
			
			
	
			





		   

		}	


	}else{
		$ejecutar=query("insert into limpio_maracion (Ci, Fecha , Registro, desde, hasta, vacio) values ('$cii','','$fecha','2024-10-21','2024-11-20',1)");
	}




				}


			}



?>
