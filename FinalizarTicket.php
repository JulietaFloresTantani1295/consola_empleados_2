
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




$diao="SELECT yu.DiasCU FROM tipoaccesos yu WHERE yu.IdTA=7";
$diao= query($diao);
$dias_l=$diao[0]['DiasCU'];
echo $dias_l ."<br>";

$fdc=query("SELECT T.IdT, T.Codigo, T.Estado, T.FechaCreacion, T.CodigoRelacion, T.Nombre, T.ultima_fecha, T.dias_transcurridos
FROM
(
 (

SELECT cc.IdT, cc.Codigo, cc.Estado, cc.FechaCreacion, cc.CodigoRelacion, ss.Nombre ,
MAX(aa.FechaFin) AS ultima_fecha,  TIMESTAMPDIFF(DAY, MAX(aa.FechaFin),NOW() ) AS dias_transcurridos
FROM ticket cc 
INNER JOIN estadoticket ss ON ss.IdET= cc.Estado
INNER JOIN asignacion aa ON aa.IdTicket= cc.IdT

WHERE ss.IdET=5 AND NOT EXISTS(SELECT * FROM  asignacion vv WHERE vv.IdTicket= cc.IdT AND vv.estado=1 )
GROUP BY cc.IdT
ORDER BY TIMESTAMPDIFF(DAY, MAX(aa.FechaFin),NOW() ) desc

)

) T WHERE T.dias_transcurridos!=0 AND  T.dias_transcurridos >= $dias_l");

if(count($fdc)>0){

	$ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
	VALUES ('FinalizarTicket.php','$fecha', 'Hay datos')";
	$ejec_ser=query($ej_servicio);

	for($ilc=0; $ilc<count($fdc); $ilc++)
    {
	        $codigo_ticket= $fdc[$ilc]['IdT'];
	        $dias_transcurridos= $fdc[$ilc]['dias_transcurridos'];

			echo $codigo_ticket ."<br>";
            $actualizar_valor ="update ticket SET Estado=6, FechaModificacion='$fecha' WHERE IdT=$codigo_ticket";
			$actualizar_valor = query($actualizar_valor);
               
			$dety="Proceso realizado automaticamente ya que han pasado ".$dias_transcurridos." días para que de conformidad al ticket y el tiempo límite es ".$dias_l." días";
            $insert_actualizado_automatico= query("insert INTO  terminadoautomatico (idticket, fecharegistro, detalle ) VALUE ($codigo_ticket, '$fecha', '$dety')");

	}
}else{
	echo $dias_l ."<br>";

	$ej_servicio="insert into ejecucion_servicios  (Servicio, FechaEjecucion, Detalle)
	VALUES ('FinalizarTicket.php','$fecha', 'No Hay datos')";
	$ejec_ser=query($ej_servicio);
}



?>
