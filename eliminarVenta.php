<?php
if(!isset($_GET['numero'])) { echo "no se toma"; } // exit();
$numeroventa = $_GET['numero'];
include_once "base_de_datos.php";

//ELIMINO LA VENTA
$sentencia = $base_de_datos->prepare("DELETE FROM ventas WHERE numeroventa = ?;");
$resultado = $sentencia->execute([$numeroventa]);

//ELIMINO LOS PRODUCTOS VENDIDOS DE ESA VENTA
$sentencia2 = $base_de_datos->prepare("DELETE FROM productosvendidos WHERE numeroventa = ?;");
$resultado2 = $sentencia2->execute([$numeroventa]);


if($resultado2 === TRUE && $resultado === TRUE){
	header("Location: ./ventas.php");
	exit;
}
else echo "Algo salió mal";
?>