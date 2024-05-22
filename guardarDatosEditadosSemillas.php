<?php

#Salir si alguno de los datos no está presente
if(
	!isset($_POST["id"]) || 
	!isset($_POST["genetica"]) || 
	!isset($_POST["tipo"]) || 
	!isset($_POST["banco"]) || 
	!isset($_POST["name"]) || 
	!isset($_POST["precio1"]) || 
	!isset($_POST["presentacion1"]) || 
	!isset($_POST["precio2"]) || 
	!isset($_POST["presentacion2"]) || 
	!isset($_POST["descripcion"])
) exit();

#Si todo va bien, se ejecuta esta parte del código...

include_once "base_de_datos.php";
$id = $_POST["id"];
$genetica = $_POST["genetica"];
$tipo=$_POST["tipo"];
$banco=$_POST["banco"];
$name=$_POST["name"];
$presentacion1=$_POST["presentacion1"];
$precio1=$_POST["precio1"];
$presentacion2=$_POST["presentacion2"];
$precio2=$_POST["precio2"];
$costo=$_POST["costo"];
$stock=$_POST["stock"];
$descripcion = $_POST["descripcion"];
$idgrow=$_POST['idgrow'];


$sentencia = $base_de_datos->prepare("UPDATE semillas SET id = ?, genetica = ?, tipo = ?, banco = ?, name = ?, presentacion1 = ?, precio1 = ?, presentacion2 = ?, precio2 = ?, costo = ?, stock = ?, descripcion = ?, idgrow = ? WHERE id = ?;");
$resultado = $sentencia->execute([$id, $genetica, $tipo, $banco, $name, $presentacion1, $precio1, $presentacion2, $precio2, $costo, $stock, $descripcion, $idgrow, $id]);

if($resultado === TRUE){
	header("Location: ./semillas.php");
	exit;
}
else echo "Algo salió mal. Por favor verifica que la tabla exista, así como el ID del producto";
?>