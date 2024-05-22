<?php

function compare_strings($str1, $str2) {
    $str1 = preg_replace('/[^a-zA-Z0-9]/', '', $str1);
    $str2 = preg_replace('/[^a-zA-Z0-9]/', '', $str2);

    $str1 = strtolower($str1);
    $str2 = strtolower($str2);

    similar_text($str1, $str2, $percent);

    return $percent;
}


include_once "encabezado.php";
include_once "base_de_datos.php";




//Obtengo el ID de la tabla STOCK del producto con el nombre más parecido
$busqueda=$_GET['busqueda'];
echo "<h2>Se está buscando el nombre en la tabla más parecido a: ".$busqueda."</h2>";

$query="SELECT id, nombre FROM stockmfk";
$sentencia = $base_de_datos->query($query);
$resultados = $sentencia->fetchAll(PDO::FETCH_OBJ);

foreach ($resultados as $resultado)
{
    $porcentaje=compare_strings($resultado->nombre,$busqueda);
    $concordancias[$resultado->nombre] = $porcentaje;
    // echo "ID: ".$resultado->id." // Nombre: ".$resultado->nombre." // Concordancia: ".$porcentaje."<br>";
}
// $idstock=$idstrockresult->id;

arsort($concordancias);
foreach ($concordancias as $nombre => $porcentaje) {
    echo "$nombre: $porcentaje%\n <br>";
}

    $nombre_mas_similar = key($concordancias);

    echo "<h3>El nombre más similar es: " . $nombre_mas_similar."</h3>";



    foreach ($resultados as $resultado) 
    {
        $porcen=compare_strings($resultado->nombre, $busqueda);
        $porcentajes[$resultado->id]= $porcen;
    }
    $id_concordancia = array_search(max($porcentajes), $porcentajes);
    echo "El ID de mayor concordancia es: " . $id_concordancia;



?>
<form action="" method="get">
    <input type="text" name="busqueda" id="busqueda">
    <input type="submit">
</form> 
