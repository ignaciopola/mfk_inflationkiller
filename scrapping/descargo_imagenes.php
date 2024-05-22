<?php

// mysqliect to the database
include('../../conexion local/config.php');

if (!$mysqli) {
    die("mysqliection failed: " . mysqli_mysqliect_error());
}

$sql = "SELECT imagen, marca, nombre FROM stockmfk";
$result = mysqli_query($mysqli, $sql);

while($row = mysqli_fetch_assoc($result)) {
    $imagen_url = $row['imagen'];
    $marca = $row['marca'];
    $nombre = $row['nombre'];
    $nombre_archivo = basename($imagen_url);
    $ruta_local = "../../imagenes_productos/$marca/$nombre.png";
    $ruta_abs="imagenes_productos/$marca/$nombre.png";
    if (!file_exists("../../imagenes_productos/$marca")) {
        mkdir("../../imagenes_productos/$marca", 0777, true);
    }

    file_put_contents($ruta_local, file_get_contents($imagen_url));

    $update_sql = "UPDATE stockmfk SET imagen_local = '$ruta_abs' WHERE imagen = '$imagen_url'";
    mysqli_query($mysqli, $update_sql);
}