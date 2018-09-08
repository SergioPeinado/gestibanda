var canvasURL="";
var nombrefoto="";
var errorImagen=false;
var modificadoReg = false;

$('.autocomplete').autocomplete();

function AJAXCrearObjeto(){//crea el objeto ajax
	var obj;
	if(window.XMLHttpRequest) { // no es IE
		obj = new XMLHttpRequest();
	}else{ // Es IE o no tiene el objeto
		try{
			obj = new ActiveXObject("Microsoft.XMLHTTP");
		}catch (e) {
			alert('El navegador utilizado no esta soportado');
		}
	}
	return obj;
}

function cambiaListado(orden,sinefecto,listados) {
	oXML = AJAXCrearObjeto();
	oXML.open('POST', fichero ,true);
	$("#preloader").fadeIn("fast");
	
	if(!sinefecto) {
		$('#cargaListado').slideUp('fast');
		$('#cargandoListado').slideDown('slow');
	}
	var palabra = $('#TPalabraabuscar').val();
	
	var desdefecha = $('#desdefecha').val();
	var hastafecha = $('#hastafecha').val();
	var mes = $('#mes').val();
	var fecha = $('#fecha').val();
	var fecha2 = $('#fecha2').val();

	
	
	oXML.onreadystatechange = function() {
		if (oXML.readyState == 4){
			setTimeout(
			function() 
			{
				$('#cargaListado').html(oXML.responseText);
				if(!sinefecto) {
					$('#cargaListado').slideDown('slow');
					$('#cargandoListado').slideUp('fast');
				}
				$("#preloader").fadeOut("fast");
				asignaClickTr();
				contador();
			}, 300);
		}
	}
	oXML.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXML.send("opcion=0&Orden="+orden+"&TPalabraabuscar="+palabra);
}






//MUEVO REGISTRO**************************************************************
function crearNuevoRegistro(){
	resultado="";
	
	if(resultado==""){
		$("#form1").off("submit")
		$("#form1").submit(function(){
			var fd = new FormData(document.getElementById("form1"));
			fd.append("opcion", "1");
			for(i=1;i<200;i++){
				fd.append("C"+i, $("#C"+i).val());
			}
			
			$.ajax({
			  url: fichero,
			  type: "POST",
			  data: fd,
			  enctype: 'multipart/form-data',
			  processData: false,
			  contentType: false,
			  dataType: "json"
			}).done(function( data ) {
				modificadoReg = false;
				if(data['resultado']==true){
					$("#modalNuevo").modal("hide");
					cambiaListado("IDEM","false")
					$('#mensaje').jGrowl("Registro modificado.",{
						theme:'verde'
					});
				}else{
					$('#mensaje').jGrowl(data['resultado'],{theme:'rojo'})
				}
			});	
			return false;
		});	
	} else {
		$('#mensaje').jGrowl(resultado,{theme:'rojo'})
		$("#form1").off("submit")
		$("#form1").submit(function(e){
			return false;
		});
	}
}

//MODIFICAR
function modificarRegistro(cod){
	resultado="";
	
	if(resultado==""){
		$("#form1").off("submit")
		$("#form1").submit(function(){
			var fd = new FormData(document.getElementById("form1"));
			fd.append("opcion", "16");
			fd.append("C0", cod);
			for(i=1;i<200;i++){
				fd.append("C"+i, $("#C"+i).val());
			}
			fd.append("C18Text", $("#C18Text").val());
			$.ajax({
			  url: fichero,
			  type: "POST",
			  data: fd,
			  enctype: 'multipart/form-data',
			  processData: false,
			  contentType: false,
			  dataType: "json"
			}).done(function( data ) {
				modificadoReg = false;
				if(data['resultado']==true){
					$('#mensaje').jGrowl("Registro modificado",{
						theme:'verde'
					});
					$("#modalNuevo").modal("hide");
					cambiaListado("IDEM","false");
					if(data['recarga']==true) window.location.href = "empresa.php";
					console.log(data);
				}else{
					$('#mensaje').jGrowl(data['resultado'],{theme:'rojo'})
				}
			});	
			return false;
		});	
	} else {
		$('#mensaje').jGrowl(resultado,{theme:'rojo'})
		$("#form1").off("submit")
		$("#form1").submit(function(e){
			return false;
		});
	}
}

