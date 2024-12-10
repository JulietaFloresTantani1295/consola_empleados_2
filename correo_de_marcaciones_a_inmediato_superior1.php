
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

$lista_is=query("CALL `lista_aprobadores_lectura`()");

$correo_e1="";
$passowrd_e1="";
$correo_envio=query("SELECT cc.Id, cc.Correo, cc.Password, cc.FechaRegistro, cc.Estado FROM correo_envio  cc  WHERE cc.Id=2  ORDER BY cc.FechaRegistro desc");
if(count($correo_envio)>0){
   $correo_e1=$correo_envio[0]['Correo'];
   $passowrd_e1=$correo_envio[0]['Password'];
}

$correo_e1_cc="";
$correo_envio_cc=query("SELECT cc.Id, cc.Correo, cc.Password, cc.FechaRegistro, cc.Estado FROM correo_envio  cc  WHERE cc.Id=3  ORDER BY cc.FechaRegistro desc");
if(count($correo_envio_cc)>0){
   $correo_e1_cc=$correo_envio_cc[0]['Correo'];
}

$message0 ='';
$message1 ='';
$message ='';
$encabezado='';

$caff=0;
$dias_fereadoss=0;
$fechaActual = date('Y-m-d');
//$fechaActual=date("Y-m-d",strtotime($fechaActual."- 2 days")); 


echo $fechaActual."<br>";
echo "<br>";

$dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
$dia = $dias[(date('N', strtotime($fechaActual))) - 1];
 
echo $dia."<br>";
echo "<br>";

