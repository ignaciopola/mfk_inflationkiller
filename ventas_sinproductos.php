<?php include_once "encabezado.php" ?>
<?php
include_once "base_de_datos.php";
$sentencia = $base_de_datos->query("SELECT ventas.numeroventa, ventas.total, ventas.mediopago, ventas.fecha, ventas.idcliente, clientes.nombre, clientes.apellido 
FROM ventas INNER JOIN clientes ON ventas.idcliente = clientes.id 
GROUP BY ventas.numeroventa ORDER BY ventas.numeroventa DESC;");
$ventas = $sentencia->fetchAll(PDO::FETCH_OBJ);
?>

	<div class="col-xs-12">
		<h1>Ventas</h1>
		<div>
			<a class="btn btn-success" href="./vender.php">Nueva <i class="fa fa-plus"></i></a>
		</div>
		<br>
		<form action="generarenvios.php" method="get">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Número</th>
						<th>Cliente</th>
						<th>Medio de Pago</th>
						<th>Fecha</th>
						<th>Productos vendidos</th>
						<th>Total</th>
						<th>Ticket</th>
						<th>Eliminar</th>
						<th><input type="submit" name="xml" value="Generar Envío">
							Seleccionar
							</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($ventas as $venta){ ?>
					<tr>
						<td><?php echo $venta->numeroventa ?></td>
						<td><?php echo $venta->nombre." ".$venta->apellido; ?> </td>
						<td><?php echo $venta->mediopago ?> </td>
						<td><?php echo $venta->fecha ?></td>
						<td>
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>ID</th>
										<th>Producto</th>
										<th>Unidades</th>
										<th>SubTotal</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</td>
						<td><?php echo $venta->total ?></td>
						<td><a class="btn btn-info" href="<?php echo "imprimirTicket.php?numero=" . $venta->numeroventa?>"><i class="fa fa-print"></i></a></td>
						<td><a class="btn btn-danger" href="<?php echo "eliminarVenta.php?numero=" . $venta->numeroventa?>"><i class="fa fa-trash"></i></a></td>
						<td><input type="checkbox" name="accion[]" value="<?php echo $venta->idcliente ?>"></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>
<?php include_once "pie.php" ?>