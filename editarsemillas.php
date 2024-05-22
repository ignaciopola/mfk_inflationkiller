<?php
if(!isset($_GET["id"])) exit();
$id = $_GET["id"];
include_once "base_de_datos.php";
$sentencia = $base_de_datos->prepare("SELECT * FROM semillas WHERE id = ?;");
$sentencia->execute([$id]);
$producto = $sentencia->fetch(PDO::FETCH_OBJ);
if($producto === FALSE){
	echo "¡No existe algún producto con ese ID!";
	exit();
}

?>
<?php include_once "encabezado.php" ?>
	<div class="col-xs-12">
		<h1>Editar producto con el ID <?php echo $producto->id; ?></h1>
		<form method="post" action="guardarDatosEditadosSemillas.php">
			<input type="hidden" name="id" value="<?php echo $producto->id; ?>">
	
			<label for="id">ID</label>
			<input value="<?php echo $producto->id ?>" class="form-control" name="id" required type="text" id="codigo" placeholder="Escribe el código">

			<label for="genetica">Genetica</label>
			<input value="<?php echo $producto->genetica ?>" class="form-control" name="genetica" required type="text" id="genetica" placeholder="Nombre Genética">

			<label for="name">Name</label>
			<input value="<?php echo $producto->name ?>" class="form-control" name="name" required type="text" id="name" placeholder="name">

			<label for="idgrow">ID Grow</label>
			<input value="<?php echo $producto->idgrow ?>" class="form-control" name="idgrow" type="text" id="idgrow" placeholder="idgrow">

			<label for="tipo">Tipo</label>
			<input value="<?php echo $producto->tipo ?>" class="form-control" name="tipo" required type="text" id="tipo" placeholder="Tipo">

			<label for="banco">Banco</label>
			<input value="<?php echo $producto->banco ?>" class="form-control" name="banco" required type="text" id="banco" placeholder="banco">

			<label for="presentacion1">Presentación 1</label>
			<input value="<?php echo $producto->presentacion1 ?>" class="form-control" name="presentacion1" required type="number" id="presentacion1" placeholder="presentacion1">
			
			<label for="precio1">Precio 1</label>
			<input value="<?php echo $producto->precio1 ?>" class="form-control" name="precio1" required type="number" id="precio1" placeholder="precio1">
			
			<label for="precio2">Precio 2</label>
			<input value="<?php echo $producto->precio2 ?>" class="form-control" name="precio2" required type="number" id="precio2" placeholder="precio2">

			<label for="presentacion2">Presentación 2</label>
			<input value="<?php echo $producto->presentacion2 ?>" class="form-control" name="presentacion2" required type="number" id="presentacion2" placeholder="presentacion2">

			<label for="Costo">Costo</label>
			<input value="<?php echo $producto->costo ?>" class="form-control" name="costo" required type="number" id="costo" placeholder="costo">
			
			<label for="stock">Stock</label>
			<input value="<?php echo $producto->stock ?>" class="form-control" name="stock" required type="number" id="stock" placeholder="stock">

			<label for="descripcion">Descripción:</label>
			<textarea required id="descripcion" name="descripcion" cols="30" rows="5" class="form-control"><?php echo $producto->descripcion ?></textarea>

			
			<br><br><input class="btn btn-info" type="submit" value="Guardar">
			<a class="btn btn-warning" href="./listar.php">Cancelar</a>
		</form>
	</div>
<?php include_once "pie.php" ?>
