<?php
include("mc_table.php");
define('FPDF_FONTPATH', 'font/');

// Encabezado del pdf 
$pdf = new PDF_Mc_Table();
$pdf->AddPage();

$pdf->SetFont('Helvetica','B', 12);
//un arreglo con su medida  a lo ancho
$pdf->SetWidths(array(80,80,30));
//un arreglo con alineacion de cada celda
$pdf->SetAligns(array('C','L','R'));
//OTro arreglo pero con el contenido
//utf8_decode es para que escriba bien
//los acentos. 
$pdf->Row(array(utf8_decode("Hola esto lo puedes hacer gracias al método Multicell, pero el cuadro que sigue estaría abajo."),utf8_decode("Pero para lograr que esta celda se quede aquí y no abajo te diré como le puedes hacer."),utf8_decode("E inclusive no solo sirve para dos celdas sirve para más celdas.")));

// fin y entrega del pdf 
$pdf->Output("ejemplo.pdf","F");
echo "<script language='javascript'>window.open('ejemplo.pdf','_self','');</script>";
exit;	
?>