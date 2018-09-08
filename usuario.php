<?php

session_start();
require_once('includes/configuracion.php');
require_once('includes/funciones.php');
include ("cabecera.php");



$IDBanda= $_SESSION['idBanda'];
$nombreuser=$_SESSION['nombre'];
$banda =$_SESSION['nombreBanda'];
$seccion = $_SESSION['seccion'];
$IDComponente=$_SESSION['IDcomponente'];

$fichero='usuario';
$opcion=$_GET['opcion'];


//MODIFICA CONTRASEÑA
if ($opcion==1){
	$pass= trim($_POST['pass']);

	$sql = "UPDATE componente SET pass='$pass' WHERE banda='$IDBanda' AND id='$IDComponente'";
	$resp = mysql_query($sql, $con);	
	
}




?>
<script language="JavaScript"> 
	function envia(pag){ 
		document.form1.action= pag 
		document.form1.target="_self"
		document.form1.submit() 
	} 
</script>
<div class="main_content">
	<div class="container container-full" style="min-height:00px;">	
		<h4 style="padding-top: 12px; padding-bottom: 5px">Panel de componente</h4>
		<ul class="nav nav-pills nav-stacked col-md-2">
			<li class="active"><a href="#datos" data-toggle="pill">Cambiar contraseña</a></li>			
		</ul>
		<form method="post" id="form1" name="form1"  enctype="multipart/form-data" action="usuario.php?opcion=1">
		<div class="tab-content col-md-10" style="border-top: 1px solid lightgrey;">
			<div class="tab-pane active" id="datos">
				<!-- DATOS -->	
				<div class="row masespacio">
					<div class="col-md-1">
						<label for="nombre" class="control-label">Nueva Contraseña</label>
					</div>
					<div class="col-md-3">
						<input maxlength="200" type="password" class="form-control" id="pass" name="pass">
					</div>
					<div class="col-md-1 center-block"><button  type="submit" class="btn btn-success control-input btn-guardar">Guardar</button></div>
				</div>

				<!-- /DATOS -->	
			</div>
		
				</div>
			</div>
		</form>
	</div>
</div> <!--end of main content-->