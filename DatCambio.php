<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/conn.php';


$Linea = isset($_POST['Linea']) ? mysqli_real_escape_string($conn, $_POST['Linea']) : '';
$Marca = isset($_POST['Marca']) ? mysqli_real_escape_string($conn, $_POST['Marca']) : '';
$Porcentaje = isset($_POST['Porcentaje']) ? mysqli_real_escape_string($conn, $_POST['Porcentaje']) : '';

if ($Marca === "TODAS") {
    $Consulta = "SELECT * FROM productos";
    $query = mysqli_query($conn, $Consulta);
    while ($row = mysqli_fetch_assoc($query)){
        $IdProducto = $row['id_producto'];
        $Precio1 = $row['Precio1'];
        $Precio2 = $row['Precio2'];
        $Precio3 = $row['Precio3'];
        $Por = $Porcentaje/100;

        $Precio1 = ($Precio1 * $Por) + $Precio1;
        $Precio2 = ($Precio2 * $Por) + $Precio2;
        $Precio3 = ($Precio3 * $Por) + $Precio3;

        $Mejora = "UPDATE productos SET Precio1='$Precio1', Precio2='$Precio2', Precio3='$Precio3' WHERE id_producto = $IdProducto";
        $Ejecucion = mysqli_query($conn, $Mejora);
    }
    if ($Ejecucion) {
        echo '<script>
        alert("Mejora realizada");
        window.location.href="CambioMasivo.php";
        </script>';
    } else{
        echo '<script>
        alert("No hubo cambios");
        window.location.href="CambioMasivo.php";
        </script>';
    }

} else{
    $Consulta = "SELECT * FROM productos";
    $query = mysqli_query($conn, $Consulta);
    while ($row = mysqli_fetch_assoc($query)){
        $IdProducto = $row['id_producto'];
        $Precio1 = $row['Precio1'];
        $Precio2 = $row['Precio2'];
        $Precio3 = $row['Precio3'];
        $Por = $Porcentaje/100;

        $Precio1 = ($Precio1 * $Por) + $Precio1;
        $Precio2 = ($Precio2 * $Por) + $Precio2;
        $Precio3 = ($Precio3 * $Por) + $Precio3;

        $Mejora = "UPDATE productos SET Precio1='$Precio1', Precio2='$Precio2', Precio3='$Precio3' WHERE id_producto = $IdProducto AND Marca = $Marca";
        $Ejecucion = mysqli_query($conn, $Mejora);
    }
    if ($Ejecucion) {
        echo '<script>
        alert("Mejora realizada");
        window.location.href="CambioMasivo.php";
        </script>';
    } else{
        echo '<script>
        alert("No hubo cambios");
        window.location.href="CambioMasivo.php";
        </script>';
    }
}

?>