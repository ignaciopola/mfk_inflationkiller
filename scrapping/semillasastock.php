<?php

include_once "../base_de_datos.php";

// Seleccionar todas las filas de la tabla "semillas"
$sql = "SELECT * FROM semillas";
$stmt = $base_de_datos->prepare($sql);
$stmt->execute();

// Recorrer los resultados y agregarlos a la tabla "stockmfk"
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $presentacion1 = $row['presentacion1'];
  $presentacion2 = $row['presentacion2'];
  
  // Agregar el registro correspondiente a presentacion1
  $nombre1 = $row['genetica'] . ' ' . $row['tipo'] . ' x' . $presentacion1;
  $precio1 = $row['precio1'];
  $marca = $row['banco'];
  $name = $row['name'];
  $imagen="https://mfkseeds.com/img/geneticas/".$name."/blanca.png";
  $costo = ($row['costo'] > 0) ? $row['costo'] : 0;
  $link = ($marca == "MFK") ? "../gengrow.php?g=".$name."&p=".$presentacion1 : $row['link'];
  $stock = $row['stock'];
  $proveedor="MFK";
  $categoria = "SEMILLAS";
  $categoria2 = ($row['tipo'] == "Feminizada") ? "FEMINIZADAS" : "AUTOMÁTICAS";
  
  $stmt2 = $base_de_datos->prepare("INSERT INTO stockmfk (proveedor, imagen, nombre, precio, marca, name, costo, link, stock, categoria, categoria2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE precio = ? ");
  $stmt2->execute([$proveedor, $imagen, $nombre1, $precio1, $marca, $name, $costo, $link, $stock, $categoria, $categoria2, $precio1]);
  echo "<br>Se agregó la primera fila de ".$nombre1;
  $id1 = $base_de_datos->lastInsertId();
  
  // Agregar el registro correspondiente a presentacion2
  if ($presentacion2>0) {
    $nombre2 = $row['genetica'] . ' ' . $row['tipo'] . ' x' . $presentacion2;
    $precio2 = $row['precio2'];
    $link2 = ($marca == "MFK") ? "../gengrow.php?g=".$name."&p=".$presentacion2 : $row['link'];
    
    $stmt3 = $base_de_datos->prepare("INSERT INTO stockmfk (proveedor, imagen, nombre, precio, marca, name, costo, link, stock, categoria, categoria2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE precio = ? ");
    $stmt3->execute([$proveedor, $imagen, $nombre2, $precio2, $marca, $name, $costo, $link2, $stock, $categoria, $categoria2, $precio2]);
    echo "<br>Se agregó la segunda fila de ".$nombre2;
    $id2 = $base_de_datos->lastInsertId();
    
    // Concatenar los ids si se generaron dos registros
    $id = $id1 . ',' . $id2;
  } else {
    $id = $id1;
  }
  
  // Actualizar la columna "idgrow" de la tabla "semillas"
  if ($stmt2->rowCount() == 1 OR $stmt3->rowCount() == 1)  
  {
    $stmt4 = $base_de_datos->prepare("UPDATE semillas SET idgrow = ? WHERE id = ?");
    $stmt4->execute([$id, $row['id']]);
  }

  
}
?>
