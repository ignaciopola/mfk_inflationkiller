<?php include_once "encabezado.php" ?>
<?php
include_once "base_de_datos.php";
$sentencia = $base_de_datos->query("SELECT * FROM semillas;");
$productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
?>

	<div class="col-xs-12">
		<h1>Productos</h1>
		<div>
			<a class="btn btn-success" href="./formulario.php">Nuevo <i class="fa fa-plus"></i></a>
		</div>
		<br>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Banco</th>
					<th>Name</th>
					<th>Tipo</th>
					<th>Presentación 1</th>
					<th>Precio 1</th>
					<th>Costo</th>
					<th>Ganancia</th>
					<th>Presentación 2</th>
					<th>Precio 2</th>
					<th>Stock</th>
					<th>ID Grow</th>
					<th>Editar</th>
					<th>Eliminar</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($productos as $producto)
				{ 
					$precio=$producto->precio1;
					$costo=$producto->costo;
					$costo=$costo*$dolar;
					$ganancia=$precio-$costo;
					?>
					<tr>
						<td><?php echo $producto->id ?></td>
						<td><?php echo $producto->genetica ?></td>
						<td><?php echo $producto->banco ?></td>
						<td><?php echo $producto->name ?></td>
						<td><?php echo $producto->tipo ?></td>
						<td><?php echo $producto->presentacion1 ?></td>
						<td><?php echo $producto->precio1 ?></td>
						<td><?php echo $costo ?></td>
						<td><?php echo $ganancia ?></td>
						<td><?php echo $producto->presentacion2 ?></td>
						<td><?php echo $producto->precio2 ?></td>
						<td><?php echo $producto->stock ?></td>
						<td><?php echo $producto->idgrow ?></td>
						<td><a class="btn btn-warning" href="<?php echo "editarsemillas.php?id=" . $producto->id?>"><i class="fa fa-edit"></i></a></td>
						<td><a class="btn btn-danger" href="<?php echo "eliminarsemillas.php?id=" . $producto->id?>"><i class="fa fa-trash"></i></a></td>
					</tr>
				<?php 
				} 
				?>
			</tbody>
		</table>
	</div>
<?php include_once "pie.php" ?>