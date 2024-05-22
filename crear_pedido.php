<?php
// if (!isset($_GET["nombre"]))  exit();
include_once "base_de_datos.php";

if (isset($_GET['Crear']))
{
    $nombre_pedido=$_GET['nombre'];
    $query="INSERT INTO pedidos (nombre, estado, fecha_apertura) VALUES ('".$nombre_pedido."', 'abierto', CURRENT_TIMESTAMP())";
    echo $query;
    $sentencia = $base_de_datos->prepare($query);
    $resultado = $sentencia->execute();
    if ($resultado===TRUE)
    {
        echo "<br>pedido añadido con éxito";
					}
} else
{
    if (isset($_GET['Eliminar']))
    {
        $pedido=$_GET['pedidoeliminar'];
        $query="DELETE FROM productos_pedidos WHERE id_pedido = '".$pedido."'";
        $sentencia = $base_de_datos->prepare($query);
        $resultado = $sentencia->execute();
        echo "Se eliminaron los productos_pedidos de con el id_pedido ".$pedido;

        $query="DELETE FROM pedidos WHERE id = '".$pedido."'";
        $sentencia = $base_de_datos->prepare($query);
        $resultado = $sentencia->execute();
        echo "<br> Se eliminó el pedido ".$pedido." de la tabla pedidos";
    }
}

?>