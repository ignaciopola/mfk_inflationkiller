<?php

#Salir si alguno de los datos no está presente
if(
	!isset($_POST["id"]) || 
	!isset($_POST["dni"]) || 
	!isset($_POST["nombre"]) || 
	!isset($_POST["apellido"]) || 
	!isset($_POST["localidad"]) || 
	!isset($_POST["calle"]) || 
	!isset($_POST["numeracion"]) || 
	!isset($_POST["postal"]) || 
	!isset($_POST["area"]) || 
	!isset($_POST["celular"]) || 
    !isset($_POST["mail"]) || 
    !isset($_POST["piso"]) || 
    !isset($_POST["depto"]) || 
    !isset($_POST["observacion"]) 
) { echo "no se tomaron to"; }
// exit();

#Si todo va bien, se ejecuta esta parte del código...

include_once "base_de_datos.php";
$id = $_POST["id"];
$dni = $_POST["dni"];
$nombre=$_POST["nombre"];
$apellido=$_POST["apellido"];
$localidad=$_POST["localidad"];
$calle=$_POST["calle"];
$numeracion=$_POST["numeracion"];
$postal=$_POST["postal"];
$area=$_POST["area"];
$celular = $_POST["celular"];
$mail = $_POST["mail"];
$piso = $_POST["piso"];
$depto = $_POST["depto"];
$observacion = $_POST["observacion"];


$sentencia = $base_de_datos->prepare("UPDATE clientes SET id = ?, dni = ?, nombre = ?, apellido = ?, localidad = ?, calle = ?, numeracion = ?, postal = ?, area = ?, celular = ?, mail = ?, piso = ?, depto = ?, observacion = ? WHERE id = ?;");
$resultado = $sentencia->execute([$id, $dni, $nombre, $apellido, $localidad, $calle, $numeracion, $postal, $area, $celular, $mail, $piso, $depto, $observacion, $id]);

if($resultado === TRUE){
	header("Location: ./clientes.php");
	exit;
}
else echo "Algo salió mal. Por favor verifica que la tabla exista, así como el ID del producto";
?>