<?php
session_start();
require_once('includes/configuracion.php');
require_once('includes/funciones.php');



$fichero='componente';
$ficheroPHP="percusion.php";
$ficheroHTML="percusion.html";

$TituloCabecera='Percusion';
$_SESSION['Fichero']=$fichero;
$ficheromayusculas=strtoupper($_SESSION['Fichero']);
$_SESSION['Titulo']="Seccion de percusion";

$IDBanda= $_SESSION['idBanda'];
$nombreuser=$_SESSION['nombre'];
$banda =$_SESSION['nombreBanda'];
$seccion = $_SESSION['seccion'];





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




//NUEVO *********************************************************************************
if ($opcion==1){
	for($i=0;$i<$ColumnasTabla;$i++){
		$C[$i]=trim($_POST['C'.$i]);
		if($C[$i]=="null" || $C[$i]=="undefined") $C[$i]="";
	}
	$C[8]=$_SESSION['idBanda'];
	$C[5]="percusion";
	//VALIDAR DATOS FORMULARIO
	if ($C[1]==""){
		$Mensaje="Introduzca Nombre";
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
		$cadena = "(";
		for($i=0;$i<$ColumnasTabla;$i++){
			$cadena.="'".$C[$i]."',";
		}
		$cadena = substr($cadena, 0, -1);
		$cadena.=")";
		$sql = "INSERT INTO $fichero VALUES	$cadena";
		$rs = mysql_query($sql, $con) or die(mysql_error());;		
		$result=array();
		if ($rs==true){ 
			$result['resultado'] = true;
			registraLog($fichero,$TituloCabecera,"1","",$C[2],"",$sql);
		}else{
			$result['resultado'] = false;
		} 
	}else{
		$result['resultado']=$Mensaje.$errores;
	}
	echo json_encode($result);
	exit();
}



//ELIMINAR******************************************************************************
if ($opcion==3){
	$id=$_POST['C0'];
	$sql="SELECT logo,Codigo,NombreProducto FROM $fichero WHERE id='$id'";
	$rs = mysql_query($sql, $con);
	$fila=mysql_fetch_array($rs);
	borraImagen($fila[0]);
	
	$sql = "DELETE from $fichero WHERE id='$id'";
	$rs = mysql_query($sql, $con);	
	if ($rs==true){ 
		echo "true";
		registraLog($fichero,$TituloCabecera,"3",$fila[1],$fila[2],"",$sql);
		return;
	}else{
		echo "false";
		return;
	} 
}


//ELIMINAR FOTO ******************************************************************************
if ($opcion==4){
	$id=$_POST['C0'];
	$sql="SELECT logo,Codigo,NombreProducto FROM $fichero WHERE id='$id'";
	$rs = mysql_query($sql, $con);
	$fila=mysql_fetch_row($rs);
	borraImagen($fila[0]);
	
	$sql="UPDATE $fichero SET logo='' WHERE id='$id'";
	$rs = mysql_query($sql, $con);
	if ($rs==true){ 
		echo "true";
		registraLog($fichero,$TituloCabecera,"5",$fila[1],$fila[2],"",$sql);
		return;
	}else{
		echo "false";
		return;
	} 
}





//CARGAR DATOS
if($opcion==15){
	$id=$_POST['C0'];
	$sql = "SELECT * FROM $fichero WHERE ".$_SESSION['NombreCampo'][0]."='$id'";
	$rs = mysql_query($sql, $con);
	$mandar="";
	while ($fila=mysql_fetch_row($rs)){ 					
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
	$C[8]=$IDBanda;
	$C[5]="percusion";
	//VALIDAR DATOS FORMULARIO
	if ($C[1]==""){
		$Mensaje="Introduzca Nombre";
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
			$fila=mysql_fetch_row($rs);
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
				if($i!=$colImagen){             //para no tocar la imagen
					$sql .= $_SESSION['NombreCampo'][$i]."='".$C[$i]."', ";
				}
			}
			$sql = substr($sql, 0, -2);
			$sql .= " WHERE ".$_SESSION['NombreCampo'][0]."='$C[0]'";
		}
		$rs = mysql_query($sql, $con);
		$result=array();
		if ($rs==true){ 
			$result['resultado'] = true;
			registraLog($fichero,$TituloCabecera,"2",$C[1],$C[2],"",$sql);
		}else{
			$result['resultado'] = false;
		} 
	}else{
		$result['resultado']=$Mensaje.$errores;
	}
	echo json_encode($result);
	exit();
}


