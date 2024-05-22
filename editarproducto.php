<?php
if(!isset($_GET["id"])) exit();
$id = $_GET["id"];
include_once "base_de_datos.php";
$sentencia = $base_de_datos->prepare("SELECT * FROM stockmfk WHERE id = ?;");
$sentencia->execute([$id]);
$producto = $sentencia->fetch(PDO::FETCH_OBJ);
if($producto === FALSE){
	echo "¡No existe algún producto con ese ID!";
	exit();
}

//Traigo proveedores
$sentencia = $base_de_datos->query("SELECT * FROM proveedores;");
$proveedores = $sentencia->fetchAll(PDO::FETCH_OBJ);


?>
<?php include_once "encabezado.php" ?>
	<div class="col-xs-12">
		<h1>Editar producto con el ID <?php echo $producto->id; ?></h1>
		<form method="post" action="guardarDatosEditadosProductos.php">
			<input type="hidden" name="id" value="<?php echo $producto->id; ?>">
	
			<label for="id">ID</label>
			<input value="<?php echo $producto->id ?>" class="form-control" name="id" required type="text" id="id" placeholder="Escribe el código">

			<label for="nombre">Nombre</label>
			<input value="<?php echo $producto->nombre ?>" class="form-control" name="nombre" required type="text" id="nombre" placeholder="Nombre Producto">

			<label for="descripcion">Descripción:</label>
			<textarea required id="descripcion" name="descripcion" cols="30" rows="5" class="form-control"><?php echo $producto->descripcion ?></textarea>
			
			<label for="link">Link</label>
			<input value="<?php echo $producto->link ?>" class="form-control" name="link" required type="text" id="link" placeholder="Link">

			<label for="marca">Marca</label>
			<input value="<?php echo $producto->marca ?>" class="form-control" name="marca" required type="text" id="marca" placeholder="Marca">

			<label for="precio">Precio</label>
			<input value="<?php echo $producto->precio ?>" class="form-control" name="precio" required type="text" id="precio" placeholder="precio">

			<label for="Costo">Costo (ARS)</label>
			<input value="<?php echo $producto->costo ?>" class="form-control" name="costo" required type="number" id="costo" placeholder="costo">
			
			<label for="Costous">Costo (U$S)</label>
			<input value="<?php echo $producto->costous ?>" class="form-control" name="costous" required type="number" id="costous" placeholder="Costo U$">

			<label for="stock">Stock</label>
			<input value="<?php echo $producto->stock ?>" class="form-control" name="stock" required type="number" id="stock" placeholder="stock">

			<label for="proveedor">Proveedor</label>
			<select name="proveedor" id="proveedor" class="form-control" required type="text">
				<?php 
					echo "<option value='".$producto->proveedor."'>".$producto->proveedor."</option>";
				
					foreach($proveedores as $proveedor)
					{
						echo "<option value='".$proveedor->nombre."'>".$proveedor->nombre."</option>";
					}
				?>		
			</select>

			<label for="cat">Categoría</label>
			<input value="<?php echo $producto->categoria ?>" class="form-control" name="categoria" required type="text" id="categoria" placeholder="categoria">

			<label for="infracat">Categoria2</label>
			<input value="<?php echo $producto->categoria2 ?>" class="form-control" name="categoria2" required type="text" id="categoria2" placeholder="categoria2">
			
			<label for="name">Name en caso de Semilla</label>
			<input value="<?php echo $producto->name ?>" class="form-control" name="name" type="text" id="name" placeholder="name">

			<?php 
				$imagenes=explode(",", $producto->imagen);
				$i=1;
				foreach($imagenes as $img)
				{
					echo "<img src='".$img."'>";
					echo "<input value=".$img." class='form-control' name='imagen".$i."' required type='text' id='imagen".$i."' placeholder='imagen".$i."'>";
					$i++;
				}
			?>
			
			
			<br><br><input class="btn btn-info" type="submit" value="Guardar">
			<a class="btn btn-warning" href="./listar.php">Cancelar</a>
		</form>
	</div>
<?php include_once "pie.php" ?>
