<?php
if(!isset($_GET["id"])) exit();
$id = $_GET["id"];
include_once "base_de_datos.php";
$sentencia = $base_de_datos->prepare("SELECT * FROM clientes WHERE id = ?;");
$sentencia->execute([$id]);
$cliente = $sentencia->fetch(PDO::FETCH_OBJ);
if($cliente === FALSE){
	echo "¡No existe algún producto con ese ID!";
	exit();
}

?>
<?php include_once "encabezado.php" ?>
	<div class="col-xs-12">
		<h1>Editar el cliente con el ID <?php echo $cliente->id; ?></h1>
		<form method="post" action="guardarDatosEditadosClientes.php">
			<input type="hidden" name="id" value="<?php echo $cliente->id; ?>">
	
			<label for="id">ID</label>
			<input value="<?php echo $cliente->id ?>" class="form-control" name="id" required type="text" id="id" placeholder="Escribe el código">

			<label for="nombre">Nombre</label>
			<input value="<?php echo $cliente->nombre ?>" class="form-control" name="nombre" required type="text" id="nombre" placeholder="Nombre">

			<label for="apellido">Apellido</label>
			<input value="<?php echo $cliente->apellido ?>" class="form-control" name="apellido" required type="text" id="apellido" placeholder="Apellido">

            <label for="dni">Apellido</label>
			<input value="<?php echo $cliente->dni ?>" class="form-control" name="dni" required type="text" id="dni" placeholder="DNI">

			<label for="localidad">Localidad</label>
			<input value="<?php echo $cliente->localidad ?>" class="form-control" name="localidad" required type="text" id="localidad" placeholder="Localidad">

			<label for="calle">Calle</label>
			<input value="<?php echo $cliente->calle ?>" class="form-control" name="calle" required type="text" id="calle" placeholder="Calle">

			<label for="numeracion">Num.</label>
			<input value="<?php echo $cliente->numeracion ?>" class="form-control" name="numeracion" required type="number" id="numeracion" placeholder="numeracion">
			
			<label for="postal">Cod. Postal</label>
			<input value="<?php echo $cliente->postal ?>" class="form-control" name="postal" required type="number" id="postal" placeholder="postal">
			
			<label for="area">Cod. Area</label>
			<input value="<?php echo $cliente->area ?>" class="form-control" name="area" type="number" id="area" placeholder="area">

			<label for="celular">Celular</label>
			<input value="<?php echo $cliente->celular ?>" class="form-control" name="celular" required type="number" id="celular" placeholder="celular">
            
            <label for="mail">Mail</label>
			<input value="<?php echo $cliente->mail ?>" class="form-control" name="mail" required type="text" id="mail" placeholder="mail">

            <label for="piso">Piso</label>
			<input value="<?php echo $cliente->piso ?>" class="form-control" name="piso" type="text" id="piso" placeholder="piso">

            <label for="depto">Depto</label>
			<input value="<?php echo $cliente->depto ?>" class="form-control" name="depto" type="text" id="depto" placeholder="depto">
			
			<label for="observacion">Observación:</label>
			<textarea id="observacion" name="observacion" cols="30" rows="5" class="form-control"><?php echo $cliente->observacion ?></textarea>

			
			<br><br><input class="btn btn-info" type="submit" value="Guardar">
			<a class="btn btn-warning" href="./clientes.php">Cancelar</a>
		</form>
	</div>
<?php include_once "pie.php" ?>
