<?php
include 'includes/conn.php';
session_start();

$Linea = "SELECT * FROM Linea";
$DatosLineas = mysqli_query($conn, $Linea);

$Marcas = "SELECT * FROM proveedores";
$DatosMarcas = mysqli_query($conn,$Marcas);
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

form{
    width: 30%;
    margin-top: 40px;
    margin-left: 5%;
}

form>div{
    padding-left: 30px;
}

form>label{
    font-size: 25px;
    padding-right: 10px;
    line-height: 30px;
}

form>input[type="text"]{
    width: 50%;
}

form>input{
    padding-left: 3px;
    height: 20px;
}

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}

input[type=number] { -moz-appearance:textfield; }

form>input[type="submit"]{
    width: 40%;
    margin-top: 20px;
    margin-left: 50%;
    height: 40px;
    font-size: 20px;
    border-radius: 20px;
    cursor: pointer;
    background-color: transparent;
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
                <li><a href="Inventario.php">Volver</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>

    <form action="DatCambio.php" method="post">
    <label for="">Linea: </label> <input type="text" list="Linea" name="Linea"> <br>
    <label for="">Marca: </label> <input type="text" list="Marca" name="Marca"> <br>
    <label for="">Porcentaje: </label> <input type="number" name="Porcentaje" id=""> <br>
    <input type="submit" value="Enviar">
    </form>

    <datalist id="Linea">
        <?php while ($row = mysqli_fetch_assoc($DatosLineas)){ ?>
        <option value="<?php echo $row['Descripcion']; ?>"><?php echo $row['Descripcion']; ?></option>
        <?php }  ?>
    </datalist>

    <datalist id="Marca">
        <option value="TODAS">TODAS</option>
        <?php while ($row2 = mysqli_fetch_assoc($DatosMarcas)){ ?>
        <option value="<?php echo $row2['Nombre']; ?>"><?php echo $row2['Nombre']; ?></option>
        <?php }  ?>
    </datalist>
</body>
</html>
