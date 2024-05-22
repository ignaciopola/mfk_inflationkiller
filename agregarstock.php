<?php  //Este mismo PHP muestra lo que se agregará 1º, y de confirmarse lo agrega. 
	include_once "encabezado.php";
	$tabla=$_GET['proveedor'];
	echo "La tabla utilizada es ".$tabla."<br>";
    if (!isset($_GET['finalagregar'])) // Paso 1: recién muestro lo que se agregará
	{
		if ($_GET['ids'])
		{
			
			
			// $consulta="SELECT * FROM santaplanta WHERE "; 
			$consulta="SELECT * FROM ".$tabla." WHERE "; 
			$i=0;
			$cuantos=(count($_GET['ids'])-1);
			// echo "El último índice del array es ".$cuantos." <br>";
			foreach($_GET['ids'] as $aid) 
			{
				if (!($i==$cuantos))
				{
					$consulta.="id = ".$aid." OR ";
				} else
				{
					$consulta.="id = ".$aid.";";
				};
				$i++;
			}
			//echo $consulta;
		}
		$sentencia = $base_de_datos->query($consulta);
		$productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
	}

	if ($_GET['finalagregar']) // Caso 2: agrego efectivamente los productos que fueron seleccionados a stockmfk
	{
		
		$agrego_query="INSERT INTO stockmfk (nombre, costo, costous, preciosugerido, marca, categoria, categoria2, link, imagen, descripcion, sku, proveedor) SELECT
											nombre, precio, precio/".$dolar.", preciosugerido, marca, categoria, categoria2, link, imagen, descripcion, sku, '".$tabla."' FROM ".$tabla." WHERE id IN (";
		$i=0;
		$cuantos=(count($_GET['ids'])-1);
		foreach($_GET['ids'] as $ids) 
			{
				if (!($i==$cuantos))
				{
					$agrego_query.=$ids.",";
				} else
				{
					$agrego_query.=$ids.")";
				};
				$i++;
			}
		// echo $agrego_query;	
		$rows_affected = $base_de_datos->exec($agrego_query);
		echo "Se han agregado $rows_affected filas a la tabla stockmfk.";
		$base_de_datos = null;
	}
	
?>

<div class="col-xs-12">
		<h1>Productos para Agregar al Stock</h1>

		<form action="agregarstock.php" method="get">
			<input type="text" hidden name="proveedor" value="<?php echo $tabla; ?>">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Precio</th>
						<th>P. Sugerido</th>
						<th>Stock</th>
						<th>Categoria</th>
						<th>Categoria2</th>
						<th>Marca</th>
						<!-- <th>SKU</th> -->
						<th>Link</th>
						<th>Imagen</th>
						<th><input type="submit" name="finalagregar" value="Agregar"></th>
						<!--<th>Descripción</th>-->
					</tr>
				</thead>
				<tbody>
					<?php foreach($productos as $producto)
					{ 
						// $precio=$producto->precio1;
						// $costo=$producto->costo;
						// $costo=$costo*$dolar;
						// $ganancia=$precio-$costo;

						$imagenes=$producto->imagen;
						$array_imagenes=explode(",", $imagenes);
						
						?>
						<tr>
							<td><?php echo $producto->id ?></td>
							<td><?php echo $producto->nombre ?></td>
							<td><?php echo "<b>".number_format($producto->precio, 0,",",".")."$</b>" ?></td>
							<td><?php echo "<b>".number_format($producto->preciosugerido, 2,",",".")."$</b>"  ?></td>
							<td><?php echo $producto->stock ?></td>
							<td><?php echo $producto->categoria ?></td>
							<td><?php echo $producto->categoria2 ?></td>
							<td><?php echo $producto->marca ?></td>
							<!-- <td><?php echo $producto->sku ?></td> -->
							<td><a href='<?php echo $producto->link ?>'>Link al proveedor</a></td>
							<td>
								<?php
									foreach($array_imagenes as $img)
									{
										echo "<img src='".$img."' height='80' width='80'>";
									}
								?>
							</td>
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