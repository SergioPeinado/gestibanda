<?php
session_start();
$nombreuser=$_SESSION['nombre'];
$banda =$_SESSION['nombreBanda'];
$seccion = $_SESSION['seccion'];
$bandaid= $_SESSION['idBanda'];
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

         <!-- Favicon and touch icons
        <link rel="shortcut icon" href="images/favicon.ico"> -->        
         <link rel="shortcut icon" href="images/chip.ico">
         <?php include('cabecera.php'); ?>
    </head>
<body>
<br />
<br />
<br />
<br />
<br />
<div class="main_content" style="width:75%">
<h2>Bienvenido a Gestibanda <?php echo $nombreuser; ?> : Proyecto de fin de ciclo de Sergio Peinado, que gestiona los componentes de bandas de música, sus eventos y archivos como partituras, notas informativas, etc.</h2>
<br />
<h1>Actualmente eres componente de la <?php echo $banda; ?> de la seccion de <?php echo $seccion;?></h1>
</div>
</body>
</html>

