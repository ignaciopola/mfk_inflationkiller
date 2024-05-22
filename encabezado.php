<?php
/*
	Pequeño, muy pequeño sistema de ventas en PHP con MySQL

	@author parzibyte

	No olvides visitar parzibyte.me/blog para más cosas como esta
*/
include_once "base_de_datos.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Ventas</title>
	
	<link rel="stylesheet" href="./css/fontawesome-all.min.css">
	<link rel="stylesheet" href="./css/2.css">
	<link rel="stylesheet" href="./css/estilo.css">
</head>
<body>
	<?php
		$dolar=800;
	?>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">POS</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="./stockmfk.php">Stock MFKGrow</a></li>
					<li><a href="./clientes.php">Clientes</a></li>
					<li><a href="./ventas.php">Ventas</a></li>
					<li><a>Proveedores</a>
						<ul>	
							<li><a href="./santaplanta.php">Santa Planta</a></li>
							<li><a href="./rosariogrow.php">Rosario Grow</a></li>
						</ul>
					</li> 
					<li><a href="./precios.php">Precios</a></li>
					<li><a href="./pedidos.php">Pedidos</a></li>
					
					<li><a>Dólar referencia <?php echo $dolar; ?></a></li>
				</ul>
			</div>
		</div>
	</nav> -->

	
	


	<div class="container" style="margin-left: 0px;">
		<div class="row">