if (screen.width < 800) { 
	document.write('<link href="estilosiphone.css" type="text/css" rel="stylesheet" />'); 
} 

function envia(pag){
    document.form1.action= pag;
	document.form1.target ="_self";
    document.form1.submit();
} 

function enviablank(pag){
    document.form1.action= pag;
	document.form1.target ="_blank";
    document.form1.submit();
} 

function ventanaSecundaria (URL){ 
   window.open(URL,"v","width=400,height=300,modal=true,scrollbars=1,status=no,resizable=no,location=n0,left=500,top=200"); 
} 

function nif(a){
var a=document.form1.TCIF.value;
var $b=a;
if ($b==""){
	return 0;
}
for (i=0;i<9-a.length;i++) { 
	$b='0'+$b;
}
a=$b;
var temp=a.toUpperCase();
var cadenadni="TRWAGMYFPDXBNJZSQVHLCKE";
if (temp!==''){
	//si no tiene un formato valido devuelve error
	if ((!/^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$/.test(temp) && !/^[T]{1}[A-Z0-9]{8}$/.test(temp)) && !/^[0-9]{8}[A-Z]{1}$/.test(temp)){
		alert ("Debe tener 9 caracteres");
		document.form1.TCIF.value="";
		document.form1.TCIF.focus()
		return 0;
	}

	//comprobacion de NIFs estandar
	if (/^[0-9]{8}[A-Z]{1}$/.test(temp)){
		posicion = a.substring(8,0) % 23;
		letra = cadenadni.charAt(posicion);
		var letradni=temp.charAt(8);
		if (letra == letradni){
			document.form1.TCIF.value=a.substring(8,0)+letra;
			return 1;
		}else{
			var answer = confirm("La letra correcta es la "+letra+" ¿Desea corregirla?");
			if (answer){
				document.form1.TCIF.value=a.substring(8,0)+letra;
				return -1;
			}else{
				return -1;
			}
		}
	}else{
		//comprobacion de CIFs estandar
		a=temp;
		par = 0;non = 0;
		for (zz=2;zz<8;zz+=2) {
			par = par+parseInt(a.charAt(zz));
		}
		for (zz=1;zz<9;zz+=2) {
			nn = 2*parseInt(a.charAt(zz));
			if (nn > 9) nn = 1+(nn-10);
			non = non+nn;
		}
			parcial = par + non;
			letra = (10 - ( parcial % 10));
			if (letra==10){
				letra=0;
			}	

		if (letra!=a.charAt(8)) {
			var answer = confirm("El Cif no es válido, LE CORRESPONDE UN "+letra+" ¿Desea corregirlo?");
			if (answer){
				document.form1.TCIF.value=a.substring(8,0)+letra;
				return -1;
			}else{
				return -1;
			}
		}

	}	
}
return 0;
} 

function borrarcampo(Nombre){
	document.getElementById(Nombre).value="";
}

function mueveReloj(){ 
    momentoActual = new Date() 
    hora = momentoActual.getHours() 
    minuto = momentoActual.getMinutes() 
    segundo = momentoActual.getSeconds() 
    horaImprimible = hora + " : " + minuto + " : " + segundo 
    document.form_reloj.reloj.value = horaImprimible 
    setTimeout("mueveReloj()",1000) 
} 


function focus(elemento){
document.getElementById(elemento).focus();
}


function getKeyCode(e){
	e= (window.event)? event : e;
	intKey = (e.keyCode)? e.keyCode: e.charCode;
	return intKey;
}

function muestraComentario($comentario){
	$comentario = $comentario.replace(/\s*[\r\n][\r\n \t]*/g, "<br>");
	document.getElementById("tabla").style.opacity=0.3;
	document.getElementById("titulo").style.opacity=0.3;
	document.getElementById("curriculums_comentario").style.opacity=0.9;
	document.getElementById("curriculums_comentario").style.display="block";
	document.getElementById('curriculums_texto').innerHTML = $comentario;
}

function ocultaComentario(){
	document.getElementById("tabla").style.opacity=1;
	document.getElementById("titulo").style.opacity=1;
	document.getElementById("curriculums_comentario").style.display="none";
}


function callprogress(vValor){ 
document.getElementById("getprogress").innerHTML = vValor; 
document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>'; 
} 

function handleEnter (field, event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		var i;
		for (i = 0; i < field.form.elements.length; i++)
			if (field == field.form.elements[i])
				break;
		i = (i + 1) % field.form.elements.length;
		field.form.elements[i].focus();
		field.form.elements[i].select();
		return false;
	} 
	else
	return true;
}      


