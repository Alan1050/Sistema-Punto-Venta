<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$_SESSION['Cod'] = 0;
$_SESSION['Precio'] = 0.0;

include 'includes/conn.php';

// Consulta SQL para obtener los productos con existencia igual o menor que la existencia mínima
$Datos2 = "SELECT 
                productos.id_producto,
                productos.CodigoBarras, 
                productos.CodigoProducto, 
                productos.Descripcion, 
                productos.Marca, 
                productos.Existencia, 
                productos.ExistenciaMinima,
                proveedores.NumeroTelefono AS TelefonoMarca
            FROM 
                productos 
            JOIN 
                proveedores 
            ON 
                productos.Marca = proveedores.Nombre
            WHERE 
                productos.Existencia <= productos.ExistenciaMinima";

$Eje = mysqli_query($conn, $Datos2);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>Lista de Productos</title>

    <style>
        * {
            padding: 0px;
            margin: 0px;
        }

        body {
            background-color: #d9d9d9;
        }

        table {
            margin-top: 20px;
            width: 98%;
            border-collapse: collapse;
            margin-left: 1%;
        }

        th {
            width: 12%; /* 100% dividido entre 7 columnas */
            text-align: left;
            padding: 8px;
            border: 1px solid black;
            text-align: center;
        }

        td {
            padding: 8px;
            border: 1px solid black;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="el">
            <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <span><?php print_r($_SESSION['nombre']) ?></span></h1>
        </div>
        <div class="">
            <ul>
                <li><a href="Inventario.php">Volver</a></li>
                <li><a href="index.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </header>
    <br><br>

    <table border="1">
        <tr>
            <th>Codigo de Barras</th>
            <th>Codigo de Producto</th>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Existencia</th>
            <th>Existencia Mínima</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row2 = mysqli_fetch_assoc($Eje)) { ?>
            <tr>
                <td><?php echo $row2['CodigoBarras']; ?></td>
                <td><?php echo $row2['CodigoProducto']; ?></td>
                <td><?php echo $row2['Descripcion']; ?></td>
                <td><?php echo $row2['Marca']; ?></td>
                <td><?php echo $row2['Existencia']; ?></td>
                <td><?php echo $row2['ExistenciaMinima']; ?></td>
                <td><button onclick="agregarPedido(<?php echo $row2['id_producto']; ?>)">Agregar al Pedido</button></td>
            </tr>
        <?php } ?>
    </table>
    <br><br>
</body>
</html>

<script>
function agregarPedido(idProducto) {
    if (confirm("¿Estás seguro de que deseas agregar este producto al pedido?")) {
        // Si el usuario confirma, redirigir a la página correspondiente con el id del producto
        window.location.href = 'AgregarPedido.php?id=' + idProducto;
    }
    // Si el usuario cancela, no sucede nada y se queda en la misma página
}
</script>