<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
// header('Access-Control-Allow-Origin: *');
date_default_timezone_set("America/La_Paz");


function basico($numero) {
    $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
    'nueve','diez','once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve','veinte','veintiuno ','vientidos ','veintitrés ', 'veinticuatro','veinticinco',
    'veintiséis','veintisiete','veintiocho','veintinueve');
    return $valor[$numero - 1];
    }
    


    function decenas($n) {
        $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta',
        70=>'setenta',80=>'ochenta',90=>'noventa');
        if( $n <= 29) return basico($n);
        $x = $n % 10;
        if ( $x == 0 ) {
        return $decenas[$n];
        } else return $decenas[$n - $x].' y '. basico($x);
        }
        
        function centenas($n) {
        $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos',
        400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
        700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
        if( $n >= 100) {
        if ( $n % 100 == 0 ) {
        return $cientos[$n];
        } else {
        $u = (int) substr($n,0,1);
        $d = (int) substr($n,1,2);
        return (($u == 1)?'ciento':$cientos[$u*100]).' '.decenas($d);
        }
        } else return decenas($n);
        }
        
        function miles($n) {
        if($n > 999) {
        if( $n == 1000) {return 'mil';}
        else {
        $l = strlen($n);
        $c = (int)substr($n,0,$l-3);
        $x = (int)substr($n,-3);
        if($c == 1) {$cadena = 'mil '.centenas($x);}
        else if($x != 0) {$cadena = centenas($c).' mil '.centenas($x);}
        else $cadena = centenas($c). ' mil';
        return $cadena;
        }
        } else return centenas($n);
        }
        
        function millones($n) {
        if($n == 1000000) {return 'un millón';}
        else {
        $l = strlen($n);
        $c = (int)substr($n,0,$l-6);
        $x = (int)substr($n,-6);
        if($c == 1) {
        $cadena = ' millón ';
        } else {
        $cadena = ' millones ';
        }
        return miles($c).$cadena.(($x > 0)?miles($x):'');
        }
        }
        function convertir($n) {
        switch (true) {
        case ( $n >= 1 && $n <= 29) : return basico($n); break;
        case ( $n >= 30 && $n < 100) : return decenas($n); break;
        case ( $n >= 100 && $n < 1000) : return centenas($n); break;
        case ($n >= 1000 && $n <= 999999): return miles($n); break;
        case ($n >= 1000000): return millones($n);
        }
        }
    
    


