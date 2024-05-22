<?php
#Salir si alguno de los datos no está presente
if(!isset($_POST["nombre"]) || !isset($_POST["marca"]) || !isset($_POST["categoria"]) || !isset($_POST["descripcion"]) || !isset($_POST["precio"])) exit();

#Si todo va bien, se ejecuta esta parte del código...

include_once "base_de_datos.php";
$nombre = $_POST["nombre"];
$costo = $_POST["costo"];
$precio = $_POST["precio"];
$categoria = $_POST["categoria"];
$categoria2 = $_POST["categoria2"];
$marca = $_POST["marca"];
$proveedor = $_POST["proveedor"];
$descripcion = $_POST["descripcion"];
$stock = $_POST["stock"];
$link = $_POST["link"];
$imagen = $_POST["imagen"];



$sentencia = $base_de_datos->prepare("INSERT INTO stockmfk (nombre, costo, precio, categoria, categoria2, marca, proveedor, descripcion, stock, link, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
$resultado = $sentencia->execute([$nombre, $costo, $precio, $categoria, $categoria2, $marca, $proveedor, $descripcion, $stock, $link, $imagen]);

if($resultado === TRUE){
	header("Location: ./stockmfk.php");
	exit;
}
else echo "Algo salió mal. Por favor verifica que la tabla exista";


?>
<?php include_once "pie.php" ?>