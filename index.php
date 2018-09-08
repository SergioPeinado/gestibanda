 <?php
session_start();

require('includes/configuracion.php');
require('includes/funciones.php');

$opcion=$_GET['opcion'];
$usuario=$_POST['user'];
$password=$_POST['password'];

function cargarDatosBanda($idBanda){
	$sql= mysql_query ("SELECT * FROM banda  WHERE id='$idBanda' LIMIT 1") or die("Error al carga los datos de la banda");
	$fila=mysql_fetch_array($sql);
	
	$_SESSION['nombreBanda']=$fila[1];
	$_SESSION['dirBanda']=$fila[2];
	$_SESSION['idBanda']=$fila[0];
}

if($opcion=="1"){
	$sql=("SELECT * FROM componente WHERE email='$usuario' AND pass='$password'") or die("Error en la consulta del componente");
	$rs=mysql_query($sql, $con);
	if ($fila=mysql_fetch_array($rs)){
		cargarDatosBanda($fila[8]);

		$_SESSION['IDcomponente']=$fila[0];
		$_SESSION['nombre']=$fila[1];
		$_SESSION['apellido'] = $fila[2];
		$_SESSION['seccion'] = $fila[5];
		$_SESSION['privilegios'] = $fila[7];
		$IDBanda=$fila[4];
	

		echo "<script>location.href='inicio.php';</script>";
	}
	else{
		echo "<script>alert('usuario o clave incorrectos')</script>";
		echo "<script>location.href='index.php';</script>";
	}
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gestibanda - Gestión de bandas de musica</title>

        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/login-form-elements.css">
        <link rel="stylesheet" href="css/login-style.css?v=201603141359">
		<link rel="stylesheet" href="css/jquery.qtip.css">

       
 		<link rel="shortcut icon" href="images/chip.ico">
    </head>

    <body>

		<!-- Top content -->
        <div class="top-content">
			
            <div class="inner-bg">
                <div class="container">
                   
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Gestion de Bandas en la nube</h3>
                            		<p>Escribe tu correo electrónico y tu contraseña:</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-key"></i>
                        		</div>
                            </div>
							<div class="form-bottom">
			                    
                                <form method="post" class="form-login" enctype="multipart/form-data" action="index.php?opcion=1" >
                                    <div class="form-group">
                                    <label class="sr-only">Username</label><input name="user" type="text" class="form-username form-control">
                                    </div>
                                    <div class="form-group">
                                    <label class="sr-only">Password</label><input name="password" type="password" class="form-password form-control">
                                    </div>
									<div>
										<input name="login" type="submit" class="btn btn-primary btn-rounded" value="Conectarse"> 
										<a class="btn btn-default btn-rounded" href="Registro.php">Registro</a>
									</div>
									
                                </form>
		                    </div>
                        </div>
                    </div>
					
                   
                </div>
            </div>
        </div>
		
        <!-- Javascript -->
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.backstretch.min.js"></script>
		<script type="text/javascript" src="js/jquery.qtip.js"></script>
		<script>		
			jQuery(document).ready(function() {
				/*
					Fullscreen background
				*/
				$.backstretch("images/logo_background.jpg");
				
				
				
			});
		</script>	
        
        <!--[if lt IE 10]>
            <script src="js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>

