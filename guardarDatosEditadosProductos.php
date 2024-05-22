<?php

#Salir si alguno de los datos no está presente
if(
	!isset($_POST["id"]) || 
	!isset($_POST["nombre"]) || 
	!isset($_POST["costo"]) || 
	!isset($_POST["costous"]) || 
	!isset($_POST["precio"]) || 
	!isset($_POST["stock"]) || 
	!isset($_POST["categoria"]) || 
    !isset($_POST["marca"]) || 
    !isset($_POST["imagen1"]) 
) exit();

#Si todo va bien, se ejecuta esta parte del código...

include_once "base_de_datos.php";
$id = $_POST["id"];
$nombre = $_POST["nombre"];
$costo=$_POST["costo"];
$costous=$_POST["costous"];
$precio=$_POST["precio"];
$preciosugerido=$_POST["preciosugerido"];
$stock=$_POST["stock"];
$categoria=$_POST["categoria"];
$categoria2=$_POST["categoria2"];
$marca=$_POST["marca"];
$imagen=$_POST["imagen1"];
$descripcion = $_POST["descripcion"];
$name=$_POST['name'];


$sentencia = $base_de_datos->prepare("UPDATE stockmfk SET id = ?, nombre = ?, costo = ?, costous = ?, precio = ?, preciosugerido = ?, stock = ?, categoria = ?, categoria2 = ?, marca = ?, imagen = ?, descripcion = ?, name = ? WHERE id = ?;");
$resultado = $sentencia->execute([$id, $nombre, $costo, $costous, $precio, $preciosugerido, $stock, $categoria, $categoria2, $marca, $imagen, $descripcion, $name, $id]);

if($resultado === TRUE){
	header("Location: ./stockmfk.php");
	exit;
}
else echo "Algo salió mal. Por favor verifica que la tabla exista, así como el ID del producto";
?>