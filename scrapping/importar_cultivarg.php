<?php

// Import PHPSpreadsheet library
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


include('../../conexion local/config.php');

// Read the data from the Excel file
$inputFileName = 'cultivarg.xlsx';
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
        if ($row[6])
        {
            $Nombre = $row[6];

            $Precio = $row[7];
            
            //Divido la cadena del precio que tiene la forma de 'Precio con IVA $xxxx' o 'Precio sin IVA $xxxx'
            $distingoprecio=explode("$", $row[7]);
            
            $iva=$distingoprecio[0];
            $sincomas = explode(",",$distingoprecio[1]);
            if (count($sincomas)>1) //Si el array tiene 2 elementos --> hay una coma --> que es mayor que mil.
            {
                $Precio=(($sincomas[0]*1000)+$sincomas[1]);
            } else
            {
                $Precio=intval($sincomas[0]);
            }







            $Stock = $row[12];
            $Categoria = $row[10];
            $Categoria2 = $row[11];
            // $Categoría3 = $row[6];
            $Marca = "cultivarg";    
            $Imagen = $row[13];
            $Descripción = mysqli_real_escape_string($conn, $row[8]);
            $Link = $row[5];
            
            $query = "INSERT INTO cultivarg (nombre, precio, Stock, Categoria, Categoria2, Marca, Link, Imagen, Descripcion)
                    VALUES ('$Nombre', '$Precio', '$Stock', '$Categoria', '$Categoria2', '$Marca', '$Link', '$Imagen', '$Descripción')
                    ON DUPLICATE KEY UPDATE marca = '$Marca', precio = '$Precio', stock = '$Stock', link = '$Link', categoria = '$Categoria', categoria2 = '$Categoria2';";
            $result = mysqli_query($conn, $query);

            if ($result) 
            {
                $rows_affected = mysqli_affected_rows($conn);

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
                echo "Ocurrió un error al ejecutar la consulta: " . mysqli_error($conn);
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
$resultimg = mysqli_query($conn, $fusionoimg_query);
if ($resultimg) 
{
    echo "Se fusionaron las filas de los productos con más de una imágen<br>";
} else 
{
    echo "Error: " . $fusionoimg_query . "<br>" . mysqli_error($conn)."<br>";
}

$eliminoduplicadas_query = "DELETE t1 FROM rosariogrow t1
                            JOIN (
                                SELECT nombre, MIN(id) AS id
                                FROM rosariogrow
                                GROUP BY nombre
                                HAVING COUNT(*) > 1
                                ) t2 ON t1.nombre = t2.nombre AND t1.id <> t2.id;";
$resultduplicadas = mysqli_query($conn, $eliminoduplicadas_query);

if ($resultimg) 
{
    echo "Se eliminaron las filas duplicadas<br>";
} else 
{
    echo "Error: " . $resultduplicadas_query . "<br>" . mysqli_error($conn)."<br>";
}



// Close the connection
mysqli_close($conn);