$opcionP = $_POST['opcion'];
if($opcionP==5){
	
	$estado=$_POST['color'];
	
	$sql="SELECT id FROM privilegios WHERE nombre='".$_POST['estado']."'";
	$rs = mysql_query($sql, $con);
	while ($fila=mysql_fetch_row($rs)){
		$filas[] = $fila;
	}
	echo json_encode($filas);
	exit();
}

//buscar codigo e incrementarlo en uno
if($opcion=="BuscarCodigo"){
	//veo ultimo codigo en la empresa.
	$sql = "SELECT MAX(Codigo)+1 FROM $fichero WHERE Empresa='$IDBanda' ORDER BY Codigo DESC";
	$rs = mysql_query($sql, $con);
	$fila=mysql_fetch_row($rs);
	echo $fila[0];
	exit();
}





if($opcion=="0") {
	//LISTADOS********************************************************	
	//----- ALGUN TIPO DE ORDENACION ?
	$Orden=$_POST['Orden'];
	if ($Orden=="undefined"||$Orden=='null'||$Orden==''){
		$OrdenarPOR=" ORDER BY 1 DESC";
	}
	//ORDEN EN EL CASO DE QUE SE BORRE ALGO PARA CONSERVAR EL MISMO ORDEN Y NO NOS MAREE LA TABLA
	if ($Orden=="IDEM"){
		$OrdenarPOR=$_SESSION["orden"];
	}
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

	//BUSCAR REGISTROS POR PALABRAS *****************************************************************
	for($i = 0; $i <= 100; $i++) {$_SESSION['C'.$i]=NULL;}	
	$Hayregistros=0;
	$i=1;				
	$Palabra=$_POST['TPalabraabuscar'];
	$_SESSION["orden"]=$OrdenarPOR;
	if ($Palabra<>""){
		$sql = "SELECT * FROM $fichero WHERE banda='$IDBanda' AND seccion='percusion' AND nombre LIKE '%".$Palabra."%' ".$OrdenarPOR;
	}else{
		$sql = "SELECT * FROM $fichero WHERE banda='$IDBanda' AND seccion='percusion' ".$OrdenarPOR;
	}

	$rs = mysql_query($sql, $con);
	if (mysql_num_rows($rs)>0) {					
		$Hayregistros=1;
		while ($fila=mysql_fetch_row($rs)){ 	
			for($a=0;$a<$ColumnasTabla;$a++){
				$_SESSION['C'.$a][$i]=$fila[$a];
			}
			$i=$i+1;
		}
	}
	//FIN LISTADOS *********************************************************
	
if ($Hayregistros<>0){	
?>
	
	<table class="table">
		<tr class="control-label">
			<th id="C2_Orden"  style="cursor: pointer; text-align: left" >Nombre</span></th>
			<th id="C3_Orden"  style="cursor: pointer; text-align: left">Apellido</span></th>
			<th id="C4_Orden"  style="cursor: pointer; text-align: left">DNI</span></th>
			<th id="C5_Orden"  style="cursor: pointer; text-align: left">Email</span></th>
			<th id="C4_Orden"  style="cursor: pointer; text-align: left" width="240" class="hidden-xs"></span></th>
			<th></th>
		</tr>
		<tbody class="tablaListado">
	
      <?php 
		for($i = 1; $i <= count($_SESSION['C0']); $i++) {
			for($a=0;$a<$ColumnasTabla;$a++){
				$value[$a] = $_SESSION['C'.$a][$i];
			}
			echo "<tr class='control-input' codigo='$value[0]'>";
		
			echo "<td class=\"clickable\" align=\"left\">$value[1]</td>";
			echo "<td class=\"clickable\" align=\"left\">$value[2]</td>";
			echo "<td class=\"clickable\" align=\"left\">$value[3]</td>";
			echo "<td class=\"clickable\" align=\"left\">$value[4]</td>";
			echo "<td class=\"clickable\" align=\"left\"></td>";
			if($_SESSION['privilegios']<3) echo '<td align="center">';
			if($_SESSION['privilegios']<3) echo '<a class="bt_editar" cod="'.$fila[0].'" nombre="'.$fila[2].'"><span class="glyphicon glyphicon-pencil hidden-xs" title="Editar registro"></span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			if($_SESSION['privilegios']<3) echo '<a class="bt_eliminar" cod="'.$fila[0].'" nombre="'.$fila[2].'"><span class="glyphicon glyphicon-remove hidden-xs" title="Eliminar registro"></span></a>';
			if($_SESSION['privilegios']<3) echo '</td>';
			echo "</tr>";
		
		}
			
		
	?>
		<tbody>
    </table>
<?php 
}else{ ?>

	  <h4 style="padding-top: 12px; padding-bottom: 5px" align="center">No hay datos</h4>

<?php
}
	exit();
}

