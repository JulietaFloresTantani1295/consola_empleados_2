
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
$totalf=0;
$detgfg='';
$diferencia='';


$fechaActualmenosa=date("Y-m-d",strtotime($fecha_info."- 40 days")); 
$fechaActualmenosa= $fechaActualmenosa.' '.'00:00:00';
$fechaActualmenosa_actual= $fecha_info.' '.'23:59:59';
$students3=query("CALL `lista_tolerancia_tabla`('".$fechaActualmenosa."', '".$fechaActualmenosa_actual."')");



echo '</br>';
echo 'version 1';
echo '</br>';
echo '</br>';
echo json_encode($students3);
echo '</br>';
echo '</br>';
echo json_encode($fechaActualmenosa);
echo '</br>';
echo '</br>';
echo json_encode($fechaActualmenosa_actual);
if(count($students3)>0){
   for($ilc=0; $ilc<count($students3); $ilc++)
   {
     echo json_encode($students3[$ilc]);
     echo '--------------'."<br>";
	 $punteroo= $students3[$ilc]['IdR'];
	 $cd= query("CALL `Informacion_puntero_st`('".$punteroo."')");
	if(count($cd)>0){

        $CI_VALI_=$cd[0]['CI'];
        $Dia_Semana_VALI_=$cd[0]['dias_cat'];
        $IdT_VALI_=$cd[0]['IdT'];
        $IdP_VALI_=$cd[0]['IdP'];
        $Fecha_VALI_=$cd[0]['Fecha'];
		$jefeId=$cd[0]['jefeId'];
        $Codigo= $cd[0]["Codigo"];
        $nombre_soli= $cd[0]["nombre_soli"];
        $correo_soli= $cd[0]["correo_soli"];
        $NombreUsuario= $cd[0]["NombreUsuario"];
        $Email= $cd[0]["Email"];
        $IdUsuario= $cd[0]["IdUsuario"];
		$contadorr= $cd[0]["Contador"];
		$Estado_TV= $cd[0]["Estado_TV"];
		$mensajee= $cd[0]["Detalle"];
	   

        $cdc=query("SELECT cc.Estado, cc.Id,cc.FechaRegistro, cc.Fecha,cc.Hora ,cc.Detalle_, if(ISNULL(cc.Hora), '1', '0') AS estado_nuevo
		 FROM reportedias_nomarcados cc WHERE cc.IdT= $IdT_VALI_ AND cc.IdP= $IdP_VALI_ AND cc.Fecha='$Fecha_VALI_' AND cc.CI='$CI_VALI_'");

        if(count($cdc)>0){

			$nuevo_estado=$cdc[0]["estado_nuevo"];
			$det= $cdc[0]["Detalle_"];
			$drr= $Fecha_VALI_.' '.$det;

			if($Estado_TV==2){
				$detalle_me='Pendiente por tu inmediato superior '.$NombreUsuario;
                $dvd= query("update reportedias_nomarcados set  FechaModificacion='$fecha', Estado_TV='$detalle_me' ,Est_TV=2, Detalle_TV='$mensajee', Solicitud_tv='$fecha',Estado=0 
				where CI='$CI_VALI_'  AND dias_cat=$Dia_Semana_VALI_  and IdT=$IdT_VALI_   and IdP=$IdP_VALI_ and Fecha='$Fecha_VALI_'");
			}
		
			if($Estado_TV==1){
				$detalle_me='Aprobado por tu inmediato superior '.$NombreUsuario;
				$dvd= query("update reportedias_nomarcados set Est_TV=1,Estado_TV='$detalle_me', Estado=0, contador='$contadorr' , FechaRegistro='$drr' , Hora='$det' 
				where CI='$CI_VALI_'  AND dias_cat=$Dia_Semana_VALI_  and IdT=$IdT_VALI_   and IdP=$IdP_VALI_ and Fecha='$Fecha_VALI_'");
			}

			if($Estado_TV==3){
				$detalle_me='Rechazado por tu inmediato superior '.$NombreUsuario;
				$dvd= query("update reportedias_nomarcados set  Est_TV=3, Estado_TV='$detalle_me', Estado=$nuevo_estado  
				where CI='$CI_VALI_'  AND dias_cat=$Dia_Semana_VALI_  and IdT=$IdT_VALI_   and IdP=$IdP_VALI_ and Fecha='$Fecha_VALI_'");
			}

		}

	}
   }
}
?>
