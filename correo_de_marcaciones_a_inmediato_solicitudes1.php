
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


$urle=query("SELECT t.* FROM tipoaccesos t WHERE t.estado=1 AND t.idta=3");
$url='';
$urllg=$urle[0]['Dominio'];
$urllnombre=$urle[0]['NombreDominio'];
$url = $urllg.'/'.$urllnombre.'/';



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

       echo "<br>";
       echo "dias diferencia";
       echo "<br>";
       echo $caff."<br>";
       echo "<br>";


       if(count($lista_is)>0){
        for($ilc=0; $ilc<count($lista_is); $ilc++)
        {
    
       
      
          $Numb1= $lista_is[$ilc]['CargoTH5'];
          $id_codigo= $lista_is[$ilc]['CargoTH5']; 
          $id_usuario= $lista_is[$ilc]['IdUsuario'];
          $nombre_usuario= $lista_is[$ilc]['NombreUsuario'];
          $nombre_usuario_cargo= $lista_is[$ilc]['cargo'];
          $nombre_usuario_nom_ofi= $lista_is[$ilc]['nom_ofi'];
          $usuario= $lista_is[$ilc]['Usuario'];
          $email= $lista_is[$ilc]['Email'];
        //  $email= 'jflores@monterreysrl.com.bo';
          $fecha_creacion= $lista_is[$ilc]['FechaCreacion'];
          $empresa= $lista_is[$ilc]['Empresa'];
          $nombre_ofi= $lista_is[$ilc]['nom_ofi'];
          $cargo= $lista_is[$ilc]['cargo'];
          $cantidad_pendiente= $lista_is[$ilc]['cantidad_dependientes'];
          
          echo $id_codigo."<br>";
          echo "<br>";
          echo $id_usuario."<br>";
          echo "<br>";
          echo $nombre_usuario."<br>";
          echo "<br>";
          echo $email."<br>";
          echo "<br>";
          echo $cargo."<br>";
          echo "<br>";
    



                
                $tor="CALL `Lista_Tolerancia`('".$id_usuario."')";

             

                $lista_dependientes = query($tor);

                  $message ='';
                  $message0 ='';
                  $message1 ='';
                  $cna=0;

                if(count($lista_dependientes)>0){


                  $message0=$message0.
                 '<table  id="tabla" border width="100%">
                  <tr>
                   
                   <td colspan=13  style="font-size: 25px;text-align: center;background: red; color: white;">
                    <strong><p style="margin:auto">Reporte de Tolerancias</p></strong>
                   </td>


                  </tr>

                   <tr>
                     <td colspan=13>Inmediato Superior: '.$nombre_usuario.' </td>
                   </tr>
                   <tr>
                   <td colspan=13>Oficina del IS: '.$nombre_usuario_nom_ofi.' </td>
                   </tr>
                   <tr>
                    <td colspan=13>Cargo del IS: '.$nombre_usuario_cargo.' </td>
                  </tr>
                    <tr>
                  
                     <td colspan=13>Tolerancia: Últimos Dos Meses </td>
                    </tr>




                    <tr style="color:red">

                      <td>#</td>
                      <td>Código Empleado</td>
                      <td>Usuario</td>
                      <td>Fecha Solicitud</td>
                      <td>Detalle</td>
                      <td>Fecha Marcación</td>
                      <td>Dia Marcación</td>
                      <td>Turno</td>
                      <td>Horario Marcación</td>
                
                    </tr>
                  ';
                   
 

               
                  


                  for($ilcg=0; $ilcg<count($lista_dependientes); $ilcg++){
                    $cna=$ilcg+1;
                    $message1=$message1.
                    '<tr>

                     <td>'.$cna.'</td>  
                     <td>'.$lista_dependientes[$ilcg]['codigo_empleado'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['NombreUsuario'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['Solicitud_tv'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['Detalle'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['FechaRegistro'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['Dia_Semana'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['turno'].'</td>
                     <td>'.$lista_dependientes[$ilcg]['Descripcion_Hora_Turno'].'</td>

                     </tr>
                    ';
                  }


                  $message=$message0.$message1.' 
                  
                  <tr>
                   <td colspan=13  style="color:red; font-size: 20px;">
                    <strong><p style="margin:auto">Ingresar al Sistema de Marcaciones de RRHH para aprobar las solicitudes</p></strong>
                   </td>
                  </tr>


                  <tr>
                      <td colspan=13  style=" text-align: center;">
                        <a href='.$url.'  style="color: red;text-align: center;	text-decoration: none; display: inline-block;	font-size: 20px; cursor: pointer;">
                          <strong><p style="margin:auto">Sistema de Control de Asistencia</p></strong>
                        </a>
                      </td>
                  </tr>


                  </table><br>';



                 $tor1="CALL `Lista_Vacacion_Permiso`('".$id_usuario."')";    
 
                  $lista_dependientes_1 = query($tor1);

                  $messageaa ='';
                  $message0aa ='';
                  $message1aa ='';
                  $cnaaa=0;
                
                  if(count($lista_dependientes_1)>0){

                    $message0aa=$message0aa.
                    '<table  id="tabla" border width="100%"  >
                     <tr>

                     <td colspan=13  style="font-size: 25px;text-align: center;background: red; color: white;">
                       <strong><p style="margin:auto">Reporte de Vacaciones y Permisos</p></strong>
                      </td>

                     </tr>

                      <tr>
                        <td colspan=13>Inmediato Superior: '.$nombre_usuario.' </td>
                      </tr>
                      <tr>
                      <td colspan=13>Oficina del IS: '.$nombre_usuario_nom_ofi.' </td>
                    </tr>
                    <tr>
                    <td colspan=13>Cargo del IS: '.$nombre_usuario_cargo.' </td>
                  </tr>
                  <tr>
                  <td colspan=13>Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.'  al '.$fechaActual.'</td>
                 </tr>
   
   
   
   
                       <tr style="color:red">
   
                         <td>#</td>
                         <td>Código Empleado</td>
                         <td>Usuario</td>
                         <td>Documento</td>
                         <td>Fecha Solicitud</td>
                         <td>Tipo Solicitud</td>
                         <td>Motivo</td>
                         <td>Número Días</td>
                         <td>Desde</td>
                         <td>Hasta</td>
                         <td>Estado</td>
                   
                       </tr>
                     ';
                      








                     for($ilcgg=0; $ilcgg<count($lista_dependientes_1); $ilcgg++){
                      $cnaaa=$ilcgg+1;
                      $message1aa=$message1aa.
                      '<tr>
  
                       <td>'.$cnaaa.'</td>  
                       <td>'.$lista_dependientes_1[$ilcgg]['codigo_e'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['Nombre'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['documento'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['fechasolicitud'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['tipo_soli'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['motivo'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['dias'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['fechainicio'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['fechafin'].'</td>
                       <td>'.$lista_dependientes_1[$ilcgg]['estado_detalle'].'</td>
  
                       </tr>
                      ';
                    }
  



                    $message=$message.$message0aa.$message1aa.' 
                    
                  

                     <tr>
                      <td colspan=13  style="color:red; font-size: 20px;">
                       <strong><p style="margin:auto">Ingresar al Sistema TH5 de RRHH para aprobar las solicitudes</p></strong>
                      </td>
                     </tr>
   
   
                     <tr>
                      <td colspan=13  style=" text-align: center;">
                        <a href='.$url.'  style="color: red;text-align: center;	text-decoration: none; display: inline-block;	font-size: 20px; cursor: pointer;">
                         <strong><p style="margin:auto">Sistema de Control de Asistencia</p></strong>
                        </a>
                      </td>
                     </tr>



                    
                    </table>';
             

                    $encabezado='Inmediato Superior: '.$nombre_usuario.' - Reporte Tolerancias, Vacaciones y Permisos,  Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.' al '.$fechaActual;	
   
        
   
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
                      $mail->From = $correo_e1;					//Sets the From email address for the message
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

                    echo "Sin vacacion permiso "."<br>";
                    echo "<br>";
                    $fecha=date('Y-m-d H:i:s');
                    $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino, DetalleTVP) values ('','$encabezado','$message', '$fecha',0,'$nombre_usuario','$email', 'Sin vacación permiso')");
                    $encabezado='Inmediato Superior: '.$nombre_usuario.' - Reporte Tolerancias, Vacaciones y Permisos,  Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.' al '.$fechaActual;	
   
      
                    $sin_info_vacaciones_permisos='<table  id="tabla" border width="100%">
                        <tr>
                         <td colspan=13  style="font-size: 25px;text-align: center;background: red; color: white;">
                          <strong><p style="margin:auto">Sin Reporte de Vacaciones y Permisos</p></strong>
                        </td>
                       </tr>
                      </table>';

                    $message=$message.$sin_info_vacaciones_permisos;


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
                      $mail->From = $correo_e1;					//Sets the From email address for the message
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

                  }

                








                }else{
                  echo "Sin dependientes "."<br>";
                  echo "<br>";


                  $fecha=date('Y-m-d H:i:s');
                  $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino, DetalleTVP) values ('','$encabezado','$message', '$fecha',0,'$nombre_usuario','$email','Sin dependientes')");



                  
                  $sin_info_dependientes='<table  id="tabla" border width="100%">
                  <tr>
                   <td colspan=13  style="font-size: 25px;text-align: center;background: red; color: white;">
                    <strong><p style="margin:auto">Sin Reporte de Tolerancias</p></strong>
                  </td>
                 </tr>
                </table>';

                   $message=$sin_info_dependientes;


                  $tor1="CALL `Lista_Vacacion_Permiso`('".$id_usuario."')";      

                  $lista_dependientes_1 = query($tor1);

                  $messageaa ='';
                  $message0aa ='';
                  $message1aa ='';
                  $cnaaa=0;
       
            if(count($lista_dependientes_1)>0){

       

              $message0aa=$message0aa.
              '<table  id="tabla" border width="100%"  >
               <tr>





               <td colspan=13  style="font-size: 25px;text-align: center;background: red; color: white;">
                <strong><p style="margin:auto">Reporte de Vacaciones y Permisos</p></strong>
               </td>




              </tr>
                <tr>
                  <td colspan=13>Inmediato Superior: '.$nombre_usuario.' </td>
                </tr>
                <tr>
                <td colspan=13>Oficina del IS: '.$nombre_usuario_nom_ofi.' </td>
              </tr>
              <tr>
              <td colspan=13>Cargo del IS: '.$nombre_usuario_cargo.' </td>
            </tr>
            <tr>
            <td colspan=13>Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.'  al '.$fechaActual.'</td>
           </tr>




                 <tr style="color:red">

                   <td>#</td>
                   <td>Código Empleado</td>
                   <td>Usuario</td>
                   <td>Documento</td>
                   <td>Fecha Solicitud</td>
                   <td>Tipo Solicitud</td>
                   <td>Motivo</td>
                   <td>Número Días</td>
                   <td>Desde</td>
                   <td>Hasta</td>
                   <td>Estado</td>
             
                 </tr>
               ';
              

               for($ilcgg=0; $ilcgg<count($lista_dependientes_1); $ilcgg++){
                $cnaaa=$ilcgg+1;
                $message1aa=$message1aa.
                '<tr>

                 <td>'.$cnaaa.'</td>  
                 <td>'.$lista_dependientes_1[$ilcgg]['codigo_e'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['Nombre'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['documento'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['fechasolicitud'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['tipo_soli'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['motivo'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['dias'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['fechainicio'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['fechafin'].'</td>
                 <td>'.$lista_dependientes_1[$ilcgg]['estado_detalle'].'</td>

                 </tr>
                ';
              }




              $message=$message.$message0aa.$message1aa.
              '<tr>
                <td colspan=13  style="color:red; font-size: 20px;">
                 <strong><p style="margin:auto">Ingresar al Sistema TH5 de RRHH para aprobar las solicitudes</p></strong>
                </td>
               </tr>


              <tr>
                <td colspan=13  style=" text-align: center;">
                 <a href='.$url.'  style="color: red;text-align: center;	text-decoration: none; display: inline-block;	font-size: 20px; cursor: pointer;">
                  <strong><p style="margin:auto">Sistema de Control de Asistencia</p></strong>
                 </a>
                </td>
              </tr>
              
              
              </table>';

              $encabezado='Inmediato Superior: '.$nombre_usuario.' - Reporte Tolerancias, Vacaciones y Permisos,  Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.' al '.$fechaActual;	
   
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
                $mail->From = $correo_e1;					//Sets the From email address for the message
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

              echo "Sin vacacion permiso "."<br>";
              echo "<br>";
              $fecha=date('Y-m-d H:i:s');
              $insert_detalle= query("insert into EstadoCorreo (CodigoTicket, Detalle, Mensaje, FechaRegistro, EstadoEnvio,User,Destino, DetalleTVP) values ('','$encabezado','$message', '$fecha',0,'$nombre_usuario','$email', 'Sin vacación permiso')");
              $encabezado='Inmediato Superior: '.$nombre_usuario.' - Reporte Tolerancias, Vacaciones y Permisos,  Rangos de Fechas del:. '.$fechaActual_menos_seis_dias.' al '.$fechaActual;	
  
              $sin_info_vacaciones_permisos='<table  id="tabla" border width="100%">
                  <tr>
                   <td colspan=13  style="font-size: 25px;text-align: center;background: red; color: white;">
                    <strong><p style="margin:auto">Sin Reporte de Vacaciones y Permisos</p></strong>
                  </td>
                 </tr>
                </table>';

              $message=$message.$sin_info_vacaciones_permisos;


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
                $mail->From = $correo_e1;					//Sets the From email address for the message
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
            }


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


