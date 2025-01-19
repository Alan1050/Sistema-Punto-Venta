<?php
include 'includes/conn.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$_SESSION['carrito'] = array();
$_SESSION['CodProd'] = 0;
$_SESSION['CodBarr'] = 0;

$_SESSION['Precio1'] = 0;
$_SESSION['Precio2'] = 0;
$_SESSION['Precio3'] = 0;

$_SESSION['Cod'] = 0;
$_SESSION['Cod2'] = 0;
$_SESSION['iD'] = 0;
$_SESSION['iD2'] = 0;

$_SESSION['IDC'] = 0;
$_SESSION['ExistenciaC'] = 0;
$_SESSION['DescripC'] = '';
$_SESSION['MarcaC'] = '';

session_start();

$_SESSION['Descrip'] = "";

$longitud = 20;
$numero = '';
for ($i = 0; $i < $longitud; $i++) {
    $numero .= mt_rand(0, 9);
}
$_SESSION['carrito'] = array();
$_SESSION['FOLIO'] = $numero;

if ($_SESSION['Rol'] === "Administrador") {
    if ($_SESSION['facturas_consultadas'] == 1) {
        $_SESSION['facturas_consultadas'] = 0;
    $consulta = "SELECT * FROM Facturas";
    $resultado = mysqli_query($conn, $consulta);

    while ($row = mysqli_fetch_assoc($resultado)) {
        $fecha_original = $row['Fecha_Limite']; 
    $fecha_menos_dos_dias = date('Y-m-d', strtotime($fecha_original . ' -2 days'));
    $fecha_menos_un_dia = date('Y-m-d', strtotime($fecha_original . ' -1 days'));
    $fecha_actua = date('Y-m-d');
    $fecha_actual= date('Y-m-d', strtotime($fecha_actua . ' -1 days'));
    $Folio = $row['Folio'];
    $Total = $row['Total_Pagar'];

    if ($fecha_actual === $fecha_menos_dos_dias) {  
        echo "<script>
        alert('Tiene 2 días para pagar la factura con Folio: $Folio,  Total a pagar: $ $Total');
        </script>";
    } 
    if ($fecha_actual === $fecha_menos_un_dia) {
        echo "<script>
        alert('Tiene 1 días para pagar la factura con Folio: $Folio, Total a pagar: $ $Total');
        </script>";
    }
    }
}
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Dashboard.css">
    <link rel="stylesheet" href="css/header.css">
    <title>Dashboard</title>
</head>
<body>

    <header>
        <div class="el">
        <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <?php   print_r($_SESSION['nombre']) ?></h1>
        </div>
        <div class="">
            <ul>
                <li><a href="Dashboard.php">Inicio</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>
    
    
    
    <?php

if ($_SESSION['Rol'] === "Administrador") {

    ?>


<section>
    <div class="">
        <button onclick="window.location.href='Inventario.php'">
            <img src="img/inventario.png" alt="Inventario">
            <br>
            <h2>INVENTARIO</h2>
        </button>
        <button onclick="window.location.href='Proveedor.php'">
            <img src="img/proveedor.png" alt="Inventario">
            <br>
            <h2>PROVEEDORES</h2>
        </button>
        <button onclick="window.location.href='Cotizaciones.php'">
            <img src="img/lista-de-verificacion (1).png" alt="Inventario">
            <br>
            <h2>COTIZACIONES</h2>
        </button>
    </div>

    <div class="">
        <button onclick="window.location.href='VentasPrueba.php'">
            <img src="img/punto-de-venta.png" alt="Ventas">
            <br>
            <h2>VENTA</h2>
        </button>
        <button onclick="window.location.href='Buscar.php'">
            <img src="img/buscar.png" alt="Buscar Producto">
            <br>
            <h2>BUSCAR PRODUCTO</h2>
        </button>
        <button onclick="window.location.href='MenuConfi.php'">
            <img src="img/ajustes.png" alt="Ajustes">
            <br>
            <h2>MAS CONFIGURACIONES</h2>
        </button>
    </div>
</section>

    <?php
} else if ($_SESSION['Rol'] === "Cajero") {
    ?>
<section>
    <div class="">
        <button onclick="window.location.href='Inventario.php'">
            <img src="img/inventario.png" alt="Inventario">
            <br>
            <h2>INVENTARIO</h2>
        </button>
        <button onclick="window.location.href='Cotizaciones.php'">
            <img src="img/lista-de-verificacion (1).png" alt="Cotizaciones">
            <br>
            <h2>COTIZACIONES</h2>
        </button>
    </div>

    <div class="">
        <button onclick="window.location.href='Ventas.php'">
            <img src="img/punto-de-venta.png" alt="Inventario">
            <br>
            <h2>VENTA</h2>
        </button>
        <button onclick="window.location.href='Buscar.php'">
            <img src="img/buscar.png" alt="Inventario">
            <br>
            <h2>BUSCAR PRODUCTO</h2>
        </button>
    </div>
</section>
<?php
} else if ($_SESSION['Rol'] === "Bodega") {
    ?>


<section>
    <div class="">
        <button onclick="window.location.href='Inventario.php'">
            <img src="img/inventario.png" alt="Inventario">
            <br>
            <h2>INVENTARIO</h2>
        </button>
        <button onclick="window.location.href='Proveedor.php'">
            <img src="img/proveedor.png" alt="Inventario">
            <br>
            <h2>PROVEEDORES</h2>
        </button>
    </div>
</section>

    <?php
}

?>

</body>
</html>