<?php
session_start();
require_once('includes/configuracion.php');
require_once('includes/funciones.php');
require ('cabecera.php');


?>
<div class="main_content" style="width: 100%">
	<div class="container container-full">
	  <h4 style="padding-top: 1px; padding-bottom: 1px"><? echo $TituloCabecera ?></h4>
		<div class="row menu-acciones-bootstrap">
			<div class="col-md-12"><div id="documentos"><div id="elfinder"></div></div></div>
		</div>
	</div>
</div>
<script src="Miscelania.js"></script>


<script type="text/javascript">
	$(document).ready(function () {

		nuevo = true;
		cargarGestorDocumentos();
	});
		
</script>