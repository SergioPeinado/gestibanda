<?php

date_default_timezone_set('Europe/Madrid');
$con = $mysqli = new mysqli('127.0.0.1', 'root', '', 'promocion');
$mysqli->set_charset("utf8");
mysql_select_db($bd_base, $con);

?>