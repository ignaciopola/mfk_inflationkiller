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



$sql = "UPDATE stockmfk 
        INNER JOIN santaplanta ON stockmfk.id = santaplanta.idstock
        SET stockmfk.imagen = santaplanta.imagen, stockmfk.link = santaplanta.link";
$resultduplicadas = mysqli_query($mysqli, $sql);




// Close the connection
mysqli_close($mysqli);
