<!DOCTYPE html>
<html lang="pt-br">
	<head>
	<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/remove.css">
		<title>Listar</title>
	</head>
	<body>
		<div class="container">
			<form  action="listar.php" method="POST">
				<div class="input-group">
					<div class="input-group-prepend">
					    <span class="input-group-text">Data Inicial</span>
					</div>
					<input class='form-control' type='datetime-local' name='DataI' >
					<div class="input-group-prepend">
					    <span class="input-group-text">Data Final</span>
					</div>
					<input class='form-control' type='datetime-local' name='DataF' >
					<input class='btn btn-secondary btn-sm' type='submit'
					value='buscar' role='button'>
				</div>	
			</form>
		</div>	

			<?php
			if (@$_POST['DataI']) 
			{
				                // cria a conexão 
				$conn = new mysqli("192.168.50.107","root","","Cadastro");
	             //verrifica se coneão foi criada com sucesso
			if ($conn->connect_error)
			{
				die("falha na conexão:". $conn->connect_error);
			}
			$sql="SELECT usuarios.Nome, acessos.RECORDED
				    FROM usuarios,acessos
				   WHERE acessos.RECORDED > '" . @$_POST['DataI'] . "' AND
							  acessos.RECORDED < '" . @$_POST['DataF'] . "' AND
							  acessos.Tag = usuarios.Tag";
			$result = $conn->query($sql);
				if (@$result->num_rows > 0)
					{
						while($row = $result->fetch_assoc()) 
						{
							echo "
							<br><div class='container col-md-10 table-responsive-sm'>
								<table class='table table-striped table-borderless table-hover table-sm table-responsive-sm'>
									<tr class='active'> 
										<th>
											<p align='center'>
												Nome: " . $row["Nome"] . "
											</p> 
										</th>
										<th>
											<p align='right'>
												Data e hora de acesso:	" . $row["RECORDED"] . "
											</p>
										</th>
									</tr>
								</table>
							</div>";
																				  		
						}														  	
					}
				else
					{
						echo "Resultado vazio";
					}	
				$conn->close();			  
			}
			?>

		
	</body>
</html>