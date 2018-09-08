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

function cargaXML(sala,facultativo) {
	if(sala=="") {
		document.getElementById("loadingsala").style.visibility="visible";
	} else {
		document.getElementById("loadingfacul").style.visibility="visible";
	}
	oXML = AJAXCrearObjeto();
	oXML.open('POST', 'respuestas.php' ,true);	
	oXML.onreadystatechange = function() {
		if (oXML.readyState == 4){	
			var sala = document.getElementById("NCTLugar");
			var facultativo = document.getElementById("NCTfacultativosinternosS");
			var elem = oXML.responseText.split('|');
			sala.value = elem[0];
			facultativo.value = elem[1];
			document.getElementById("loadingsala").style.visibility="hidden";
			document.getElementById("loadingfacul").style.visibility="hidden";
			revisaTipoCita(9,document.form1.campo_fecha.value,sala.value)
		}
	}

	oXML.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXML.send("sala="+sala+"&facultativo="+facultativo);
}

function cargaListado(opcion) {
	document.getElementById("respuesta").innerHTML='<center><img style="width:25px;" src="images/cargando.gif" /></center>';
	oXML = AJAXCrearObjeto();
	oXML.open('POST', 'respuestas.php' ,true);	
	oXML.onreadystatechange = function() {
		if (oXML.readyState == 4){		
			document.getElementById("respuesta").innerHTML=oXML.responseText;
		}
	}
	oXML.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXML.send("opcion="+opcion);
}

function revisaTipoCita(opcion,fecha,sala) {
	oXML = AJAXCrearObjeto();
	oXML.open('POST', 'respuestas.php' ,true);	
	oXML.onreadystatechange = function() {
		if (oXML.readyState == 4){
			var asunto = document.getElementById("TAsunto");
			if(oXML.responseText == "Revision" && sala!="Enfermeria"){
				asunto.value = oXML.responseText;
				asunto.style.border = "3px solid red";
				asunto.title="Este paciente ha tenido dos citas en los últimos 30 días con este facultativo, se aconseja marcar 'Revisión'.";
			} else {
				asunto.value = "Primera";
				asunto.style.border = "3px solid #ebe6e2";
				asunto.title="";
			}
		}
	}
oXML.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	oXML.send("opcion="+opcion+"&date="+fecha);
}