
<?php


function querybio($sql){
	$conn =  mysqli_connect(DB_HOST_,DB_USER_,DB_PASS_,DB_NAME_);
	if ($conn->connect_error) {
		trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
	}
	mysqli_query($conn,"SET NAMES 'utf8'");
	if (strpos($sql, 'insert') !== false) {
		mysqli_query($conn,$sql);
		$last_id = mysqli_insert_id($conn);
		return $last_id;
	}
	if (strpos($sql, 'update') !== false|strpos($sql, 'delete') !== false) {
		$result = $conn->query($sql);
		$affected=mysqli_affected_rows($conn);
		return $affected;
	}
	$result=mysqli_query($conn,$sql);
	$arr = array();
	if($result === false) {
		trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
	} else {
    	$result->data_seek(0);
		while($row = $result->fetch_assoc()){
			$arr[] = $row;
		}
	}
	return $arr;
}

function query($sql){
	$conn =  mysqli_connect(DB_HOST_s,DB_USER_s,DB_PASS_s,DB_NAME_s);
	if ($conn->connect_error) {
		trigger_error('Database connection failed: '  . $conn->connect_error, E_USER_ERROR);
	}
	mysqli_query($conn,"SET NAMES 'utf8'");
	if (strpos($sql, 'insert') !== false) {
		mysqli_query($conn,$sql);
		$last_id = mysqli_insert_id($conn);
		return $last_id;
	}
	if (strpos($sql, 'update') !== false|strpos($sql, 'delete') !== false) {
		$result = $conn->query($sql);
		$affected=mysqli_affected_rows($conn);
		return $affected;
	}
	$result=mysqli_query($conn,$sql);
	$arr = array();
	if($result === false) {
		trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
	} else {
    	$result->data_seek(0);
		while($row = $result->fetch_assoc()){
			$arr[] = $row;
		}
	}
	return $arr;
}




function queryth5t($sql, $servidor,$dbn , $usuario_n, $contrasenia_n){
	$arr = array();
	$serverName = $servidor;
	$connectionInfo = array("Database"=>$dbn,
	 "UID"=>$usuario_n, "PWD"=>$contrasenia_n,"Encrypt"=>true,
	 "TrustServerCertificate"=>true, "CharacterSet"=>"UTF-8");
	$conectar = sqlsrv_connect($serverName, $connectionInfo);
	if($conectar) 
	 {
		$stmt = sqlsrv_query($conectar, $sql);
		while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
		{
			$arr[] = $row;
		}
		sqlsrv_free_stmt($stmt);
	 }
	 return $arr;
}



function queryth5($sql ){
	$arr = array();
	$serverName = "172.20.20.5";
	$connectionInfo = array("Database"=>"MONTERREYTH5", "UID"=>"sa", "PWD"=>"SaM0nt3rr3y*","Encrypt"=>true,
	"TrustServerCertificate"=>true, "CharacterSet"=>"UTF-8");
	$conectar = sqlsrv_connect($serverName, $connectionInfo);
	if($conectar) 
	 {
		$stmt = sqlsrv_query($conectar, $sql);
		while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
		{
			$arr[] = $row;
		}
		sqlsrv_free_stmt($stmt);
	 }
	 return $arr;
}






?>
