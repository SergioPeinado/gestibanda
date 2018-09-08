<?php
session_name("GESTIBANDA");


include_once("configuracion.php");

//Evitamos que muestre errores!
ini_set('display_errors', FALSE);




$opcion = $_POST['opcion'];


$Hoy=strftime( "%d-%m-%Y", time() );
$fechalargahoy=fechalarga($Hoy);
$_SESSION['fechalargahoy']=$fechalargahoy;
$_SESSION['Hoysql']=SpanishToSQL($Hoy);
$Hoyaqui=SpanishToSQL($Hoy);
$Hoy=SQLToSpanish($_SESSION['Hoysql']);
$Hoysql=SpanishToSQL($Hoy);
$NombreMes=calcula_nombre_mes(substr($Hoysql,5,2));
$_SESSION['NombreMes']=$NombreMes;






//Function for converting MySQL timestamp to Datetime format
function TimestampToDatetime($Tstamp) {
   $dt[0] = substr($Tstamp,0,2);
   $dt[1] = substr($Tstamp,3,2);
   //$dt[2] = substr($Tstamp,6,4);   
   return (join($dt,":"));
} 
function str2no($number){
  $number = str_replace(".", ".", $number);
  $number = str_replace(",", "", $number);
  return $number;
}
function no2str($number){
  $number = number_format($number,2, '.', ',');
  return $number;
}
function SQLToSpanish($Tstamp) {
   $dt[0] = substr($Tstamp,8,2);
   $dt[1] = substr($Tstamp,5,2);
   $dt[2] = substr($Tstamp,0,4);   
   return (join($dt,"-"));
} 
function SpanishToSQL($Tstamp) {
   $dt[0] = substr($Tstamp,6,4);
   $dt[1] = substr($Tstamp,3,2);
   $dt[2] = substr($Tstamp,0,2);   
   return (join($dt,"-"));
} 
function Day($Tstamp) {
   $dt = substr($Tstamp,0,2);     
   return ($dt);
} 
function Month($Tstamp) {
   $dt = substr($Tstamp,3,2);
   return ($dt);
} 
function Year($Tstamp) {
   $dt= substr($Tstamp,6,4);
   return ($dt);
} 
function fechasistema(){ 
	$dias = array("Monday"    => "Lunes"     ,"Lunes"     => "Monday", 
				  "Tuesday"   => "Martes"    ,"Martes"    => "Tuesday", 
				  "Wednesday" => "Miercoles" ,"Miercoles" => "Wednesday", 
				  "Thursday"  => "Jueves"    ,"Jueves"    => "Thursday", 
				  "Friday"    => "Viernes"   ,"Viernes"   => "Friday", 
				  "Saturday"  => "Sabado"    ,"Sabado"    => "Saturday", 
				  "Sunday"    => "Domingo"   ,"Domingo"   => "Sunday" ); 

	$mes = array("January"   =>"ENERO"      ,"ENERO"      => "January", 
				 "February"  =>"FEBRERO"    ,"FEBRERO"    => "February", 
				 "March"     =>"MARZO"      ,"MARZO"      => "March", 
				 "April"     =>"ABRIL"      ,"ABRIL"      => "April", 
				 "May"       =>"MAYO"       ,"MAYO"       => "May", 
				 "June"      =>"JUNIO"      ,"JUNIO"      => "June", 
				 "July"      =>"JULIO"      ,"JULIO"      => "July", 
				 "August"    =>"AGOSTO"     ,"AGOSTO"     => "August", 
				 "September" =>"SEPTIEMBRE" ,"SEPTIEMBRE" => "September", 
				 "October"   =>"OCTUBRE"    ,"OCTUBRE"    => "October", 
				 "November"  =>"NOVIEMBRE"  ,"NOVIEMBRE"  => "November", 
				 "December"  =>"DICIEMBRE"  ,"DICIEMBRE"  => "December"); 
	$fecha = $dias[date("l")] . ", " .date("d"). " de ". $mes[date("F")]. " ".date("Y"); 
	return $mes[date("F")];
//	return $fecha; 
}


/*************************************
 Devuelve una cadena con la fecha que se 
 le manda como par&aacute;metro en formato largo.
 *************************************/
function fechalarga($data, $tipus=1){
	if ($data != '' && $tipus == 0 || $tipus == 1)  {
		$setmana = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'S&aacute;bado');    
		$mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');     
		if ($tipus == 1)    {      
		ereg('([0-9]{1,2})-([0-9]{1,2})-([0-9]{2,4})', $data, $data);      
		$data = mktime(0,0,0,$data[2],$data[1],$data[3]);    }     
	
		return $setmana[date('w', $data)].', '.date('d', $data).' '.$mes[date('m',$data)-1].' de '.date('Y', $data);  
	} else  {    
	  return 0;  
	}
}



