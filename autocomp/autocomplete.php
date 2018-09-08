<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
{
	require_once("autocompletar.class.php");
	$search = $_POST["autocomplete"];
	$autocompletar = new Autocompletar();
	$autocompletar->findData($search);
}