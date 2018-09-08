<?php
require_once('includes/configuracion.php');
require_once('includes/funciones.php');



if($_SESSION['usuario']==NULL || $_SESSION['id_Empresa']==NULL){
	echo "<meta http-equiv='Refresh' content='0;url=login.php'>";
	return;
}




$fichero='lineasalbaranesclientes';
$ficheroPHP="facturasclientes.php";
$ficheroHTML="facturasclientes.html";
$TituloCabecera='Facturas Clientes';
$_SESSION['Fichero']=$fichero;
$ficheromayusculas=strtoupper($_SESSION['Fichero']);
$_SESSION['Titulo']="Listado de Facturas Clientes";

$_SESSION['privilegios']=$_SESSION['privilegios']['fichero26'];
$_SESSION['acceso']=$_SESSION['privilegios'];
if($_SESSION['privilegios']==0) {
	echo "<br><center>No tiene permisos para ver esta sección.</center>";
	return;
}





$Comunidad=$_SESSION['Comunidad'];
$IDEmpresa=$_SESSION['id_Empresa'];
$Usuario=$_SESSION['usuario'];
$NombreUsuario=$_SESSION['nombreusuario'];
$DNI=$_SESSION['dniusuario'];

$opcion=$_POST['opcion'];
$exportar=$_GET['exportar'];




//CONECTO CON LA BASE DE DATOS Y VEO LAS COLUMNAS DEL REGISTRO
for($i = 0; $i <= 200; $i++) {$_SESSION['NombreCampo'][$i]=NULL;}	
$sql = "SHOW COLUMNS FROM ".$fichero;
$rs = mysql_query($sql, $con);
$ColumnasTabla=mysql_num_rows($rs);
if (mysql_num_rows($rs) > 0) {
    $a=0;
	while ($fila = mysql_fetch_array($rs)) {
		$_SESSION['NombreCampo'][$a]=$fila[0];
    	$a++;
	}
}







//Actualiza Select
if($opcion==18){
	echo '<option selected="selected"></option>';
	$sql = "SELECT * FROM articulos WHERE Empresa='".$_SESSION['id_Empresa']."' ORDER BY NombreArticulo ASC";
	$rs = mysql_query($sql, $con);
	while ($fila=mysql_fetch_array($rs)){ 
		echo "<option cod='$fila[1]'>$fila[3] - Precio.: $fila[6] - Exist.: $fila[28]</option>";
	}
	exit();
}













//ELIMINAR******************************************************************************
if ($opcion==3){
	$id=$_POST['C0'];
	$nserie=$_POST['C43'];

	$sql = "SELECT * FROM $fichero WHERE Empresa='$IDEmpresa' AND CodigoAlbaran='$id' AND NSerie='$nserie'".$_SESSION['entreañoFechaFactura']." ORDER BY NSerie,CodigoAlbaran ASC";
	$rs = mysql_query($sql, $con);	
	if (mysql_num_rows($rs) > 0) {
		while ($fila=mysql_fetch_array($rs)){
			$sql1="UPDATE $fichero SET NumFactura='0',FechaFactura='0000-00-000' WHERE Empresa='$IDEmpresa' AND CodigoAlbaran='$id' AND NSerie='$nserie' ".$_SESSION['entreañoFechaFactura'];
			$rs1 = mysql_query($sql1, $con);
		}
	}

	if ($rs==true){ 
		echo "true";
		return;
	}else{
		echo "false";
		return;
	} 
}





//PAGADO******************************************************************************
if ($opcion==4){
	$_SESSION['Pagado']='';
	$id=$_POST['C0'];
	$sql = "SELECT * FROM $fichero WHERE Empresa='$IDEmpresa' AND CodigoAlbaran='$id' ".$_SESSION['entrefechas']." ORDER BY CodigoAlbaran ASC";
	$rs = mysql_query($sql, $con);	
	if (mysql_num_rows($rs) > 0) {
		while ($fila=mysql_fetch_array($rs)){
			$_SESSION['ClienteAlbaran']=$fila[44];
			$_SESSION['Pagado']=$fila[47];
			$_SESSION['Saldo']+=$fila[19]+$fila[35]-$fila[36];
		}
	}
	if ($_SESSION['Pagado']=='Si'){ 
		$sql="UPDATE $fichero SET Pagado='' WHERE Empresa='$IDEmpresa'".$_SESSION['entrefechas']." AND CodigoAlbaran='$id'";
		$rs = mysql_query($sql, $con);
		//modifico saldo
		$sqlc="UPDATE clientes SET Saldo=Saldo+'".$_SESSION['Saldo']."' WHERE Empresa='$IDEmpresa' AND CodCliente='".$_SESSION['ClienteAlbaran']."'";
		$rsc = mysql_query($sqlc, $con);
		$_SESSION['Saldo']=0;

		echo "true";
		return;

	}else{
		$sql="UPDATE $fichero SET Pagado='Si' WHERE Empresa='$IDEmpresa'".$_SESSION['entrefechas']." AND CodigoAlbaran='$id'";
		$rs = mysql_query($sql, $con);
		//modifico saldo
		$sqlc="UPDATE clientes SET Saldo=Saldo-'".$_SESSION['Saldo']."' WHERE Empresa='$IDEmpresa' AND CodCliente='".$_SESSION['ClienteAlbaran']."'";
		$rsc = mysql_query($sqlc, $con);
		$_SESSION['Saldo']=0;

		echo "true";
		return;

	} 
}



//Cuaderno19 ******************************************************************************
if ($opcion==5){
	$id=$_POST['C0'];
	$nserie=$_POST['C43'];
	$_SESSION['C19']='';
	$sql = "SELECT * FROM $fichero WHERE Empresa='$IDEmpresa' AND CodigoAlbaran='$id' AND NSerie='$nserie' ".$_SESSION['entrefechas']." ORDER BY CodigoAlbaran ASC";
	$rs = mysql_query($sql, $con);	
	if (mysql_num_rows($rs) > 0) {
		while ($fila=mysql_fetch_array($rs)){
			$_SESSION['C19']=$fila[49];
		}
	}
	if ($_SESSION['C19']=='Si'){ 
		$sql="UPDATE $fichero SET C19='' WHERE Empresa='$IDEmpresa'".$_SESSION['entrefechas']." AND CodigoAlbaran='$id' AND NSerie='$nserie'";
		$rs = mysql_query($sql, $con);
		echo "true";
		return;

	}else{
		$sql="UPDATE $fichero SET C19='Si' WHERE Empresa='$IDEmpresa'".$_SESSION['entrefechas']." AND CodigoAlbaran='$id' AND NSerie='$nserie'";
		$rs = mysql_query($sql, $con);
		echo "true";
		return;

	} 
}



//Comprobar si existe Cliente ******************************************************************************
if ($opcion==6){
	$CodCliente=$_POST['C0'];
	$sqlc = "SELECT * FROM clientes WHERE Empresa='$IDEmpresa' AND CodCliente='$CodCliente' ORDER BY CodCliente ASC";
	$rsc = mysql_query($sqlc, $con);	
	if (mysql_num_rows($rsc) > 0) {
		echo "true";
		return;
	}
	echo "false";
	return;
}

//Añadir Cliente ******************************************************************************
if ($opcion==7){
	$CodCliente=$_POST['C0'];
	$Nombre=$_POST['Nombre'];
	$DNI=$_POST['DNI'];
	$Direccion=$_POST['Direccion'];
	$Poblacion=$_POST['Poblacion'];
	$Provincia=$_POST['Provincia'];
	$CodPostal=$_POST['CodPostal'];
	$Telefono=$_POST['Telefono'];
	$Telefono2=$_POST['Telefono2'];
	$E_Mail=$_POST['E_Mail'];
	$Fechadealta=date('Y-m-d H:i:s');
	$NumdeCuenta=$_POST['NumdeCuenta'];
	$RecargodeEquivalencia=$_POST['RecargodeEquivalencia'];
	$IRPF=$_POST['IRPF'];
		
	//VALIDAR DATOS FORMULARIO
	if ($Nombre==""){
		$Mensaje="Introduzca Nombre";
	}
	
	if ($Mensaje=="" && $errores==""){
		$sql = "INSERT INTO clientes
		(CodCliente,Apellidos_Nombre,DNI,Direccion,Poblacion,Provincia,CodPostal,Telefono,Telefono2,E_Mail,Fechadealta,NumdeCuenta,RecargodeEquivalencia,IRPF,Empresa) 
		
		VALUES					
		('$CodCliente','$Nombre','$DNI','$Direccion','$Poblacion','$Provincia','$CodPostal','$Telefono','$Telefono2','$E_Mail','$Fechadealta','$NumdeCuenta','$RecargodeEquivalencia','$IRPF','".$_SESSION['id_Empresa']."')";	
		$rs = mysql_query($sql, $con);

		echo "true";
		return;
	}
	echo "false";
	return;
}




//Comprobar si existe Articulo ******************************************************************************
if ($opcion==8){
	$CodArticulo=$_POST['C0'];
	$sqla = "SELECT * FROM articulos WHERE Empresa='$IDEmpresa' AND CodArticulo='$CodArticulo' ORDER BY CodArticulo ASC";
	$rsa = mysql_query($sqla, $con);	
	if (mysql_num_rows($rsa) > 0) {
		echo "true";
		return;
	}
	echo "false";
	return;
}

//Añadir o Modificar Articulo ******************************************************************************
if ($opcion==9){
	$CodArticulo=$_POST['C0'];
	$NombreArticulo=$_POST['Nombre'];
	$Familia=$_POST['Familia'];
	$PrecioCompra=$_POST['PrecioCompra'];
	$PrecioVenta=$_POST['PrecioVenta'];
	$TipoIva=$_POST['TipoIva'];
	$Existencias=$_POST['Existencias'];

	//VALIDAR DATOS FORMULARIO
	if ($NombreArticulo==""){
		$Mensaje="Introduzca Nombre Artículo";
	}
	if ($Familia==""){
		$Mensaje="Introduzca Familia ";
	}
	
	if ($Mensaje=="" && $errores==""){
		$sql = "INSERT INTO articulos
		(CodArticulo,NombreArticulo,Familia,PrecioCompra,PrecioVenta,TipoIva,Existencias,Empresa) 
		VALUES					
		('$CodArticulo','$NombreArticulo','$Familia','$PrecioCompra','$PrecioVenta','$TipoIva','$Existencias','".$_SESSION['id_Empresa']."')";	
		$rs = mysql_query($sql, $con);

		echo "true";
		return;
	}
	echo "false";
	return;
}








//CARGAR DATOS
if($opcion==15){
	$id=$_POST['C0'];
	$sql = "SELECT * FROM $fichero WHERE ".$_SESSION['NombreCampo'][0]."='$id'";
	$rs = mysql_query($sql, $con);
	$mandar="";
	while ($fila=mysql_fetch_array($rs)){ 					
		$mandar=json_encode($fila);
	}
	echo $mandar;

	exit();
}



