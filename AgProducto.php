<?php

session_start();

include 'includes/conn.php';

$Marcas = "SELECT Nombre FROM proveedores";
$Datos = mysqli_query($conn, $Marcas);

$Linea = "SELECT * FROM Linea";
$DatosLineas = mysqli_query($conn, $Linea);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/AgProducto.css">
    <title></title>
    <!-- CSS de Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<!-- JS de Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
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

    <form action="DatProd.php" method="POST" autocomplete="off">
        <div>
            <label>Codigo de Barras:</label> <input type="text" name="CodBarras"><br>
            <label>Codigo del Producto:</label> <input type="text" style="text-transform:uppercase" name="CodProd"><br>
            <label>Descripcion:</label> <input type="text" style="text-transform:uppercase" name="Desc"><br>
            <label>Presentacion:</label> <input type="text" list="list" name="Prese"><br>
            <label>Marca:</label> <input type="text" list="list2" name="Marca"><br>
            <label>Minimo Requerido:</label> <input type="number" name="MinReq"><br>
            <label>Ultima Fecha:</label> <input id="monthPicker" type="text" name="UltFecha"><br>

            <script>
    flatpickr("#monthPicker", {
        defaultDate: new Date(), // Establece el mes y año actual como predeterminado
        plugins: [
            new monthSelectPlugin({
                shorthand: true,      // Mostrar nombres abreviados
                dateFormat: "m-Y",    // Formato de envío: Año-Mes (ejemplo: 2024-01)
                altFormat: "F Y"      // Formato visual: Enero 2024
            })
        ]
    });
</script><br>
        </div>
        <div>
            <label>Precio 1:</label> <input type="number" name="Precio1" step="0.01" value="0"><br>
            <label>Precio 2:</label> <input type="number" name="Precio2" step="0.01" value="0"><br>
            <label>Precio 3:</label> <input type="number" name="Precio3" step="0.01" value="0"><br>
            <label>Existencia:</label> <input type="number" name="Exi"><br>
            <label>Linea:</label> <input type="text" list="list4" name="Linea"><br>
            <label>Codigo Principal:</label> <input type="text" name="Rela"><br><br>
            <input type="submit" value="Agregar">
        </div>
    </form>

    <button onclick="window.location.href='AgLinea.php'" class="btn">AGREGAR LINEA</button>

    <datalist id="list">
        <option value="GALON">GALON</option>
        <option value="LITRO">LITRO</option>
        <option value="PIEZA">PIEZA</option>
        <option value="CAJA">CAJA</option>
    </datalist>
    
    <datalist id="list2">
        <?php while ($row = mysqli_fetch_assoc($Datos)) { ?>
        <option value="<?php echo $row['Nombre']; ?>"><?php echo $row['Nombre']; ?></option>
        <?php } ?>
    </datalist>

    <datalist id="list4">
        <?php while ($row = mysqli_fetch_assoc($DatosLineas)) { ?>
        <option value="<?php echo $row['Linea']; ?>"><?php echo $row['Descripcion']; ?></option>
        <?php } ?>
    </datalist>

</body>
</html>

