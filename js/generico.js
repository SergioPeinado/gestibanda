function seleccionaTodo(){
	Form1.cNParteV.checked = true;
	
	Form1.cPartesV.checked = true;
	Form1.cPartesM.checked = true;
	Form1.cPartesE.checked = true;
	
	Form1.cMantV.checked = true;
	Form1.cMantM.checked = true;
	Form1.cMantE.checked = true;
	
	Form1.cCliV.checked = true;
	Form1.cCliM.checked = true;
	Form1.cCliE.checked = true;
	
	Form1.cUsuV.checked = true;
	Form1.cUsuM.checked = true;
	Form1.cUsuE.checked = true;
	
	Form1.cCuentaV.checked = true;
	Form1.cCuentaM.checked = true;
	
	Form1.cRolesV.checked = true;
	Form1.cRolesM.checked = true;
	Form1.cRolesE.checked = true;
}
  
function eliminaSeleccion(){
	Form1.cNParteV.checked = false;
	
	Form1.cPartesV.checked = false;
	Form1.cPartesM.checked = false;
	Form1.cPartesE.checked = false;
	
	Form1.cMantV.checked = false;
	Form1.cMantM.checked = false;
	Form1.cMantE.checked = false;
	
	Form1.cCliV.checked = false;
	Form1.cCliM.checked = false;
	Form1.cCliE.checked = false;
	
	Form1.cUsuV.checked = false;
	Form1.cUsuM.checked = false;
	Form1.cUsuE.checked = false;
	
	Form1.cCuentaV.checked = false;
	Form1.cCuentaM.checked = false;
	
	Form1.cRolesV.checked = false;
	Form1.cRolesM.checked = false;
	Form1.cRolesE.checked = false;
}

function pulsaVer($obj){
	if(document.getElementById($obj+"V").checked == false){
		document.getElementById($obj+"M").checked = false;
		document.getElementById($obj+"E").checked = false;
	}
}

function pulsaModificar($obj){
	if(document.getElementById($obj+"M").checked == true){
		document.getElementById($obj+"V").checked = true;
	} else {
		document.getElementById($obj+"E").checked = false;
	}
}

function pulsaEliminar($obj){
	if(document.getElementById($obj+"E").checked == true){
		document.getElementById($obj+"V").checked = true;
		document.getElementById($obj+"M").checked = true;
	}
}