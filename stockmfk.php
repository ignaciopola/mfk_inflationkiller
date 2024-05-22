<?php 
	include_once "encabezado.php";
    include_once "base_de_datos.php";
    include_once "arbol_categorias.php"; 
    $tabla="stockmfk";
	$seccion="stockmfk";
?>
<script>
    document.querySelector("title").innerHTML = "Stock MFK";
	let proveedor="stockmfk";
</script>

	<div class="col-xs-12">
		<h1>Stock de MFKGrow</h1>
		<a class="btn btn-success" href="./agregarproducto.php">Nuevo <i class="fa fa-plus"></i></a>
		<?php         
            $productos=Farbol($tabla, $seccion, $_GET["cat"],$_GET["marca"], $base_de_datos); 
        ?>


			<table class="table table-bordered l-12">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
                        <th>Costo</th>
                        <th>Costo U$S</th>
						<th>Precio</th>
						<th>P.Sugerido</th>
						<th>Stock</th>
						<th>Categoria</th>
						<th>Categoria2</th>
						<th>Marca</th>
						<!-- <th>SKU</th> -->
						<th>Link</th>
						<th>Imagen</th>
                        <th>Proveedor</th>
						<th>
							<button onclick="PasajeIDS('eliminarproducto')">Eliminar</button>
							<button onclick="PasajeIDS('agregarpedidos')">Agregar a Pedido</button>
							<button onclick="selectAll()">Seleccionar todos</button>	
                        </th>
						<!--<th>Descripción</th>-->
						<th>Editar</th>
						<th>Eliminar</th>
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
                            <td><?php echo "<b>".number_format($producto->costo, 0,",",".")."$</b>" ?></td>
                            <td><?php echo "<b>".number_format($producto->costous, 2,",",".")."$</b>" ?></td>
							<td><?php echo "<b>".number_format($producto->precio, 0,",",".")."$</b>" ?></td>
							<td><?php echo "<b>".number_format($producto->preciosugerido, 0,",",".")."$</b>"  ?></td>
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
                            <td><?php echo $producto->proveedor ?></td>
							<td><input type="checkbox" name="ids[]" value="<?php echo $producto->id ?>"></td>
							<!-- <td><?php //echo $producto->descripcion ?></td> -->
							<td><a class="btn btn-warning" href="<?php echo "editarproducto.php?id=" . $producto->id?>"><i class="fa fa-edit"></i></a></td>
							<td><a class="btn btn-danger" href="<?php echo "eliminarproducto.php?id=" . $producto->id?>"><i class="fa fa-trash"></i></a></td>
						</tr>
					<?php 
					} 
					?>
				</tbody>
			</table>
	</div>
<?php include_once "pie.php" ?>