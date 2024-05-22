<?php include_once "encabezado.php" ?>
<?php 

    include_once "base_de_datos.php";
    include_once "arbol_categorias.php"; 
    $tabla="stockmfk";
    $seccion="precios";
?>
<script>
    document.querySelector("title").innerHTML = "Precios Controlador";
	let allSelected = false;
	// let proveedor="rosariogrow";

function selectAll() 
{
	var checkboxes = document.querySelectorAll('input[name="ids[]"]');
	allSelected = !allSelected;
    for (var i = 0; i < checkboxes.length; i++) 
	{
		checkboxes[i].checked = allSelected;
    }
}

</script>
<?php





//PASO 2 DE ESTA PÁGINA: ACTUALIZAR LOS PRECIOS SI YA FUERON DETERMINADOS
if (isset($_GET['ids']))
{
    echo "<b>Se procede a la actualización de los precios</b><br>";
    $precios=$_GET['precio'];
    $ids_actualizar=$_GET['ids'];
    $i=0;
    $fechaActual = date("Y-m-d");
    foreach($ids_actualizar as $ide)
    {
        $actualizacion_query="UPDATE stockmfk SET precio='".$precios[$ide]."', actualizado='".$fechaActual."' WHERE id = ".$ide;
        $sentencia = $base_de_datos->prepare($actualizacion_query);
        $resultado = $sentencia->execute();
        $i++;
    }
    echo "<b>Se han actualizado ".$i." precios</b><br>";
}

//PASO 3 DE ESTA PÁGINA: COMPARAR PRODUCTOS CON LA COMPETENCIA
if (isset($_GET['competencia']))
{
    $competidor=$_GET['competencia'];
    echo "Ha elegido comparar su stock con el de: ".$competidor;
    if ($competidor=="todos")
    {
        //Veremos

    } else
    {
        $prod_competencia_query="SELECT * FROM ".$competidor." WHERE idstock IS NOT NULL;";
        $sentencia=$base_de_datos->query($prod_competencia_query);
        $prod_competencia=$sentencia->fetchAll(PDO::FETCH_OBJ);
    }
}

