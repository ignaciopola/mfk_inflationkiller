<?php  //Este mismo PHP muestra lo que se agregará 1º, y de confirmarse lo agrega. 
include_once "encabezado.php";
$tabla = $_GET['proveedor'];
echo "La tabla utilizada es " . $tabla . "<br>";

if (!isset($_GET['finalagregar'])) // Paso 1: recién muestro lo que se agregará
{
	if ($_GET['ids']) {


		// $consulta="SELECT * FROM santaplanta WHERE "; 
		$consulta = "SELECT * FROM " . $tabla . " WHERE ";
		$i = 0;
		$cuantos = (count($_GET['ids']) - 1);
		// echo "El último índice del array es ".$cuantos." <br>";
		foreach ($_GET['ids'] as $aid) {
			if (!($i == $cuantos)) {
				$consulta .= "id = " . $aid . " OR ";
			} else {
				$consulta .= "id = " . $aid . ";";
			};
			$i++;
		}
		//echo $consulta;
	}
	$sentencia = $base_de_datos->query($consulta);
	$productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
}

if ($_GET['finalagregar']) // Caso 2: agrego efectivamente los productos que fueron seleccionados al pedido indicado
{
	$id_pedido = $_GET['pedido'];


	if (isset($_GET['ids'])) {
		echo "<b>Se procede a agregar los pedidos</b><br>";
		$ids_agregar = $_GET['ids'];
		$i = 0;
		$unidades = $_GET['unidades'];
		$provprod = $_GET['provprod'];
		$costo = $_GET['costo'];
		foreach ($ids_agregar as $key => $ide) {
			if ($unidades[$key] > 0) {
				$query = "INSERT INTO productos_pedidos (id_producto, id_pedido, proveedor, costo, unidades) VALUES (";
				$query .= $ide . ", " . $id_pedido . ", '" . $provprod[$key] . "', " . $costo[$key] . ", " . $unidades[$key] . ")";
				$query .= "ON DUPLICATE KEY UPDATE unidades = unidades + " . $unidades[$key] . ";";
				echo "<br>" . $query . "<br>";
				$sentencia = $base_de_datos->prepare($query);
				$resultado = $sentencia->execute();
				$i++;
			}
		}
		echo "<b>Se han añadido " . $i . " productos</b><br>";
		exit();
	}
}

?>
<script>
	document.querySelector("title").innerHTML = "Agregar a Pedido";
</script>
<div class="col-xs-12">
	<h1>Productos para Agregar a Pedidos</h1>
	<h2>Seleccione el pedido al que quiere agregar los productos</h2>

	<?php
	//Traigo pedidos abiertos
	$sentencia = $base_de_datos->query("SELECT * FROM pedidos WHERE estado = 'abierto';");
	$pedidosabiertos = $sentencia->fetchAll(PDO::FETCH_OBJ);

	?>
	<form action="agregarpedidos.php" method="get">
		<label for="Pedidos Abiertos">Pedidos Abiertos</label>
		<select name="pedido" id="pedido" class="form-control" required type="text">
			<?php


			foreach ($pedidosabiertos as $pedidosa) {
				echo "<option value='" . $pedidosa->id . "'>" . $pedidosa->id . " - ".$pedidosa->nombre."</option>";
			}
			?>
		</select>


		<input type="text" hidden name="proveedor" value="<?php echo $tabla; ?>">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nombre</th>
					<th>Costo Proveedor</th>
					<th>Stock Proveedor</th>
					<th>Categoria</th>
					<th>Categoria2</th>
					<th>Marca</th>
					<!-- <th>SKU</th> -->
					<th>Link</th>
					<th>Imagen</th>
					<th>Proveedor</th>
					<th>Unidades</th>
					<th><input type="submit" name="finalagregar" value="Agregar"></th>
					<!--<th>Descripción</th>-->
				</tr>
			</thead>
			<tbody>



				<?php
				//Traigo 1º todos los proveedores para verificar el stock de ellos
				$sentencia = $base_de_datos->query("SELECT tabla FROM proveedores");
				$proveedores = $sentencia->fetchAll(PDO::FETCH_OBJ);

				foreach ($productos as $producto) {
					$imagenes = $producto->imagen;
					$array_imagenes = explode(",", $imagenes);

					// Sentencia original
					$query = "SELECT * FROM " . $producto->proveedor . " WHERE idstock = " . $producto->id;

					//Sentencia para que traiga solo de santa planta, provisoria
					// $query="SELECT * FROM santaplanta WHERE idstock = ".$producto->id;
					$sentencia = $base_de_datos->query($query);
					$productoenproveedor = $sentencia->fetchAll(PDO::FETCH_OBJ);
					foreach ($productoenproveedor as $prodprove) {
						// $proveedor=$pro->tabla;
						$proveedor = $producto->proveedor;
						$stockproveedor = $prodprove->stock;
						$precioproveedor = $prodprove->precio;
						$skuproveedor = $prodprove->sku;
					}




				?>
					<tr>
						<td><?php echo $producto->id ?></td>
						<td><?php echo $producto->nombre ?></td>
						<td>
							<?php echo "<b>" . number_format($precioproveedor, 0, ",", ".") . " $ </b>" ?>
							<input name="costo[]" type="text" hidden readonly value=<?php echo $precioproveedor; ?>>
						</td>
						<td><?php echo $stockproveedor ?></td>
						<td><?php echo $producto->categoria ?></td>
						<td><?php echo $producto->categoria2 ?></td>
						<td><?php echo $producto->marca ?></td>
						<!--<td><?php echo $skuproveedor ?></td>-->
						<td><a href='<?php echo $producto->link ?>'>Link al proveedor</a></td>
						<td>
							<?php
							foreach ($array_imagenes as $img) {
								echo "<img src='" . $img . "' height='80' width='80'>";
							}
							?>
						</td>
						<td>
							<input name="provprod[]" hidden type="text" value="<?php echo $proveedor; ?>">
							<?php echo $proveedor; ?>
						</td>
						<td><input name="unidades[]" type="number"></td>
						<td><input type="checkbox" checked name="ids[]" value="<?php echo $producto->id ?>"></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</form>
</div>
<?php include_once "pie.php" ?>