<?php
include 'includes/conn.php';

if (isset($_GET['folio'])) {
    $folio = $_GET['folio'];

    // Consulta para obtener los detalles de la venta
    $Consulta = "SELECT * FROM ventas WHERE Folio = '$folio'";
    $query = mysqli_query($conn, $Consulta);

    if ($row = mysqli_fetch_assoc($query)) {
        $descripcion = $row['Descripcion'];
        $totalPagar = $row['TotalPagar'];
        $Fecha = $row['Fecha'];
    } else {
        echo "Venta no encontrada.";
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
    <title>Ticket de Venta</title>
    <script src="https://cdn.neodynamic.com/jsprintmanager/2.0.0/jsprintmanager.js"></script>
</head>
<body>

<div id="ticket" class="ticket">
    <h2>Ticket de Venta</h2>
    <h3>FERRETERIA Y REFACCIONARIA DELGADO</h3>
    <p class="Bar"><?php echo $folio; ?></p>
    <p><strong>Descripción:</strong> <?php echo nl2br($descripcion); ?></p>
    <p class="total"><strong>Total a Pagar:</strong> $<?php echo $totalPagar; ?></p>
    <p><?php echo $Fecha; ?></p>
</div>

<button onclick="imprimirTicket()">Imprimir Ticket</button>

<script>
    JSPM.JSPrintManager.auto_reconnect = true;
    JSPM.JSPrintManager.start();

    async function imprimirTicket() {
        if (JSPM.JSPrintManager.websocket_status) {
            var cpj = new JSPM.ClientPrintJob();
            cpj.clientPrinter = new JSPM.InstalledPrinter('XPrinter'); // Asegúrate de que el nombre de la impresora sea correcto

            var esc = '\x1B';  // ESC byte
            var newline = '\x0A';  // LF byte

            // Contenido del ticket
            var ticket = esc + '@' + newline; // Initialize Printer
            ticket += "FERRETERIA Y REFACCIONARIA DELGADO" + newline;
            ticket += "Folio: <?php echo $folio; ?>" + newline;
            ticket += "Fecha: <?php echo $Fecha; ?>" + newline;
            ticket += "Descripcion: <?php echo nl2br($descripcion); ?>" + newline;
            ticket += "Total a Pagar: $<?php echo $totalPagar; ?>" + newline;
            ticket += esc + 'd' + '\x03';  // Feed 3 lines
            ticket += esc + 'i';  // Cut

            // Añadir comandos al trabajo de impresión
            cpj.printerCommands = ticket;
            cpj.sendToClient();
        } else {
            alert('JSPrintManager no está conectado.');
        }
    }
</script>

</body>
</html>