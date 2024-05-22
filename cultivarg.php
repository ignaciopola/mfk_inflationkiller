<?php 
	include_once "encabezado.php";
    include_once "base_de_datos.php";
    include_once "arbol_categorias.php"; 
    $tabla="cultivarg";
	$seccion="cultivarg";
?>
<script>
    document.querySelector("title").innerHTML = "Cultivarg";
	let proveedor="cultivarg";
</script>

	<div class="col-xs-12">
		<h1>Productos de Cultivarg</h1>
		
			
		<?php         
            $productos=Farbol($tabla, $seccion, $_GET["cat"],$_GET["marca"], $base_de_datos); 
        ?>
		
		<br>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>ID Stock</th>
						<th>Nombre</th>
						<th>Precio</th>
						<th>Stock</th>
						<th>Categoria</th>
						<th>Categoria2</th>
						<th>Marca</th>
						<!-- <th>SKU</th> -->
						<th>Link</th>
						<th>Imagen</th>
						<th>
							<button onclick="PasajeIDS('agregarstock')">Agregar al Stock</button>
							<button onclick="PasajeIDS('emparentar')">Emparentar con Stock</button>
							<button onclick="selectAll()">Seleccionar todos</button>	
						</th>
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
							<td><?php echo $producto->idstock ?></td>
							<td><?php echo $producto->nombre ?></td>
							<td><?php echo "<b>".number_format($producto->precio, 0,",",".")."$</b>" ?></td>
							<td><?php echo $producto->stock ?></td>
							<td><?php echo $producto->categoria ?></td>
							<td><?php echo $producto->categoria2 ?></td>
							<td><?php echo $producto->marca ?></td>
							<td><a href='<?php echo $producto->link ?>'>Link al proveedor</a></td>
							<td>
								<?php
									foreach($array_imagenes as $img)
									{
										echo "<img src='".$img."' height='80' width='80'>";
									}
								?>
							</td>
							<td><input type="checkbox" name="ids[]" value="<?php echo $producto->id ?>"></td>
							<!-- <td><?php //echo $producto->descripcion ?></td> -->
							
						</tr>
					<?php 
					} 
					?>
				</tbody>
			</table>
	</div>
<?php include_once "pie.php" ?>