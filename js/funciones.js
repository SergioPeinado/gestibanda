
/*
 * Tiene que recibir el cif sin espacios ni guiones
 */
function validateCIF(cif){
	//Quitamos el primer caracter y el ultimo digito
	var valueCif=cif.substr(1,cif.length-2);
	var suma=0;
	//Sumamos las cifras pares de la cadena
	for(i=1;i<valueCif.length;i=i+2){
		suma=suma+parseInt(valueCif.substr(i,1));
	}
	var suma2=0;
	//Sumamos las cifras impares de la cadena
	for(i=0;i<valueCif.length;i=i+2){
		result=parseInt(valueCif.substr(i,1))*2;
		if(String(result).length==1){
			// Un solo caracter
			suma2=suma2+parseInt(result);
		}else{
			// Dos caracteres. Los sumamos...
			suma2=suma2+parseInt(String(result).substr(0,1))+parseInt(String(result).substr(1,1));
		}
	}
	// Sumamos las dos sumas que hemos realizado
	suma=suma+suma2;
	var unidad=String(suma).substr(1,1)
	unidad=10-parseInt(unidad);
	var primerCaracter=cif.substr(0,1).toUpperCase();
	if(primerCaracter.match(/^[FJKNPQRSUVW]$/)){
		//Empieza por .... Comparamos la ultima letra
		if(String.fromCharCode(64+unidad).toUpperCase()==cif.substr(cif.length-1,1).toUpperCase())
			return true;
	}else if(primerCaracter.match(/^[XYZ]$/)){
		//Se valida como un dni
		var newcif;
		if(primerCaracter=="X")
			newcif=cif.substr(1);
		else if(primerCaracter=="Y")
			newcif="1"+cif.substr(1);
		else if(primerCaracter=="Z")
			newcif="2"+cif.substr(1);
		return validateDNI(newcif);
	}else if(primerCaracter.match(/^[ABCDEFGHLM]$/)){
		//Se revisa que el ultimo valor coincida con el calculo
		if(unidad==10)
			unidad=0;
		if(cif.substr(cif.length-1,1)==String(unidad))
			return true;
	}else{
		//Se valida como un dni
		return validateDNI(cif);
	}
	return false;
}

/*
 * Tiene que recibir el dni sin espacios ni guiones
 * Esta funcion es llamada
 */
function validateDNI(dni){
	var lockup = 'TRWAGMYFPDXBNJZSQVHLCKE';
	var valueDni=dni.substr(0,dni.length-1);
	var letra=dni.substr(dni.length-1,1).toUpperCase();
	if(lockup.charAt(valueDni % 23)==letra)
		return true;
	return false;
}

function soloNumeros(e){
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;
	return /\d/.test(String.fromCharCode(keynum));
}
function soloNumerosEnteros(e){
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8))
	return true;
	return /\d/.test(String.fromCharCode(keynum));
}

function validaCCC(val){
    var banco = val.substring(0,4);
    var sucursal = val.substring(4,8);
    var dc = val.substring(8,10);
    var cuenta=val.substring(10,20);
    var CCC = banco+sucursal+dc+cuenta;
    if (!/^[0-9]{20}$/.test(banco+sucursal+dc+cuenta)){
        return false;
    }else{
        valores = new Array(1, 2, 4, 8, 5, 10, 9, 7, 3, 6);
        control = 0;
        for (i=0; i<=9; i++)
        control += parseInt(cuenta.charAt(i)) * valores[i];
        control = 11 - (control % 11);
        if (control == 11) control = 0;
        else if (control == 10) control = 1;
        if(control!=parseInt(dc.charAt(1))) {
            return false;
        }
        control=0;
        var zbs="00"+banco+sucursal;
        for (i=0; i<=9; i++)
            control += parseInt(zbs.charAt(i)) * valores[i];
        control = 11 - (control % 11);
        if (control == 11) control = 0;
            else if (control == 10) control = 1;
        if(control!=parseInt(dc.charAt(0))) {
            return false;
        }
        return true;
    }
}

