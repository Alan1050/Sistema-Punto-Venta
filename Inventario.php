<?php

session_start();
$_SESSION['Cod'] = 0;
$_SESSION['Precio1'] = 0.0;
$_SESSION['Precio2'] = 0.0;
$_SESSION['Precio3'] = 0.0;
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
    width: 80%;
    margin-left: 10%;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-column-gap: 20px;
    margin-top: 50px;
}

section>div{
    display: grid;
    grid-template-rows: 1fr 1fr;
    grid-row-gap: 20px;
}

section>div>button{
    border-radius: 20px;
    background-color: transparent;
    cursor: pointer;
}

section>div>button:hover{
    background-color: #FFFF;
}

section>div>button>img{
    width: 30%;
    height: auto;
    padding-top: 20px;
    padding-bottom: 20px;
}

section>div>button>h2{
    padding-bottom: 20px;
}
    </style>
</head>
<body>
    <header>
        <div class="el">
            <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@,  <span></span> <?php print_r($_SESSION['nombre']) ?> </h1>
        </div>
        <div class="">
            <ul>
                <li><a href="Dashboard.php">Inicio</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>

   <section>

        <div>
        <button onclick="window.location.href='AgProducto.php'">
            <img src="img/mas.png" alt="">
            <br>
            <h2>AGREGAR PRODUCTO</h2>
        </button>

        <button onclick="window.location.href='AgExistenciaRes.php'">
            <img src="img/embalaje.png" alt="">
            <br>
            <h2>AGREGAR EXISTENCIA</h2>
        </button>
        </div>
        
        <div>

        <button onclick="window.location.href='PorAgotarse.php'">
            <img src="img/vendido.png" alt="">
            <br>
            <h2>POR AGOTARSE</h2>
        </button>  

        <button onclick="window.location.href='CambioMasivo.php'">
            <img src="img/precio-alto.png" alt="">
            <br>
            <h2>CAMBIO DE PRECIO MASIVO</h2>
        </button> 

        </div>

        <div>
            
        <button onclick="window.location.href='ListaProducto.php'">
            <img src="img/archivos.png" alt="">
            <br>
            <h2>LISTA DE PRODUCTOS</h2>

            <button onclick="window.location.href='Devoluciones.php'">
            <img src="img/devoluciones-faciles.png" alt="">
            <br>
            <h2>DEVOLUCIONES</h2>
        </button> 

        </div>

   </section>
<br><br>
</body>
</html>