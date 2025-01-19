<?php
include 'includes/conn.php';

if (isset($_GET['folio'])) {
    $folio = $_GET['folio'];

    // Consulta para obtener los detalles de la venta
    $Consulta = "SELECT * FROM cotizaciones WHERE Folio = '$folio'";
    $query = mysqli_query($conn, $Consulta);

    if ($row = mysqli_fetch_assoc($query)) {
        $descripcion = $row['Descripcion'];
        $totalPagar = $row['Total_Pagar'];
        $Fecha = $row['Fecha'];
    } else {
        echo "Cotizacion no encontrada.";
        exit();
    }
} else {
    echo "Folio no especificado.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .ticket {
            width: 300px;
            max-width: 250px;
            padding: 10px;
            border: 1px solid #000;
            margin: 0 auto;
            text-align: center;
        }
        .ticket h2 {
            text-align: center;
        }
        .Bar{
            font-family: "Libre Barcode 39", system-ui;
            font-size: 24px;
        }
        .ticket p {
            margin: 5px 0;
        }
        .ticket .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="ticket">
    <h2>Ticket de Cotizacion</h2>
    <h3>FERRETERIA Y REFACCIONARIA DELGADO</h3>
    <p class="Bar"><?php print $folio; ?></p>
    <p><strong>Descripción:</strong> <?php print nl2br($descripcion); ?></p>
    <p class="total"><strong>Total a Pagar:</strong> $<?php print $totalPagar; ?></p>
    <p><?php echo $Fecha; ?></p>
</div>

<button onclick="window.print()">Imprimir Ticket</button>

</body>
</html>