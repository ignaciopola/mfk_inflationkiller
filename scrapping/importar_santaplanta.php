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

// Insert the data into the MySQL table
$rowsInserted = 0;
$i=0;
$actualizadas=0;
$omitidas=0;
$insertadas=0;
foreach ($data as $row) 
{
    if ($i==0)
    {
        $i++;
    } else
    {
        if ($row[7])
        {
            // $ID = $row[0];
            $Nombre = $row[6];
            
            //Divido la cadena del precio que tiene la forma de 'Precio con IVA $xxxx' o 'Precio sin IVA $xxxx'
            $distingoprecio=explode("$", $row[8]);
            
            $iva=$distingoprecio[0];
            $sincomas = explode(",",$distingoprecio[1]);
            if (count($sincomas)>1) //Si el array tiene 2 elementos --> hay una coma --> que es mayor que mil.
            {
                $Precio=(($sincomas[0]*1000)+$sincomas[1]);
            } else
            {
                $Precio=intval($sincomas[0]);
            }
            
            
            $distingopreciosuge=explode("$", $row[9]);
            $sincomassuge=explode(",",$distingopreciosuge[1]);
            if (count($sincomassuge)>1) //Si el array tiene 2 elementos --> hay una coma --> que es mayor que mil.
            {
                $PrecioSugerido = (($sincomassuge[0]*1000)+$sincomassuge[1]);
            } else
            {
                $PrecioSugerido=intval($sincomassuge[0]);
            }


            $Stock = $row[10];
            $Categoria = $row[12];
            $Categoria2 = $row[13];            
            $Marca = $row[11];    
            $Imagen = $row[15];

            $LimpioSku=explode(":",$row[19]);
            $Sku=$LimpioSku[1];

            $Descripción = mysqli_real_escape_string($mysqli, $row[14]);
            $Link = $row[5];
            
            //Obtengo el ID de la tabla STOCK del producto con el nombre más parecido
            




            $query = "INSERT INTO santaplanta (nombre, iva, precio, preciosugerido, stock, categoria, categoria2, marca, link, imagen, sku, descripcion)
            VALUES ('$Nombre', '$iva', '$Precio', '$PrecioSugerido', '$Stock', '$Categoria', '$Categoria2', '$Marca', '$Link', '$Imagen', '$Sku', '$Descripción')
            ON DUPLICATE KEY UPDATE imagen = '$Imagen', iva = '$iva', precio = '$Precio', preciosugerido = '$PrecioSugerido', stock = '$Stock', link = '$Link', categoria = '$Categoria', categoria2 = '$Categoria2';";
            $result = mysqli_query($mysqli, $query);

            if ($result) 
            {
                $rows_affected = mysqli_affected_rows($mysqli);

                if ($rows_affected == 1) {
                    echo "Se insertó una nueva fila en la tabla ($Nombre)";
                    $insertadas++;
                } elseif ($rows_affected == 2) {
                    echo "Se actualizó una fila existente en la tabla ($Nombre)";
                    $actualizadas++;
                } else {
                    echo "Se omitió la fila porque es idéntica ($Nombre)";
                    $omitidas++;
                }
            } else {
                echo "Ocurrió un error al ejecutar la consulta: " . mysqli_error($mysqli);
            }
            echo "<br>";
        }
    }
}

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
if ($resultimg) 
{
    echo "Se fusionaron las filas de los productos con más de una imágen<br>";
} else 
{
    echo "Error: " . $fusionoimg_query . "<br>" . mysqli_error($mysqli)."<br>";
}

$eliminoduplicadas_query = "DELETE t1 FROM santaplanta t1
                            JOIN (
                                SELECT nombre, MIN(id) AS id
                                FROM santaplanta
                                GROUP BY nombre
                                HAVING COUNT(*) > 1
                                ) t2 ON t1.nombre = t2.nombre AND t1.id <> t2.id;";
$resultduplicadas = mysqli_query($mysqli, $eliminoduplicadas_query);

if ($resultimg) 
{
    echo "Se eliminaron las filas duplicadas<br>";
} else 
{
    echo "Error: " . $resultduplicadas_query . "<br>" . mysqli_error($mysqli)."<br>";
}



// Close the connection
mysqli_close($mysqli);
