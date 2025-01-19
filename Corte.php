<?php
session_start();
include 'includes/conn.php'; // Incluye tu archivo de conexión a la base de datos

// Consulta para obtener todas las ventas del día
$query = "SELECT NombreEmpleado, Descripcion, Folio, TotalPagar FROM ventas WHERE DATE(Fecha) = CURDATE()";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/Corte.css">
    <title>Ventas del Día</title>
    <style>
        section h1{
            margin-top: 20px;
            margin-bottom: 20px;
            padding-left: 20px;
        }
        h2{
            padding-left: 80%;
            margin-top: 20px;
        }
        table {
            width: 95%;
            border-collapse: collapse;
            margin-left: 2.5%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <div class="el">
        <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <?php print_r($_SESSION['nombre']); ?> </h1>
        </div>
        <div class="">
            <ul>
                <li><a href="MenuConfi.php">Volver</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>

    <section>
        <h1>Ventas del Día</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Nombre Empleado</th>
                    <th>Descripción</th>
                    <th>Folio</th>
                    <th>Total a Pagar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Reemplazar los paréntesis en la descripción con saltos de línea
                        $descripcion = nl2br($row['Descripcion']);
                        echo "<tr>";
                        echo "<td>{$row['NombreEmpleado']}</td>";
                        echo "<td>{$descripcion}</td>";
                        echo "<td>{$row['Folio']}</td>";
                        echo "<td>$ {$row['TotalPagar']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No se encontraron ventas para hoy.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
    <?php

// Consulta para sumar todos los totales de hoy
$query = "SELECT SUM(TotalPagar) AS TotalHoy FROM ventas WHERE DATE(Fecha) = CURDATE()";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $totalHoy = $row['TotalHoy'];
    echo "<h2> Total Vendido: $" . number_format($totalHoy, 2). "</h2>";
} else {
    echo "No se encontraron ventas para hoy.";
}

?>
</body>
</html>

