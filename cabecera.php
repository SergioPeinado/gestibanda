<!DOCTYPE html>
<html>
<head>
	<title>GestiBanda</title>
	
	<link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/style.css?201602101015" /> 
    <!--<link rel="stylesheet" type="text/css" href="css/menu_style.css" />-->
    <link rel="stylesheet" type="text/css" href="css/jquery.jgrowl.css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/datepicker.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/modificacionBootstrap.css?201602091007">
    <link rel="stylesheet" href="css/jquery.qtip.css">
    <link rel="stylesheet" href="css/scrolling-nav.css">
    <link rel="stylesheet" href="css/movil.css">
    <link rel="stylesheet" href="css/autocomplete.css">
    
    <script type="text/javascript" src="js/ddaccordion.js"></script>
    <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script src="js/autocomplete.jquery.js"></script>
    <script src="js/ajax.js"></script>
    <script src="js/funciones.js?v=201602151012"></script>
    <script src="js/jquery.jgrowl.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/moment-with-locales.js"></script>
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/thaw.js"></script>
    <script src="js/compruebaUser.js"></script>
    <!--<script src="js/jquery.confirm.js"></script>-->
    <script src="js/jquery.qtip.js"></script>
    <script src="js/scrolling-nav.js"></script>
    <!--<script src="js/autocomplete.jquery.js"></script>-->
    
    <link rel="stylesheet" type="text/css" href="dist/jquery-confirm.min.css" />
    <script type="text/javascript" src="dist/jquery-confirm.min.js"></script>
    
    <script language="javascript" type="text/javascript" src="js/niceforms.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="css/niceforms-default.css" />
    
    <!--Date Picker-->
    <link rel="stylesheet/less" type="text/css" href="less/timepicker.less" />
    <script src="less/less.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-timepicker.js"></script>
    <!--/Date Picker-->
    
    <!-- ElFinder -->
    <link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="elfinder/css/elfinder.full.css">
    <!--<link rel="stylesheet" type="text/css" media="screen" href="elfinder/css/theme-bootstrap-libreicons-svg.css">-->
    <script type="text/javascript" src="elfinder/js/elfinder.min.js"></script>
    <script type="text/javascript" src="elfinder/js/i18n/elfinder.es.js"></script>
    <!-- ElFinder -->

	

</head>
<body>

      
<div id="preloader">
<div id="loader">&nbsp;</div>
</div>


<!--/ Barra superior Codrops -->
<div id="main_container">
<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">

        <!-- Recoge los enlaces de navegación, formularios y otros contenidos para alternar -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
        
            <ul class="nav navbar-nav navbar-left">
                <li><a href="inicio.php"><span>Inicio</span></a></li> 

                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Secciones <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                       <li><a href="trompetas.php"><span>Trompeteria</span></a></li> 
                        <li><a href="bajos.php"><span>Bajos</span></a></li> 
                        <li><a href="corneteria.php"><span>Corneteria</span></a></li> 
                        <li><a href="percusion.php"><span>Percusion</span></a></li>
                       
                    </ul>
                </li>              
                
                <li><a href="eventos.php"><span>Eventos</span></a></li> 

                <li><a href="documentos.php"><span>Documentos</span></a></li> 
               
            </ul>
        
        <ul class="nav navbar-nav navbar-right">
            <!-- /.dropdown -->
            <!--<li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    <li class="nodisponible">
                        <a href="#">
                            <div>
                                <i class="fa fa-comments fa-fw"></i> 2 nuevos tickets
                                <span class="pull-right text-muted small">Hace 4 minutos</span>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li class="nodisponible">
                        <a class="text-center" href="#">
                            <strong>Ver todas las alertas</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </li>-->
            <!-- /.dropdown -->
            <!-- /.dropdown -->
            
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="usuario.php"><i class="fa fa-user fa-fw"></i> Cambiar contraseña</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Salir</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<!-- Añadimos la clase last_submenu_item al último elemento de cada submenú -->
<script>
$( "ul li ul" ).each(function() {
$(this).find("li a").last().addClass("last_submenu_item");
});


</script>


