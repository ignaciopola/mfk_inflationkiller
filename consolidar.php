<?php
if (!isset($_GET["pedido"]))  exit();

include_once "base_de_datos.php";
$pedido=$_GET['pedido'];
echo "Consolidando el pedido ".$pedido."<br>";
$query="SELECT * FROM productos_pedidos WHERE id_pedido = '".$pedido."'";
$sentencia = $base_de_datos->query($query);
$productos = $sentencia->fetchAll(PDO::FETCH_OBJ);

$i=0;
foreach($productos as $producto)
	{
        $query_update="UPDATE stockmfk SET stock = stock + '".$producto->unidades."' WHERE id = '".$producto->id_producto."';";
        echo $query_update;
        echo "<br>";
        $sentencia = $base_de_datos->prepare($query_update);
		$resultado = $sentencia->execute();
        $i++;
	}

	echo "Se ha actualizado el stock de ".$i." productos ";

    $sentencia = $base_de_datos->prepare("UPDATE pedidos SET estado = 'cerrado', fecha_cierre = CURRENT_TIMESTAMP() WHERE id = ? ");
    $estado = $sentencia->execute([$pedido]);
    if ($estado===TRUE)
    {
        echo "Se actualizó el estado del pedido a Cerrado";
    }
    //header("Location: ./pedidos.php?pedido=".$pedido);
	//exit;

?>