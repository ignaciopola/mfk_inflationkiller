<?php

// Import PHPSpreadsheet library
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

include('../../conexion local/config.php');
// // Connect to the database
// $mysqli = mysqli_connect('localhost', 'root', '', 'mfkgrow');

// if (!$mysqli) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// Read the data from the Excel file
$inputFileName = 'santaplanta.xlsx';
$spreadsheet = IOFactory::load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();
$data = $worksheet->toArray();
$productsFound = array();

// Insert the data into the MySQL table
$rowsInserted = 0;
$i = 0;
$actualizadas = 0;
$omitidas = 0;
$insertadas = 0;



// PASO TODOS LOS PRECIOS A 0 y ESTADO DESAPARECIDO, luego aquellos que puedan ser actualizados lo serán y quedarán en DESAPARECIDOS los que no hayan sido afectados en todo el script. 
$updateQuery = "UPDATE santaplanta SET precio = 0, estado = 'DESAPARECIDO', preciosugerido = 0, stock = 0";
$resultUpdate = mysqli_query($mysqli, $updateQuery);

if ($resultUpdate) {
    $filasActualizadas = mysqli_affected_rows($mysqli);
    echo "Se actualizaron $filasActualizadas filas a precio 0 y enlace 'Desaparecido'.<br>";
} else {
    echo "Ocurrió un error al actualizar las filas existentes: " . mysqli_error($mysqli) . "<br>";
}

