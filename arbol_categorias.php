<?php 
function Farbol($tabla, $seccion, $cat="", $brand="", $bd, $competencia="") 
{
    //Verifico si hay algun filtro activado o no
    if (isset($cat)) // Filtro por categoría detectado
    {
        $filtro="Categoría: ".$cat;
        $productos_query="SELECT * FROM ".$tabla." WHERE categoria = '".$cat."';";
    } else if (isset($brand)) // Filtro por marca categoría
    {
        $filtro="Marca :".$brand;
        $productos_query="SELECT * FROM ".$tabla." WHERE marca = '".$brand."';";
    }
    else // Ningún filtro detectado
    {
        $filtro="Ningún filtro activado";
        $productos_query="SELECT * FROM ".$tabla.";";
    }

    $sentencia = $bd->query($productos_query);
    $productos = $sentencia->fetchAll(PDO::FETCH_OBJ);
    

    //Creo un array con todas las categorias existentes en la BD
    // $categorias_query = $bd->query("SELECT DISTINCT categoria FROM rosariogrow");
    $categorias_query = $bd->query("SELECT categoria, COUNT(*) as total FROM ".$tabla." GROUP BY categoria;");
    $categorias = $categorias_query->fetchAll(PDO::FETCH_OBJ);

    $marcas_query=$bd->query("SELECT marca, COUNT(*) as totalmarca FROM ".$tabla." GROUP BY marca;");
    $marcas = $marcas_query->fetchAll(PDO::FETCH_OBJ);


    

        
    //Imprimo el árbol de categorías
    echo "Categorías
            <ul>";
                foreach($categorias as $categoria)
                    {
                        if (!($competencia=="")) //Caso: Sección precios, comparando con la competencia
                        {
                            echo "<a href='".$seccion.".php?cat=".$categoria->categoria."&competencia=".$competencia."'>".$categoria->categoria." (".$categoria->total.") </a> /// ";
                        } else 
                        {
                            echo "<a href='".$seccion.".php?cat=".$categoria->categoria."'>".$categoria->categoria." (".$categoria->total.") </a> /// ";
                        }
                    }
        echo "</ul>
            Marcas:
            <ul>";
                foreach($marcas as $marca)
                    {
                        if (!($competencia=="")) //Caso: Sección precios, comparando con la competencia
                        {
                            echo "<a href='".$seccion.".php?marca=".$marca->marca."&competencia=".$competencia."'>".$marca->marca." (".$marca->totalmarca.") </a> /// ";
                        } else
                        {
                            echo "<a href='".$seccion.".php?marca=".$marca->marca."'>".$marca->marca." (".$marca->totalmarca.") </a> /// ";
                        }
                    }
        echo"</ul>";

        return $productos;
    }
?>

<script>
	let allSelected = false;

function selectAll() 
{
	var checkboxes = document.querySelectorAll('input[name="ids[]"]');
	allSelected = !allSelected;
    for (var i = 0; i < checkboxes.length; i++) 
	{
		checkboxes[i].checked = allSelected;
    }
    event.preventDefault();
}

function PasajeIDS(action) 
{
    var form = document.createElement("form");
    form.action = action + ".php";
    form.method = "get";
    
    var checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
    for (var i = 0; i < checkboxes.length; i++) 
	{
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "ids[]";
        input.value = checkboxes[i].value;
        form.appendChild(input);
    }
    if (typeof proveedor !== "undefined")
    {
        var proveedor_input=document.createElement("input")
        proveedor_input.type="hidden";
        proveedor_input.name="proveedor";
        proveedor_input.value=proveedor;
        form.appendChild(proveedor_input);
    }
    document.body.appendChild(form);
    form.submit();
    
}

function PasajePedido(action, pedido) 
{
    var form = document.createElement("form");
    form.action = action + ".php";
    form.method = "get";
    
    var checkboxes = document.querySelectorAll('input[name="ids[]"]:checked');
    for (var i = 0; i < checkboxes.length; i++) 
	{
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "ids[]";
        input.value = checkboxes[i].value;
        form.appendChild(input);
    }
    if (typeof pedido !== "undefined")
    {
        var pedido_input=document.createElement("input")
        pedido_input.type="hidden";
        pedido_input.name="pedido";
        pedido_input.value=pedido;
        form.appendChild(pedido_input);
    }

    document.body.appendChild(form);
    form.submit();
    
}
</script>