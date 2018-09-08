<?php

session_name("GESTIBANDA");
session_start();
require('includes/configuracion.php');
require('includes/funciones.php');

$fichero='Registro';
$opcion=$_GET['opcion'];



if($opcion==2){
	$Nombre=trim($_POST['BNombre']);
	$Telefono=trim($_POST['BTelefono']);
	$EMAIL=trim($_POST['BEMAIL']);
	$Estilo=trim($_POST['Bestilo']);
	$Direccion=trim($_POST['BDireccion']);
	$CP=trim($_POST['BCP']);
	$poblacion=trim($_POST['Bpoblacion']);
	$provincia=trim($_POST['Bprovincia']);
	$web=trim($_POST['Bweb']);

	$NombreC=trim($_POST['BNombreC']);
	$ApellidoC=trim($_POST['BApellidoC']);
	$Dni=trim($_POST['BDniC']);
	$EmailC=trim($_POST['BEmailC']);
	$Seccion=trim($_POST['BSeccionC']);
	$Pass1=trim($_POST['CPass1']);

	
	
	

		$sql = "INSERT INTO banda(nombre,direccion,poblacion,provincia,cp,telefono,email,web,estilo) 
				VALUES					
				('$Nombre','$Direccion','$poblacion','$provincia','$CP','$Telefono','$EMAIL','$web','$Estilo')";
		$rs = mysql_query($sql, $con);

		$sql = "SELECT id FROM banda WHERE email='$EMAIL' ORDER BY id DESC LIMIT 1";
		$rs = mysql_query($sql, $con);
		$fila=mysql_fetch_row($rs);
		$idBanda = $fila[0];

		$sql = "INSERT INTO componente(nombre,apellido,dni,email,seccion,contraseña,privilegios,banda) 
		VALUES
		('$NombreC','$ApellidoC','$Dni','$EmailC','$Seccion','$Pass1',1,'$idBanda')";
		$rs = mysql_query($sql, $con);

	
	
}


	include('Registro.html');
	
?>

