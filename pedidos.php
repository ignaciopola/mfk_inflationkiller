
<?php  //Este mismo PHP muestra lo que se agregará 1º, y de confirmarse lo agrega. 
	include_once "encabezado.php";
	include_once "arbol_categorias.php";
		
	if (isset($_GET['pedido']))
	{
		$pedido=$_GET['pedido'];
		$consulta="SELECT * FROM productos_pedidos WHERE id_pedido = ".$pedido.""; 
		$sentencia = $base_de_datos->query($consulta);
		$productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
	}

	
?>
<script>
	document.querySelector("title").innerHTML = "Pedidos";
</script>
<div class="col-xs-12">
		<?php 
			$consulta="SELECT * FROM pedidos WHERE id = '".$pedido."'"; 
			$sentencia = $base_de_datos->query($consulta);
			$datos_pedidos = $sentencia->fetch(PDO::FETCH_OBJ);
		?>
		<h2>
			<?php 
				echo "Pedido ".$pedido." - ".$datos_pedidos->nombre." - ".$datos_pedidos->estado." - ";
				if ($datos_pedidos->estado=="cerrado")
				{
					echo "Fecha de Cierre: ".$datos_pedidos->fecha_cierre;
				} else {
					echo "Fecha de Apertura: ".$datos_pedidos->fecha_apertura;
				}
			?>
		</h2>
		

		<div class="s-12 m-6 l-6">
			<form action="crear_pedido.php" method="get">
				<input type="text" name="nombre">
				<input type="submit" name="Crear" value="Crear Nuevo Pedido">
			</form>				
		</div>
		<br><br>

		<div class="s-12 m-6 l-6">
			<form action="crear_pedido.php" method="get">
				<?php
					//Traigo todos los pedidos
					$sentencia = $base_de_datos->query("SELECT * FROM pedidos");
					$pedidos = $sentencia->fetchAll(PDO::FETCH_OBJ);
				?>
				<select name="pedidoeliminar" id="pedido" class="form-control" required type="text">
				<?php
					foreach($pedidos as $pedidosa)
					{
						echo "<option value='".$pedidosa->id."'>".$pedidosa->id." - ". $pedidosa->nombre." - ".$pedidosa->fecha_apertura." - ".$pedidosa->estado."</option>";
					}
				?>
				</select>
				<input type="submit" name="Eliminar" value="Eliminar Pedido">
			</form>				
		</div>


		<?php 
			//Traigo pedidos abiertos
			$sentencia = $base_de_datos->query("SELECT * FROM pedidos WHERE estado = 'abierto'");
			$pedidos = $sentencia->fetchAll(PDO::FETCH_OBJ);
			
		?>
		<div class="s-12 m-6 l-6">
			<form action="pedidos.php" method="get">
				<label for="Pedidos Abiertos">Pedidos Abiertos</label>
					<select name="pedido" id="pedido" class="form-control" required type="text">
						<?php 
							foreach($pedidos as $pedidosa)
							{
								echo "<option value='".$pedidosa->id."'>".$pedidosa->id." - ". $pedidosa->nombre." - ".$pedidosa->fecha_apertura." - ".$pedidosa->estado."</option>";
							}
						?>		
					</select>
				<input type="submit" value="Abrir Pedido">
			</form>				
		</div>
		
		<?php 
			//Traigo pedidos cerrados
			$sentencia = $base_de_datos->query("SELECT * FROM pedidos WHERE estado = 'cerrado'");
			$pedidos = $sentencia->fetchAll(PDO::FETCH_OBJ);
		?>
		<div class="s-12 m-6 l-6">
			<form action="pedidos.php" method="get">
				<label for="Pedidos Abiertos">Pedidos Cerrados</label>
					<select name="pedido" id="pedido" class="form-control" required type="text">
						<?php 
							foreach($pedidos as $pedidosa)
							{
								echo "<option value='".$pedidosa->id."'>".$pedidosa->id." - ". $pedidosa->nombre." - ".$pedidosa->fecha_cierre." - ".$pedidosa->estado."</option>";
							}
						?>		
					</select>
				<input type="submit" value="Abrir Pedido">
			</form>				
		</div>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Stock MFK</th>
						<th>Categoria</th>
						<th>Categoria2</th>
						<th>Marca</th>
						<th>SKU</th>
						<th>Link</th>
						<th>Imagen</th>
						<th>Precio</th>
						<th>Unidades</th>
						<th>SubTotal</th>
						<th>
							<form method="get" action="consolidar.php">
								<input type="text" value="<?php echo $pedido ?>" name="pedido" hidden>
								<input type="submit" value="Consolidar">
							</form>
							<button onclick="PasajePedido('eliminarpedido',<?php echo $pedido ?>)">Eliminar</button>
							<button onclick="selectAll()">Seleccionar todos</button>	
						</th>
						<th>Eliminar</th>
						<!--<th>Descripción</th>-->
					</tr>
				</thead>
				<tbody>
					
					<?php 
					$total=0;
					$cantidad=0;
					$candistintos=0;
					foreach($productos as $producto)
					{ 
						
						//Recojo los datos que no están en la tabla "productos_pedidos" de latabla "stockmfk"
						$query="SELECT * FROM stockmfk WHERE id = ".$producto->id_producto.""; 
						$sentencia = $base_de_datos->query($query);
						$datosmfk = $sentencia->fetch(PDO::FETCH_ASSOC);
						

						$imagenes=$datosmfk['imagen'];
						$array_imagenes=explode(",", $imagenes);
						
						$subtotal=$producto->unidades*$producto->costo;
						$total=$total+$subtotal;

						$cantidad=$cantidad+$producto->unidades;
						$cantdistintos++;
						?>
						<tr>
							<td><?php echo $datosmfk['id']; ?></td>
							<td><?php echo $datosmfk['nombre']; ?></td>
							<td><?php echo $datosmfk['stock']; ?></td>
							<td><?php echo $datosmfk['categoria']; ?></td>
							<td><?php echo $datosmfk['categoria2']; ?></td>
							<td><?php echo $datosmfk['marca']; ?></td>
							<td><?php echo $datosmfk['sku']; ?></td>
							<td><a href='<?php echo $datosmfk['link']; ?>'>Link al proveedor</a></td>
							<td>
								<?php
									foreach($array_imagenes as $img)
									{
										echo "<img src='".$img."' height='80' width='80'>";
									}
								?>
							</td>
							<td><?php echo "<b>".number_format($producto->costo, 0,",",".")."$</b>" ?></td>
							<td><?php echo $producto->unidades ?></td>
							<td><?php echo "<b>".$subtotal."</b>" ?>
							<td><input type="checkbox" name="ids[]" value="<?php echo $datosmfk['id']; ?>"></td>
							<td><a class="btn btn-danger" href="<?php echo "eliminarpedido.php?id=".$datosmfk['id']."&pedido=".$pedido; ?>"><i class="fa fa-trash"></i></a></td>
						</tr>
					<?php 
					} 
					?>
				</tbody>
			</table>
	</div>
	
	<h2>Resumen Pedido</h2>
	<h3>
		Monto total = <?php echo number_format($total, 0,",",".") ?> <br>
		Cantidad de Productos = <?php echo $cantidad ?> <br>
		Cantidad de Productos Distintos = <?php echo $cantdistintos ?>
	</h3>
	<br><br><br>
<?php include_once "pie.php" ?>