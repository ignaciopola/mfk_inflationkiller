<?php
if (!isset($_GET["numero"])) {
    exit("No hay numero");
}
$numero = $_GET["numero"];
include_once "base_de_datos.php";
$sentencia = $base_de_datos->prepare("SELECT id, fecha, total FROM ventas WHERE numeroventa = ?");
$sentencia->execute([$numero]);
$venta = $sentencia->fetchObject();
if (!$venta) {
    exit("No existe venta con el número proporcionado");
}

$sentenciaProductos = $base_de_datos->prepare("SELECT p.id, p.genetica, p.banco, p.tipo, p.precio1, p.precio2, p.presentacion1, p.presentacion2, pv.unidades, pv.presentacion
FROM semillas p
INNER JOIN 
productosvendidos pv
ON p.id = pv.idprod
WHERE pv.numeroventa = ?");
$sentenciaProductos->execute([$numero]);
$productos = $sentenciaProductos->fetchAll();
if (!$productos) {
    exit("No hay productos");
}

?>
<style>
    * {
        font-size: 12px;
        font-family: 'Times New Roman';
    }

    td,
    th,
    tr,
    table {
        border-top: 1px solid black;
        border-collapse: collapse;
    }

    td.producto,
    th.producto {
        width: 75px;
        max-width: 75px;
    }

    td.banco,
    th.banco {
        width: 50px;
        max-width: 50px;
    }

    td.tipo,
    th.tipo {
        width: 75px;
        max-width: 75px;
    }
    td.presentacion,
    th.presentacion {
        width: 20px;
        max-width: 20px;
    }

    td.cantidad,
    th.cantidad {
        width: 20px;
        max-width: 20px;
        word-break: break-all;
    }

    td.precio,
    th.precio {
        width: 70px;
        max-width: 70px;
        word-break: break-all;
        text-align: right;
    }

    .centrado {
        text-align: center;
        align-content: center;
    }

    .ticket {
        width: 350px;
        max-width: 350px;
    }

    img {
        max-width: inherit;
        width: inherit;
    }

    @media print {

        .oculto-impresion,
        .oculto-impresion * {
            display: none !important;
        }
    }
</style>

<body>
    <div class="ticket">
        <img src="./logo.png" alt="Logotipo">
        <p class="centrado">TICKET DE VENTA
            <br><?php echo $venta->fecha; ?>
        </p>
        <table>
            <thead>
                <tr>
                    <th class="cantidad">UN.</th>
                    <th class="producto">PRODUCTO</th>
                    <th class="banco">BANCO</th>
                    <th class="tipo">TIPO</th>
                    <th class="presentacion">X</th>
                    <th class="precio">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($productos as $producto) {
                    if ($producto->presentacion==$producto->presentacion1)
                    {
                        $precioreal=$producto->precio1;
                        $subtotal = $producto->precio1 * $producto->unidades;
                        $total += $subtotal;
                    } 
                    else
                    { 
                        if ($producto->presentacion==$producto->presentacion2)
                        {
                            $precioreal=$producto->precio2;
                            $subtotal = $producto->precio2 * $producto->unidades;
                            $total += $subtotal;
                        }
                    }
                ?>
                    <tr>
                        <td class="cantidad"><?php echo $producto->unidades;  ?></td>
                        <td class="producto"><?php echo $producto->genetica;  ?> <strong>$<?php echo number_format($precioreal, 0) ?></strong></td>
                        <td class="banco"><?php echo $producto->banco;  ?></td>
                        <td class="tipo"><?php echo $producto->tipo;  ?></td>
                        <td class="presentacion"><?php echo $producto->presentacion;  ?></td>
                        <td class="precio">$<?php echo number_format($subtotal, 0)  ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5" style="text-align: right;">TOTAL</td>
                    <td class="precio">
                        <strong>$<?php echo number_format($total, 0) ?></strong>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="centrado">¡GRACIAS POR SU COMPRA!
        </p>
    </div>
</body>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        window.print();
        setTimeout(() => {
            window.location.href = "./ventas.php";
        }, 1000);
    });
</script>