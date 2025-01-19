<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'includes/conn.php';


$CodBarras = isset($_POST['CodBarras']) ? mysqli_real_escape_string($conn, $_POST['CodBarras']) : '';
$CodProd = isset($_POST['CodProd']) ? mysqli_real_escape_string($conn, $_POST['CodProd']) : '';
$Desc = isset($_POST['Desc']) ? mysqli_real_escape_string($conn, $_POST['Desc']) : '';
$Prese = isset($_POST['Prese']) ? mysqli_real_escape_string($conn, $_POST['Prese']) : '';
$Marca = isset($_POST['Marca']) ? mysqli_real_escape_string($conn, $_POST['Marca']) : '';
$Precio1 = isset($_POST['Precio1']) ? mysqli_real_escape_string($conn, $_POST['Precio1']) : '';
$Exi = isset($_POST['Exi']) ? mysqli_real_escape_string($conn, $_POST['Exi']) : '';
$Precio2 = isset($_POST['Precio2']) ? mysqli_real_escape_string($conn, $_POST['Precio2']) : '';
$Precio3 = isset($_POST['Precio3']) ? mysqli_real_escape_string($conn, $_POST['Precio3']) : '';
$Linea = isset($_POST['Linea']) ? mysqli_real_escape_string($conn, $_POST['Linea']) : '';
$MinRequerido = isset($_POST['MinReq']) ? mysqli_real_escape_string($conn, $_POST['MinReq']) : '';
$UltFecha = isset($_POST['UltFecha']) ? mysqli_real_escape_string($conn, $_POST['UltFecha']) : '';

if (isset($_POST['Rela'])) {
  $Rela = $_POST['Rela'];
} else {
  $Rela = "NULL";
}


// Verificar que todos los campos estén llenos
if (empty($CodBarras) || empty($CodProd) || empty($Desc) || empty($Prese) || empty($Marca) || empty($Exi)) {
    echo "<script type='text/javascript'>
    alert('Por favor, completa todos los campos.');
    window.location.href = 'AgProducto.php';
  </script>";
exit();
}

// Preparar la consulta SQL
$Descripcion = strtoupper($Desc);
$CodBarrasNew = str_replace("'", "-", $CodBarras);
$InsertProdNew = "INSERT INTO Productos (CodigoBarras, CodigoProducto, CodigoPrincipal, Descripcion, Presentacion, Marca, Precio1, Precio2, Precio3, Existencia, Linea, ExistenciaMinima, UltimaFecha) 
                  VALUES ('$CodBarrasNew', '$CodProd','$Rela', '$Descripcion', '$Prese', '$Marca', '$Precio1','$Precio2', '$Precio3', '$Exi', '$Linea', '$MinRequerido', '$UltFecha')";

// Ejecutar la consulta
if (mysqli_query($conn, $InsertProdNew)) {
    echo '<script>
            alert("Registro exitoso");
            window.location.href="AgProducto.php";
          </script>';
} else {
    echo "Error: " . $InsertProdNew . "<br>" . mysqli_error($conn);
}