include("cabecera.php");
include("$ficheroHTML");

?>

<script src="Miscelania.js"></script>


<script type="text/javascript">
	$(document).ready(function () {
		fichero = '<?php echo $ficheroPHP; ?>';
		colImagen = '<?php echo $colImagen; ?>';
		ColumnasTabla='<?php echo $ColumnasTabla; ?>';
		foco = "C1";
		seleccionaInput('TPalabraabuscar');
		cambiaListado();
		nuevo = true;
		
		//Nuevo
		$("#bt_nuevo").click(function () {
			nuevo = true;
			controlarNuevo();
			$("#modal_titulo").html("Añadir - "+ '<?php echo $TituloCabecera; ?>');
			seleccionaInput(foco);
			$("#C1").val();
//			$("#C3").val(moment().format('DD-MM-YYYY'));
//			$("#C4").val('Informe de resultados');
		});
	
		//Modificar
		$(document).on("click", ".bt_editar", function(){
			nuevo = false;
			$("#modal_titulo").html("Modificar - "+ '<?php echo $TituloCabecera; ?>');
			$("input,textarea").attr("placeholder","").css("border","3px solid #ebe6e2")
			cargarDatos($(this).parent().parent().attr("codigo"))
			seleccionaInput(foco);
		});
		
		//Eliminar
		$(document).on("click", ".bt_eliminar", function(){
			Borrar($(this).parent().parent().attr("codigo"))
		});
	
		//Para buscar palabras
		$("#TPalabraabuscar").keypress(function(e) {
			if(e.which==13 || $("#TPalabraabuscar").val().length % 3 === 0) cambiaListado('IDEM','false');
		});
		$("#TPalabraabuscar").keyup(function(e){
			if(e.keyCode == 8 && $("#TPalabraabuscar").val().length % 3 === 0) cambiaListado('IDEM','false');
		});
		

		//para ordenar
		<?php for($i = 0; $i <= $ColumnasTabla; $i++) { 
			echo "$(document).on('click', '#C".$i."_Orden', function(){
				cambiaListado('C".$i."');
			});";	
		} ?>
	});	
	// Fin document.ready
	
	

	function asignaClickTr(){
		for(var i=1;i<$("tr").size();i++)
		{
			$("tr:eq("+i+")").children(".clickable").click(function(){
				$("#modal_titulo").html("Modificar - "+ '<?php echo $TituloCabecera; ?>');
				$("input,textarea").attr("placeholder","").css("border","3px solid #ebe6e2")
				//actualizaSelect18();
				cargarDatos($(this).parent().attr("codigo"))
				seleccionaInput(foco);
			})
		}
	}


</script>

	

