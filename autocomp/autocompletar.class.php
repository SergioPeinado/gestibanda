<?php
class Autocompletar
{

	private $dbh;

	public function __construct()
	{
		$this->dbh = new PDO("mysql:host=localhost;dbname=soporte_medicos", "esija", "esija2010");
	}

	public function findData($search)
	{
		$query = $this->dbh->prepare("SELECT CIF, Nombre, compania FROM pacientes WHERE Nombre LIKE '%$search%' OR CIF LIKE '%$search%' OR telefono LIKE '%$search%' OR movil LIKE '%$search%' OR historia LIKE '%$search%' AND Nombre<>''");
        $query->execute(array(':search' => '%'.$search.'%'));
        $this->dbh = null;
        if($query->rowCount() > 0)
        {
        	echo json_encode(array('res' => 'full', 'data' => $query->fetchAll()));
        }
        else
        {
        	echo json_encode(array('res' => 'empty'));
        }
	}
}