//CARGAR DATOS DEL REGISTRO
function cargarDatos(cod){
	var control = $("#timagen");
	$('#tabs a:first').tab('show');		// Seleccionamos la primera pestaña
	control.replaceWith(control = control.clone(true));		// Es la mejor forma de limpiar el input file
	errorImagen = false;
	$.post(fichero,{opcion: 15,C0:cod},function(data){
	;
		$("#resul_error").css({border:"none",padding:"0px"}).text("");
		canvasURL="";
		$(".eliminar").show();
		$(".eliminar").off("click");
		$(".eliminar").click(function(){Borrar(cod);});
		$("input,select,textarea").attr("placeholder","").css("border","1px solid #ebe6e2")
		$("#modalNuevo").modal("show");
		for($i = 0; $i <= 200; $i++) {	
			$("#C"+$i).val(data[$i]);
		}
		$("#guardarRegistro").off("click")
		$("#guardarRegistro").click(function(){modificarRegistro(cod);});
		
    },"json");
}


//BORRAR 
function Borrar(cod,nserie){
	$("#modalEliminar").modal("show");
	$("#eliminaRegistro").off("click");
	$("#eliminaRegistro").click(function(){
		$("#modalEliminar").modal("hide");
		$.post(fichero,{opcion:3,C0:cod,C43:nserie},function(data){
			if(data==true){
				$("#mensaje").jGrowl("Registro BORRADO",{theme:'rojo'});
				cambiaListado("IDEM","false");
			}else{
				$('#mensaje').jGrowl("Error al borrar",{theme:'negro'});
			}
		},"json")
	})
}


/* AL PULSAR ENTER */
$('input,select').on("keypress", function(e) {
	if (e.keyCode == 13) {
		/* FOCUS ELEMENT */
		//var inputs = $(this).parents("form").eq(0).find(":input");
		var inputs = $("#modalNuevo input:enabled, #modalNuevo select:enabled, #modalNuevo textarea:enabled");
		var idx = inputs.index(this);

		if (idx == inputs.length - 1) {
			inputs[0].select();
		} else {
			sigName = $(inputs[idx + 1]).attr('name');
			$(sigName).focus();
			seleccionaInput(sigName);
			//inputs[idx + 1].focus(); //  handles submit buttons
			//inputs[idx + 1].select();
		}
		return false;
	}
});  




function seleccionaInput(objeto){
	setTimeout(function() { 
		$('#'+objeto).focus();
		$('#'+objeto).select(); }, 290);

}









function controlarNuevo(){
	$fechacabeceraprimera=$('#fecha').val();
	$fechacabecerasegunda=$('#fecha2').val();
	$("input,textarea,file").val("");
	$('#tabs a:first').tab('show');		// Seleccionamos la primera pestaña
	$("#resul_error").css({border:"none",padding:"0px"}).text("");
	$(".eliminar").hide();
	canvasURL="";
	$("#contenedor").attr("src","");
	$("input,select,textarea").attr("placeholder","").css("border","1px solid #ebe6e2")
	$("#modalNuevo select").val("");
	$("#guardarRegistro").off("click");
	$("#guardarRegistro").click(crearNuevoRegistro);
	$("#modalNuevo").modal("show");
	var control = $("#timagen");
	control.replaceWith(control = control.clone(true));		// Es la mejor forma de limpiar el input file

}



function cargarGestorDocumentos(cod){
	/* Mostramos ElFinder */		
	$("#documentos").html('<div class="row"><div id="elfinder"></div></div>')
	
//	if(privilegios>1) {
//		var url = 'elfinder/php/connector.minimal.php?p=' + cod;
//	} else {
//		var url = 'elfinder/php/connector.minimal.locked.php?p=' + cod;
//	}
	var url = 'elfinder/php/connector.minimal.php?p=' + cod;

	var elf = $('#elfinder').elfinder({
        lang: 'es',             					// language (OPTIONAL)
        url : url,
		resizable: false,
		height: 500,
		commands : [
			'open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook', 
			'download', 'rm', 'duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy', 
			'cut', 'paste', 'edit', 'extract', 'archive', 'info', 'view', 
			'resize', 'sort'
		],
    }).elfinder('instance');
	/* Mostramos ElFinder */
}