$validar_domingo=query("SELECT cc.IdS, cc.Nombre, cc.Dias_M FROM dias_semana cc WHERE cc.Dias_M=6");
if(count($validar_domingo)>0){


   $dia_domingo= $validar_domingo[0]['Nombre'];
   if($dia_domingo==$dia){
    echo "Es domingo !!!";
    echo "<br>";
   
    $detalle_dia='Es domingo !!!';	
    $fecha=date('Y-m-d H:i:s');
    $insert_detalle= query("insert into log_reporte_tvp_dt (Detalle, FechaRegistro, TipoEjecucion) values ('$detalle_dia','$fecha', 'Procesos automatico')");




    $fechaActual_menos_seis_dias=date("Y-m-d",strtotime($fechaActual."- 6 days")); 
    echo $fechaActual."<br>";
    echo "<br>";
    echo $fechaActual_menos_seis_dias."<br>";
    echo "<br>";

  
    $fecha_inicio = $fechaActual_menos_seis_dias;
    $fecha_inicio= date("Y-m-d",strtotime($fecha_inicio."- 1 days")); 
    echo $fecha_inicio."<br>";
    echo "<br>";





    $dias_se= query("SELECT cc.IdS, cc.Nombre, cc.Dias_M FROM dias_semana cc WHERE cc.IdS>1");

    if(count($dias_se)>0){

       $dias_s_=query("SELECT C.Dias,C.domingos, C.sabados,
       (C.Dias-C.domingos-C.sabados) AS dias_habiles,
       ((C.Dias-C.domingos)) as diasT
       FROM ((
       SELECT DATEDIFF('$fechaActual', '$fecha_inicio') AS Dias,
       truncate((datediff(STR_TO_DATE('$fechaActual', '%Y-%m-%d'), STR_TO_DATE('$fechaActual_menos_seis_dias', '%Y-%m-%d')) - Weekday(date_add(STR_TO_DATE('$fechaActual', '%Y-%m-%d'), INTERVAL(-0 + 1)day)) + 7) / 7, 0) AS domingos,
       truncate((datediff(STR_TO_DATE('$fechaActual', '%Y-%m-%d'), STR_TO_DATE('$fechaActual_menos_seis_dias', '%Y-%m-%d')) - Weekday(date_add(STR_TO_DATE('$fechaActual', '%Y-%m-%d'), INTERVAL(-6 + 1)day)) + 7) / 7, 0) AS sabados
      )) C");

       $caff= $dias_s_[0]['diasT'];
       $caffff= $dias_s_[0]['Dias'];
       echo "<br>";
       echo "dias diferencia";
       echo "<br>";
       echo $caff."<br>";
       echo "<br>";


       if(count($lista_is)>0){
        for($ilc=0; $ilc<count($lista_is); $ilc++)
        {
    
        
          $Numb1= $lista_is[$ilc]['CargoTH5'];
          $id_usuario= $lista_is[$ilc]['IdUsuario'];
          $nombre_usuario= $lista_is[$ilc]['NombreUsuario'];
          $nombre_usuario_cargo= $lista_is[$ilc]['cargo'];
          $nombre_usuario_nom_ofi= $lista_is[$ilc]['nom_ofi'];
          $usuario= $lista_is[$ilc]['Usuario'];
          $email= $lista_is[$ilc]['Email'];
       //   $email= 'jflores@monterreysrl.com.bo';
          $fecha_creacion= $lista_is[$ilc]['FechaCreacion'];
          $empresa= $lista_is[$ilc]['Empresa'];
          $nombre_ofi= $lista_is[$ilc]['nom_ofi'];
          $cargo= $lista_is[$ilc]['cargo'];
          $cantidad_pendiente= $lista_is[$ilc]['cantidad_dependientes'];
          
      
          echo "<br>";
          echo $id_usuario."<br>";
          echo "<br>";
          echo $nombre_usuario."<br>";
          echo "<br>";
          echo $email."<br>";
          echo "<br>";
          echo $cargo."<br>";
          echo "<br>";
  

          $tor="CALL `Lista_Marcaciones_Superior`('$caffff', '$fechaActual_menos_seis_dias', '$fechaActual', '$Numb1','$caff')";
          $lista_dependientes = query($tor);
          if(count($lista_dependientes)>0){

                  $message ='';
                  $message0 ='';
                  $message1 ='';
                  $cna=0;
                  $message0=$message0.
                 '<table  id="tabla" border width="100%"  >
                
                
                
                 <tr>

                   <td colspan=15  style="font-size: 25px;text-align: center;background: red; color: white;">
                    <strong><p style="margin:auto">Reporte de días Trabajados</p></strong>
                   </td>

                 </tr>


                



                   <tr>
                     <td colspan=15>Inmediato Superior: '.$nombre_usuario.' </td>
                   </tr>
                   <tr>
                   <td colspan=15>Oficina del IS: '.$nombre_usuario_nom_ofi.' </td>
                 </tr>
                 <tr>
                 <td colspan=15>Cargo del IS: '.$nombre_usuario_cargo.' </td>
               </tr>
                    <tr>
                     <td colspan=5>Reporte de días Trabajados </td>
                     <td colspan=8>Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.'  al '.$fechaActual.'</td>
                    </tr>
                    <tr>
                     <td colspan=4>Dias Contados:. '.$dias_s_[0]['Dias'].'</td>
                     <td colspan=4>Dias Domingos:. '.$dias_s_[0]['domingos'].'</td>
                     <td colspan=5>Dias Habiles:.' .$caff.'</td>
                    </tr>



                    <tr style="color:red">

                      <td>#</td>  
                      <td>Código Empleado</td>
                      <td>Usuario</td>
                      <td>Cargo</td>
                      <td>Oficina</td>
                      <td>Días Contados</td>
                      <td>Días Habiles</td>
                      <td>Total Huellas Marcadas</td>
                      <td>Días Marcados</td>
                      <td>Vacaciones</td>
                      <td>Permisos</td>
                      <td>Feriados</td>
                      <td style="color:red">Cantidad dias No Marcados</td>
                      <td>Dias No Marcados</td>
                      <td>Minutos Retraso</td>

                    </tr>

                  ';


                  for($ilcg=0; $ilcg<count($lista_dependientes); $ilcg++){
                    $cna=$ilcg+1;
                    $message1=$message1.
                    '<tr>

                     <td>'.$cna.'</td>  
                     <td>'.$lista_dependientes[$ilcg]['Codigo'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['nombre_usuario'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['cargo'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['oficina'].'</td>

                     <td>'.$lista_dependientes[$ilcg]['cantidad_marcaciones_real'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['cantidad_marcaciones_habiles'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['cantidad_marcaciones'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['Diferencia'].'</td>

                     <td>'.$lista_dependientes[$ilcg]['Vacacion'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['Permiso'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['feriado'].'</td>
                     <td style="color:red">'.$lista_dependientes[$ilcg]['dias_no_marcados'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['dia_diferencia'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['minutos_nuevo'].'</td>   

                     </tr>
                    ';
                  }

                $message=$message0.$message1.' </table>';
                $encabezado='Inmediato Superior: '.$nombre_usuario.' - Reporte de días Trabajados,  Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.' al '.$fechaActual;	

                 echo $message."<br>";
                 echo "<br>";
                 echo $encabezado."<br>";
                 echo "<br>";
           

                  
                  if(is_valid_email($email)){
                  
                    $mail = new PHPMailer;
                    $mail->IsSMTP();	
                    $mail->CharSet   = 'UTF-8';
                    $mail->Encoding  = 'base64';							//Sets Mailer to send message using SMTP
                    $mail->Host = 'mail.monterreysrl.com.bo';		
                    $mail->Port = 587;								//Sets the default SMTP server port
                    $mail->SMTPAuth = true;							//Sets SMTP authentication.
                    $mail->Username = $correo_e1;					//Sets SMTP username
                    $mail->Password = $passowrd_e1;					//Sets SMTP password
                    $mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
                    $mail->From = $correo_e1;			//Sets the From email address for the message
                    $mail->FromName = $encabezado;
                    $mail->AddAddress($email,$nombre_usuario);		//Adds a "To" address
                    $mail->Subject = $encabezado;
                    $mail->WordWrap = 50;	
                    $mail->IsHTML(true);							//Sets message type to HTML
                    $mail->Body = $message;
                    $mail->addCC($correo_e1_cc);				
                  

                    if (!$mail->send()) {
                      $fecha=date('Y-m-d H:i:s');
                      $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino, CC_detalle) 
                       values ('','$encabezado','$message', '$fecha',0,'$nombre_usuario','$email', '$correo_e1_cc')");
                    }else{
                      $fecha=date('Y-m-d H:i:s');
                      $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino, CC_detalle) 
                       values ('','$encabezado','$message', '$fecha',1,'$nombre_usuario','$email', '$correo_e1_cc')");
                    }

                  }else{
                    $fecha=date('Y-m-d H:i:s');
                    $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('','$encabezado','$message', '$fecha',0,'$nombre_usuario','$email')");
                  }
                 
                }else{
                  echo "Sin dependientes "."<br>";
                  echo "<br>";


                  $fecha=date('Y-m-d H:i:s');
                  $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino) values ('','$encabezado','$message', '$fecha',0,'$nombre_usuario','$email')");

                }
          
        }
    }
  
    }
  
  }else{

    echo "No es domingo !!!";
    echo "<br>";
    $encabezado='No es Domingo !!!';	
    $fecha=date('Y-m-d H:i:s');
    $insert_detalle= query("insert into log_reporte_tvp_dt (Detalle, FechaRegistro, TipoEjecucion) values ('$encabezado','$fecha', 'Procesos automatico')");


   }



}



?>