//MODIFICAR DATOS ***********************************************************************************
if($opcion==16){
	for($i=0;$i<$ColumnasTabla;$i++){
		$C[$i]=trim($_POST['C'.$i]);
		if($C[$i]=="null" || $C[$i]=="undefined") $C[$i]="";
	}
	$C[41]=$_SESSION['id_Empresa'];

	//VALIDAR DATOS FORMULARIO
	if ($C[2]==""){
		$Mensaje="Introduzca Código";
	}
	
	/* Imagen */
	if($Mensaje==""){
		$resultado = explode("|",subeImagen($_FILES["timagen"]));
		
		if($resultado[0]=="NOK"){
			$errores = $resultado[1];
		} else {
			$urlimagen=$resultado[1];
		}	
		$C[$colImagen]=$urlimagen;
	}
	/* /Imagen */
	
	if ($Mensaje=="" && $errores==""){
		if($urlimagen!=""){
			$sql="SELECT logo FROM $fichero WHERE id='$C[0]'";
			$rs = mysql_query($sql, $con);
			$fila=mysql_fetch_array($rs);
			borraImagen($fila[0]);
			
			$sql = "UPDATE $fichero SET ";
			for($i=1; $i<$ColumnasTabla; $i++){
				$sql .= $_SESSION['NombreCampo'][$i]."='".$C[$i]."', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE ".$_SESSION['NombreCampo'][0]."='$C[0]'";
		} else {
			$sql = "UPDATE $fichero SET ";
			for($i=1; $i<$ColumnasTabla; $i++){
				if($i!=($colImagen+1))
					$sql .= $_SESSION['NombreCampo'][$i]."='".$C[$i]."', ";
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE ".$_SESSION['NombreCampo'][0]."='$C[0]'";
		}
		$rs = mysql_query($sql, $con);
		$result=array();
		if ($rs==true){ 
			$result['resultado'] = true;
		}else{
			$result['resultado'] = false;
		} 
	}else{
		$result['resultado']=$Mensaje.$errores;
	}
	echo json_encode($result);
	exit();
}




//cargar lineas de albaran
if($opcion=="cargarlineasalbaran"){
	$CodigoAlbaran=$_POST["codigo"];
	$nserie=$_POST["nserie"];
	$_SESSION['nserie']=$nserie;
	$sql = "SELECT L.*,C.RecargodeEquivalencia,T.RecargoEquivalencia
			FROM lineasalbaranesclientes L, tiposdeiva T, clientes C
			WHERE L.Empresa='$IDEmpresa' AND L.Empresa=T.Empresa AND L.Empresa=C.Empresa
				   ".$_SESSION['entrefechas']." AND L.CodigoAlbaran='".$_POST['codigo']."' AND L.NSerie='".$_POST['nserie']."' AND L.CodigoCliente=C.CodCliente AND L.CodigoTipoIva=T.TipoIva
				  ORDER BY CodigoAlbaran ASC";
				  				  
	$rs = mysql_query($sql, $con);
	$consulta = mysql_query($sql) or die ("Error en la consulta");
	for($i=0;$i<mysql_num_rows($consulta);$i++){
		$fila=mysql_fetch_assoc($consulta);

		echo "<tr class='filaPresupuesto' codigolinea='".$fila['id']."' ";
		echo "C4='".$fila['CodigoArticulo']."' ";
		echo "C6='".$fila['Descripcion']."' ";
		echo "C16='".$fila['Zona']."' ";
		echo "C17='".$fila['Bultos']."' ";
		echo "C7='".$fila['Cantidad']."' ";
		echo "C29='".$fila['Precio']."' ";
		echo "C9='".$fila['DtoPor']."' ";
		echo "C10='".$fila['ImporteDto']."' ";
		echo "C12='".$fila['CodigoTipoIva']."' ";
//		$iva=$fila['ImporteLinea']*$fila['CodigoTipoIva']/100;
//		echo "C30='".$iva."' ";
		echo "C30='".$fila['ImporteIva']."' ";
		echo "C18='".$fila['ImporteLinea']."' ";
		echo "C19='".$fila['ImporteLineaSin']."' ";
		if($fila['RecargodeEquivalencia']=="Si"){
			$importerecargolinea=$fila['ImporteLinea']*$fila['RecargoEquivalencia']/100;
			echo "C35='".$importerecargolinea."' ";
		}else{
			echo "C35='0' ";
		}
		if($fila['RecargodeEquivalencia']=="Si"){
			$importerecargolinea=$fila['ImporteLinea']*$fila['RecargoEquivalencia']/100;
			echo "C35='".$importerecargolinea."' ";
		}else{
			echo "C35='0' ";
		}
		echo "C36='".$fila['ImporteIRPF']."' ";
		echo ">";
		
		echo "<td align='center'>".$fila['CodigoArticulo']."</td>";
		echo "<td align='left'>".$fila['Descripcion']."</td>";
		echo "<td align='center'>".$fila['Zona']."</td>";
		echo "<td align='center'>".$fila['Bulto']."</td>";
		echo "<td align='center'>".$fila['Cantidad']."</td>";
		echo "<td align='right'>".number_format($fila['Precio'],2)."</td>";
		echo "<td colspan='2' align='right'>".number_format($fila['ImporteDto'],2)."</td>";
		echo "<td align='center'>".$fila['CodigoTipoIva']."% = ".number_format($iva,2)." €</td>";
		echo "<td align='right'>".number_format($fila['ImporteLineaSin'],2)."</td>";
		echo "<td align='right'>".$fila['Trabajador']."</td>";
		echo "<td align='center'><a class='bt_editarLinea' ><span class='glyphicon glyphicon-pencil' style='cursor:pointer;' title='Modificar línea'></span></a></td>";
		echo "<td align='center'><a class='bt_eliminarLinea'><span class='glyphicon glyphicon-remove' style='cursor:pointer;' title='Eliminar línea'></span></a></td>";
		echo "</tr>";
	}

	exit();
}

if($opcion=="recargoEquivalencia"){
	$resultado = array();
	$sql = "SELECT RecargodeEquivalencia,IRPF FROM clientes WHERE Empresa='$IDEmpresa' AND CodCliente='".$_POST['CodCliente']."' LIMIT 1";
	$rs = mysql_query($sql, $con);
	$consulta = mysql_query($sql) or die ("Error en la consulta");
	$fila=mysql_fetch_assoc($consulta);
	$resultado['IRPF'] = $fila['IRPF'];
	if($fila['RecargodeEquivalencia']=="Si") {
		$sql = "SELECT RecargoEquivalencia FROM tiposdeiva WHERE Empresa='$IDEmpresa' AND TipoIva='".$_POST['tipoiva']."' LIMIT 1";
		$rs = mysql_query($sql, $con);
		$consulta = mysql_query($sql) or die ("Error en la consulta");
		$fila=mysql_fetch_assoc($consulta);
		$resultado['RecargoEquivalencia'] = $fila['RecargoEquivalencia'];
	} else {
		$resultado['RecargoEquivalencia'] = "0";
	}
	
	echo json_encode($resultado);
	
	exit();
}






//MODIFICAR E INSERTAR LINEAS DE ALBARAN ***********************************************************************************
if($opcion==26){
	$_SESSION['Pagado']='';
	$_SESSION['Cuaderno19']='';
	$_SESSION['CodCli']='';
	$NSerie = $_POST['NSerie'];
	$_SESSION['NSerie']=$NSerie;
	$CodigoAlbaran = $_POST['CodigoAlbaran'];
	$_SESSION['CodigodeAlbaran']=$CodigoAlbaran;
	$Fecha = SpanishToSQL($_POST['FechaFactura']);  //($_POST['Fecha']);
	$FechaFactura = SpanishToSQL($_POST['FechaFactura']);
	$CodigoCliente = $_POST['CodigoCliente'];
	$_SESSION['CodCli']=$CodigoCliente;
	$NombreClien = $_POST['NombreClien'];
	$Tipo = $_POST['Tipo'];
	$_SESSION['Tipo']=$Tipo;
	$NumFactura = $_POST['NumFactura'];
	$Formadepago = $_POST['Formadepago'];
	$filasPresupuesto = $_POST['filas'];
	
	//VALIDAR DATOS FORMULARIO
	if ($CodigoAlbaran==""){
		$Mensaje.="Falta número de Albarán.<br>";
	}
	if ($CodigoCliente==""){
		$Mensaje.="Debe seleccionar un Cliente<br>";
	}
	if ($Fecha==""){
		$Mensaje.="Indique una Fecha.<br>";
	}
	if (count($filasPresupuesto)==0){
		$Mensaje.="Añada alguna línea.<br>";
	}
	
	if ($Mensaje=="" && $errores==""){
		//recuperar existencias de articulo
		$sql = "SELECT * FROM lineasalbaranesclientes WHERE Empresa='".$_SESSION['id_Empresa']."' ".$_SESSION['entreañoFechaFactura']." AND CodigoAlbaran='".$_SESSION['CodigodeAlbaran']."' AND NSerie='".$_SESSION['NSerie']."'";
		$rs = mysql_query($sql, $con);	
		if (mysql_num_rows($rs) > 0) {
			while ($fila=mysql_fetch_array($rs)){
				$_SESSION['Pagado']=$fila[47];
				$_SESSION['Cuaderno19']=$fila[49];
				$sqla = "SELECT * FROM articulos WHERE Empresa='$IDEmpresa' AND CodArticulo='$fila[4]' AND CodAlmacen='$fila[5]' ORDER BY CodArticulo ASC LIMIT 1";
				$rsa = mysql_query($sqla, $con);	
				if (mysql_num_rows($rsa) > 0) {
					while ($filaa=mysql_fetch_array($rsa)){
						if ($_SESSION['Tipo']=='P' or $_SESSION['Tipo']=='F'){     // si es presupuesto o factura proforma no hago nada
						}else{
							$Existencias=$filaa[28]+$fila[7];
							$sqlar="UPDATE articulos SET Existencias='$Existencias' WHERE Empresa='$IDEmpresa' AND CodArticulo='$fila[4]' AND CodAlmacen='$fila[5]'";
							$rsar = mysql_query($sqlar, $con);
							$Existencias=0;
						}
					}
				}
				//fin de recuperar existencias
				//actualizar agentes / trabajadores
				$sqlag = "SELECT * FROM agentes WHERE Empresa='$IDEmpresa' AND Codigo='$fila[37]' ORDER BY Codigo ASC LIMIT 1";
				$rsag = mysql_query($sqlag, $con);	
				mysql_num_rows($rsag);
				if (mysql_num_rows($rsag) > 0) {
					while ($filaag=mysql_fetch_array($rsag)){
						if ($_SESSION['Tipo']=='P' or $_SESSION['Tipo']=='F'){     // si es presupuesto o factura proforma no hago nada
						}else{
							$ImporteGenerado=$filaag[25]-$fila[19]-$fila[35]+$fila[36];
							$sqlage="UPDATE agentes SET ImporteGenerado='$ImporteGenerado' WHERE Empresa='$IDEmpresa' AND Codigo='$filaag[1]' ORDER BY Codigo ASC";
							$rsage = mysql_query($sqlage, $con);
							$ImporteGenerado=0;
						}
					}
				}
				//fin de actualizar agentes / trabajadores
			}
		}
				
		

		//Borro las lineas
		$rs = mysql_query("DELETE FROM lineasalbaranesclientes WHERE Empresa='".$_SESSION['id_Empresa']."' AND CodigoAlbaran='$CodigoAlbaran'") or die ("Error en la consulta");
		
		//inserto las nuevas lineas
		$Pagado=$_SESSION['Pagado'];
		$Cuaderno19=$_SESSION['Cuaderno19'];
		$cadena = "INSERT INTO `lineasalbaranesclientes`(Empresa,NSerie,CodigoAlbaran,Fecha,FechaFactura,CodigoCliente,NombreCliente,Tipo,NumFactura,Formadepago,Pagado,C19,CodigoArticulo,Descripcion,Zona,Bulto,Cantidad,Precio,DtoPor,ImporteDto,ImporteLinea,CodigoTipoIva,ImporteIva,ImporteRecargo,ImporteIRPF,ImporteLineaSin,TotalLinea,Trabajador) VALUES ";
		for($i=0; $i<count($filasPresupuesto); $i++){
			$TotalLinea=$filasPresupuesto[$i]['total']+$filasPresupuesto[$i]['ImporteRecargo']-$filasPresupuesto[$i]['ImporteIRPF'];
			$cadena.="(".
					"'".$_SESSION['id_Empresa']."',".
					"'".$NSerie."',".
					"'".$CodigoAlbaran."',".
					"'".$Fecha."',".
					"'".$FechaFactura."',".
					"'".$CodigoCliente."',".
					"'".$NombreClien."',".
					"'".$Tipo."',".
					"'".$NumFactura."',".
					"'".$Formadepago."',".
					"'".$Pagado."',".
					"'".$Cuaderno19."',".
					"'".$filasPresupuesto[$i]['codArticulo']."',".
					"'".$filasPresupuesto[$i]['concepto']."',".
					"'".$filasPresupuesto[$i]['lote']."',".
					"'".$filasPresupuesto[$i]['bultos']."',".
					"'".$filasPresupuesto[$i]['cantidad']."',".
					"'".$filasPresupuesto[$i]['precio']."',".
					"'".$filasPresupuesto[$i]['tdescuento']."',".
					"'".$filasPresupuesto[$i]['descuento']."',".
					"'".($filasPresupuesto[$i]['total']-$filasPresupuesto[$i]['iva'])."',".
					"'".$filasPresupuesto[$i]['tiva']."',".
					"'".$filasPresupuesto[$i]['iva']."',".
					"'".$filasPresupuesto[$i]['ImporteRecargo']."',".
					"'".$filasPresupuesto[$i]['ImporteIRPF']."',".
					"'".$filasPresupuesto[$i]['total']."',".
					"'".$TotalLinea."',".
					"'".$filasPresupuesto[$i]['Trabajador']."'".
					"),";

				//disminuir las existencias
				$sqla = "SELECT id,CodArticulo,CodAlmacen,Existencias FROM articulos WHERE Empresa='$IDEmpresa' AND CodArticulo='".$filasPresupuesto[$i]['codArticulo']."' ORDER BY CodArticulo ASC LIMIT 1";
				$rsa = mysql_query($sqla, $con);	
				mysql_num_rows($rsa);
				if (mysql_num_rows($rsa) > 0) {
					while ($filaa=mysql_fetch_array($rsa)){
						if ($_SESSION['Tipo']=='P' or $_SESSION['Tipo']=='F'){     // si es presupuesto o factura proforma no hago nada
						}else{
							$Existencias=$filaa[3]-$filasPresupuesto[$i]['cantidad'];
							$sql="UPDATE articulos SET Existencias='$Existencias' WHERE Empresa='$IDEmpresa' AND id='$filaa[0]' ORDER BY id ASC";
							$rs = mysql_query($sql, $con);
							$Existencias=0;
						}
					}
				}
				//fin de disminuir las existencias
				//actualizar agentes / trabajadores
				$sqlag = "SELECT * FROM agentes WHERE Empresa='$IDEmpresa' AND Codigo='".$filasPresupuesto[$i]['Trabajador']."' ORDER BY Codigo ASC LIMIT 1";
				$rsag = mysql_query($sqlag, $con);	
				mysql_num_rows($rsag);
				if (mysql_num_rows($rsag) > 0) {
					while ($filaag=mysql_fetch_array($rsag)){
						if ($_SESSION['Tipo']=='P' or $_SESSION['Tipo']=='F'){     // si es presupuesto o factura proforma no hago nada
						}else{
							$ImporteGenerado=$filaag[25]+$filasPresupuesto[$i]['total']+$filasPresupuesto[$i]['ImporteRecargo']-$filasPresupuesto[$i]['ImporteIRPF'];
							$sql="UPDATE agentes SET ImporteGenerado='$ImporteGenerado' WHERE Empresa='$IDEmpresa' AND Codigo='$filaag[1]' ORDER BY Codigo ASC";
							$rs = mysql_query($sql, $con);
							$ImporteGenerado=0;
						}
					}
				}
				//fin de actualizar agentes / trabajadores

		}
		$cadena=substr($cadena,0,-1);
		$rs = mysql_query($cadena) or die ("Error en la consulta");


		//actualizar saldo y facturas de clientes.
		$Final=$_SESSION['finaldeaño'];
		$FechaBusqueda=" AND (Fecha='2016-01-01' or Fecha>'2016-01-01') AND (Fecha='$Final' or Fecha<'$Final')";
		$sql = "SELECT *,SUM(TotalLinea) AS totallinea FROM lineasalbaranesclientes WHERE Empresa='".$_SESSION['id_Empresa']."' $FechaBusqueda AND CodigoCliente='".$_SESSION['CodCli']."' GROUP BY NSerie,CodigoAlbaran ORDER BY NSerie,CodigoAlbaran ASC";
		$rs = mysql_query($sql, $con);	
		if (mysql_num_rows($rs) > 0) {
			while ($fila=mysql_fetch_array($rs)){
				$MesFactura=substr($fila[42],5,2);
				$TOTALALBARAN=round($fila['totallinea'],2);
				$TotalFactura=$TOTALALBARAN;
				$_SESSION['TotalFac'.$MesFactura]+=$TotalFactura;
				if ($MesFactura<04){
					$TotalFacPrimerTrimestre+=$TotalFactura;
				}else if($MesFactura>03 and $MesFactura<07){
					$TotalFacSegundoTrimestre+=$TotalFactura;
				}else if($MesFactura>06 and $MesFactura<10){
					$TotalFacTercerTrimestre+=$TotalFactura;
				}else if($MesFactura>09 and $MesFactura<12){
					$TotalFacCuartoTrimestre+=$TotalFactura;
				}else{
					$TotalFacCuartoTrimestre+=$TotalFactura;
				}
				if ($fila[47]<>'Si'){
					$TOTALSALDO=$TOTALSALDO+$TotalFactura;
				}
				$_SESSION["TotalFacturadoCliente"]=$_SESSION["TotalFacturadoCliente"]+$TotalFactura;
			}
			if ($_SESSION['Tipo']=='P' or $_SESSION['Tipo']=='F'){     // si es presupuesto o factura proforma no hago nada
			}else{
				$sqlc="UPDATE clientes SET 
				Total_Facturado='".$_SESSION["TotalFacturadoCliente"]."',
				Enero='".$_SESSION["TotalFac01"]."',
				Febrero='".$_SESSION["TotalFac02"]."',
				Marzo='".$_SESSION["TotalFac03"]."',
				Abril='".$_SESSION["TotalFac04"]."',
				Mayo='".$_SESSION["TotalFac05"]."',
				Junio='".$_SESSION["TotalFac06"]."',
				Julio='".$_SESSION["TotalFac07"]."',
				Agosto='".$_SESSION["TotalFac08"]."',
				Septiembre='".$_SESSION["TotalFac09"]."',
				Octubre='".$_SESSION["TotalFac10"]."',
				Noviembre='".$_SESSION["TotalFac11"]."',
				Diciembre='".$_SESSION["TotalFac12"]."',
				TotalFacturadoPrimerTrimestre='$TotalFacPrimerTrimestre',
				TotalFacturadoSegundoTrimestre='$TotalFacSegundoTrimestre',
				TotalFacturadoTercerTrimestre='$TotalFacTercerTrimestre',
				TotalFacturadoCuartoTrimestre='$TotalFacCuartoTrimestre',
				Saldo='$TOTALSALDO' WHERE Empresa='$IDEmpresa' AND CodCliente='".$_SESSION['CodCli']."'";
				$rsc = mysql_query($sqlc, $con);
			}
			
			$_SESSION['TotalFac01']=0;$_SESSION['TotalFac02']=0;$_SESSION['TotalFac03']=0;$_SESSION['TotalFac04']=0;$_SESSION['TotalFac05']=0;
			$_SESSION['TotalFac06']=0;$_SESSION['TotalFac07']=0;$_SESSION['TotalFac08']=0;$_SESSION['TotalFac09']=0;$_SESSION['TotalFac10']=0;
			$_SESSION['TotalFac11']=0;$_SESSION['TotalFac12']=0;
			$TotalFacPrimerTrimestre=0;
			$TotalFacSegundoTrimestre=0;
			$TotalFacTercerTrimestre=0;
			$TotalFacCuartoTrimestre=0;
			$TOTALSALDO=0;
			$_SESSION["TotalFacturadoCliente"]=0;
		}
		//fin de actualizar saldo y facturas de clientes.


		$result=array();
		if ($rs==true){ 
			$result['resultado'] = true;
		}else{
			$result['resultado'] = false;
		}
	}else{
		$result['resultado']=$Mensaje.$errores;
	}
	echo json_encode($result);
	exit();
}













//veo ultimo codigo del albaran en la empresa y le sumo uno.
if($opcion=="BuscarCodigoAlbaran"){
	$sql = "SELECT MAX(CodigoAlbaran)+1 FROM $fichero WHERE Empresa='$IDEmpresa'".$_SESSION['entreañoFechaFactura']." ORDER BY CodigoAlbaran DESC";
	$rs = mysql_query($sql, $con);
	$fila=mysql_fetch_array($rs);
	if ($fila[0]=='') {					
		$fila[0]='1';
	}	
	echo $fila[0];
	exit();
}
//veo ultimo codigo del albaran en la empresa y le sumo uno.
if($opcion=="BuscarCodigoAlbaranFactura"){
	$sql = "SELECT MAX(CodigoAlbaran)+1 FROM $fichero WHERE Empresa='$IDEmpresa'".$_SESSION['entreañoFecha']." ORDER BY CodigoAlbaran DESC";
	$rs = mysql_query($sql, $con);
	$fila=mysql_fetch_array($rs);
	if ($fila[0]=='') {					
		$fila[0]='1';
	}	
	echo $fila[0];
	exit();
}
//veo ultimo codigo de factura en la empresa y le sumo uno.
if($opcion=="BuscarCodigoFactura"){
	$sql = "SELECT MAX(NumFactura)+1 FROM $fichero WHERE Empresa='$IDEmpresa'".$_SESSION['entreañoFechaFactura']." ORDER BY NumFactura DESC";
	$rs = mysql_query($sql, $con);
	$fila=mysql_fetch_array($rs);
	if ($fila[0]=='') {					
		$fila[0]='1';
	}	
	echo $fila[0];
	exit();
}

//busco la ultima FACTURA de la serie.
if($opcion=="BuscarCodigoFacturaySerie"){
	$Codigo=$_POST['Codigo'];
	$sqlc = "SELECT * FROM $fichero WHERE Empresa='$IDEmpresa'".$_SESSION['entreañoFechaFactura']." AND NSerie='$Codigo' ORDER BY NSerie DESC";
	$rsc = mysql_query($sqlc, $con);
	if (mysql_num_rows($rsc)>0) {					
		while ($filac=mysql_fetch_array($rsc)){
			$mandar=json_encode($filac);
		}
	}else{
		$mandar=json_encode(array(0,0,0,0));  //mando un 0,0,0,0 porque no hay registros y es data[3]. Si fuera data[1], mandaria 0,0
	}
	echo $mandar;
	exit();
}





//compruebo si existe el código de ALBARAN en la empresa.
if($opcion=="comprobarCodigoAlbaran"){
	$Codigo=$_POST['C1'];
	$NSerie=$_POST['C43'];
	$sql = "SELECT CodigoAlbaran,NSerie FROM $fichero WHERE Empresa='$IDEmpresa' AND CodigoAlbaran='$Codigo' ".$_SESSION['entreañoFechaFactura']." AND NSerie='$NSerie' ORDER BY CodigoAlbaran DESC LIMIT 1";
	$rs = mysql_query($sql, $con);
	if (mysql_num_rows($rs)>0) {					
		echo "true";
	}else{
		echo "false";
	}
	return;
}


//compruebo si existe el código y la serie de la FACTURA en la empresa.
if($opcion=="comprobarCodigoFactura"){
	$Codigo=$_POST['C1'];
	$NSerie=$_POST['C43'];
	$sql = "SELECT NumFactura,NSerie FROM $fichero WHERE Empresa='$IDEmpresa' AND NumFactura='$Codigo' ".$_SESSION['entreañoFechaFactura']." AND NSerie='$NSerie' ORDER BY NumFactura DESC LIMIT 1";
	$rs = mysql_query($sql, $con);
	if (mysql_num_rows($rs)>0) {					
		echo "true";
	}else{
		echo "false";
	}
	return;
}


//BUSCAR ARTICULO ************************************
if ($opcion=='cargarArticulo'){
	$Codigo=$_POST['codigo'];
	$sqlc = "SELECT * FROM articulos WHERE Empresa='$IDEmpresa' AND CodArticulo='$Codigo' ORDER BY CodArticulo ASC";
	$rsc = mysql_query($sqlc, $con);
	while ($filac=mysql_fetch_array($rsc)){ 					
		$mandar=json_encode($filac);
	}
	echo $mandar;
	exit();
}

//BUSCAR CLIENTE ************************************
if ($opcion=='Cliente'){
	$Codigo=$_POST['Codigo'];
	$sqlc = "SELECT * FROM clientes WHERE Empresa='$IDEmpresa' AND CodCliente='$Codigo' LIMIT 1";
	$rsc = mysql_query($sqlc, $con);
	while ($filac=mysql_fetch_array($rsc)){
		$mandar=json_encode($filac);
	}
	echo $mandar;
	exit();
}

//BUSCAR TRABAJADOR ************************************
if ($opcion=='Trabajador'){
	$Codigo=$_POST['Codigo'];
	$sqlc = "SELECT * FROM agentes WHERE Empresa='$IDEmpresa' AND Codigo='$Codigo' LIMIT 1";
	$rsc = mysql_query($sqlc, $con);
	while ($filac=mysql_fetch_array($rsc)){
		$mandar=json_encode($filac);
	}
	echo $mandar;
	exit();
}



//para PDF *************************************************
if ($exportar=="pdf"){
	define('FPDF_FONTPATH', 'fpdf/font/');
	require('fpdf/fpdf_js.php');
	class PDF_AutoPrint extends PDF_Javascript{
	   //Cabecera de página
		function Header(){
			if($_SESSION['datEmp'][12]!=""){ $logo=$_SESSION['datEmp'][12]; } else { $logo="logos/logo_generico.jpg"; }
			$this->SetMargins(3, 3 , 3); 
			$this->Image($logo,5,5,40);
			$this->Ln(22);
			$this->SetTextColor(0);
			$this->SetFont('Arial','I',12);
			$this->Cell($LTitulo,'10',utf8_decode($_SESSION['Titulo']));
			$this->Ln(6);
			$this->SetTextColor(100);
			$this->SetFont('Arial','',7);
			$this->Cell('252','10',utf8_decode($_SESSION['fechalargahoy']));
			$this->Cell('32','10','N.Reg. '.count($_SESSION['C0']),0,0,'R');
			$this->Ln(10);

			
			$this->SetTextColor(0);
			$this->SetFont('Arial','',9);
			$this->SetWidths(array(28,10,10,22,22,90,22,50,30));
			$this->SetAligns(array('C','C','C','C','R','L','L','L','R'));
			$this->SetFillColor(160,160,160);
			$this->SetDrawColor(255,255,255);
			$this->Row(array(
					utf8_decode('Número'),
					utf8_decode('Pag'),
					utf8_decode('C19'),
					utf8_decode('Fecha'),
					utf8_decode('N.Cliente'),
					utf8_decode('Nombre'),
					utf8_decode('Teléfono'),
					utf8_decode('Población'),
					utf8_decode('Importe'),
					));
		}

		function Footer(){
			// Posición: a 2,0 cm del final
			$this->SetY(-20);
			// Arial italic 6
			$this->SetFont('Arial','I',6);
			// Número de página
			$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
		}		
	}
	// Creación del objeto de la clase heredada
	$_SESSION['$TotalCantidad']=0;
	$_SESSION['$Total']=0;
	$pdf = new PDF_AutoPrint();
	$pdf->AliasNbPages();
//	$pdf->AddPage(); //pagina horizontal. Sin la L normal.
	$pdf->AddPage(L); //pagina horizontal. Sin la L normal.
	$pdf->SetDrawColor(255,255,255);
	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(100);
	for($i = 1; $i <= count($_SESSION['C0']); $i++) 
	{
		$pdf->SetFillColor(255,255,255);
		$pdf->Row(array(
			utf8_decode($_SESSION['C43'][$i]." ".$_SESSION['C3'][$i]),
			utf8_decode($_SESSION['C47'][$i]),
			utf8_decode($_SESSION['C49'][$i]),
			utf8_decode(SQLToSpanish($_SESSION['C48'][$i])),
			utf8_decode($_SESSION['C44'][$i]),
			utf8_decode($_SESSION['NombreCliente'][$i]),
			utf8_decode($_SESSION['TelefonoCliente'][$i]),
			utf8_decode($_SESSION['PoblacionCliente'][$i]),
			number_format($_SESSION['C19'][$i],2),
		));
		$Total += $_SESSION['C19'][$i];
	}
	$pdf->Ln(4);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Row(array(
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode('Total ..:'),
		utf8_decode(number_format(($Total),2)),
	));

	$pdf->Output();

}// fin de listados********************************************







//para Excel *************************************************
if ($exportar==excel){
	require("excel/iam_xls.php");	
	$mid_excel = new IAM_XLS($fichero);
	$i = 0;
	$fila = 0;
	while($i <=  count($_SESSION['C0'])) {
		$mid_excel->WriteCellNumber($fila, 0, $i);
		$mid_excel->WriteCellText($fila, 1, utf8_decode($_SESSION['C43'][$i]." ".$_SESSION['C3'][$i]));
		$mid_excel->WriteCellText($fila, 2, utf8_decode($_SESSION['C47'][$i]));
		$mid_excel->WriteCellText($fila, 3, utf8_decode($_SESSION['C49'][$i]));
		$mid_excel->WriteCellText($fila, 4, utf8_decode(SQLToSpanish($_SESSION['C48'][$i])));
		$mid_excel->WriteCellText($fila, 5, utf8_decode($_SESSION['C44'][$i]));
		$mid_excel->WriteCellText($fila, 6, utf8_decode($_SESSION['NombreCliente'][$i]));
		$mid_excel->WriteCellText($fila, 7, utf8_decode($_SESSION['TelefonoCliente'][$i]));
		$mid_excel->WriteCellText($fila, 8, utf8_decode($_SESSION['PoblacionCliente'][$i]));
		$mid_excel->WriteCellText($fila, 9, number_format($_SESSION['C19'][$i],2));
		$fila++;
		$i++;
	} 
	
	//Sacar por el navegador el archivo
	$mid_excel->OutputFile();
}
// fin en excel ********************************************




//HacerCuaderno19 ******************************************************************************
if ($opcion=='HacerCuaderno19'){
	for($i = 0; $i <=1000; $i++){
		$_SESSION['Alba'][$i]="";
		$_SESSION['NombreCli'][$i]="";
		$_SESSION['PoblacionCli'][$i]="";
		$_SESSION['CCCli'][$i]="";
		$_SESSION['Importe'][$i]="";
		$_SESSION['Grabado'][$i]="";
	}
	$_SESSION['NFacturas']=0;

	
	$NombreFichero=$_SESSION['id_Empresa']."_Cuaderno19Factu_".date("d-m-Y").".txt";
	$_SESSION['NombreFichero']=$NombreFichero;
	$file = fopen($NombreFichero, "w");
	
	//IDENTIFICADOR DEL ACREEDOR (AT-02)
	$CIFEmpresa=$_SESSION['datEmp'][0];
	$numero[10] = "A";
	$numero[11] = "B";
	$numero[12] = "C";
	$numero[13] = "D";
	$numero[14] = "E";
	$numero[15] = "F";
	$numero[16] = "G";
	$numero[17] = "H";
	$numero[18] = "Y";
	$numero[19] = "J";
	$numero[20] = "K";
	$numero[21] = "L";
	$numero[22] = "M";
	$numero[23] = "N";
	$numero[24] = "O";
	$numero[25] = "P";
	$numero[26] = "Q";
	$numero[27] = "R";
	$numero[28] = "S";
	$numero[29] = "T";
	$numero[30] = "U";
	$numero[31] = "V";
	$numero[32] = "W";
	$numero[33] = "X";
	$numero[34] = "Y";
	$numero[35] = "Z";
	for($i=10;$i<=35;$i++){
		if (substr($CIFEmpresa,0,1)==$numero[$i]){
			$Cif=$i.substr($CIFEmpresa,1,8).'142800';
		}
		if (substr($CIFEmpresa,8,1)==$numero[$i]){
			$Cif=substr($CIFEmpresa,0,8).$i.'142800';
		}
	}
	$Cif = abs($Cif) - (97 * intval(abs($Cif) / 97));
	$RestoCif=$Cif % 97;
	$RestoCif=98-$RestoCif;
	$identificador = "ES".$RestoCif."000".$CIFEmpresa.str_pad("",19," ");
	//FIN DE IDENTIFICADOR DEL ACREEDOR


	//REGISTRO DE CABECERA 1
	$NombreEmpresa=str_pad($_SESSION['datEmp'][1],70," ");
	$EntOfi=substr($_SESSION['datEmp'][9],4,8);
	$iban=validaIBAN(substr($_SESSION['datEmp'][9],0,20),'ES');
	$_SESSION['iban']=$iban;

	$registrodecabecera1="0119154001".$identificador.$NombreEmpresa.date("Ymd")."PRE".date("Ymdhhmmss").str_pad("",12," ").$EntOfi.str_pad("",434," ");
	$_SESSION['registrodecabecera1']=$registrodecabecera1;
	fwrite($file, $registrodecabecera1 . PHP_EOL);

	//REGISTRO DE CABECERA 2
	$registrodecabecera2="0219154002".$identificador.date("Ymd").$NombreEmpresa.str_pad("",142," ").$_SESSION['iban'].str_pad("",10," ").str_pad("",301," ");
	$_SESSION['registrodecabecera2']=$registrodecabecera2;
	fwrite($file, $registrodecabecera2 . PHP_EOL);







	$i=1;
	$sql = "SELECT *,SUM(ImporteLineaSin),SUM(ImporteRecargo) AS totalalbaran FROM $fichero WHERE Empresa='$IDEmpresa' AND NumFactura<>'' AND Pagado='' AND C19='Si' ".$_SESSION['entreañoFechaFactura']." GROUP BY NSerie,NumFactura ORDER BY NSerie,NumFactura ASC";

	$rs = mysql_query($sql, $con);
	if (mysql_num_rows($rs)>0) {					
		$_SESSION['NFacturas']=mysql_num_rows($rs);
		while ($fila=mysql_fetch_array($rs)){ 	
			$NumFactura=$fila[3];
			$nserie=$fila[43];

			$_SESSION['Alba'][$i]=$fila[3];
			$CodReferencia =str_pad($fila[2],12," ");
			$ReferenciaUnica = "Factura: ".$fila[3];
			$ReferenciaUnica = str_pad($ReferenciaUnica,35," ");
			$TOTALALBARAN=round($fila[51]+$fila[52]-$fila[53],2);
			$ImporteAlba=$TOTALALBARAN;
			$ImporteAlbaran1=intval($ImporteAlba*100);
			$ImporteAlbaran=str_pad($ImporteAlbaran1,11,"0", STR_PAD_LEFT);
			$_SESSION['Importe'][$i]=$TOTALALBARAN;

			$sqlcli = "SELECT * FROM clientes WHERE Empresa='$IDEmpresa' AND CodCliente='$fila[44]' ORDER BY CodCliente ASC";
			$rscli = mysql_query($sqlcli, $con);
			if (mysql_num_rows($rscli)>0) {					
				while ($filacli=mysql_fetch_array($rscli)){ 	
					$NombreCliente1=formatearNombre($filacli[2]);
					$NombreCliente=str_pad(substr($NombreCliente1,0,70),70," ");
					$DireccionCliente1=formatearNombre($filacli[4]);
					$DireccionCliente=str_pad(substr($DireccionCliente1,0,50),50," ");
					$PoblacionCliente1=formatearNombre($filacli[5]);
					$PoblacionCliente=str_pad(substr($PoblacionCliente1,0,50),50," ");
					$ProvinciaCliente1=formatearNombre($filacli[6]);
					$ProvinciaCliente=str_pad(substr($ProvinciaCliente1,0,40),40," ");
					$CCCliente=str_pad(substr($filacli[24],0,24),24," ");

					$_SESSION['CCCli'][$i]=$filacli[24];
					$_SESSION['NombreCli'][$i]=$filacli[2];
					$_SESSION['PoblacionCli'][$i]=$filacli[5];
				}
			}

			$_SESSION['Digitos']='';
			if (calcularCCC($_SESSION['CCCli'][$i])=='' || $_SESSION['CCCli'][$i]==''){
//			if ($_SESSION['Digitos']<>substr($_SESSION['CCCli'][$i],8,2)){
				$sqlalba="UPDATE $fichero SET C19='' WHERE Empresa='$IDEmpresa'".$_SESSION['entreañoFecha']." AND CodigoAlbaran='$CodigoAlbaran' AND NSerie='$nserie'";
				$rsalba = mysql_query($sqlalba, $con);
				$_SESSION['Grabado'][$i]="NO";
			}else{
				$sqlalba="UPDATE $fichero SET Pagado='Si' WHERE Empresa='$IDEmpresa'".$_SESSION['entreañoFechaFactura']." AND NumFactura='$NumFactura' AND NSerie='$nserie'";
				$rsalba = mysql_query($sqlalba, $con);
				//modifico saldo
				$sqlc="UPDATE clientes SET Saldo=Saldo-'".$_SESSION['Importe'][$i]."' WHERE Empresa='$IDEmpresa' AND CodCliente='$fila[44]'";
				$rsc = mysql_query($sqlc, $con);

				$ibanCliente=validaIBAN($_SESSION['CCCli'][$i],'ES');
				$_SESSION['ibanCliente']=$ibanCliente;
				$PrimerCampodeConcepto1="Recibo de ".$_SESSION['datEmp'][1];
				$PrimerCampodeConcepto=str_pad($PrimerCampodeConcepto1,140," ");
	
				$registroindividual="0319154003".$CodReferencia.str_pad("",23," ").$ReferenciaUnica."RCUR".str_pad("",4," ").$ImporteAlbaran.date("Ymd").str_pad("",11," ").$NombreCliente.$DireccionCliente.$PoblacionCliente.$ProvinciaCliente."ES".str_pad("",72," ")."A".$_SESSION['ibanCliente'].str_pad("",10," ").str_pad("",4," ").$PrimerCampodeConcepto.str_pad("",19," ");
				
				fwrite($file, $registroindividual . PHP_EOL);
				
				$SumaTotal = $SumaTotal + $ImporteAlbaran;
				$STotal=str_pad($SumaTotal,17,"0", STR_PAD_LEFT);
				$NREG003=$NREG003 + 1;
			}			
			$i=$i+1;
		}
		$TREG003=str_pad($NREG003,8,"0", STR_PAD_LEFT);
		$NREG20=$NREG003 + 2;
		$TREG20=str_pad($NREG20,10,"0", STR_PAD_LEFT);
		$NREGISTROS = $NREG20 + 1;
		$TREGISTROS=str_pad($NREGISTROS,10,"0", STR_PAD_LEFT);
		$NREGISTROS = $NREG003 + 5;
		$TREGISTROSTOTALES=str_pad($NREGISTROS,10,"0", STR_PAD_LEFT);

		$registrototal_04="04".$identificador.date("Ymd").$STotal.$TREG003.$TREG20.str_pad("",520," ");
		$registrototal_05="05".$identificador.$STotal.$TREG003.$TREGISTROS.str_pad("",528," ");
		$registrototal_99="99".$STotal.$TREG003.$TREGISTROSTOTALES.str_pad("",563," ");
			
		fwrite($file, $registrototal_04 . PHP_EOL);
		fwrite($file, $registrototal_05 . PHP_EOL);
		fwrite($file, $registrototal_99 . PHP_EOL);

		fclose($file);


	}
	



}




//LISTAR CUADERNO 19
if ($exportar=='ListarCuaderno19'){
	define('FPDF_FONTPATH', 'fpdf/font/');
	require('fpdf/fpdf_js.php');
	class PDF_AutoPrint extends PDF_Javascript{
	   //Cabecera de página
		function Header(){
			if($_SESSION['datEmp'][12]!=""){ $logo=$_SESSION['datEmp'][12]; } else { $logo="logos/logo_generico.jpg"; }
			$this->SetMargins(3, 3 , 3); 
			$this->Image($logo,5,5,40);
			$this->Ln(22);
			$this->SetTextColor(0);
			$this->SetFont('Arial','I',12);
			$this->Cell($LTitulo,'10','Listado Cuaderno 19 FACTURAS');
			$this->Ln(6);
			$this->SetTextColor(100);
			$this->SetFont('Arial','',7);
			$this->Cell('160','10',utf8_decode($_SESSION['fechalargahoy']));
			$this->Cell('40','10','N.Reg. '.$_SESSION['NFacturas'],0,0,'R');
			$this->Ln(10);

			
			$this->SetTextColor(0);
			$this->SetFont('Arial','',9);
			$this->SetWidths(array(18,77,30,42,20,16));
			$this->SetAligns(array('C','L','L','L','R','C'));
			$this->SetFillColor(160,160,160);
			$this->SetDrawColor(255,255,255);
			$this->Row(array(
					utf8_decode('Factura'),
					utf8_decode('Nombre Cliente'),
					utf8_decode('Población'),
					utf8_decode('Numero de Cuenta'),
					utf8_decode('Importe'),
					utf8_decode('Grabado'),
					));
		}

		function Footer(){
			// Posición: a 2,0 cm del final
			$this->SetY(-20);
			$this->SetFont('Arial','B',7);
			// Número de página
			$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
		}		
	}
	// Creación del objeto de la clase heredada
	$Total=0;
	$pdf = new PDF_AutoPrint();
	$pdf->AliasNbPages();
	$pdf->AddPage(); //pagina horizontal. Sin la L normal.
	//$pdf->AddPage(L); //pagina horizontal. Sin la L normal.
	$pdf->SetDrawColor(255,255,255);
	$pdf->SetFillColor(255,255,255);
	$pdf->SetFont('Arial','',9);
	$pdf->SetTextColor(100);


	for($i = 1; $i <= $_SESSION['NFacturas']; $i++){
		$pdf->Row(array(
			utf8_decode($_SESSION['Alba'][$i]),
			utf8_decode($_SESSION['NombreCli'][$i]),
			utf8_decode($_SESSION['PoblacionCli'][$i]),
			utf8_decode($_SESSION['CCCli'][$i]),
			number_format($_SESSION['Importe'][$i],2),
			utf8_decode($_SESSION['Grabado'][$i]),
		));
		if($_SESSION['Grabado'][$i]<>"NO"){
			$Total += $_SESSION['Importe'][$i];
		}
	}
	$pdf->Ln(4);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Row(array(
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode(''),
		utf8_decode('Total ..:'),
		utf8_decode(number_format(($Total),2)),
	));

	$pdf->Output();




	for($i = 0; $i <= $_SESSION['NFacturas']; $i++){
		$_SESSION['Alba'][$i]="";
		$_SESSION['NombreCli'][$i]="";
		$_SESSION['PoblacionCli'][$i]="";
		$_SESSION['CCCli'][$i]="";
		$_SESSION['Importe'][$i]="";
		$_SESSION['Grabado'][$i]="";
	}
	$_SESSION['NFacturas']=0;
}






//buscar todos los clientes
if($opcion==21){
	$sql = "SELECT * FROM clientes WHERE Empresa='".$_SESSION['id_Empresa']."'";
	$rs = mysql_query($sql, $con);
	$arr = array();
	while ($fila = mysql_fetch_object($rs)){
		$arr[] = $fila;
	}
	echo '{"clientes":'.json_encode($arr).'}';
	exit();
}


//buscar todos los articulos
if($opcion==22){
	$sql = "SELECT * FROM articulos WHERE Empresa='".$_SESSION['id_Empresa']."'";
	$rs = mysql_query($sql, $con);
	$arr = array();
	while ($fila = mysql_fetch_object($rs)){
		$arr[] = $fila;
	}
	echo '{"articulos":'.json_encode($arr).'}';
	exit();
}








if($opcion=="0") {
//echo "Salva ".$_SESSION['sql'];
	//LISTADOS********************************************************	
	//----- ALGUN TIPO DE ORDENACION ?
	$Orden=$_POST['Orden'];
//	if ($Orden=="undefined"||$Orden=='null'||$Orden==''){
		$OrdenarPOR=" ORDER BY 4 DESC";
//	}
	//ORDEN EN EL CASO DE QUE SE BORRE ALGO PARA CONSERVAR EL MISMO ORDEN Y NO NOS MAREE LA TABLA
//	if ($Orden=="IDEM"){
//		$OrdenarPOR=$_SESSION["orden"];
//	}
	for($i=1;$i<=$ColumnasTabla;$i++){
		if ($Orden=="C".$i){
			if ($_SESSION['ORDEN1']==1){
				$_SESSION['ORDEN1']=0;
				$OrdenarPOR=" ORDER BY ".$i." ASC";
			}else{
				$_SESSION['ORDEN1']=1;
				$OrdenarPOR=" ORDER BY ".$i." DESC";
			}
		}
	}

	//PARA COMPARACION ENTRE FECHAS
	$_SESSION['fecha1']=$_POST['fecha'];
	$_SESSION['fecha2']=$_POST['fecha2'];
	$fechabusqueda1=SpanishToSQL($_SESSION['fecha1']);
	$fechabusqueda2=SpanishToSQL($_SESSION['fecha2']);
	$_SESSION['fechabusqueda1']=$fechabusqueda1;
	$_SESSION['fechabusqueda2']=$fechabusqueda2;
	$_SESSION['entrefechas']=" AND (FechaFactura='".$_SESSION['fechabusqueda1']."' or FechaFactura>'".$_SESSION['fechabusqueda1']."') AND (FechaFactura='".$_SESSION['fechabusqueda2']."' or FechaFactura<'".$_SESSION['fechabusqueda2']."')";
	$primerodeaño=substr($fechabusqueda1,0,4)."-01-01";
	$finaldeaño=substr($fechabusqueda1,0,4)."-12-31";
	$_SESSION['entreañoFecha']=" AND (Fecha='$primerodeaño' or Fecha>'$primerodeaño') AND (Fecha='$finaldeaño' or Fecha<'$finaldeaño')";
	$_SESSION['entreañoFechaFactura']=" AND (FechaFactura='$primerodeaño' or FechaFactura>'$primerodeaño') AND (FechaFactura='$finaldeaño' or FechaFactura<'$finaldeaño')";
	//FIN PARA COMPARACION ENTRE FECHAS

	//BUSCAR REGISTROS POR PALABRAS *****************************************************************
	for($i = 0; $i <= 100; $i++) {$_SESSION['C'.$i]=NULL;}	
	$_SESSION["NReg"]=0;
	$_SESSION["Totalalbaranes"]=0;
	$Totalalbaranes=0;
	$Hayregistros=0;
	$i=1;				
	$Palabra=$_POST['TPalabraabuscar'];
	$_SESSION["orden"]=$OrdenarPOR;
	
	if ($_POST['cod']<>""){$buscarcodigo=" AND CodigoCliente='".$_POST['cod']."'";}

	if ($_POST['listados']=='todo' || $_POST['listados']=='' || $_POST['listados']==NULL || $_POST['listados']=='undefined'){
		$sql = "SELECT *,SUM(TotalLinea) AS totallinea FROM $fichero WHERE Empresa='$IDEmpresa' AND NumFactura<>''".$buscarcodigo."".$_SESSION['entrefechas']." GROUP BY NSerie,NumFactura".$OrdenarPOR;
	}

	if ($_POST['listados']=='facturasC19'){
		$sql = "SELECT *,SUM(TotalLinea) AS totallinea FROM $fichero WHERE Empresa='$IDEmpresa' AND NumFactura<>'' AND C19='Si'".$buscarcodigo."".$_SESSION['entrefechas']." GROUP BY NSerie,NumFactura".$OrdenarPOR;		
	}

	if ($_POST['listados']=='facturassincobrar'){
		$sql = "SELECT *,SUM(TotalLinea) AS totallinea FROM $fichero WHERE Empresa='$IDEmpresa' AND NumFactura<>'' AND Pagado<>'Si'".$buscarcodigo."".$_SESSION['entrefechas']." GROUP BY NSerie,NumFactura".$OrdenarPOR;		
	}

	if ($_POST['listados']=='facturascobradas'){
		$sql = "SELECT *,SUM(TotalLinea) AS totallinea FROM $fichero WHERE Empresa='$IDEmpresa' AND NumFactura<>'' AND Pagado='Si'".$buscarcodigo."".$_SESSION['entrefechas']." GROUP BY NSerie,NumFactura".$OrdenarPOR;		
	}


echo "salva ".$sql;

	$rs = mysql_query($sql, $con);
	if (mysql_num_rows($rs)>0) {					
		$Hayregistros=1;
		while ($fila=mysql_fetch_array($rs)){ 	
			for($a=0;$a<$ColumnasTabla+1;$a++){
				$_SESSION['C'.$a][$i]=$fila[$a];
			}
			if ($fila[45]=='P' || $fila[45]=='F'){ 			//si es presupuesto o factuta proforma no sumo
			}else{
				$TOTALALBARAN=round($fila['totallinea'],2);
				$_SESSION["Totalalbaranes"]=$_SESSION["Totalalbaranes"]+$TOTALALBARAN;
			}
			$i=$i+1;
		}
	}
	$Orden='';
	//FIN LISTADOS *********************************************************
	
if ($Hayregistros<>0){	
?>
	
	<table class="table">
		<tr class="control-label">
			<th id="C44_Orden" style="cursor: pointer" class="hidden-xs">Serie</span></th>
			<th id="C4_Orden" style="cursor: pointer" class="hidden-xs">N.Fact.</span></th>
			<th id="C48_Orden" style="cursor: pointer; text-align: left" class="hidden-xs" title="Señale en las casillas correspondientes para marcar como pagado el - ALBARAN -, o pulse para ordenar.">Pag.</span></th>
			<th id="C49_Orden" style="cursor: pointer; color:#FF0000; text-align: left" class="hidden-xs" title="Señale en las casillas correspondientes para incluir en un fichero Cuaderno 19 - RECIBOS -, o pulse para ordenar.">C19</span></th>
			<th id="C43_Orden" style="cursor: pointer; text-align: left" class="hidden-xs" width="80">Fecha</span></th>
			<th id="C3_Orden" style="cursor: pointer; text-align: right" class="hidden-xs">Albarán</span></th>
			<th id="C45_Orden" style="cursor: pointer; text-align: right" class="hidden-xs">N.Cliente</span></th>
			<th id="C45_Orden" style="cursor: pointer; text-align: left">Nombre</span></th>
			<th id="C45_Orden" style="cursor: pointer; text-align: left" width="85" class="hidden-xs">Teléfono</span></th>
			<th id="C45_Orden" style="cursor: pointer; text-align: left" class="hidden-xs">Población</span></th>
			<th id="C20_Orden" style="cursor: pointer; text-align: right" align="right">Importe</span></th>
			<th id="C99_Orden" style="cursor: pointer; text-align: right">Saldo</span></th>
			<th colspan="2" style="text-align: right" class="hidden-xs">Total Facturas..:</th>
			<th colspan="1" style="text-align: center"><span class="control-label" style="font-size:14px;color:#009999"><? echo number_format($_SESSION["Totalalbaranes"],2);?></span></th>
		</tr>
		<tbody class="tablaListado">
	
      <?php 
		for($i = 1; $i <= count($_SESSION['C0']); $i++) {
			for($a=0;$a<$ColumnasTabla+1;$a++){
				$value[$a] = $_SESSION['C'.$a][$i];
			}
			$TOTALALBARAN=round($value[51],2);

			echo "<tr class='control-input' codigo='$value[0]' nalbaran='$value[2]' nserie='$value[43]'>";
			echo "<td class=\"hidden-xs clickable\" align=\"left\">$value[43]</td>";
			echo "<td class=\"hidden-xs clickable\" align=\"left\">$value[3]</td>";
			if ($value[47]=='Si'){
				$marcar='CHECKED';
			}else{
				$marcar='UNCHECKED';
			}
			echo "<td class=\"hidden-xs pagado\"><input type=\"checkbox\" name=\"checkbox\" value=$value[47] $marcar/></td>";
			if ($value[49]=='Si'){
				$marcarCuaderno19='CHECKED';
			}else{
				$marcarCuaderno19='UNCHECKED';
			}
			echo "<td class=\"hidden-xs Cuaderno19\" align=\"center\"><input type=\"checkbox\" name=\"checkbox\" value=$value[49] $marcarCuaderno19/></td>";
			$Fecha=SQLToSpanish($value[48]);
			echo "<td class=\"hidden-xs clickable\" align=\"left\">$Fecha</td>";
			echo "<td class=\"hidden-xs clickable\" align=\"right\">$value[2]</td>";
			echo "<td class='hidden-xs clickable' align=\"right\">$value[44]</td>";
			
			$sql = "SELECT * FROM clientes WHERE Empresa='$IDEmpresa' AND CodCliente='$value[44]' ORDER BY CodCliente ASC";
			$rs = mysql_query($sql, $con);
			if (mysql_num_rows($rs)>0) {					
				while ($fila=mysql_fetch_array($rs)){ 	
					$NombreCliente=$fila[2]." ".$fila[62];
					$_SESSION['NombreCliente'][$i]=$NombreCliente;
					$TelefonoCliente=$fila[8];
					$_SESSION['TelefonoCliente'][$i]=$fila[8];
					$PoblacionCliente=$fila[5];
					$_SESSION['PoblacionCliente'][$i]=$fila[5];
					$SaldoCliente=$fila[19];
					$_SESSION['SaldoCliente'][$i]=$SaldoCliente;
				}
			}else{
				$NombreCliente='';
				$_SESSION['NombreCliente'][$i]='';
				$TelefonoCliente='';
				$_SESSION['TelefonoCliente'][$i]='';
				$PoblacionCliente='';
				$_SESSION['PoblacionCliente'][$i]='';
				$SaldoCliente=0;
				$_SESSION['SaldoCliente'][$i]=0;
			}
			
			echo "<td class='clickable' align=\"left\"><b>$value[50]</b></td>";
			echo "<td class=\"hidden-xs clickable\" align=\"left\">$TelefonoCliente</td>";
			echo "<td class=\"hidden-xs clickable\" align=\"left\">$PoblacionCliente</td>";
			$importe1=$TOTALALBARAN;
			$importe=number_format($importe1,2);
			echo "<td class='clickable' align=\"right\"><b>$importe</b></td>";
			$SaldoClienteF=number_format($SaldoCliente,2);
			echo "<td class=\"clickable\" align=\"right\">$SaldoClienteF</td>";
			echo '<td class=\"hidden-xs\" align="center" width="100">';
			echo '<td class=\"hidden-xs\" style="text-align: center" title="Imprimir Factura"><a target="_blank" href="facturapdf.php?numAlb='.$value[2].'"><i class="fa fa-print hidden-xs"></i></a></td>'; //Factura,ALBARAN
			if($_SESSION['privilegios']>1) echo '<td align="center" width="130">';
			if($_SESSION['privilegios']>1) echo '<a class="bt_editar" cod="'.$fila[0].'" nombre="'.$fila[2].'"><span class="glyphicon glyphicon-pencil hidden-xs" title="Editar registro"></span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			if($_SESSION['privilegios']>2) echo '<a class="bt_eliminar" cod="'.$fila[0].'" nombre="'.$fila[2].'"><span class="glyphicon glyphicon-remove hidden-xs" title="Eliminar registro"></span></a>';
			if($_SESSION['privilegios']>1) echo '</td>';
			echo "</tr>";
			$Total=$Total+$TOTALALBARAN;
			$TotalSaldo=$TotalSaldo+$SaldoCliente;
		}
		$TotalF=number_format($Total,2);
		$TotalSaldoF=number_format($TotalSaldo,2);
		echo "<tr class='control-input'>";
		echo "<td class=\"hidden-xs clickable\" align=\"right\"></td>";
		echo "<td class=\"hidden-xs clickable\" align=\"right\"></td>";
		echo "<td class=\"hidden-xs clickable\" align=\"right\"></td>";
		echo "<td colspan=\"7\" class=\"hidden-xs clickable\" align=\"right\"></td>";
		echo "<td align=\"left\"><b>Total .... </b></td>";
		echo "<td  align=\"right\"><b><span class=\"control-label\" style=\"font-size:14px;color:#009999\">$TotalF</b></td>";
		echo "<td  align=\"right\"><b><span class=\"control-label\" style=\"font-size:14px;color:#999999\">$TotalSaldoF</b></td>";
		$Total=0;
		$TotalSaldo=0;

	?>
		<tbody>
    </table>
<?php 
}else{ ?>

	  <h4 style="padding-top: 12px; padding-bottom: 5px" align="center">No hay datos entre estas fechas.</h4>

<?
}
	exit();
}

include("cabecera.html");
include("$ficheroHTML");
include("pie.html");
?>

<script src="Miscelania.js"></script>


<script type="text/javascript">
	var modificado = false;
	$(document).ready(function () {
		fichero = '<?php echo $ficheroPHP; ?>';
		colImagen  = "";
		ColumnasTabla='<?php echo $ColumnasTabla; ?>';
		foco = "C2";
		cambiaListado("IDEM");
		nuevo = true;
		modificar=false;
		rellenayCargaDatosClientes(); 		//para buscar clientes como con autocomplete
		rellenayCargaDatosArticulos();		//para buscar articulos como con autocomplete
		//Nuevo
		$("#bt_nuevo").click(function () {
			nuevo = true;
			controlarNuevo();
			$("#modal_titulo").html("Añadir - "+ '<?php echo $TituloCabecera; ?>');
			$("#C42").val(moment().format('DD-MM-YYYY'));
			$("#C48").val(moment().format('DD-MM-YYYY'));
			$("#C2").val(cargaCodigoAlbaranFactura());
			$("#C3").val(cargaCodigoFactura());
			$("#C46").val('CONTADO');
			seleccionaInput('C3');
			
			$(".lineasPresupuesto tbody tr:not(:last)").each(function() {
				$(this).remove();
			});
		});
	
		//Modificar
		$(document).on("click", ".bt_editar", function(){
			nuevo = false;
			$("#modal_titulo").html("Modificar - "+ '<?php echo $TituloCabecera; ?>');
			$("input,textarea").attr("placeholder","").css("border","3px solid #ebe6e2")
			cargarDatos($(this).parent().parent().attr("codigo"))
			if(!$(this).hasClass("bt_editarLinea")) cargarlineasalbaran($(this).parent().parent().attr("nalbaran"),$(this).parent().parent().attr("nserie"));
			seleccionaInput('C4');
		});


		//Modificar linea
		$(document).on("click", ".bt_editarLinea", function(){
			$(".filaPresupuesto").each(function(){
				$(this).removeClass("editando");
			});
			$(this).parent().parent().addClass("editando");
			cantidad = $(this).parent().parent().attr("cantidad");
			importe = $(this).parent().parent().attr("importe");
			tdescuento = $(this).parent().parent().attr("tdescuento");
			descuento = $(this).parent().parent().attr("descuento");
			tiva = $(this).parent().parent().attr("tiva");
			total = $(this).parent().parent().attr("total");
			
			$("#C4").val( $(this).parent().parent().attr("C4") );
			$("#C6").val( $(this).parent().parent().attr("C6") );
			$("#C16").val( $(this).parent().parent().attr("C16") );
			$("#C17").val( $(this).parent().parent().attr("C17") );
			$("#C7").val( $(this).parent().parent().attr("C7") );
			$("#C29").val( $(this).parent().parent().attr("C29") );
			$("#C10").val( $(this).parent().parent().attr("C10") );
			$("#C12").val( $(this).parent().parent().attr("C12") );
			$("#C19").val( $(this).parent().parent().attr("C19") );
			calculaTotalLinea();		

			seleccionaInput('C4');
		});
		

		//Eliminar
		$(document).on("click", ".bt_eliminar", function(){
			Borrar($(this).parent().parent().attr("nalbaran"),$(this).parent().parent().attr("nserie"))
			actualizaSelect18();
		});
	
		//Para buscar palabras
		$("#TPalabraabuscar").keypress(function(e) {
			if(e.which==13 || $("#TPalabraabuscar").val().length % 3 === 0) cambiaListado();
		});
		$("#TPalabraabuscar").keyup(function(e){
			if(e.keyCode == 8 && $("#TPalabraabuscar").val().length % 3 === 0) cambiaListado();
		});
		

		//para ordenar
		<?php for($i = 0; $i <= $ColumnasTabla; $i++) { 
			echo "$(document).on('click', '#C".$i."_Orden', function(){
				cambiaListado('C".$i."');
			});";	
		} ?>
		


		//BORRAR LINEA
		$(document).on("click", ".bt_eliminarLinea", function(){
			$(this).parent().parent().remove();
			calculaTotal();
		});
		//FIN DE BORRAR LINEA


	});	
	// Fin document.ready






	
	function asignaClickTr(){
		for(var i=1;i<$("tr").size();i++){
			$("tr:eq("+i+")").children(".clickable").click(function(){
				$("#modal_titulo").html("Modificar - "+ '<?php echo $TituloCabecera; ?>');
				$("input,textarea").attr("placeholder","").css("border","3px solid #ebe6e2")
				actualizaSelect18();
				cargarDatos($(this).parent().attr("codigo"))
				cargarlineasalbaran($(this).parent().attr("nalbaran"),$(this).parent().attr("nserie"));
				seleccionaInput('C4');
			})
		}
	}











	//controlar si existe el código del ALBARAN 
	$("#C2").on("change",function (e) {
		comprobarCodigoAlbaran($("#C2").val(),$("#C43").val());
		seleccionaInput("C2");
	});


	//controlar si existe el código de la FACTURA  
	$("#C3").on("change",function (e) {
		comprobarCodigoFactura($("#C3").val(),$("#C43").val());
		seleccionaInput("C3");
	});


	//buscar el ultimo código de FACTURA según la serie  
	$("#C43").on("keyup",function (e) {
		$.post(fichero,{opcion:'BuscarCodigoFacturaySerie',Codigo:$("#C43").val()},function(data){
			$("#C3").val(Number(data[3])+1);
	   	},"json");
		seleccionaInput("C44");
	});




	 
	//para CARGAR EL NOMBRE clientes
	$('#C44').on('keyup', function() {
		cargarCliente($("#C44").val());
	});	
	//para BUSCAR EL NOMBRE clientes
	$('#NombreCliente').on('change', function() {
		seleccionaInput("C4");
	});	

	//para cambiar el select de clientes
	$('#NombreCliente').on('change', function() {
		cargarCliente($("#NombreCliente option:selected").attr("cod"));
		seleccionaInput("C4");
	});	
	function cargarCliente(cod){
		$("#NombreClien").val("");
		$("#NombreCliente").val("");
		$.post(fichero,{opcion:'Cliente',Codigo:cod},function(data){
			$("#C44").val(data[1]);
			$("#NombreCliente option[cod="+ cod +"]").prop("selected",true);
			$("#NombreClien").val(data[2]+' '+data[62]);
			$("#DireccionCliente").val(data[4]);
			$("#PoblacionCliente").val(data[5]);
			$("#ProvinciaCliente").val(data[6]);
			$("#CPCliente").val(data[7]);
			$("#C44").attr("title",'Nombre..:'+data[2] + "<br>" + "<br>" + 'Telefono.:'+ data[8] + " - " + data[9]+ "<br>" + "<br>" + 'DNI.....:'+data[3] + "<br>" + "<br>" + 'Direcc..:' + data[4] + "<br>" + "<br>" + 'Poblac..:' + data[5] + "<br>" + "<br>" +'Recargo.:' + data[15] + "<br>" + "<br>" + 'IRPF.:'+data[63] + "<br>");
			$("#NombreCliente").attr("title",'Nombre..:'+data[2] + "<br>" + "<br>" + 'Telefono.:'+ data[8] + " - " + data[9]+ "<br>" + "<br>" + 'DNI.....:'+data[3] + "<br>" + "<br>" + 'Direcc..:' + data[4] + "<br>" + "<br>" + 'Poblac..:' + data[5] + "<br>" + "<br>" +'Recargo.:' + data[15] + "<br>" + "<br>" + 'IRPF.:'+data[63] + "<br>");
	   },"json");
	}






	//para cambiar el select de articulos
	$('#C4').on('keyup', function() {
		cargarArticulo($("#C4").val());
		modificado=true;
	});	
	//select articulos
	$('#Clineas4').on('change', function() {
		cargarArticulo($("#Clineas4 option:selected").attr("cod"),true);
		seleccionaInput("C6");
		modificado=true;
	});	

	//cargar Articulo
	function cargarArticulo(cod){
		$("#C6").val("");
		$("#C29").val("0");
		$("#C12").val("21");
		$.post(fichero,{opcion:'cargarArticulo',codigo:cod},function(data){
			$("#C4").val(data[1]);            				//CodArticulo
			$("#Clineas4").val(data[1]);					//CodArticulo
			$("#C6").val(data[3]);							//NombreArticulo
			$("#C7").val("1");								//Cantidad
			$("#C29").val(data[6]);							//PrecioVenta
			$("#C12").val(data[32]);						//TipoIva
			var importelinea=Number($("#C7").val()*$("#C29").val()-$("#C10").val());
			var ivalinea=Number(importelinea*$("#C12").val()/100);
			var totallinea=Number(importelinea+ivalinea);
			$("#C19").val(Number(importelinea+ivalinea).toFixed(2));
	   },"json");
	}


	//para calcular la linea desde el total.
	$('#C19').on('keyup', function() {
		if($("#C7").val()==''){
			$("#C7").val('1');
		}
		
		$("#C12").val('21');
		
		if($("#C12").val()<10){
			var divisor='1.0'+$("#C12").val();
		}else{
			var divisor='1.'+$("#C12").val();
		}
		var cantidad=Number($("#C7").val());
		var total=Number($('#C19').val());
		var precio=Number(((total/cantidad)/divisor)).toFixed(4);
		$("#C29").val(precio);
	});	





	//Imprime Albarán
	$(document).on("click", "#bt_imprimirAlbaran", function(){
		guardarAlbaran();
		var win = window.open("facturapdf.php?numAlb=" + $("#C2").val() + "&serAlb=" + $("#C43").val(), '_blank');
		win.focus();
	})	


	//para calculo de la linea del albaran
	$("#C7, #C29, #C10").on("keyup",function (e) {
		var importelinea=Number($("#C7").val()*$("#C29").val()-$("#C10").val());
		var ivalinea=Number(importelinea*$("#C12").val()/100);
		$("#C19").val(Number(importelinea+ivalinea).toFixed(2));
		modificado = true;
	});	


	//para calculo de la linea del albaran
	$('#C9').on('change', function() {
		var importelinea=Number($("#C7").val()*$("#C29").val());
		switch($("#C9 option:selected").val()){
			case "1":
				var descuento=$("#C10").val();
				var calculo=importelinea*descuento/100;
				$("#C10").val(Number(calculo.toFixed(2)));
				break;
			case "2":
				break;
			default:
		}
		var importelinea=Number($("#C7").val()*$("#C29").val()-$("#C10").val());
		var ivalinea=Number(importelinea*$("#C12").val()/100);
		$("#C19").val(Number(importelinea+ivalinea).toFixed(2));
		modificado = true;
	});	

	//para calculo de la linea del albaran
	$('#C12').on('change', function() {
		var importelinea=Number($("#C7").val()*$("#C29").val()-$("#C10").val());
		var ivalinea=Number(importelinea*$("#C12").val()/100);
		$("#C19").val(Number(importelinea+ivalinea).toFixed(2));
		modificado = true;
	});	
	

	function cargarlineasalbaran(codigo,nserie){
		$.post(fichero,{opcion:'cargarlineasalbaran',codigo:codigo,nserie:nserie},function(data){
			$(".lineasPresupuesto tbody tr:not(:last)").each(function() {
				$(this).remove();
			});
			$(".lineasPresupuesto tbody tr:first").before(data);
			calculaTotal();
		})
	}


	
	function capturaRecargo(CodCliente,tipoiva){
		var recargo = 0;
		$.ajax({
			type: "POST",
			async: false,   
			url: "albaranesclientes.php",
			dataType: "json",
			data: {opcion:"recargoEquivalencia",CodCliente:CodCliente,tipoiva:tipoiva},
			success:  function(respuesta){
				recargo=respuesta;
			}
		})
		return recargo;
	}

	//AÑADE LINEA
	$(document).on("click", "#bt_nuevaLinea", function(){
		AñadirArticulo();
		anadeLinea($(".editando").length > 0);
	})		
	//AÑADE LINEA
	$('#C19').on('keypress', function() {
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if (keyCode == 13) {
			seleccionaInput("C4");
			AñadirArticulo();
			anadeLinea($(".editando").length > 0);
		}
	})		

	function anadeLinea(editando){
		resultado="";
		if($("#C6").val()!="") {								//concepto
			$("#C6").css("border", "1px solid #CCC");
		} else {
			$("#C6").css("border", "1px solid red");
			resultado = "Indique un concepto";	
		}
//		if($("#C16").val()!="") {								//lote
//			$("#C16").css("border", "1px solid #CCC");
//		} else {
//			$("#C16").css("border", "1px solid red");
//			resultado = "Indique un Lote";	
//		}
		if($("#C7").val()!="") {								//cantidad
			$("#C7").css("border", "1px solid #CCC");
		} else {
			$("#C7").css("border", "1px solid red");
			resultado = "Indique una cantidad";	
		}
		if($("#C19").val()!="") {
			$("#C19").css("border", "1px solid #CCC");
		} else {
			$("#C19").css("border", "1px solid red");
			resultado = "El total tiene que ser un número.";	
		}
		if($("#C29").val()!="") {								//precio
			$("#C29").css("border", "1px solid #CCC");
		} else {
			$("#C29").css("border", "1px solid red");
			resultado = "Indique un precio";	
		}
		if($("#C4").val()!="") {
			$("#C4").css("border", "1px solid #CCC");
		} else {
			$("#C4").css("border", "1px solid red");
			resultado = "Seleccione Artículo";
		}
		if(resultado=="") {
			var recargo = capturaRecargo($("#C44").val(),$("#C12").val());
			var C35 = recargo['RecargoEquivalencia'];
			var C36 = recargo['IRPF'];
			var recargo=Number(C35);
			var importerecargolinea=(Number($("#C7").val())*Number($("#C29").val())-Number($("#C10").val()))*recargo/100;
			var IRPF=Number(C36);
//			var importeIRPFlinea=($("#C19").val()*IRPF)/100;
			var importeIRPFlinea=(((Number($("#C7").val())*Number($("#C29").val()))-Number($("#C10").val()))*IRPF)/100;

			seleccionaInput("C4");
			tr0 = "<tr class='filaPresupuesto'";
			tr1 = "C4='" + $("#C4").val() + "'";												//Codigo Articulo
			tr2 = "C6='" + $("#C6").val() + "'";												//Concepto
			tr3 = "C16='" + $("#C16").val() + "'";												//Lote
			tr4 = "C17='" + $("#C17").val() + "'";												//Bultos
			tr5 = "C7='" + $("#C7").val() + "'";												//Cantidad
			tr6 = "C29='" + $("#C29").val() + "'";												//Precio
			tr7 = "C10='" + $("#C10").val() + "'";												//Importe Descuento
			tr8 = "C12='" + $("#C12 option:selected").val() + "'";								//Tipo de Iva
			$iva=((($("#C7").val()*$("#C29").val())-$("#C10").val())*$("#C12 option:selected").val())/100;
			tr9 = "C30='" + $iva + "'";
			tr10 = "C19='" + $("#C19").val() + "'";												//Total
			tr11 = "C35='" + importerecargolinea + "'";											//Recargo
			tr12 = "C36='" + importeIRPFlinea + "'";											//ImporteIRPF
			tr13 = "C37='" + $("#C37").val() + "'";												//Trabajador
			tr14 = ">";

			col1 = "<td align='center'>" + $("#C4").val()+ "</td>";								//Codigo Articulo
			col2 = "<td>" + $("#C6").val() + "</td>";											//Concepto
			col3 = "<td align='center'>" + $("#C16").val() + "</td>";							//Lote
			col4 = "<td align='center'>" + $("#C17").val() + "</td>";							//Bultos
			col5 = "<td align='center'>" + $("#C7").val() + "</td>";							//Cantidad
			col6 = "<td align='right'>" + Number($("#C29").val()).toFixed(2) + "</td>";			//Precio
			var desc = "";
			col7 = "<td colspan='2' align='right'>" + Number($("#C10").val()) + "</td>";
			col8 = "<td align='center'>" + $("#C12 option:selected").val() + "% = " + (Number($("#C7").val()*Number($("#C29").val())-Number($("#C10").val()))*$("#C12 option:selected").val()/100).toFixed(2) + " €</td>";
			col9 = "<td align='right'>" + Number($("#C19").val()).toFixed(2) + "</td>";
			col10 = "<td align='right'>" + Number($("#C37").val()).toFixed(0) + "</td>";
			col11 = "<td align='center'><a class='bt_editarLinea' ><span class='glyphicon glyphicon-pencil' style='cursor:pointer;' title='Modificar línea'></span></a></td>";
			col12 = "<td align='center'><a class='bt_eliminarLinea'><span class='glyphicon glyphicon-remove' style='cursor:pointer;' title='Eliminar línea'></span></a></td>";
			
			filaInsertar = tr0 + tr1 + tr2 + tr3 + tr4 + tr5 + tr6 + tr7 + tr8 + tr9 + tr10 + tr11 + tr12 + tr13 + tr14 + col1 + col2 + col3 + col4 + col5 + col6 + col7 + col8 + col9 + col10 + col11 + col12 + "</tr>" + "</tr>";
			
			if(!editando){
				$(".lineasPresupuesto tr:last").before(filaInsertar);
			} else {
				$(".editando").replaceWith(filaInsertar)
			}
			
			calculaTotal();
		}else{
			$("#res").html(resultado);
			$("#modalresultado").modal("show");
		}	
		modificado = false;
	}
	//FIN DE AÑADE LINEA
	






	//CALCULA TOTAL ALBARAN
	function calculaTotal(){
//		modificadoReg = true;
		var totalalbaran = 0;
		var totDescuento = 0;
		totImporte = [];
		totIVA = [];
		totRecargo = [];
		tipoRecargo = [];
		totIRPF = [];
		tipoIRPF = [];
		totTotal = [];
		$(".filaPresupuesto").each(function() {
			var cantidad = Number($(this).attr("C7"));
			var precio = Number($(this).attr("C29"));
			var tdescuento = Number($(this).attr("tdescuento"));
			var descuento = Number($(this).attr("C10"));
			var tiva = Number($(this).attr("C12"));
			var iva = Number($(this).attr("C30"));
			var total = Number($(this).attr("C19"));
			var base = total-iva;
			if(totImporte[tiva]==undefined) totImporte[tiva]=0;
			if(totRecargo[tiva]==undefined) totRecargo[tiva]=0;
			if(totIRPF[tiva]==undefined) totIRPF[tiva]=0;
			if(totIVA[tiva]==undefined) totIVA[tiva]=0;
			if(totTotal[tiva]==undefined) totTotal[tiva]=0;
			if (base==0){
				var recargo = 0;
				var IRPF = 0;
			}else{
				var recargo = Number(($(this).attr("C35")*100)/(base-descuento));
				var IRPF = (Number($(this).attr("C36"))*100)/(base-descuento);
				tipoRecargo[tiva] = recargo;
				tipoIRPF[tiva] = IRPF;
			}
			totImporte[tiva] += (base-descuento);
			var importerecargo = (base)*recargo/100;
			var importeirpf = ((base-descuento)*IRPF)/100;
			totRecargo[tiva] += importerecargo;
			totIRPF[tiva] += ((base-descuento)*IRPF)/100;
			totIVA[tiva] += iva;
			totTotal[tiva] += total + (importerecargo)-(importeirpf);
			totalalbaran += total + (importerecargo)-(importeirpf);
		});
		
		$(".tablaTotales tr:not(:first)").remove();
		$(totImporte).each(function(index, value) {
			if (totImporte[index]!=undefined){
				tr0 = "<tr>";
				col0 = "<td style='text-align:right'>" + totImporte[index].toFixed(2) + "</td>";
				col1 = "<td style='text-align:right'>" + index + "%</td>";
				col2 = "<td style='text-align:right'>" + totIVA[index].toFixed(2) + "</td>";
				col3 = "<td style='text-align:right'>" + tipoRecargo[index].toFixed(2) + "%</td>";
				col4 = "<td style='text-align:right'>" + totRecargo[index].toFixed(2) + "</td>";
				col5 = "<td style='text-align:right'>" + tipoIRPF[index].toFixed(2) + "%</td>";
				col6 = "<td style='text-align:right'>" + totIRPF[index].toFixed(2) + "</td>";
				col7 = "<td style='text-align:right'>" + '' + "</td>";
				col8 = "<td style='text-align:right'>" + totTotal[index].toFixed(2) + "</td>";
				$(".tablaTotales tbody").append(tr0 + col0 + col1 + col2 + col3 + col4 + col5 + col6 + col7 + col8 + "</tr>");
			}
		});	
				tr0 = "<tr>";
				col0 = "<td colspan='5'>" + '' + "</td>";
				col1 = "<td colspan='3' style='text-align:right;'><h5><b>" + 'Total Albarán..:' + "</td>";
				col2 = "<td style='text-align:right;'><h4><b>" + totalalbaran.toFixed(2) + "</td>";
				$(".tablaTotales tbody").append(tr0 + col0 + col1 + col2 + "</tr>");
		modificado=false;
	}



	

	//GUARDAR ALBARAN
	$(document).on("click", "#bt_guardarAlbaran", function(){
		guardarAlbaran();
	})	
	
	
	
	$(document).on("click", "#confirmNo", function(){
		modificado=false;
		guardarAlbaran();
		$("#C4,#C6,#C16,#C17,#C7,#C29,#C9,#C10,#C12,#C19").val("");
	});
		
	function guardarAlbaran(){
		var resultado = "";
		var NSerie = $("#C43").val();
		var CodigoAlbaran = $("#C2").val();
		var Fecha=$("#C42").val();
		var CodigoCliente=$("#C44").val();
		var Tipo=$("#C45").val();
		var NumFactura=$("#C3").val();
		var Formadepago=$("#C46").val();
		var filas = crearJSON();
		
		if(modificado==true) {
			$("#modalYesNo").modal("show");
			resultado="El guardado se ha pausado";
		}
		
		if(resultado==""){
			$.post(fichero,{opcion:26,NSerie:$("#C43").val(),CodigoAlbaran:$("#C2").val(),Fecha:$("#C42").val(),FechaFactura:$("#C48").val(),CodigoCliente:$("#C44").val(),NombreClien:$("#NombreClien").val(),Tipo:$("#C45").val(),NumFactura:$("#C3").val(),Formadepago:$("#C46").val(),filas:filas},function(data){
				if(data['resultado']==true){
					cambiaListado();
					modificadoReg = false
					actualizaSelect18();
					//$("#modalNuevo").modal("show");
					$('#mensaje').jGrowl('Factura Grabada',{theme:'verde'})
				}else{
					if($("#C2").val()=="") {
						$("#C2").css("border", "1px solid red");
					}
					if($("#C42").val()=="") {
						$("#C42").css("border", "1px solid red");
					}
					if($("#C44").val()=="") {
						$("#C44").css("border", "1px solid red");
					}
					$('#mensaje').jGrowl(data['resultado'],{theme:'rojo'})
				}
			},"json");	
		} else {
			if(modificado!=true) $('#mensaje').jGrowl(resultado,{theme:'rojo'})
		}
	}
	
	
	
	
	function crearJSON() {
		jsonObj = [];
		$(".filaPresupuesto").each(function() {
			item = {}
			item ["codArticulo"] = $(this).attr("C4");
			item ["concepto"] = $(this).attr("C6");
			item ["lote"] = $(this).attr("C16");
			item ["bultos"] = $(this).attr("C17");
			item ["cantidad"] = $(this).attr("C7");
			item ["precio"] = $(this).attr("C29");
			item ["tdescuento"] = $(this).attr("C9");
			item ["descuento"] = $(this).attr("C10");
			item ["tiva"] = $(this).attr("C12");
			item ["iva"] = ($(this).attr("C7")*$(this).attr("C29")-$(this).attr("C10"))*$(this).attr("C12")/100;
			item ["total"] = $(this).attr("C19");
			item ["ImporteRecargo"] = $(this).attr("C35");
			item ["ImporteIRPF"] = $(this).attr("C36");
			item ["Trabajador"] = $(this).attr("C37");
	
			jsonObj.push(item);
		});
	
		return jsonObj;
	
	}
	



	//FACTURAR ALBARAN
	$(document).on("click", "#bt_Facturar", function(){
		guardarAlbaran();
		Facturar($("#C2").val(),$("#serieFactura").val())
	})	
	
	//Pagado
	$(document).on("click", ".pagado", function(){
		Pagado($(this).parent().attr("nalbaran"),$(this).parent().attr("nserie"))
		cambiaListado("IDEM",'false');
	});

	//Cuaderno19
	$(document).on("click", ".Cuaderno19", function(){
		Cuaderno19($(this).parent().attr("nalbaran"),$(this).parent().attr("nserie"))
		cambiaListado("IDEM",'false');
	});



