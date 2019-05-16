<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/remove.css">
		<title>Remover</title>
	</head>
	<body>
	<?php
		// cria a conexão 
		$conn = new mysqli("192.168.50.107","root","","Cadastro");
		//verrifica se coneão foi criada com sucesso
		if($conn->connect_error)
			{
				die("falha na conexão:". $conn->connect_error);
			}
		foreach (@$_POST as $val)
			{
				$sql = "DELETE FROM Usuarios WHERE TAG='".$val ."'";
				$result = $conn->query($sql);
				if($result)
				echo "
				<div class='alert   container'>
   					<p align='center'><strong> Usuário removido com sucesso 
   					(" .$val. ")</strong></p>
   
				</div>";
	
			}
	?>
			<div class='alert   container'>
   				<p align='center'><strong>Selecione os usuários a  serem excluídos</strong></p>
			</div>
		<form action=remove_cliente.php method=post>
		<?php
			$sql= "SELECT TAG,Nome FROM Usuarios";
			$result = $conn->query($sql);
			if ($result->num_rows > 0)
				{
					//Imprime os dados de cada linha da tabela
					while($row = $result->fetch_assoc())
				echo " <div class='container col-md-8 'table-responsive-sm'>
					   	<table class='table table-striped table-borderless table-hover table-sm'>
							<tr  class='table-active'>
								<th>
									<p align='left'>
										<input type=checkbox aria-label='Checkbox for following
										 text input'name=check ".$row["TAG"]."
										 value= ".$row["TAG"].">
									</p>	 
								</th>
								<th>
									<h6 align='left'>
										".$row["TAG"]."
									</h6>	
								</th>
								<th>
									<h6 align='center'>
										".$row["Nome"]."
									</h6>	
								</th>
								<th>
									<p align='right'>
										<input class='btn btn-secondary btn-sm' type='submit' 
										value='Deletar' role='button'>
									</p>
								</th>
							</tr>
						</table>		
					   </div>";
				}
				else
					{
						echo " <div class='alert  container'>
   									<p align='center'><strong>Não existe usuários cadastrado no sistema</strong></p>
								</div>";
					}
				$conn->close();
		?>
	</form>
		
		
		
	</body>
</html>





