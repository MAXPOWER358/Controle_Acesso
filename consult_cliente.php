
<?php
 //O tipo de caracteres a ser usado
    header('Content-Type: text/html; charset=utf-8');



$conn = new mysqli("192.168.50.107","root","","Cadastro");

if ($conn->connect_error)
	{
		die("falha na conexão:" . $conn->connect_error);
	}

	$sql = "SELECT * FROM usuarios WHERE tag = '" .@$_POST[tag]. "'";

	$result = $conn->query($sql);

if ((!$result)|| ($result->num_rows == 0))
	{
    	echo "NOTAG\n";
		$result->free(); 
		$sql = "INSERT INTO Insercao  (TAG) VALUES ('" . @$_POST[tag]. "')";
		$result = $conn->query($sql);
	}
else
	{ echo "OK\n"; 			
		$result->free();
		$sql = "INSERT INTO acessos (TAG) VALUES ('" . @$_POST[tag]. "')";
		$result = $conn->query($sql);
	}

$conn->close();

?>



