<?php include_once "encabezado.php" ?>

<div class="col-xs-12">
	<h1>Nueva Producto</h1>
	<form method="post" action="nuevo.php">

	

		<label for="genetica">Nombre</label>
		<input class="form-control" name="genetica" required type="text" id="genetica" placeholder="Nombre Genética">

		<label for="tipo">Tipo</label>
		<input class="form-control" name="tipo" required type="text" id="tipo" placeholder="Auto o Fem">

		<label for="banco">Banco</label>
		<input class="form-control" name="banco" required type="text" id="banco" placeholder="Banco">

		<label for="descripcion">Descripción</label>
		<textarea required id="descripcion" name="descripcion" cols="30" rows="5" class="form-control"></textarea>

		<label for="name">Name</label>
		<input class="form-control" name="name" required type="text" id="name" placeholder="Iniciales Genética-Iniciales Banco">

		<label for="precio1">Precio 1:</label>
		<input class="form-control" name="precio1" required type="number" id="precio1" placeholder="Precio 1">

		<label for="presentacion1">Presentación 1:</label>
		<input class="form-control" name="presentacion1" required type="number" id="presentacion1" placeholder="Presentación 1">

		<label for="precio2">Precio2:</label>
		<input class="form-control" name="precio2" required type="number" id="precio2" placeholder="Precio 2">

		<label for="presentacion2">Presentación 2:</label>
		<input class="form-control" name="presentacion2" required type="number" id="presentacion2" placeholder="Presentación 2">

		<label for="stock">Stock</label>
		<input class="form-control" name="stock" required type="number" id="stock" placeholder="Stock">

		<label for="link">Link Oficial</label>
		<input class="form-control" name="link" required type="text" id="link" placeholder="Link">


		<br><br><input class="btn btn-info" type="submit" value="Guardar">
	</form>
</div>
<?php include_once "pie.php" ?>