//Hacer Cuaderno19 
function HacerCuaderno19(cod,nserie){
	$.post(fichero,{opcion:'HacerCuaderno19',C0:cod,C43:nserie},function(data){
		$("#res").html("Cuaderno 19 Generado <br><br><br><center><a class='btn btn-success' href=<?php echo $_SESSION['NombreFichero']; ?> download=<?php echo $_SESSION['NombreFichero']; ?>>DESCARGAR FICHERO:  <?php echo $_SESSION['NombreFichero']; ?></a></center>");
		$("#modalresultado").modal("show");
		cambiaListado("IDEM",'false');
		ListarCuaderno19();
	})
	function ListarCuaderno19() {
		var win = window.open(fichero+"?exportar=ListarCuaderno19", '_blank');
		win.focus();
	}
}





//Añadir Articulo
function AñadirArticulo() {
	$.post(fichero,{opcion:8,C0:$("#C4").val()},function(data){			//compruebo si existe el Artículo
		if(data==true){
			$("#C4,#C6,#C16,#C17,#C7,#C29,#C9,#C10,#C12,#C19,#Existencias").val("");
//			$("#mensaje").jGrowl("Artículo Existente",{theme:'verde'});
		}else{
			$("#CodArticulo").val($("#C4").val());						//si no existe el articulo lo doy de alta mostrando el modal de artículos
			$("#NombreArticulo").val($("#C6").val());
			if($("#Familia").val()==''){
				$("#Familia").val('Ventas');
			}
			$("#PrecioVenta").val($("#C29").val());
			MargenArticulos='<?php echo $_SESSION['datEmp'][23]; ?>';
			var preciocompra=(Number($("#C29").val())/((MargenArticulos/100)+1)).toFixed(4);
			$("#PrecioCompra").val(preciocompra);
			$Existencias=$("#C7").val();

			$("#grabarArticulo").off("click");
			$("#modalArticulo").modal("show");
			$("#grabarArticulo").click(function(){
				$.post(fichero,{opcion:9,C0:$("#C4").val(),Nombre:$("#C6").val(),Familia:$("#Familia").val(),PrecioCompra:$("#PrecioCompra").val(),PrecioVenta:$("#PrecioVenta").val(),TipoIva:$("#C12").val(),Existencias:$Existencias},function(data){
					if(data==true){
						$("#mensaje").jGrowl("Articulo Grabado",{theme:'verde'});
						$("#modalArticulo").modal("hide");
						$("#C4,#C6,#C16,#C17,#C7,#C29,#C9,#C10,#C12,#C19,#Existencias").val("");
						$("#CodArticulo,#NombreArticulo,#Familia,#PrecioVenta,#PrecioCompra").val("");
					}else{
						$('#mensaje').jGrowl("Error al grabar. Nombre Artículo erróneo",{theme:'rojo'});
					}
				},"json")
			});
		}
	},"json")
}


