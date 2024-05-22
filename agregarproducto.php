<?php include_once "encabezado.php" ?>

<div class="col-xs-12">
	<h1>Nueva Producto</h1>
	<form method="post" action="nuevo.php">

		<label for="nombre">Nombre</label>
		<input class="form-control" name="nombre" required type="text" id="nombre" placeholder="Nombre">

		<label for="costo">Costo</label>
		<input class="form-control" name="costo" type="text" id="costo" placeholder="Costo">

		<label for="precio">Precio</label>
		<input class="form-control" name="precio" required type="text" id="precio" placeholder="precio">

		<label for="Categoria">Categoría</label>
		<input class="form-control" name="categoria" required type="text" id="categoria" placeholder="Categoria">

		<label for="Categoria2">Categoría 2</label>
		<input class="form-control" name="categoria2" required type="text" id="categoria2" placeholder="Categoria">

		<label for="name">Name en caso de Semilla</label>
		<input value="<?php echo $producto->name ?>" class="form-control" name="name" type="text" id="name" placeholder="name">

		<label for="Marca">Marca</label>
		<input class="form-control" name="marca" required type="text" id="marca" placeholder="marca">

		<label for="Proveedor">Proveedor</label>
		<input class="form-control" name="proveedor" required type="text" id="proveedor" placeholder="proveedor">

		<label for="descripcion">Descripción</label>
		<textarea required id="descripcion" name="descripcion" cols="30" rows="5" class="form-control"></textarea>

		<label for="stock">Stock</label>
		<input class="form-control" name="stock" required type="number" id="stock" placeholder="Stock">

		<label for="link">Link Oficial</label>
		<input class="form-control" name="link" type="text" id="link" placeholder="Link">

		<label for="imagen">Imágen (separados por ,)</label>
		<input class="form-control" name="imagen" required type="text" id="imagen" placeholder="imagen">


		<br><br><input class="btn btn-info" type="submit" value="Guardar">
	</form>
</div>
<?php include_once "pie.php" ?>