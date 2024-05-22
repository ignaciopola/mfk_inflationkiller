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


$proveedor=$_GET['proveedor'];

if ($_GET['concordancias']) 
{
    //FUNCIÓN EJECUTIVA DE ESTA PÁGINA: SE HAN APROBADO LAS CONCORDANCIAS -> ACTUALIZO EL IDSTOCK
    echo "Se procede al emparentamiento<br>";
    $concordancias=$_GET['concordancias'];
    $ids_emparentar=$_GET['ids_emparentar'];
    // var_dump($concordancias);
    $i=0;
    foreach($ids_emparentar as $ide)
    {
        $actualizacion_query="UPDATE ".$proveedor." SET idstock='".$concordancias[$ide]."' WHERE id = ".$ide;
        $sentencia = $base_de_datos->prepare($actualizacion_query);
        $resultado = $sentencia->execute();
        $i++;
    }
    echo "Se han actualizado ".$i." productos<br>";
} else //FUNCIÓN EXHIBITORIA DE ESTA PÁGINA: MUESTRO LOS RESULTADOS DE LAS CONCORDANCIAS PARA QUE SEAN APROBADOS
{


        


    ?>
    <script>
        document.querySelector("title").innerHTML = "Emparentador de Stocks";
        allSelected = false;
        function selectAll() 
        {
            var checkboxes = document.querySelectorAll('input[name="ids_emparentar[]"');
            allSelected = !allSelected;
            for (var i = 0; i < checkboxes.length; i++) 
            {
                checkboxes[i].checked = allSelected;
            }
        }
    </script>


    <form name="emparentar" action="emparentar.php" method="get">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>% Concordancia</th>
                    <th>ID Stock</th>
                    <th>Nombre Stock</th>
                    <th>Nombre Proveedor</th>
                    <th>ID Proveedor</th>
                    <th>
                        <button onclick="submitForm('emparentar')">Consolidar</button>
                        <button onclick="selectAll()">Seleccionar todos</button>	
                    </th>
                </tr>
            </thead>
            <tbody>
            <input type="hidden" name="proveedor" value=<?php echo $proveedor ?>>
                <?php
                    
                    foreach($_GET['ids'] as $ids) 
                    {
                        $prodproveedor_query="SELECT id, nombre FROM ".$proveedor." WHERE id = ".$ids; 
                        $sentencia=$base_de_datos->query($prodproveedor_query);
                        $prodproveedor=$sentencia->fetchAll(PDO::FETCH_OBJ);
                        foreach($prodproveedor as $producto)
                        {
                            //Busco el nombre del Stock más parecido
                            $query="SELECT id, nombre FROM stockmfk";
                            $sentencia = $base_de_datos->query($query);
                            $resultados = $sentencia->fetchAll(PDO::FETCH_OBJ);
                            foreach ($resultados as $resultado) 
                            {
                                $porcen=compare_strings($resultado->nombre, $producto->nombre);
                                $porcentajes[$resultado->id]= $porcen;
                                $nombres[$resultado->id]=$resultado->nombre;
                            }
                            $id_concordancia = array_search(max($porcentajes), $porcentajes);
                            $nombre_concordancia=$nombres[$id_concordancia];
                            // echo "El ID de mayor concordancia es: " . $id_concordancia;
                            
                        
                        ?>  
                            <input type="hidden" name="concordancias[<?php echo $producto->id; ?>]" value=<?php echo $id_concordancia ?>>
                            
                            <tr>
                                <td><?php echo intval($porcentajes[$id_concordancia])."%"; ?></td>
                                <td><?php echo $id_concordancia ?></td>
                                <td><?php echo $nombre_concordancia ?></td>
                                <td><?php echo $producto->nombre; ?></td>
                                <td><?php echo $producto->id; ?></td>
                                <td><input type="checkbox" name="ids_emparentar[]" value="<?php echo $producto->id ?>"></td>
                            </tr>

                        <?php
                        }
                    }
                        ?>
            </tbody>
        </table>
    </form>

<?php
}
?>

<?php include_once "pie.php" ?>