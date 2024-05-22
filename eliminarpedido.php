<?php
if ((!isset($_GET["ids"])) AND (!isset($_GET['id']))) exit();

include_once "base_de_datos.php";
$pedido=$_GET['pedido'];
if (isset($_GET['ids'])) //Caso botón eliminar múltiple
{
	if (!(is_array($_GET['ids']))) //Caso de que el múltiple traiga un solo id
	{
		$sentencia = $base_de_datos->prepare("DELETE FROM productos_pedidos WHERE id_producto = ? AND id_pedido = ?;");
		$resultado = $sentencia->execute([$_GET['ids'], $pedido]);
	} else
	{
		foreach($_GET['ids'] as $ids) //Caso múltiple trayendo múltiples
			{
				$sentencia = $base_de_datos->prepare("DELETE FROM productos_pedidos WHERE id_producto = ? AND id_pedido = ?;");
				$resultado = $sentencia->execute([$ids, $pedido]);
			}
		}
}
if (isset($_GET['id'])) //Caso botón eliminar individual
{
	$sentencia = $base_de_datos->prepare("DELETE FROM productos_pedidos WHERE id_producto = ? AND id_pedido = ?;");
	$resultado = $sentencia->execute([$_GET['id'], $pedido]);
}

if($resultado === TRUE){
	header("Location: ./pedidos.php?pedido=".$pedido);
	exit;
}
else echo "Algo salió mal";
?>