?>
<div class="col-xs-12">
            <h1>Controlador de Precios</h1>
            <!-- //Muestro la competencia para comparar-->
            <form action="precios.php" method="get">
                <label for="competencia">Selecciona una competencia:</label>
                <select name="competencia" id="competencia">
                    <?php 
                        $competencias_query = $base_de_datos->query("SELECT nombre, tabla FROM competencia;");
                        $competencias = $competencias_query->fetchAll(PDO::FETCH_OBJ);
                        foreach ($competencias as $competencia) 
                        {
                            echo '<option value="' . $competencia->tabla . '">' . $competencia->nombre . '</option>';
                        } 
                    ?>
                    <option value="todos">Todos</option>
                </select>
                <input type="submit" value="Comparar">
            </form>
            
            <?php 
                
                $productos=Farbol($tabla, $seccion, $_GET["cat"],$_GET["marca"], $base_de_datos, $competidor); 
            ?>
            
            <br>
            <form name="actualizarprecios" action="precios.php" method="get">
            
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Stock</th>
                            <th>Estado Proveedor</th>
                            <th>Fecha última actualización</th>
                            <th>Costo</th>
                            <th>Costo U$S</th>
                            <th>Precio</th>
                            <th>P.Sugerido</th>
                            <th>Link</th>
                            <?php
                                if (isset($competidor))
                                {
                                    if ($competidor=="todos")
                                    {
                                        foreach ($competencias as $competencia)
                                        {
                                            echo "<th>".$competencia->nombre."</th>";
                                        }
                                    } else
                                    {
                                        echo "<th>Precio ".$competidor."</th>";
                                    }
                                }
                            ?>
                            <th>Imagen</th>
                            
                            <th>
                                <button onclick="submitForm('actualizarprecios')">Actualizar Precios</button><br>
                                <button onclick="selectAll()">Seleccionar todos</button>	
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($productos as $producto)
                            { 
                                //Tomo los valores de StockMFK
                                $precio=$producto->precio;
                                $costo=$producto->costo;
                                $costous=$producto->costous;

                                $ganancianeta=$precio-($costous*$dolar);
                                $margen=($precio/($costous*$dolar)-1)*100;
                                $margen=intval($margen);


                                $sugerido=$producto->preciosugerido;
                                $margensugerido=(($sugerido/$costo)-1)*100;
                                $margensugerido=intval($margensugerido);
                                $gananciasugerido=$sugerido-$costo;

                                if (isset($competidor)) //Tomo el precio de la competencia para cada producto de mi stock emparentado
                                {
                                    if (!($competidor=="todos"))
                                    {
                                        foreach($prod_competencia as $prodcomp)
                                        {
                                            if ($prodcomp->idstock==$producto->id)
                                            {
                                                $precio_competencia=$prodcomp->precio;
                                                break;
                                            } else { $precio_competencia=0; }
                                        }
                                    }
                                }
                            
                            ?>
                            <tr>
                                <td><?php echo $producto->id ?></td>
                                <td><?php echo $producto->nombre ?></td>
                                <td><?php echo $producto->stock ?></td>
                                <td><?php echo $producto->estado ?></td>
                                <td>
                                    <?php 
                                        $fechaFormateada = date('d/m/Y', strtotime($producto->actualizado));
                                        echo $fechaFormateada;
                                    ?>
                                </td>
                                <td><?php echo "<b>".number_format($costo, 0,",",".")."$</b>" ?></td>
                                <td><?php echo "<b>".number_format($costous, 2,",",".")."$</b>" ?></td>
                                <td>
                                    <input type="text" name="precio[<?php echo $producto->id ?>]" value=<?php echo $precio ?>>
                                    
                                    <br><b>Margen: <?php echo $margen; ?>%
                                    <br>Ganancia: <?php echo $ganancianeta; ?> $</b>
                                    
                                </td>
                                <td>
                                    <?php echo "<b>".number_format($sugerido, 0,",",".")."$</b>"  ?>
                                    <br><b>Margen: <?php echo $margensugerido; ?>%
                                    <br>Ganancia: <?php echo number_format($gananciasugerido, 0,",",".") ?> $</b>
                                </td>
                                <td><a href='<?php echo $producto->link ?>' target="_blank">Link al proveedor</a></td>
                                <?php
                                    $existe_pariente=false;
                                    if (isset($competidor))
                                    {
                                        if ($competidor=="todos")
                                        {
                                            foreach ($competencias as $competencia)
                                            {
                                                $cada_competencia_query="SELECT * FROM ".$competencia->tabla." WHERE idstock IS NOT NULL;";
                                                $sentencia=$base_de_datos->query($cada_competencia_query);
                                                $cada_competencia=$sentencia->fetchAll(PDO::FETCH_OBJ);
                                                foreach ($cada_competencia as $cada)
                                                {
                                                    if ($cada->idstock==$producto->id)
                                                    {
                                                        $precio_competencia=$cada->precio;
                                                        $margen=(($precio_competencia/$costo)-1)*100;
                                                        $margen=intval($margen);
                                                        $ganancia=$precio_competencia-$costo;
                                                        echo "<td><b>";
                                                        echo number_format($precio_competencia, 0,",",".")." $<br>"; 
                                                        
                                                        echo "Margen: ".$margen." %<br>";
                                                        echo "Ganancia: ".number_format($ganancia, 0,",",".")." $";
                                                        echo "</b></td>";
                                                        $existe_pariente=true;
                                                    } 
                                                }
                                                if ($existe_pariente==false)
                                                    { 
                                                        $precio_competencia=0; 
                                                        $margen=(($precio_competencia/$costo)-1)*100;
                                                        $margen=intval($margen);
                                                        $ganancia=$precio_competencia-$costo;
                                                        echo "<td><b>";
                                                        echo number_format($precio_competencia, 0,",",".")." $<br>"; 
                                                        
                                                        echo "Margen: ".$margen." %<br>";
                                                        echo "Ganancia: ".number_format($ganancia, 0,",",".")." $";
                                                        echo "<br>Estoy tirando esto<br>";
                                                        echo "</b></td>";
                                                    }
                                            }
                                        } else //Caso una sola competencia
                                        {
                                            $margen=(($precio_competencia/$costo)-1)*100;
                                            $margen=intval($margen);
                                            $ganancia=$precio_competencia-$costo;
                                            echo "<td><b>";
                                            echo number_format($precio_competencia, 0,",",".")." $<br>"; 
                                            
                                            echo "Margen: ".$margen." %<br>";
                                            echo "Ganancia: ".number_format($ganancia, 0,",",".")." $";
                                            echo "</b></td>";
                                        }
                                    }
                                ?>
                                <td><img src='<?php echo $producto->imagen ?>' height="80" width="80"></td>
                                <td><input type="checkbox" name="ids[]" value="<?php echo $producto->id ?>"></td>
                            </tr>
                        <?php 
                        } 
                        ?>
                    </tbody>
                </table>

            </form>
        </div>

<?php include_once "pie.php" ?>