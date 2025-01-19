<?php
include 'includes/conn.php';

session_start();
$longitud = 20;
$numero = '';
for ($i = 0; $i < $longitud; $i++) {
    $numero .= mt_rand(0, 9);
}
$_SESSION['carrito'] = array();
$_SESSION['FOLIO'] = $numero;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title></title>
    <style>
        *{
    padding: 0px;
    margin: 0px;
}

body{
    background-color: #d9d9d9;
}

section{
    display: grid;
    grid-template-rows: 1fr;
    width: 90%;
    margin-left: 5%;
    margin-top: 20px;
}

section>div{
    display: grid;
    grid-template-columns: 1fr;
    grid-column-gap: 10px;
}

section>div:nth-child(2){
    margin-top: 20px;
}

section>div>button{
    width: 40%;
    background-color: transparent;
    border-radius: 20px;
    height: 300px;
    margin-left: 30%;
}

section>div>button:hover{
    background-color: #FFFF;
    cursor: pointer;
}

section>div>button>img{
    width: 50%;
}
    </style>
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
    
    


<section>
    <div class="">
        <button onclick="window.location.href='Corte.php'">
            <img src="img/caja-registradora.png" alt="Corte Caja">
            <br>
            <h2>CORTE DE CAJA</h2>
        </button>
    </div>

    <div class="">
        <button onclick="window.location.href='Facturas.php'">
            <img src="img/archivos.png" alt="Ventas">
            <br>
            <h2>FACTURAS</h2>
        </button>
    </div>
</section>


</body>
</html>