//Añadir cliente si no existe
$('#NombreClien').on('blur', function() {
	AñadirCliente($("#C44").val());
	seleccionaInput("C4");
});	


//Añadir Cliente
function AñadirCliente() {
	$.post(fichero,{opcion:6,C0:$("#C44").val()},function(data){			//compruebo si existe el Artículo
		if(data==true){
//			$("#mensaje").jGrowl("Cliente Existente",{theme:'verde'});
		}else{
			$("#CodCliente").val($("#C44").val());						//si no existe el articulo lo doy de alta mostrando el modal de artículos
			$("#NombreC").val($("#NombreClien").val());
			$("#DNI").val($("#DNI").val());
			$("#Direccion").val($("#Direccion").val());
			$("#Poblacion").val($("#Poblacion").val());
			$("#Provincia").val($("#Provincia").val());
			$("#CodPostal").val($("#CodPostal").val());
			$("#Telefono").val($("#Telefono").val());
			$("#Telefono2").val($("#Telefono2").val());
			$("#E_Mail").val($("#E_Mail").val());
			$("#NumerodeCuenta").val($("#NumerodeCuenta").val());
			$("#RecargodeEquivalencia").val($("#RecargodeEquivalencia").val());
			$("#IRPF").val($("#IRPF").val());
			
			$("#grabarCliente").off("click");
			$("#modalCliente").modal("show");
			$("#grabarCliente").click(function(){
				$.post(fichero,{opcion:7,C0:$("#C44").val(),Nombre:$("#NombreC").val(),DNI:$("#DNI").val(),Direccion:$("#Direccion").val(),Poblacion:$("#Poblacion").val(),Provincia:$("#Provincia").val(),CodPostal:$("#CodPostal").val(),Telefono:$("#Telefono").val(),Telefono2:$("#Telefono2").val(),E_Mail:$("#E_Mail").val(),NumdeCuenta:$("#NumerodeCuenta").val(),RecargodeEquivalencia:$("#RecargodeEquivalencia").val(),IRPF:$("#IRPF").val()},function(data){
					if(data==true){
						$("#mensaje").jGrowl("Cliente Grabado",{theme:'verde'});
						$("#modalCliente").modal("hide");
//						$("#C4,#C6,#C16,#C17,#C7,#C29,#C9,#C10,#C12,#C19,#Existencias").val("");
						$("#CodCliente,#NombreC,#DNI,#Direccion,#Poblacion,#Provincia,#CodPostal,#Telefono,#Telefono2,#E_Mail,#NumerodeCuenta,#RecargodeEquivalencia,#IRPF").val("");
						seleccionaInput("C4");
					}else{
						$('#mensaje').jGrowl("Error al grabar. Nombre Cliente erróneo",{theme:'rojo'});
					}
				},"json")
			});
		}
	},"json")
}


	//seleccionar provincia y poblacion
	$('#provincia').on('change', function() {
		cargaPoblaciones("provincia","poblacion",true);	//provincia,municipio
		$("#Provincia").val($("#provincia").val())	
	});	

	//seleccionar  poblacion
	$('#poblacion').on('change', function() {
		$("#Poblacion").val($("#poblacion").val())	
	});	









</script>