function CalcularIBAN(numerocuenta, codigopais) {
    //Conversión de letras por números
    //A=10 B=11 C=12 D=13 E=14
    //F=15 G=16 H=17 I=18 J=19
    //K=20 L=21 M=22 N=23 O=24
    //P=25 Q=26 R=27 S=28 T=29
    //U=30 V=31 W=32 X=33 Y=34
    //Z=35
    
    if (codigopais.length != 2)
        return "";
    else {
        var Aux;
        var CaracteresSiguientes;
        var TmpInt;
        var CaracteresSiguientes;
        numerocuenta = numerocuenta + (codigopais.charCodeAt(0) - 55).toString() + (codigopais.charCodeAt(1) - 55).toString() + "00";
        //Hay que calcular el módulo 97 del valor contenido en número de cuenta
        //Como el número es muy grande vamos calculando módulos 97 de 9 en 9 dígitos
        //Lo que se hace es calcular el módulo 97 y al resto se le añaden 7 u 8 dígitos en función de que el resto sea de 1 ó 2 dígitos
        //Y así sucesivamente hasta tratar todos los dígitos
        TmpInt = parseInt(numerocuenta.substring(0, 9), 10) % 97;
        if (TmpInt < 10)
            Aux = "0";
        else
            Aux = "";

        Aux=Aux + TmpInt.toString();
        numerocuenta = numerocuenta.substring(9);

        while (numerocuenta!="") {
            if (parseInt(Aux, 10) < 10)
                CaracteresSiguientes = 8;
            else
                CaracteresSiguientes = 7;

            if (numerocuenta.length<CaracteresSiguientes) {
                Aux=Aux + numerocuenta;
                numerocuenta="";
            }else{
                Aux=Aux + numerocuenta.substring(0, CaracteresSiguientes);
                numerocuenta=numerocuenta.substring(CaracteresSiguientes);
            }
            TmpInt = parseInt(Aux, 10) % 97;
            if (TmpInt < 10)
                Aux = "0";
            else
                Aux = "";
            Aux=Aux + TmpInt.toString();
        }
        TmpInt = 98 - parseInt(Aux, 10);
        if (TmpInt<10)
            return codigopais + "0" + TmpInt.toString();
        else
            return codigopais + TmpInt.toString();
    }
}





//PARA VALIDAR DESDE FECHA HASTA FECHA
function destino(){
	var fecha11 = document.getElementById('desdefecha');
	var fecha22 = document.getElementById('hastafecha');
	var ano=fecha11.value.substring(6, 10);
	var Trimestre=document.getElementById('seccionestrimestres').options[document.getElementById('seccionestrimestres').selectedIndex].value;
	document.getElementById('seccionesmes').value="";
	
	if (Trimestre==1){
		$('#fecha_control_desde').data("DateTimePicker").date('01-01-'+ano);
		$('#fecha_control_hasta').data("DateTimePicker").date('31-03-'+ano);
	}
	if (Trimestre==2){
		$('#fecha_control_desde').data("DateTimePicker").date('01-04-'+ano);
		$('#fecha_control_hasta').data("DateTimePicker").date('30-06-'+ano);
	}
	if (Trimestre==3){
		$('#fecha_control_desde').data("DateTimePicker").date('01-07-'+ano);
		$('#fecha_control_hasta').data("DateTimePicker").date('30-09-'+ano);
	}
	if (Trimestre==4){
		$('#fecha_control_desde').data("DateTimePicker").date('01-10-'+ano);
		$('#fecha_control_hasta').data("DateTimePicker").date('31-12-'+ano);
	}
} 

function destinomes(){
	var fecha11 = document.getElementById('desdefecha');
	var fecha22 = document.getElementById('hastafecha');
	var ano = fecha11.value.substring(6, 10);
	var Mes = document.getElementById('seccionesmes').options[document.getElementById('seccionesmes').selectedIndex].value
	document.getElementById('seccionestrimestres').value="";
	var dias = diasdelmes(Mes, ano);
	
	$('#fecha_control_desde').data("DateTimePicker").date("01-"+Mes+"-"+ano);
	$('#fecha_control_hasta').data("DateTimePicker").date(dias+"-"+Mes+"-"+ano);
}

function diasdelmes(mes, year) { 
	return new Date(year || new Date().getFullYear(), mes, 0).getDate();
}
//FIN PARA VALIDAR DESDE FECHA HASTA FECHA








