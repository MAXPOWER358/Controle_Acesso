<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/signin.css">
	<title>Cadastro_clientes </title>
</head>
<body>
	<div class='container'>
		<?php
		
	                   // cria a conexão 
		$conn = new mysqli("192.168.50.107","root","","Cadastro");
	             //verrifica se coneão foi criada com sucesso
		if($conn->connect_error)
		{
			die("falha na conexão:". $conn->connect_error);
		}
		if(!@$_POST[Nome])
									//1º etapa: Se não tem os dados, verifica se uma nova tag foi liga
		{
			$sql = "SELECT * FROM Insercao WHERE recorded > NOW() - INTERVAL 2 SECOND ORDER BY recorded DESC LIMIT 1";
	                  //Recupera a ùtima tag enviada 
			$result = $conn->query($sql);
			if ((!@$result)||($result->num_rows == 0))
			{
				echo "

				<h4 align='center'>Não foi possível identificar o cartão.<br>Aproxime o cartão do leitor.</h4><BR>

					 "; header("Refresh:3");//Tag não lida ... Recarrega a página a cada 3s até a leitura


					} 
		else {   //2º Etapa:Tag lida, monta o formulário 
			$row = $result->fetch_assoc();
			echo"<form action='cadastramento_cliente.php' method='POST'>
						<div class='form-signin' style='background: #BABEC8;'>
								<h3>Formulário de cadastro</h3><br>
								<input type='text' name='Nome' class='form-control' placeholder='Digite o nome e o sobrenome' required><br>
								<input type='text' name='Documento' class='form-control' placeholder='Digite a carteira de identidade - (RG)' required><br>
								<input type='text'name='Tag'readonly='true'  value=" . $row ['Tag'] ." class='form-control'><br>
							<div class='row d-flex justify-content-center'>
								<button type='submit' class='btn btn-secondary btn-sm'>Enviar</button>&ensp;
								<button type='reset' class='btn btn-secondary btn-sm'>Limpar</button>&ensp;
								
								<a class='btn btn-secondary' href='http://localhost/controle_acesso/cadastramento_cliente.php' role='button'>Atualizar</a>
							</div>
						</div>
				  </form>
	</div>
			<script src='js/bootstrap.js'></script>";
		}
	}
	else {
		$sql = "INSERT INTO Usuarios 
		VALUES('".@$_POST['Tag']."',
		'".@$_POST[Nome]."','".@$_POST[Documento]."')";
		$result = $conn->query($sql);
		if ($result)
			echo "<h4 align='center'>Cadastro realizado com sucesso.</h4>";
	}
	?>
</body>
</html>