function registrar_ticket_usuario_th5_inactivo($codigo_empleado)
{
    $fecha=date('Y-m-d H:i:s');
    $nombre_archivo='';
    $ext='';
    $nombre_imagen='';

    echo 'Holaa';

    
    $datosce=query("SELECT cc.Codigo, cc.Nombre FROM cempleados cc WHERE cc.Codigo=$codigo_empleado ORDER BY cc.FechaRegistro DESC LIMIT 0,1");
    $nombre_ce=$datosce[0]['Nombre'];
    $descripcion='Dar de Bajar al Usuario '.$nombre_ce;
   


      $idusuario_d=query("SELECT uu.IdUsuario,uu.NombreUsuario, uu.nro_telefono,cc.IdCargo FROM usuario uu
      INNER JOIN cargo cc ON cc.IdCargo= uu.IdCargo AND cc.Estado=1
      INNER JOIN area aa ON aa.IdArea= cc.IdArea AND aa.Estado=1
       WHERE uu.ISuperiorIdUsuario=1 AND uu.Estado=1 AND cc.IdCargo=1");
       $Idusuario=$idusuario_d[0]['IdUsuario'];
       $Nro_telefono=$idusuario_d[0]['nro_telefono'];
   
  $rescodigo=query("SELECT c.IdCodigo, c.NLugar, c.Descripcion, c.Estado FROM codigo c WHERE c.Estado=1 AND c.IdCodigo='4'");
  $nl=$rescodigo[0]['NLugar']+1;
  $sql2= query("update codigo set NLugar='$nl' where Idcodigo=4");
  $codigoot= $rescodigo[0]['Descripcion'].$nl;
  $tokenf=uniqid(rand());

  $sql="insert into tablaheldesk (
    Detalle,
  Imagen,
  IdUsuario,
  FechaRegistro, 
  Codigo, 
  FechaModificacion, 
  Token,
  Archivo, nro_telefono_t,extension) VALUES (  
   '$descripcion',
   '$nombre_imagen',
    $Idusuario,
   '$fecha',
   '$codigoot',
   '$fecha',
   '$tokenf',
   '$nombre_archivo','$Nro_telefono','$ext'

   )";

   $res=query($sql);
  



   $rescodigo=query("SELECT c.IdCodigo, c.NLugar, c.Descripcion, c.Estado FROM codigo c WHERE c.Estado=1 AND c.IdCodigo='3'");
   $nl=$rescodigo[0]['NLugar']+1;
   $sql2= query("update codigo set NLugar='$nl' where Idcodigo=3");
   $codigooticket= $rescodigo[0]['Descripcion'].$nl;

   $tokentt=uniqid(rand());
   $sql22=query("insert into ticket (Codigo, IdUsuario, Estado, FechaCreacion, TipoTicket, CodigoRelacion, FechaFin , Token, FechaModificacion, Prioridad, FechaRegistroPrioridad) VALUES ('$codigooticket', '$Idusuario', '3', '$fecha', '2', '$codigoot', '$fecha', '$tokentt', '$fecha', 2,'$fecha')");
 

   
   $poidt=query("SELECT * FROM ticket WHERE CodigoRelacion='$codigoot' ");
   $Idtickett=$poidt[0]['IdT'];
   $codigoo=$codigoot;

 


   $va_detalle=convertir(1);
   $sql33=query("insert into posi_eje (IdTicket, Number, Detalle) values ($Idtickett, 1,'$va_detalle' )");
    


  //asignacion
  $poi=query("SELECT * FROM posi_eje WHERE IdTicket=$Idtickett ");
  $vg=$poi[0]['Number'];
  $cars=array("Green","Blue","Pink", "Purple", "brown", "orange");
  $d=rand(0,5);
  $colorr=$cars[$d];


    $dddas=query("SELECT aa.IdActividad, aa.Detalle, aa.`Default` FROM actividadp aa WHERE aa.Lugar=2 AND aa.Estado=1 ");

    for ($if = 0; $if < count($dddas); ++$if){
     $dt=$dddas[$if]['IdActividad'];
     $tokenf=uniqid(rand());
     $ddlo= query("SELECT aa.IdUsuario as id_admin, aa.IdUsuarioAsig as user_asig FROM actividad_ticket aa WHERE aa.IdAct_t=$dt");
     $id1=$ddlo[0]['id_admin'];
     $id2=$ddlo[0]['user_asig'];
     $sql="insert into asignacion 
     (IdUsuario, IdUsuarioAsig, IdTicket, IdActividad, Estado, FechaRegistro, FechaFin , Detalle, Token, Color, CorreoEnviado, FechaInicio, Estado_eje,PrioridadA)
      values
     ($id2, $id1, $Idtickett,$dt,  1, '$fecha', Null,'Sin detalle','$tokenf' , '$colorr',  0, Null, $vg,3)";
     $res=query($sql);


    $cddc=query("SELECT  dty.IdTS, aat.IdAT,act.IdAct_t ,CONCAT_WS(' ', aat.Nombre_AT,' - ', tt.Nombre, ' - ',act.Nombre)  AS Nombre  FROM actividad_ticket act 
    INNER JOIN tareas_ticket tt ON tt.IdTarea= act.Id_T AND tt.Estado=1 
    INNER JOIN area_ticket aat ON aat.IdAT= tt.IdAT AND aat.Estado=1
    INNER JOIN detallets dty ON dty.IdA= aat.IdAT AND dty.Estado=1
    INNER JOIN tipo_soporte tu ON tu.Idts= dty.IdTS AND tu.Estado=1
    WHERE act.Estado=1 AND act.IdAct_t=$dt");
    $dts=$cddc[0]['IdTS'];


     $rt=query("insert into detalle_heldesk(CodigoRelacion, TS, Ids, FechaRegistro, IdUsuario, Estado) values ('$codigoo',$dts,$dt, '$fecha',1, 1 )");
    }
    $resap=asignacion_ticket_soli($Idtickett,$vg);



}



?>