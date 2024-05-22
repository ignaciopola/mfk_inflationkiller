<?php

// Import PHPSpreadsheet library
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

include('../../conexion local/config.php');
// // mysqliect to the database
// $mysqli = mysqli_mysqliect('localhost', 'root', '', 'mfkgrow');

// if (!$mysqli) {
//     die("mysqliection failed: " . mysqli_mysqliect_error());
// }


// Read the data from the Excel file
$inputFileName = 'rosariogrow.xlsx';
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
        if ($row[0])
        {
            $ID = $row[0];
            // $Nombre = $row[1];
            //$Nombre = substr(strstr($row[7], '('), 0, -1);
            $Nombre = $row[7];
            // $Precio = $row[2];
            $numero = $row[8];
            $numero = str_replace(".", "", $numero);
            $numero = str_replace(",", ".", $numero);
            $numero = intval($numero);
            $Precio = $numero;

            // $Stock = $row[3];
            // if (is_numeric(substr($row[9], 0, 1))) {
            //     $pos = strpos($row[3], ' ');
            //     $Stock = doubleval(substr($row[3], 0, $pos));
            // } else {
            //     $Stock = 0;
            // }

            $cadena=$row[9];
            if (preg_match('/\d+/', $cadena, $matches)) {
                $Stock = intval($matches[0]);
            } else {
                // Si no se encuentra ningún número, establecemos el valor de $stock en 0
                $Stock = 0;
            }

            
            $Categoria=preg_replace('/\s*\([^\)]*\)/', '', $row[2]);
            $Categoria2 = $row[12];
            $Categoría3 = $row[14];
            
            $Marca = $row[11];    
            
            $raizlink = 'http://www.rosariogrow.com.ar';
            $linkimagen = $row[10];
            $Imagen = $raizlink . $linkimagen;


            $Descripción = mysqli_real_escape_string($mysqli, $row[13]);

            
            $Link = $row[6];
            // $Link = $row[10];
            
            $query = "INSERT INTO rosariogrow (nombre, precio, Stock, Categoria, Categoria2, Categoria3, Marca, Link, Imagen, Descripcion)
                    VALUES ('$Nombre', '$Precio', '$Stock', '$Categoria', '$Categoria2', '$Categoría3', '$Marca', '$Link', '$Imagen', '$Descripción')
                    ON DUPLICATE KEY UPDATE marca = '$Marca', precio = '$Precio', stock = '$Stock', link = '$Link', categoria = '$Categoria', categoria2 = '$Categoria2';";
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

$fusionoimg_query = "UPDATE rosariogrow t1
        SET imagen = (SELECT GROUP_CONCAT(imagen SEPARATOR ',')
                    FROM rosariogrow t2
                        WHERE t1.nombre = t2.nombre)
        WHERE EXISTS (SELECT 1
                        FROM rosariogrow t2
                        WHERE t1.nombre = t2.nombre AND t1.id <> t2.id)";
$resultimg = mysqli_query($mysqli, $fusionoimg_query);
if ($resultimg) 
{
    echo "Se fusionaron las filas de los productos con más de una imágen<br>";
} else 
{
    echo "Error: " . $fusionoimg_query . "<br>" . mysqli_error($mysqli)."<br>";
}

$eliminoduplicadas_query = "DELETE t1 FROM rosariogrow t1
                            JOIN (
                                SELECT nombre, MIN(id) AS id
                                FROM rosariogrow
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



// Close the mysqliection
mysqli_close($mysqli);