foreach ($data as $row) {
    if ($i == 0) {
        $i++;
    } else {
        if ($row[7]) {
            // $ID = $row[0];
            $Nombre = $row[6];

            //Divido la cadena del precio que tiene la forma de 'Precio con IVA $xxxx' o 'Precio sin IVA $xxxx'
            $distingoprecio = explode("$", $row[8]);

            $iva = $distingoprecio[0];
            $sincomas = explode(",", $distingoprecio[1]);
            if (count($sincomas) > 1) //Si el array tiene 2 elementos --> hay una coma --> que es mayor que mil.
            {
                $Precio = (($sincomas[0] * 1000) + $sincomas[1]);
            } else {
                $Precio = intval($sincomas[0]);
            }


            $distingopreciosuge = explode("$", $row[9]);
            $sincomassuge = explode(",", $distingopreciosuge[1]);
            if (count($sincomassuge) > 1) //Si el array tiene 2 elementos --> hay una coma --> que es mayor que mil.
            {
                $PrecioSugerido = (($sincomassuge[0] * 1000) + $sincomassuge[1]);
            } else {
                $PrecioSugerido = intval($sincomassuge[0]);
            }


            $Stock = $row[10];
            $Categoria = $row[12];
            $Categoria2 = $row[13];
            $Marca = $row[11];
            $Imagen = $row[15];

            $LimpioSku = explode(":", $row[19]);
            $Sku = $LimpioSku[1];

            $Descripción = mysqli_real_escape_string($mysqli, $row[14]);
            $Link = $row[5];
            $Estado="VIGENTE";
            $fechaActual = date("Y-m-d");


            // Me fijo si el producto ya existe en la BD
            $query = "SELECT * FROM santaplanta WHERE nombre = '$Nombre'";
            $result = mysqli_query($mysqli, $query);

            if (mysqli_num_rows($result) > 0) {
                // Devuelve un resultado --> El producto existe en la BD
                $productsFound[$Nombre] = true;

                // Actualizo sus valores. 
                $query = "UPDATE santaplanta SET 
                    iva = '$iva',
                    precio = '$Precio',
                    preciosugerido = '$PrecioSugerido',
                    stock = '$Stock',
                    categoria = '$Categoria',
                    categoria2 = '$Categoria2',
                    marca = '$Marca',
                    link = '$Link',
                    imagen = '$Imagen',
                    sku = '$Sku',
                    descripcion = '$Descripción',
                    actualizado = '$fechaActual',
                    estado = '$Estado'
                    WHERE nombre = '$Nombre'";
                $result = mysqli_query($mysqli, $query);

                if ($result) {
                    $rows_affected = mysqli_affected_rows($mysqli);
                    if ($rows_affected == 2) 
                    {
                        $actualizadas++;
                        echo "Se actualizó una fila existente en la tabla ($Nombre)<br>";
                    }
                    else {
                        echo "Se omitió la fila porque es idéntica ($Nombre) <br>";
                        $omitidas++;
                    }
                } else {
                    echo "Ocurrió un error al actualizar el producto ($Nombre): " . mysqli_error($mysqli). "<br>";
                }
            } else { //El producto no existe en la BD

                

                //INSERTO EL PRODUCTO EN LA BD. 
                $query = "INSERT INTO santaplanta (nombre, iva, precio, preciosugerido, stock, categoria, categoria2, marca, link, imagen, sku, descripcion, estado, actualizado)
            VALUES ('$Nombre', '$iva', '$Precio', '$PrecioSugerido', '$Stock', '$Categoria', '$Categoria2', '$Marca', '$Link', '$Imagen', '$Sku', '$Descripción', '$Estado', '$fechaActual)";
                $result = mysqli_query($mysqli, $query);

                if ($result) 
                {
                    $rows_affected = mysqli_affected_rows($mysqli);

                    if ($rows_affected == 1) {
                        echo "Se insertó una nueva fila en la tabla ($Nombre) <br>";
                        $insertadas++;
                    }
                } else {
                    echo "Ocurrió un error al ejecutar la consulta: " . mysqli_error($mysqli);
                }
                echo "<br>";
            }
        }
    }
}
echo "<br><br><b>";
echo "Se insertaron $insertadas filas <br>";
echo "Se actualizaron $actualizadas filas <br>";
echo "Se omitieron $omitidas filas <br>";


$fusionoimg_query = "UPDATE santaplanta t1
        SET imagen = (SELECT GROUP_CONCAT(imagen SEPARATOR ',')
                    FROM santaplanta t2
                        WHERE t1.nombre = t2.nombre)
        WHERE EXISTS (SELECT 1
                        FROM santaplanta t2
                        WHERE t1.nombre = t2.nombre AND t1.id <> t2.id)";
$resultimg = mysqli_query($mysqli, $fusionoimg_query);
if ($resultimg) {
    echo "Se fusionaron las filas de los productos con más de una imágen<br>";
} else {
    echo "Error: " . $fusionoimg_query . "<br>" . mysqli_error($mysqli) . "<br>";
}

$eliminoduplicadas_query = "DELETE t1 FROM santaplanta t1
                            JOIN (
                                SELECT nombre, MIN(id) AS id
                                FROM santaplanta
                                GROUP BY nombre
                                HAVING COUNT(*) > 1
                                ) t2 ON t1.nombre = t2.nombre AND t1.id <> t2.id;";
$resultduplicadas = mysqli_query($mysqli, $eliminoduplicadas_query);

if ($resultimg) {
    echo "Se eliminaron las filas duplicadas<br>";
} else {
    echo "Error: " . $resultduplicadas_query . "<br>" . mysqli_error($mysqli) . "<br>";
}

$desaparecidos=0;



// Consulta para contar las filas con estado "DESAPARECIDO"
$countQuery = "SELECT COUNT(*) AS total FROM santaplanta WHERE estado = 'DESAPARECIDO'";
$resultCount = mysqli_query($mysqli, $countQuery);

if ($resultCount) {
    $row = mysqli_fetch_assoc($resultCount);
    $filasDesaparecidas = $row['total'];
    echo "Total de filas con estado 'DESAPARECIDO': $filasDesaparecidas<br>";
} else {
    echo "Ocurrió un error al contar las filas con estado 'DESAPARECIDO': " . mysqli_error($mysqli) . "<br>";
}



$query = "UPDATE stockmfk AS s
          JOIN santaplanta AS sp ON s.id = sp.idstock
          SET s.costo = sp.precio, s.estado = sp.estado
          WHERE sp.idstock IS NOT NULL";

if ($mysqli->query($query) === TRUE) {
    echo "Se actualizaron los costos en STOCKMFK de los productos correspondientes.";
} else {
    echo "Error al actualizar los costos: " . $mysqli->error;
}




// Close the connection
mysqli_close($mysqli);
