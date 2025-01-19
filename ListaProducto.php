<?php

session_start();

include 'includes/conn.php';

// Número de registros por página
$registrosPorPagina = 100;

// Página actual (si no se define, será la primera)
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Consulta para obtener el número total de registros
$consultaTotal = "SELECT COUNT(*) AS total FROM Productos";
$resultadoTotal = mysqli_query($conn, $consultaTotal);
$totalRegistros = mysqli_fetch_assoc($resultadoTotal)['total'];

    $consultaPagina = "SELECT * FROM Productos LIMIT $inicio, $registrosPorPagina";
    $Eje2 = mysqli_query($conn, $consultaPagina);

// Consulta para los selectores de marcas y líneas
$Marcas = "SELECT Nombre FROM proveedores";
$Datos = mysqli_query($conn, $Marcas);

$Linea = "SELECT * FROM Linea";
$DatosLineas = mysqli_query($conn, $Linea);

// Calcular el número total de páginas
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/AgProducto.css">
    <title></title>
    <style>
        *{
            padding: 0px;
            margin: 0px;
        }

        body{
            background-color: #d9d9d9;
        }

        form{
            width: 80%;
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        form>div{
            padding-left: 30px;
        }

        form>div>label{
            font-size: 25px;
            padding-right: 10px;
            line-height: 30px;
        }

        form>div>input[type="text"]{
            width: 50%;
        }

        form>div>input{
            padding-left: 3px;
            height: 20px;
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

        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
        }

        input[type=number] { -moz-appearance:textfield; }

        form>div>input[type="submit"]{
            width: 30%;
            margin-left: 80%;
            height: 40px;
            font-size: 20px;
            border-radius: 20px;
            cursor: pointer;
            background-color: #d9d9d9;
        }

        form>div>input[type="submit"]:hover{
            background-color: white;
        }

        .btn{
            width: 15%;
            font-size: 20px;
            border-radius: 30px;
            padding-top: 10px;
            padding-bottom: 10px;
            background-color: #d9d9d9;
            cursor: pointer;
            margin-left: 30px;
        }
        .btn:hover{
            background-color: white;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            text-decoration: none;
            color: black;
            background-color: #d9d9d9;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: white;
        }

        .pagination .active {
            font-weight: bold;
            background-color: #a9a9a9;
        }
        .btn{
        width: 15%;
        font-size: 20px;
        border-radius: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
        background-color: #d9d9d9;
        cursor: pointer;
        margin-left: 30px;
        }
        .btn:hover{
            background-color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="el">
            <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <span></span> <?php echo $_SESSION['nombre']; ?></h1>
        </div>
        <div class="">
            <ul>
                <li><a href="Inventario.php">Volver</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>
<br><br>
    <table border="1">
        <tr>
            <th>Codigo de Producto</th>
            <th>Descripcion</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Precio 2</th>
            <th>Precio 3</th>
            <th>Linea</th>
            <th>Existencia</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row2 = mysqli_fetch_assoc($Eje2)) { ?>
        <tr>
            <td><?php echo $row2['CodigoProducto']; ?></td>
            <td><?php echo $row2['Descripcion']; ?></td>
            <td><?php echo $row2['Marca']; ?></td>
            <td>$<?php echo $row2['Precio1']; ?></td>
            <td>$<?php echo $row2['Precio2']; ?></td>
            <td>$<?php echo $row2['Precio3']; ?></td>
            <td><?php echo $row2['Linea']; ?></td>
            <td><?php echo $row2['Existencia']; ?></td>
            <td>
                <button onclick="window.location.href='ModProd.php?id=<?php echo $row2['id_producto']; ?>'">Modificar</button><br>
                <button onclick="confirmarEliminacion(<?php echo $row2['id_producto']; ?>)">Eliminar</button>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="pagination">
    <?php if ($paginaActual > 1) { ?>
        <a href="?pagina=<?php echo $paginaActual - 1; ?>">&laquo; Anterior</a>
    <?php } ?>

    <?php
    // Mostrar las primeras 2 páginas siempre
    if ($totalPaginas > 1) {
        for ($i = 1; $i <= 2; $i++) {
            echo '<a href="?pagina=' . $i . '" class="' . ($i == $paginaActual ? 'active' : '') . '">' . $i . '</a>';
        }

        // Si estamos más allá de la página 4, mostrar "..."
        if ($paginaActual > 4) {
            echo '<span>...</span>';
        }

        // Mostrar algunas páginas cercanas a la página actual (por ejemplo, 2 antes y 2 después)
        for ($i = max(3, $paginaActual - 2); $i <= min($paginaActual + 2, $totalPaginas - 2); $i++) {
            echo '<a href="?pagina=' . $i . '" class="' . ($i == $paginaActual ? 'active' : '') . '">' . $i . '</a>';
        }

        // Si quedan más de 4 páginas después de la actual, mostrar "..."
        if ($paginaActual < $totalPaginas - 3) {
            echo '<span>...</span>';
        }

        // Mostrar las últimas 2 páginas siempre
        for ($i = $totalPaginas - 1; $i <= $totalPaginas; $i++) {
            if ($i > 2) { // Para evitar duplicados si hay pocas páginas
                echo '<a href="?pagina=' . $i . '" class="' . ($i == $paginaActual ? 'active' : '') . '">' . $i . '</a>';
            }
        }
    }
    ?>

    <?php if ($paginaActual < $totalPaginas) { ?>
        <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente &raquo;</a>
    <?php } ?>
</div>
    <br>
    <br>
</body>
</html>

<script>
function confirmarEliminacion(idProducto) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        // Si el usuario confirma, redirigir a la página de eliminación con el id del producto
        window.location.href = 'Eliminar.php?id=' + idProducto;
    }
    // Si el usuario cancela, no sucede nada y se queda en la misma página
}
</script>