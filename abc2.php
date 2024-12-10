<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("America/La_Paz");

require_once(dirname(__FILE__).'/class/class.phpmailer.php');
require_once(dirname(__FILE__).'/lib/Exception.php');
require_once(dirname(__FILE__).'/lib/PHPMailer.php');
require_once(dirname(__FILE__).'/lib/SMTP.php');


function is_valid_email($str)
{
  $matches = null;
  return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
}


function asignacion_ticket_soli($IdTicket,$vg){
	$message1 ='';
	$message2 ='';
	$fecha=date('Y-m-d H:i:s');


  $ff=query("SELECT uu.IdUsuario, CONCAT_WS(' ', aty.Nombre_AT,' - ', ak.Nombre, ' - ',att.Nombre)  AS NombreActividad,
   uu.NombreUsuario,  uu.Email, tt.Codigo , ui.NombreUsuario AS nombre_soli ,ui.Email AS email_soli , pp.Nombre AS estado_prioridad from asignacion aa 
  INNER JOIN actividad_ticket att ON att.IdAct_t= aa.IdActividad  AND att.Estado=1
  INNER JOIN tareas_ticket ak ON ak.IdTarea= att.Id_T AND ak.Estado=1
  INNER JOIN area_ticket aty ON aty.IdAT= ak.IdAT AND aty.Estado=1
  INNER JOIN usuario uu ON uu.IdUsuario= aa.IdUsuario AND uu.Estado=1
  INNER JOIN ticket tt ON tt.IdT= aa.IdTicket 
  INNER JOIN tipoticket top ON top.IdTT= tt.TipoTicket AND top.Estado=1
  INNER JOIN usuario ui ON ui.IdUsuario= tt.IdUsuario AND ui.Estado=1
  INNER JOIN prioridad pp ON pp.idp= tt.Prioridad AND pp.Estado=1
  WHERE aa.idticket=$IdTicket AND aa.Estado_eje=$vg
  GROUP BY uu.IdUsuario ");
  

  $url= query("SELECT tt.Dominio, tt.NombreDominio FROM tipoaccesos tt  WHERE tt.IdTA=2");
  $url =$url[0]['Dominio'].'/'.$url[0]['Dominio'];



$ff_soli= query(" SELECT T.IdUsuario, T.NombreUsuario, T.NombreActividad, T.nro_telefono, T.Email, T.Codigo, T.nombre_soli, T.email_soli, T.estado_prioridad
FROM
  (
   (
   SELECT uu.IdUsuario, uu.NombreUsuario, CONCAT_WS(' ', aty.Nombre_AT,' - ', ak.Nombre, ' - ',att.Nombre)  AS NombreActividad,
uu.nro_telefono, uu.Email, tt.Codigo , ui.NombreUsuario AS nombre_soli ,ui.Email AS email_soli , pp.Nombre AS estado_prioridad from asignacion aa 
INNER JOIN actividad_ticket att ON att.IdAct_t= aa.IdActividad  AND att.Estado=1
INNER JOIN tareas_ticket ak ON ak.IdTarea= att.Id_T AND ak.Estado=1
INNER JOIN area_ticket aty ON aty.IdAT= ak.IdAT AND aty.Estado=1
INNER JOIN usuario uu ON uu.IdUsuario= aa.IdUsuario AND uu.Estado=1
INNER JOIN ticket tt ON tt.IdT= aa.IdTicket 
INNER JOIN tipoticket top ON top.IdTT= tt.TipoTicket AND top.Estado=1
INNER JOIN usuario ui ON ui.IdUsuario= tt.IdUsuario AND ui.Estado=1
INNER JOIN prioridad pp ON pp.idp= tt.Prioridad AND pp.Estado=1
WHERE aa.idticket=$IdTicket AND aa.Estado_eje=$vg


UNION 


SELECT 0 as IdUsuario, 'SIN ANALISTA' as NombreUsuario,	CONCAT_WS(' ', aty2.Nombre_AT,' - ', aty1.Nombre, ' - ',aty.Nombre)   AS NombreActividad, '' AS nro_telefono,'' AS Email , tt.Codigo, uu.NombreUsuario AS nombre_soli, uu.Email AS email_soli,pp.Nombre AS estado_prioridad
FROM ticket tt 
  INNER JOIN detalle_heldesk df ON df.CodigoRelacion= tt.CodigoRelacion AND df.Estado=1
  INNER JOIN usuario uu ON uu.IdUsuario= tt.IdUsuario AND uu.Estado=1
  INNER JOIN prioridad pp ON pp.idp= tt.Prioridad AND pp.Estado=1
  INNER JOIN actividad_ticket aty ON aty.IdAct_t= df.Ids AND aty.Estado=1
  INNER JOIN tareas_ticket aty1 ON aty1.IdTarea= aty.Id_T AND aty1.Estado=1
  INNER JOIN area_ticket aty2 on aty2.IdAT= aty1.IdAT AND aty2.Estado=1
  WHERE tt.idt=$IdTicket AND NOT EXISTS(SELECT * FROM asignacion aa WHERE aa.IdActividad= df.Ids AND aa.IdTicket=$IdTicket)

   
   )

) T ORDER BY T.IdUsuario desc");


if(count($ff_soli)>0){


	$soli=$ff_soli[0]['nombre_soli'];
	$email_soli=$ff_soli[0]['email_soli'];
	$estado_pri=$ff_soli[0]['estado_prioridad'];
	$codigo_tt=$ff_soli[0]['Codigo'];

	$detyy='';
for($id=0; $id<count($ff_soli); $id++)
{ 
	
	$soli_ana=$ff_soli[$id]['NombreUsuario'];
	$soli_correo_ana=$ff_soli[$id]['Email'];
	$soli_telefono=$ff_soli[$id]['nro_telefono'];
	$actividad=$ff_soli[$id]['NombreActividad'];

	$dd=$id+1;

	 $detyy=$detyy.'<tr>		
	 
	 <td width="10%" style="    text-align: center;">'.$dd.'</td>
	<td width="30%" style="    text-align: center;">'.$soli_ana.'</td>
	<td width="12%" style="    text-align: center;">'.$soli_telefono.'</td>
	<td width="18%" style="    text-align: center;">'.$soli_correo_ana.'</td>
	<td width="30%" style="    text-align: center;">'.$actividad.'</td>


     </tr>';
}





$message2 = '
<h3 align="center">Estimad@ '.$soli.' </h3>
<h3 align="center"> Detalle Tipo de Soporte</h3>
<h3 align="center">Código de Ticket: '.$codigo_tt.'  , estado de Prioridad  "'.$estado_pri.'"</h3>



<table border="1" width="100%" cellpadding="5" cellspacing="5">
<tr>				
	<td width="10%" style="    text-align: center;">#</td>
	<td width="30%" style="    text-align: center;">Nombre del Analista</td>
	<td width="12%" style="    text-align: center;">Número de Contacto</td>
	<td width="18%" style="    text-align: center;">Correo</td>
	<td width="30%" style="    text-align: center;">Actividad Asignada</td>
	
</tr>

'.$detyy.'


</table>


<table border="1" width="100%" cellpadding="5" cellspacing="5">
	<tr>				
		<td width="100%" style="    text-align: center;">Respeta tu número de Ticket !!!</td>
	</tr>
	<tr>
	<td width="100%" style="    text-align: center;"><a href='.$url.'  style="background-color: red;border: none;color: white;padding: 15px 32px;text-align: center;	text-decoration: none;		display: inline-block;	font-size: 30px;	margin: 4px 2px;width: auto; cursor: pointer;">
		  <strong> <font color="#FFFFFF"  style="font-size: 25px;">Revisa en la Plataforma</font> </strong></a>

		  </td>
	
	</tr>
</table>
';





try {
	$mail = new PHPMailer;
	$mail->IsSMTP();	
	$mail->CharSet   = 'UTF-8';
	$mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
	$mail->Host = 'mail.monterreysrl.com.bo';		
	$mail->Port = 587;								//Sets the default SMTP server port
	$mail->SMTPAuth = true;							//Sets SMTP authentication.
	$mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
	$mail->Password = 'sssTTT123';					//Sets SMTP password
	$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
	$mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
	$mail->FromName = 'Detalle de Solicitud de Ticket '.$codigo_tt;				//Sets the From name of the message
	
	if(is_valid_email($email_soli)){
		$mail->AddAddress($email_soli,$soli);		//Adds a "To" address
		$mail->Subject = 'Detalle de Solicitud de Ticket '.$codigo_tt;	
	}
	$mail->WordWrap = 50;							
	$mail->IsHTML(true);							//Sets message type to HTML
	$mail->Body = $message2;
	$mail->send();	
	
	
	$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('$codigo_tt','Detalle de Solicitud de Ticket '.$codigo_tt,'$message2', '$fecha',1,'','$email_soli')");
	
	} catch (Exception $e) {
	
	$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino) values ('$codigo_tt','Detalle de Solicitud de Ticket '.$codigo_tt,'$message2', '$fecha',0,'','$email_soli')");
	
	}
	  





}else{

	$detyy='';
	 $liop=query("SELECT tt.Codigo, uu.NombreUsuario AS nombre_soli, uu.Email AS email_soli,pp.Nombre AS estado_prioridad, df.Ids FROM ticket tt 
	 INNER JOIN detalle_heldesk df ON df.CodigoRelacion= tt.CodigoRelacion AND df.Estado=1
	 INNER JOIN usuario uu ON uu.IdUsuario= tt.IdUsuario AND uu.Estado=1
	 INNER JOIN prioridad pp ON pp.idp= tt.Prioridad AND pp.Estado=1
	 WHERE tt.idt=$IdTicket");

	$soli=$liop[0]['nombre_soli'];
	$email_soli=$liop[0]['email_soli'];
	$estado_pri=$liop[0]['estado_prioridad'];
	$codigo_tt=$liop[0]['Codigo'];

     if(count($liop)>0 && count($liop)==1){
  
		$detyy=$detyy.'<td width="100%" style="    text-align: center;">El Jefe de Sistemas asignara a los analistas correspondientes para el soporte que solicitaste !!!  </td>';
	 }else{
		if(count($liop)>0 && count($liop)>1){
  
			$detyy=$detyy.'<td width="100%" style="    text-align: center;">El Jefe de Sistemas asignara a los analistas correspondientes para los soportes que solicitaste !!!  </td>';
		}	 
	 }


	$message2 = '
	<h3 align="center">Estimad@ '.$soli.' </h3>
	<h3 align="center"> Detalle Tipo de Soporte</h3>
	<h3 align="center">Código de Ticket: '.$codigo_tt.'  , estado de Prioridad  "'.$estado_pri.'"</h3>
	
	
	
	<table border="1" width="100%" cellpadding="5" cellspacing="5">
	<tr>				
		'.$detyy.'
	</tr>
	

	
	</table>
	
	
	<table border="1" width="100%" cellpadding="5" cellspacing="5">
		<tr>				
			<td width="100%" style="    text-align: center;">Respeta tu número de Ticket !!!</td>
		</tr>
		<tr>
		<td width="100%" style="    text-align: center;"><a href='.$url.'  style="background-color: red;border: none;color: white;padding: 15px 32px;text-align: center;	text-decoration: none;		display: inline-block;	font-size: 30px;	margin: 4px 2px;width: auto; cursor: pointer;">
			  <strong> <font color="#FFFFFF"  style="font-size: 25px;">Revisa en la Plataforma</font> </strong></a>
	
			  </td>
		
		</tr>
	</table>
	';
	
	
	
	
	
	try {
		$mail = new PHPMailer;
		$mail->IsSMTP();	
		$mail->CharSet   = 'UTF-8';
		$mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
		$mail->Host = 'mail.monterreysrl.com.bo';		
		$mail->Port = 587;								//Sets the default SMTP server port
		$mail->SMTPAuth = true;							//Sets SMTP authentication.
		$mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
		$mail->Password = 'sssTTT123';					//Sets SMTP password
		$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
		$mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
		$mail->FromName = 'Detalle de Solicitud de Ticket '.$codigo_tt;				//Sets the From name of the message
		
		if(is_valid_email($email_soli)){
			$mail->AddAddress($email_soli,$soli);		//Adds a "To" address
			$mail->Subject = 'Detalle de Solicitud de Ticket '.$codigo_tt;	
		}
		$mail->WordWrap = 50;							
		$mail->IsHTML(true);							//Sets message type to HTML
		$mail->Body = $message2;
		$mail->send();	
		
		
		$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('$codigo_tt','Detalle de Solicitud de Ticket '.$codigo_tt,'$message2', '$fecha',1,'','$email_soli')");
		
		} catch (Exception $e) {
		
		$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino) values ('$codigo_tt','Detalle de Solicitud de Ticket '.$codigo_tt,'$message2', '$fecha',0,'','$email_soli')");
		
		}
		  
	
	



}






if(count($ff)>0){





	for($i=0; $i<count($ff); $i++)
	{ 
	  $ido=$ff[$i]['IdUsuario'];
	  $ido_nombre=$ff[$i]['NombreUsuario'];
	  $ido_correo=$ff[$i]['Email'];
	  $ido_codigo=$ff[$i]['Codigo'];
	  $ido_nombre_soli=$ff[$i]['nombre_soli'];
	  $ido_email_soli=$ff[$i]['email_soli'];
	  $ido_estado_prioridad=$ff[$i]['estado_prioridad'];
	
	
	 
	  $list=query("SELECT  aa.IdActividad, tt.TipoTicket,top.Nombre AS tipo_t,CONCAT_WS(' ', aty.Nombre_AT,' - ', ak.Nombre, ' - ',att.Nombre)  AS NombreActividad    from asignacion aa 
	  INNER JOIN actividad_ticket att ON att.IdAct_t= aa.IdActividad  AND att.Estado=1
	  INNER JOIN tareas_ticket ak ON ak.IdTarea= att.Id_T AND ak.Estado=1
	  INNER JOIN area_ticket aty ON aty.IdAT= ak.IdAT AND aty.Estado=1
	  INNER JOIN usuario uu ON uu.IdUsuario= aa.IdUsuario AND uu.Estado=1
	  INNER JOIN ticket tt ON tt.IdT= aa.IdTicket 
	  INNER JOIN tipoticket top ON top.IdTT= tt.TipoTicket AND top.Estado=1
	  WHERE aa.idticket=$IdTicket AND aa.Estado_eje=$vg AND uu.IdUsuario=$ido
	  ORDER BY uu.IdUsuario asc");
		$tipo_ticket=$list[0]['tipo_t'];
		$tipt=$list[0]['TipoTicket'];
    	$dety='';
	   $dety_det='';

      if($tipt==2){

          $sx=query("SELECT tt.Detalle FROM tablaheldesk tt
		  INNER JOIN ticket tl ON tl.CodigoRelacion= tt.Codigo
		  WHERE tl.IdT=$IdTicket");
          $fty=  $sx[0]['Detalle'];

		$dety_det='	<table border="1" width="100%" cellpadding="5" cellspacing="5">
		<tr>				
			<td width="30%" style="    text-align: center;">Detalle :</td>
			<td width="70%" style="    text-align: center;">'.$fty.'</td>
		</tr>
		</table>
	';
	  }


	for($ii=0; $ii<count($list); $ii++)
	{ 
		 $dd=$ii+1;
		$ido_nombreactividad=$list[$ii]['NombreActividad'];
		 $dety=$dety.'<tr>				
		 <td width="10%" style="    text-align: center;">'.$dd.'</td>
		 <td width="90%" style="    text-align: center;">'.$ido_nombreactividad.'</td>
	 </tr>';
	   
	}
	
	$message1 = '
	<h3 align="center">Estimad@ '.$ido_nombre.' </h3>
	<h3 align="center">Asignación de Tareas </h3>
	<h3 align="center">Código de Ticket: '.$ido_codigo.'  , estado de Prioridad  "'.$ido_estado_prioridad.'"</h3>
	<h3 align="center">Solicitante del Ticket: '.$ido_nombre_soli.'</h3>
	
	
	
	<table border="1" width="100%" cellpadding="5" cellspacing="5">
	<tr>				
		<td width="10%" style="    text-align: center;">#</td>
		<td width="90%" style="    text-align: center;">DETALLE</td>
	</tr>
	
	'.$dety.'
	
	
	</table>
	
	

	<table border="1" width="100%" cellpadding="5" cellspacing="5">
	<tr>				
		<td width="30%" style="    text-align: center;">Tipo de Ticket:</td>
		<td width="70%" style="    text-align: center;">'.$tipo_ticket.'</td>
	</tr>
	</table>



'.$dety_det.'



	<table border="1" width="100%" cellpadding="5" cellspacing="5">


	
		<tr>				
			<td width="100%" style="    text-align: center;">Exitos en tus tareas</td>
		</tr>
		<tr>
		<td width="100%" style="    text-align: center;"><a href='.$url.'  style="background-color: red;border: none;color: white;padding: 15px 32px;text-align: center;	text-decoration: none;		display: inline-block;	font-size: 30px;	margin: 4px 2px;width: auto; cursor: pointer;">
			  <strong> <font color="#FFFFFF"  style="font-size: 25px;">Revisa en la Plataforma</font> </strong></a>
	
			  </td>
		
		</tr>
	</table>
	';
	
	
	
	
	
	try {
	$mail = new PHPMailer;
	$mail->IsSMTP();	
	$mail->CharSet   = 'UTF-8';
	$mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
	$mail->Host = 'mail.monterreysrl.com.bo';		
	$mail->Port = 587;								//Sets the default SMTP server port
	$mail->SMTPAuth = true;							//Sets SMTP authentication.
	$mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
	$mail->Password = 'sssTTT123';					//Sets SMTP password
	$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
	$mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
	$mail->FromName = 'Asignación de tarea Ticket '.$ido_codigo;				//Sets the From name of the message
	
	if(is_valid_email($ido_correo)){
		$mail->AddAddress($ido_correo,$ido_nombre);		//Adds a "To" address
		$mail->Subject = 'Asignación de tarea Ticket '.$ido_codigo;	
		  $li_adm=query("SELECT c.posi  , c.OAdmin, u.NombreUsuario, u.Email FROM cargo c 
		  INNER JOIN usuario u ON u.IdCargo= c.IdCargo  AND c.Estado=1
		  WHERE c.idarea=21 AND c.OAdmin=1");
		
	if(count($li_adm)>0){
	
	for($ig=0; $ig<count($li_adm); $ig++)
	{  
		$correo_admin=$li_adm[$ig]['Email'];
		 if(is_valid_email($correo_admin)){
			$mail->addCC($correo_admin);
		  }	
	}
	}
	
	
	
		
	}else{
		
	
		$li_adm=query("SELECT c.posi  , c.OAdmin, u.NombreUsuario, u.Email FROM cargo c 
		INNER JOIN usuario u ON u.IdCargo= c.IdCargo  AND c.Estado=1
		WHERE c.idarea=21 AND c.OAdmin=1");
	  
	if(count($li_adm)>0){
	
	for($ig=0; $ig<count($li_adm); $ig++)
	{  
		$correo_admin=$li_adm[$ig]['Email'];
		$nombre_admin=$li_adm[$ig]['NombreUsuario'];
		
	
		if(is_valid_email($correo_admin)){
		   $mail->AddAddress($correo_admin,$nombre_admin);	
		   $mail->Subject = 'Analista no tiene Correo Valido Verificar - Asignación de tarea Ticket '.$ido_codigo;			
		}	
	}
	}
	
	
	}	
	
	$mail->WordWrap = 50;							
	$mail->IsHTML(true);							//Sets message type to HTML
		//Sets the Subject of the message
	$mail->Body = $message1;
	$mail->send();	
	
	
	$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('$ido_codigo','Asignación de tarea Ticket $ido_codigo','$message1', '$fecha',1,'','$ido_correo')");
	
	} catch (Exception $e) {
	
	$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino) values ('$ido_codigo','Asignación de tarea Ticket $ido_codigo','$message1', '$fecha',0,'','$ido_correo')");
	
	}
	
	
	
	
	
	
	}
	


}





	$detyy='';

	$liop = query("SELECT tt.Codigo, uu.NombreUsuario AS nombre_soli, uu.Email AS email_soli,pp.Nombre AS estado_prioridad, df.Ids, 
	CONCAT_WS(' ', aty2.Nombre_AT,' - ', aty1.Nombre, ' - ',aty.Nombre)   AS nom_actividad FROM ticket tt 
	INNER JOIN detalle_heldesk df ON df.CodigoRelacion= tt.CodigoRelacion AND df.Estado=1
	INNER JOIN usuario uu ON uu.IdUsuario= tt.IdUsuario AND uu.Estado=1
	INNER JOIN prioridad pp ON pp.idp= tt.Prioridad AND pp.Estado=1
	INNER JOIN actividad_ticket aty ON aty.IdAct_t= df.Ids AND aty.Estado=1
	INNER JOIN tareas_ticket aty1 ON aty1.IdTarea= aty.Id_T AND aty1.Estado=1
	INNER JOIN area_ticket aty2 on aty2.IdAT= aty1.IdAT AND aty2.Estado=1
	WHERE tt.idt=$IdTicket AND NOT EXISTS(SELECT * FROM asignacion aa WHERE aa.IdActividad= df.Ids AND aa.IdTicket=$IdTicket)");

    if(count($liop)>0){

		for($id=0; $id<count($liop); $id++)
		{ 
			$dd=$id+1;
			$nom_actividad=$liop[$id]['nom_actividad'];
			 $detyy=$detyy.'<tr>		
			 <td width="10%" style="    text-align: center;">'.$dd.'</td>
			<td width="90%" style="    text-align: center;">'.$nom_actividad.'</td>
			 </tr>';
		}
	
		
		$soli=$liop[0]['nombre_soli'];
     	$email_soli=$liop[0]['email_soli'];
	    $estado_pri=$liop[0]['estado_prioridad'];
	    $codigo_tt=$liop[0]['Codigo'];




		$li_adm=query("SELECT c.posi  , c.OAdmin, u.NombreUsuario, u.Email FROM cargo c 
		INNER JOIN usuario u ON u.IdCargo= c.IdCargo  AND c.Estado=1
		WHERE c.idarea=21 AND c.OAdmin=1");
		if(count($li_adm)>0){
		
			for($ig=0; $ig<count($li_adm); $ig++)
			{  
				$correo_admin=$li_adm[$ig]['Email'];
				$nombre_admin=$li_adm[$ig]['NombreUsuario'];
				
				$message2 = '
		<h3 align="center">Estimad@ '.$nombre_admin.' </h3>
		<h3 align="center"> Detalle Tipo de Soporte sin Analista asignado</h3>
		<h3 align="center">Código de Ticket: '.$codigo_tt.'  , estado de Prioridad  "'.$estado_pri.'"</h3>
		<h3 align="center">Solicitante del Ticket: '.$soli.'</h3>
	
	
		<table border="1" width="100%" cellpadding="5" cellspacing="5">
		<tr>				
			<td width="10%" style="    text-align: center;">#</td>
			<td width="90%" style="    text-align: center;">Actividad</td>
		
			
		</tr>
		
		'.$detyy.'
		
		
		</table>
		
		
		<table border="1" width="100%" cellpadding="5" cellspacing="5">
			<tr>				
				<td width="100%" style="    text-align: center;">Asignar Analista a estas Actividades !!!</td>
			</tr>
			<tr>
			<td width="100%" style="    text-align: center;"><a href='.$url.'  style="background-color: red;border: none;color: white;padding: 15px 32px;text-align: center;	text-decoration: none;		display: inline-block;	font-size: 30px;	margin: 4px 2px;width: auto; cursor: pointer;">
				  <strong> <font color="#FFFFFF"  style="font-size: 25px;">Revisa en la Plataforma</font> </strong></a>
		
				  </td>
			
			</tr>
		</table>
		';
		
		try {
			$mail = new PHPMailer;
			$mail->IsSMTP();	
			$mail->CharSet   = 'UTF-8';
			$mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
			$mail->Host = 'mail.monterreysrl.com.bo';		
			$mail->Port = 587;								//Sets the default SMTP server port
			$mail->SMTPAuth = true;							//Sets SMTP authentication.
			$mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
			$mail->Password = 'sssTTT123';					//Sets SMTP password
			$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
			$mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
			$mail->FromName = 'Detalle de Solicitud de Ticket Actividades Sin Analistas '.$codigo_tt;				//Sets the From name of the message
			
			if(is_valid_email($correo_admin)){
				$mail->AddAddress($correo_admin,$nombre_admin);		//Adds a "To" address
				$mail->Subject = 'Detalle de Solicitud de Ticket Actividades Sin Analistas '.$codigo_tt;	
			}
			$mail->WordWrap = 50;							
			$mail->IsHTML(true);							//Sets message type to HTML
			$mail->Body = $message2;
			$mail->send();	
			
			
			$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('$codigo_tt','Detalle de Solicitud de Ticket Actividades Sin Analistas '.$codigo_tt,'$message2', '$fecha',1,'','$correo_admin')");
			
			} catch (Exception $e) {
			
			$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino) values ('$codigo_tt','Detalle de Solicitud de Ticket Actividades Sin Analistas '.$codigo_tt,'$message2', '$fecha',0,'','$correo_admin')");
			
			}
			  
			
	
	
	
			}
			
		
		}
	





	








	}



	
  

	
	
	
	
	







	
























}

















function enviar_correo_actividad_pendiente_total(){
    $fef=query("SELECT uu.IdUsuario, uu.NombreUsuario,uu.CodigoE ,
    cc.Nombre, cc.Estado , if((SELECT  COUNT(tt.IdT) FROM ticket tt
    INNER JOIN  estadoticket ee ON ee.IdET= tt.Estado 
    WHERE ee.IdET IN (2,3,4,5) AND tt.IdUsuario=uu.IdUsuario)=0, 'Sin Pendiente', (SELECT GROUP_CONCAT(tt.Codigo SEPARATOR ' ;        ') AS ticketd FROM ticket tt
    INNER JOIN  estadoticket ee ON ee.IdET= tt.Estado 
    WHERE ee.IdET IN (2,3,4,5) AND tt.IdUsuario=uu.IdUsuario)  ) AS Ticket_Activo,
    
    if((SELECT COUNT(f.IdFormulario) FROM formulario f 
                    INNER JOIN detalleformulario df ON df.IdFormulario= f.IdFormulario
                    INNER JOIN usuario uw ON uw.IdUsuario = df.ISuperior
                    INNER JOIN estadoformulario ss ON ss.IdEF= df.IdEF
                    WHERE f.idusuario=uu.IdUsuario
                     )=0,'Sin Pendiente',(SELECT  GROUP_CONCAT(f.CodForm SEPARATOR ' ;        ')  AS formularios FROM formulario f 
                    INNER JOIN detalleformulario df ON df.IdFormulario= f.IdFormulario
                    INNER JOIN usuario uw ON uw.IdUsuario = df.ISuperior
                    INNER JOIN estadoformulario ss ON ss.IdEF= df.IdEF
                    WHERE f.idusuario=uu.IdUsuario
                     )) AS solicitud_personal
    
      FROM usuario uu
    INNER JOIN cempleados cc ON cc.Codigo= uu.CodigoE AND cc.Estado=0 
    WHERE  uu.Estado=1 AND  NOT EXISTS (SELECT * FROM cempleados ccd WHERE ccd.Codigo= uu.CodigoE AND ccd.Estado=1)
    GROUP BY uu.IdUsuario
    ORDER BY cc.Codigo asc");
    $message = '';
    $detyy='';
    $fecha=date('Y-m-d H:i:s');
      if(count($fef)>0){
    
       
        for($ids=0; $ids<count($fef); $ids++)
        { 
            $nom_normal= $fef[$ids]['NombreUsuario'];
            $nom_th5= $fef[$ids]['Nombre'];
            $codigo_th5= $fef[$ids]['CodigoE'];
            $fde=$ids+1;
            $t_pendiente=$fef[$ids]['Ticket_Activo'];
            $sp_pendiente=$fef[$ids]['solicitud_personal'];
            $detyy=$detyy.'<tr>		
            <td width="5%" ROWSPAN=2 style="    text-align: center;">'.$fde.'</td>
            <td width="15%" ROWSPAN=2  style="    text-align: center;">'.$nom_normal.'</td>
            <td width="5%"  ROWSPAN=2 style="    text-align: center;">'.$codigo_th5.'</td>
            <td width="15%" ROWSPAN=2  style="    text-align: center;">'.$nom_th5.'</td>


            <td width="10%" style="    text-align: center;">Ticket Pendiente</td>
            <td width="20%" style="    text-align: center;">'.$t_pendiente.'</td>

            </tr>      
             <tr>	   <td width="10%" style="    text-align: center;">Solicitud Personal Pendiente</td>
            <td width="20%" style="    text-align: center;">'.$sp_pendiente.'</td>  </tr> ';
    
         
        }

        $lis_admin="SELECT T.IdUsuario, T.NombreUsuario, T.Email 
        FROM
        ((
        SELECT uu.IdUsuario, uu.NombreUsuario, uu.Email  FROM usuario uu
        INNER JOIN oficina oo ON oo.IdOficina= uu.IdOficina AND oo.Estado=1
        INNER JOIN empresa ee ON ee.IdEmpresa= oo.IdEmpresa AND ee.Estado=1
         WHERE uu.TI=1 AND uu.Estado=1 AND ee.IdEmpresa IN (1,2,3)
        UNION 
        SELECT uu.IdUsuario, uu.NombreUsuario, uu.Email FROM usuario uu
         WHERE uu.AdminRRHH=1 AND uu.Estado=1 
        ))T
         ORDER BY T.IdUsuario asc LIMIT 0,1";
       $lis_admin= query($lis_admin);
       if(count($lis_admin)){
        for($ids=0; $ids<count($lis_admin); $ids++)
        { 
            $nombre_admin=$lis_admin[$ids]['NombreUsuario'];
            $email_admin=$lis_admin[$ids]['Email'];

            $message = '
            <h3 align="center">Lista de Pendientes en el Sistema</h3>
            <h3 align="center">Estimad@ '.$nombre_admin.'</h3>
      

            <table border="1" width="100%" cellpadding="5" cellspacing="5">
            <tr>				
                <td width="10%" style="    text-align: center;">#</td>
                <td width="20%" style="    text-align: center;">Nombre En Nuestro Sistema</td>
                <td width="10%" style="    text-align: center;">Codigo TH5</td>
                <td width="20%" style="    text-align: center;">Nombre TH5</td>
                <td width="10%" style="    text-align: center;">Sistema</td>
                <td width="30%" style="    text-align: center;">Detalle</td>
                
            </tr>
            '.$detyy.'';


         
            if(is_valid_email($email_admin)){
                try {
                    $mail = new PHPMailer;
                    $mail->IsSMTP();	
                    $mail->CharSet   = 'UTF-8';
                    $mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
                    $mail->Host = 'mail.monterreysrl.com.bo';		
                    $mail->Port = 587;								//Sets the default SMTP server port
                    $mail->SMTPAuth = true;							//Sets SMTP authentication.
                    $mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
                    $mail->Password = 'sssTTT123';					//Sets SMTP password
                    $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
                    $mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
                    $mail->FromName = 'Detalle Lista de Pendientes en el Sistema Usuario ';				//Sets the From name of the message
                
                    $mail->AddAddress($email_admin,$nombre_admin);		//Adds a "To" address
                    $mail->Subject = 'Detalle Lista de Pendientes en el Sistema Usuario ';	
                
                    $mail->WordWrap = 50;							
                    $mail->IsHTML(true);							//Sets message type to HTML
                    $mail->Body = $message;
                    $mail->send();	
                    
                    
                    $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('','Detalle Lista de Pendientes en el Sistema Usuario','$message', '$fecha',1,'','$email_admin')");
                     
                    } catch (Exception $e) {
                    
                    $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino) values ('','Detalle Lista de Pendientes en el Sistema Usuario','$message', '$fecha',0,'','$email_admin')");
                    
                    }


            }


        }
       }
       

      }




}



























function enviar_correo_actividad_pendiente($idusuario, $coe){
 //return 1;
             $fef=query("SELECT uu.IdUsuario, uu.NombreUsuario,uu.CodigoE ,
             cc.Nombre, cc.Estado , if((SELECT  COUNT(tt.IdT) FROM ticket tt
             INNER JOIN  estadoticket ee ON ee.IdET= tt.Estado 
             WHERE ee.IdET IN (2,3,4,5) AND tt.IdUsuario=uu.IdUsuario)=0, 0, (SELECT GROUP_CONCAT(tt.Codigo SEPARATOR ' ;        ') AS ticketd FROM ticket tt
             INNER JOIN  estadoticket ee ON ee.IdET= tt.Estado 
             WHERE ee.IdET IN (2,3,4,5) AND tt.IdUsuario=uu.IdUsuario)  ) AS Ticket_Activo,
             
             if((SELECT COUNT(f.IdFormulario) FROM formulario f 
                             INNER JOIN detalleformulario df ON df.IdFormulario= f.IdFormulario
                             INNER JOIN usuario uw ON uw.IdUsuario = df.ISuperior
                             INNER JOIN estadoformulario ss ON ss.IdEF= df.IdEF
                             WHERE f.idusuario=uu.IdUsuario
                              )=0,0,(SELECT  GROUP_CONCAT(f.CodForm SEPARATOR ' ;        ')  AS formularios FROM formulario f 
                             INNER JOIN detalleformulario df ON df.IdFormulario= f.IdFormulario
                             INNER JOIN usuario uw ON uw.IdUsuario = df.ISuperior
                             INNER JOIN estadoformulario ss ON ss.IdEF= df.IdEF
                             WHERE f.idusuario=uu.IdUsuario
                              )) AS solicitud_personal
             
               FROM usuario uu
             INNER JOIN cempleados cc ON cc.Codigo= uu.CodigoE AND cc.Estado=0 
             WHERE  uu.Estado=1 AND  NOT EXISTS (SELECT * FROM cempleados ccd WHERE ccd.Codigo= uu.CodigoE AND ccd.Estado=1)
             AND uu.IdUsuario=$idusuario AND cc.Codigo=$coe
             ORDER BY uu.IdUsuario");





        	$message = '';
            $nom_normal= $fef[0]['NombreUsuario'];
            $nom_th5= $fef[0]['Nombre'];
            $detyy='';
            $t_pendiente=$fef[0]['Ticket_Activo'];
            $sp_pendiente=$fef[0]['solicitud_personal'];
         
            if($t_pendiente!='0'){
                $detyy=$detyy.'<tr>		
                <td width="10%" style="    text-align: center;">1</td>
                <td width="40%" style="    text-align: center;">Ticket Pendiente</td>
                <td width="50%" style="    text-align: center;">'.$t_pendiente.'</td>
                </tr>';
            }

            if($sp_pendiente!='0'){
                $detyy=$detyy.'<tr>		
                <td width="10%" style="    text-align: center;">2</td>
                <td width="40%" style="    text-align: center;">Solicitud de Personal Pendiente</td>
                <td width="50%" style="    text-align: center;">'.$sp_pendiente.'</td>
                </tr>';
            }


            if($sp_pendiente!='0'  || $t_pendiente!='0'){

                $fecha=date('Y-m-d H:i:s');

             $cdc= "update lista_pendientes_ui aa SET  aa.FechaModificacion='$fecha',  aa.Ticket_Pendiente='$t_pendiente',
              aa.SP_Pendiente='$sp_pendiente' WHERE aa.IdUsuario=$idusuario AND aa.CodigoE=$coe ";
              $cdc= query($cdc);

      
    

                $lis_admin="SELECT T.IdUsuario, T.NombreUsuario, T.Email 
                FROM
                ((
                SELECT uu.IdUsuario, uu.NombreUsuario, uu.Email  FROM usuario uu
                INNER JOIN oficina oo ON oo.IdOficina= uu.IdOficina AND oo.Estado=1
                INNER JOIN empresa ee ON ee.IdEmpresa= oo.IdEmpresa AND ee.Estado=1
                 WHERE uu.TI=1 AND uu.Estado=1 AND ee.IdEmpresa IN (1,2,3)
                UNION 
                SELECT uu.IdUsuario, uu.NombreUsuario, uu.Email FROM usuario uu
                 WHERE uu.AdminRRHH=1 AND uu.Estado=1 
                ))T
                 ORDER BY T.IdUsuario asc LIMIT 0,1";
               $lis_admin= query($lis_admin);
               if(count($lis_admin)){
                for($ids=0; $ids<count($lis_admin); $ids++)
                { 
                    $nombre_admin=$lis_admin[$ids]['NombreUsuario'];
                    $email_admin=$lis_admin[$ids]['Email'];

                    $message = '
                    <h3 align="center">Lista de Pendientes en el Sistema</h3>
                    <h3 align="center">Estimad@ '.$nombre_admin.'</h3>
                    <h3 align="center"> Usuario Sistema TH5: '.$nom_th5.'</h3>
                    <h3 align="center">Usuario Sistema Nuestro: '.$nom_normal.'</h3>
                
        
                    <table border="1" width="100%" cellpadding="5" cellspacing="5">
                    <tr>				
                        <td width="10%" style="    text-align: center;">#</td>
                        <td width="40%" style="    text-align: center;">Sistema</td>
                        <td width="50%" style="    text-align: center;">Detalle</td>
                        
                    </tr>
                    '.$detyy.'';


                 
                    if(is_valid_email($email_admin)){
                        try {
                            $mail = new PHPMailer;
                            $mail->IsSMTP();	
                            $mail->CharSet   = 'UTF-8';
                            $mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
                            $mail->Host = 'mail.monterreysrl.com.bo';		
                            $mail->Port = 587;								//Sets the default SMTP server port
                            $mail->SMTPAuth = true;							//Sets SMTP authentication.
                            $mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
                            $mail->Password = 'sssTTT123';					//Sets SMTP password
                            $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
                            $mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
                            $mail->FromName = 'Lista de Pendientes en el Sistema Usuario '.$nom_th5;				//Sets the From name of the message
                        
                            $mail->AddAddress($email_admin,$nombre_admin);		//Adds a "To" address
                            $mail->Subject = 'Lista de Pendientes en el Sistema Usuario '.$nom_th5;	
                        
                            $mail->WordWrap = 50;							
                            $mail->IsHTML(true);							//Sets message type to HTML
                            $mail->Body = $message;
                            $mail->send();	
                            
                            
                            $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('','Lista de Pendientes en el Sistema '.$nom_th5,'$message', '$fecha',1,'','$email_admin')");
                             
                            } catch (Exception $e) {
                            
                            $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino) values ('','Lista de Pendientes en el Sistema '.$nom_th5,'$message', '$fecha',0,'','$email_admin')");
                            
                            }
    
    
                    }
    
    
                }
               }
    

            }else{

                $fecha=date('Y-m-d H:i:s');

               $cdc= "update lista_pendientes_ui aa SET  aa.FechaModificacion='$fecha' WHERE aa.IdUsuario=$idusuario AND aa.CodigoE=$coe ";
              $cdc= query($cdc);

      
    

                $lis_admin="SELECT T.IdUsuario, T.NombreUsuario, T.Email 
                FROM
                ((
                SELECT uu.IdUsuario, uu.NombreUsuario, uu.Email  FROM usuario uu
                INNER JOIN oficina oo ON oo.IdOficina= uu.IdOficina AND oo.Estado=1
                INNER JOIN empresa ee ON ee.IdEmpresa= oo.IdEmpresa AND ee.Estado=1
                 WHERE uu.TI=1 AND uu.Estado=1 AND ee.IdEmpresa IN (1,2,3)
                UNION 
                SELECT uu.IdUsuario, uu.NombreUsuario, uu.Email FROM usuario uu
                 WHERE uu.AdminRRHH=1 AND uu.Estado=1 
                ))T
                 ORDER BY T.IdUsuario asc LIMIT 0,1";
               $lis_admin= query($lis_admin);
               if(count($lis_admin)){
                for($ids=0; $ids<count($lis_admin); $ids++)
                { 
                    $nombre_admin=$lis_admin[$ids]['NombreUsuario'];
                    $email_admin=$lis_admin[$ids]['Email'];

                    $message = '
                    <h3 align="center">Lista de Pendientes en el Sistema</h3>
                    <h3 align="center">Estimad@ '.$nombre_admin.'</h3>
                    <h3 align="center"> Usuario Sistema TH5: '.$nom_th5.'</h3>
                    <h3 align="center">Usuario Sistema Nuestro: '.$nom_normal.'</h3>
                
        
                    <table border="1" width="100%" cellpadding="5" cellspacing="5">
                    <tr>				
                     
                        <td width="100%" style="    text-align: center;">Detalle</td>
                        
                    </tr>


                   <tr>		
                   

                    <td width="100%" style="    text-align: center;">Dar de Baja al Usuario en Nuestros Sistemas Activos !!!</td>
                    </tr>';
                   


                 
                    if(is_valid_email($email_admin)){
                        try {
                            $mail = new PHPMailer;
                            $mail->IsSMTP();	
                            $mail->CharSet   = 'UTF-8';
                            $mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
                            $mail->Host = 'mail.monterreysrl.com.bo';		
                            $mail->Port = 587;								//Sets the default SMTP server port
                            $mail->SMTPAuth = true;							//Sets SMTP authentication.
                            $mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
                            $mail->Password = 'sssTTT123';					//Sets SMTP password
                            $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
                            $mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
                            $mail->FromName = 'Sin Pendientes el Usuario '.$nom_th5;				//Sets the From name of the message
                        
                            $mail->AddAddress($email_admin,$nombre_admin);		//Adds a "To" address
                            $mail->Subject = 'Sin Pendientes el Usuario '.$nom_th5;	
                        
                            $mail->WordWrap = 50;							
                            $mail->IsHTML(true);							//Sets message type to HTML
                            $mail->Body = $message;
                            $mail->send();	
                            
                            
                            $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('','Lista de Pendientes en el Sistema','$message', '$fecha',1,'','$email_admin')");
                             
                            } catch (Exception $e) {
                            
                            $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino) values ('','Lista de Pendientes en el Sistema','$message', '$fecha',0,'','$email_admin')");
                            
                            }
    
    
                    }
    
    
                }
               }
    

            }







}


































function enviar_correo_sin_marcacion($iduser,$nombre_admin,$email_admin,$message, $fechaActual){
	//return 1;
          $dety='Lista Sin Marcaciones fecha:. '.$fechaActual;
		  $fecha=date('Y-m-d H:i:s');

	    if(is_valid_email($email_admin)){
		try {
			$mail = new PHPMailer;
			$mail->IsSMTP();	
			$mail->CharSet   = 'UTF-8';
			$mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
			$mail->Host = 'mail.monterreysrl.com.bo';		
			$mail->Port = 587;								//Sets the default SMTP server port
			$mail->SMTPAuth = true;							//Sets SMTP authentication.
			$mail->Username = 'soportetic@monterreysrl.com.bo';					//Sets SMTP username
			$mail->Password = 'sssTTT123';					//Sets SMTP password
			$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
			$mail->From = 'soportetic@monterreysrl.com.bo';					//Sets the From email address for the message
			$mail->FromName = $dety;				//Sets the From name of the message
			$mail->AddAddress($email_admin,$nombre_admin);		//Adds a "To" address
			$mail->Subject = $dety;
			$mail->WordWrap = 50;							
			$mail->IsHTML(true);							//Sets message type to HTML
			$mail->Body = $message;
			$mail->send();	
			
			
			$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino, DiaRegistrado, Cantidad, IdUser) values ('','$dety','$message', '$fecha',1,'','$email_admin', '$fechaActual', 0, $iduser)");
			 
			} catch (Exception $e) {
			
			$insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User, Destino, DiaRegistrado, Cantidad, IdUser) values ('','$dety','$message', '$fecha',0,'','$email_admin', '$fechaActual',0, $iduser)");
			
			}


	}

   
   
	
   
   
   
   
   }
   
   
   













?>