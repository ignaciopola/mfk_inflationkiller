<?php 
	include_once "encabezado.php";
    include_once "base_de_datos.php";
    include_once "arbol_categorias.php"; 
    $tabla="rosariogrow";
	$seccion="rosariogrow";
?>
<script>
    document.querySelector("title").innerHTML = "Rosario Grow";
	let proveedor="rosariogrow";
</script>

	<div class="col-xs-12">
		<h1>Productos de RosarioGrow</h1>

        <?php
			$productos=Farbol($tabla, $seccion, $_GET["cat"],$_GET["marca"], $base_de_datos); 
		?>
		<br>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>ID en StockMFK</th>
					<th>Nombre</th>
					<th>Precio</th>
					<th>Stock</th>
					<th>Categoria</th>
                    <th>Categoria2</th>
                    <th>Categoria3</th>
                    <th>Marca</th>
                    <th>Link</th>
                    <th>Imagen</th>
                    <!--<th>Descripción</th>-->
					<th>
						<button onclick="PasajeIDS('emparentar')">Emparentar con Stock</button>
						<button onclick="selectAll()">Seleccionar todos</button>	
					</th>
					<!-- <th>Editar</th>
					<th>Eliminar</th> -->
					
				</tr>
			</thead>
			<tbody>
				<?php foreach($productos as $producto)
				{ 
					// $precio=$producto->precio1;
					// $costo=$producto->costo;
					// $costo=$costo*$dolar;
					// $ganancia=$precio-$costo;
					
					?>
					<tr>
						<td><?php echo $producto->id ?></td>
						<td><?php echo $producto->idstock ?></td>
						<td><?php echo $producto->nombre ?></td>
						<td><?php echo $producto->precio ?></td>
                        <td><?php echo $producto->stock ?></td>
                        <td><?php echo $producto->categoria ?></td>
						<td><?php echo $producto->categoria2 ?></td>
						<td><?php echo $producto->categoria3 ?></td>
                        <td><?php echo $producto->marca ?></td>
                        <td><a href='<?php echo $producto->link ?>'>Link al proveedor</a></td>
                        <td><img src='<?php echo $producto->imagen ?>' height="80" width="80"></td>
						<td><input type="checkbox" name="ids[]" value="<?php echo $producto->id ?>"></td>
                        <!-- <td><?php //echo $producto->descripcion ?></td> 
						<td><a class="btn btn-warning" href="<?php echo "editarsemillas.php?id=" . $producto->id?>"><i class="fa fa-edit"></i></a></td>
						<td><a class="btn btn-danger" href="<?php echo "eliminarsemillas.php?id=" . $producto->id?>"><i class="fa fa-trash"></i></a></td>
						-->
					</tr>
				<?php 
				} 
				?>
			</tbody>
		</table>
	</div>
<?php include_once "pie.php" ?>