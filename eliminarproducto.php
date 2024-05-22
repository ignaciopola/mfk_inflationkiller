<?php
if ((!isset($_GET["ids"])) AND (!isset($_GET['id']))) exit();

include_once "base_de_datos.php";

if (isset($_GET['ids'])) //Caso botón eliminar múltiple
{
	if (!(is_array($_GET['ids']))) //Caso de que el múltiple traiga un solo id
	{
		$sentencia = $base_de_datos->prepare("DELETE FROM stockmfk WHERE id = ?;");
		$resultado = $sentencia->execute([$_GET['ids']]);
	} else
	{
		foreach($_GET['ids'] as $ids) //Caso múltiple trayendo múltiples
			{
				$sentencia = $base_de_datos->prepare("DELETE FROM stockmfk WHERE id = ?;");
				$resultado = $sentencia->execute([$ids]);
			}
		}
}
if (isset($_GET['id'])) //Caso botón eliminar individual
{
	$sentencia = $base_de_datos->prepare("DELETE FROM stockmfk WHERE id = ?;");
	$resultado = $sentencia->execute([$_GET['id']]);
}

if($resultado === TRUE){
	header("Location: ./stockmfk.php");
	exit;
}
else echo "Algo salió mal";
?>