function calcula_nombre_mes($mes){
 switch ($mes){
  case 1:  $nombremes='ENERO';
     break; 
  case 2:  $nombremes='FEBRERO';
     break; 
  case 3:  $nombremes='MARZO';
     break; 
  case 4:  $nombremes='ABRIL';
     break;
  case 5:  $nombremes='MAYO';
     break; 
  case 6:  $nombremes='JUNIO';
     break; 
  case 7:   $nombremes='JULIO';
     break; 
  case 8:  $nombremes='AGOSTO';
     break;
  case 9:  $nombremes='SEPTIEMBRE';
     break; 
  case 10: $nombremes='OCTUBRE';
     break;
  case 11:  $nombremes='NOVIEMBRE';
     break;
  case 12: $nombremes='DICIEMBRE';
     break;
 }
 return $nombremes;
}




function subeImagen($timagen,$soloImagen,$anchoImagen){
	include("configuracion.php");
	$imagen=$timagen["name"];
	
	$aleatorio = rand(100000,999999);
	$imagen = $aleatorio.$imagen;
		
	if (isset($timagen) && $timagen['size'] > 0) {
		//configuracion
		$permitidas = array('jpg','jpeg','png','pjpeg','gif','tif','tiff','bmp'); //extensiones permitidas
		if(!$soloImagen) array_push($permitidas,'pdf','doc','docx','txt','xls','xlsx','rtf');
		$size=9437184; //tamano maximo en bytes
		$carpeta="uploads/".$_SESSION['id_Empresa']."/";	//carpeta de las imagenes
		mkdir($carpeta, 0755, true);
		
		$nombre = formatearNombre(trim($timagen['name']));
		$value = explode('.',$nombre);
		$ext = strtolower(end($value));
		$tamano = $timagen['size'];
		$tmp = $timagen['tmp_name'];
		$urlimagen=$carpeta.$aleatorio.$nombre;
	
		if(in_array($ext,$permitidas) === false){
			$errores = 'Extension no permitida<br>';
		}
		if($tamano>$size){
			$errores .= 'El tamaño del archivo debe ser menor a 9MB<br>';
		}
		if(empty($errores)){
			if(!move_uploaded_file($tmp,$urlimagen)){
				$errores .= "Ha ocurrido un error al guardar el fichero en el servidor<br>";
			} else {
				//Redimensionamos imagen ajustándolo a $anchoImagen, que son los píxeles de ancho deseados
				if($soloImagen && $anchoImagen!="") {
					list($width, $height) = getimagesize($urlimagen);
					
					$division = $anchoImagen / $width;
					$alto = $height * $division;
					
					redim($urlimagen,$urlimagen,$anchoImagen,$alto); 
					//recorta_horizontal($urlimagen,1140,614);
				}
				
				//Actualizamos el tamaño de las imágenes de la empresa en la base de datos
				$tamano=filesize($urlimagen)/1048576;
				$res = mysql_query("UPDATE empresas SET tamFicheros=(tamFicheros + ".$tamano.") WHERE id='".$_SESSION['id_Empresa']."'", $con);
			}
		}
		if($errores==""){
			return "OK|".$urlimagen;
		} else {
			return "NOK|".$errores;
		}
    }
	return "OK|";
}

function borraImagen($nombre){
	if (file_exists($nombre)) {
		include("configuracion.php");
		$tamano=filesize($nombre)/1048576;
		$res = mysql_query("UPDATE empresas SET tamFicheros=(tamFicheros - ".$tamano.") WHERE id='".$_SESSION['id_Empresa']."'", $con) or die(mysql_error());

		$resp=unlink($nombre);
	}
}



function registraLog($fichero,$nombrefichero,$accion,$idregistro,$nombreregistro,$comentario,$sql){
	if($fichero!="" && $accion!="") {
		global $con;
		$sql = "INSERT INTO logs(`Empresa`, `idusuario`, `emailusuario`, `nombreusuario`, `hora`, `fichero`, `nombrefichero`,`accion`, `idregistro`, `nombreregistro`, `comentario`, `sql`) 
				VALUES ('".$_SESSION['id_Empresa']."','".$_SESSION['IDUsuario']."','".$_SESSION['usuario']."','".$_SESSION['NombreUsuario']."','".date("Y-m-d H:i:s")."','".$fichero."','".$nombrefichero."','".$accion."','".$idregistro."','".$nombreregistro."','".$comentario."',\"".$sql."\")";
		$res = mysql_query($sql, $con);
	}
}

?>