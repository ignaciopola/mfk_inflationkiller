<?php include_once "encabezado.php" ?>
<?php
include_once "base_de_datos.php";
$sentencia = $base_de_datos->query("SELECT * FROM clientes ORDER BY id DESC");
$clientes = $sentencia->fetchAll(PDO::FETCH_OBJ);
?>

	<div class="col-xs-12">
		<h1>Clientes</h1>
		<div>
			<a class="btn btn-success" href="./formulario.php">Nuevo <i class="fa fa-plus"></i></a>
		</div>
		<br>
		<table class="table table-bordered">
			<thead>ID
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>DNI</th>
					<th>Localidad</th>
					<th>Calle</th>
					<th>Numeración</th>
					<th>Cod. Postal</th>
					<th>Cod. Area</th>
					<th>Celular</th>
                    <th>Mail</th>
					<th>Piso</th>
                    <th>Depto</th>
                    <th>Observación</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($clientes as $cliente){ ?>
				<tr>
					<td><?php echo $cliente->id ?></td>
					<td><?php echo $cliente->nombre ?></td>
					<td><?php echo $cliente->apellido ?></td>
					<td><?php echo $cliente->dni ?></td>
					<td><?php echo $cliente->localidad ?></td>
					<td><?php echo $cliente->calle ?></td>
					<td><?php echo $cliente->numeracion ?></td>
					<td><?php echo $cliente->postal ?></td>
					<td><?php echo $cliente->area ?></td>
                    <td><?php echo $cliente->celular ?></td>
                    <td><?php echo $cliente->mail ?></td>
                    <td><?php echo $cliente->piso ?></td>
                    <td><?php echo $cliente->depto ?></td>
                    <td><?php echo $cliente->observacion ?></td>
					<td><a class="btn btn-warning" href="<?php echo "editarclientes.php?id=" . $cliente->id?>"><i class="fa fa-edit"></i></a></td>
					<td><a class="btn btn-danger" href="<?php echo "eliminarclientes.php?id=" . $cliente->id?>"><i class="fa fa-trash"></i></a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php include_once "pie.php" ?>