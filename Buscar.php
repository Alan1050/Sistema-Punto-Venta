<?php
include 'includes/conn.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>


<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/Buscar.css">
    <title></title>
    <style>
        .bt{
            width: 90%;
            margin-left: 5%;
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>
<body>
    <header>
        <div class="el">
        <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <?php print_r($_SESSION['nombre']) ?> </h1>
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

            <form action="Buscar.php" method="post" autocomplete="off">
                <p>Buscar Producto</p> <input type="text" name="CodigoPro" list="list2" placeholder="Ingrese el Codigo de Barra, Nombre o Codigo de Producto" autocomplete="off">
                <button type="submit" name="CodPro"><img src="img/buscar.png" alt=""><br><p>Buscar</p></button>
                <datalist id="list2">
                <?php
                $CodPro = "SELECT * FROM productos";
                $Datos = mysqli_query($conn, $CodPro);
                while ($row = mysqli_fetch_assoc($Datos)){ ?>
                <option value="<?php echo $row['CodigoProducto']; ?>"><?php echo $row['CodigoProducto']; ?></option>
                <option value="<?php echo $row['Descripcion']; ?>"><?php echo $row['Descripcion']; ?></option>
                <option value="<?php echo $row['CodigoBarras']; ?>"><?php echo $row['CodigoBarras']; ?></option>
                <?php }  ?>
            </datalist>
            </form>

        </div>
    </section>

    
    <table border="1">
        <tr>
            <th>Codigo de Barras</th>
            <th>Codigo de Producto</th>
            <th>Descripcion</th>
            <th>Marca</th>
            <th>Precio 1</th>
            <th>Precio 2</th>
            <th>Ultima Fecha</th>
            <th>Presentacion</th>
            <th>Existencia</th>
            <th colspan="2">Acciones:</th>
        </tr>

        <?php
if (isset($_POST['CodigoPro'])) {
    $CodiPro = str_replace("'", "-", $_POST['CodigoPro']); // Primero reemplaza
    $CodiProN = mysqli_real_escape_string($conn, $CodiPro); // Luego escapa los caracteres

    // Consulta para obtener el producto basado en CodigoProducto
    $Consulta30 = "SELECT * FROM productos WHERE CodigoProducto = '$CodiPro'";
    $query30 = mysqli_query($conn, $Consulta30);

    $Consulta31 = "SELECT * FROM productos WHERE CodigoBarras = '$CodiProN'";
    $query31 = mysqli_query($conn, $Consulta31);

    $Consulta32 = "SELECT * FROM productos WHERE Descripcion = '$CodiPro'";
    $query32 = mysqli_query($conn, $Consulta32);
    
if ($query30 && mysqli_num_rows($query30) > 0) {
    $Cod = $query30;
    while ($row3 = mysqli_fetch_assoc($Cod)) {
        ?>
        <tr>
            <td><?php echo $row3['CodigoBarras']; ?></td>
            <td><?php echo $row3['CodigoProducto']; ?></td>
            <td><?php echo $row3['Descripcion']; ?></td>
            <td><?php echo $row3['Marca']; ?></td>
            <td>$<?php echo $row3['Precio1']; ?></td>
            <td>$<?php echo $row3['Precio2']; ?></td>
            <td><?php echo $row3['UltimaFecha']; ?></td>
            <td><?php echo $row3['Presentacion']; ?></td>
            <td><?php echo $row3['Existencia']; ?></td>
            <td><button onclick="window.location.href='ModProd2.php?id=<?php echo $row3['id_producto']; ?>'" class="bt">Modificar</button></td>
            <td><button onclick="confirmDeletion(<?php echo $row3['id_producto']; ?>)" class="bt">Eliminar</button>   </td>    
        </tr>
        <?php
    }
} else if ($query31 && mysqli_num_rows($query31) > 0) {
    $Cod = $query31;
    while ($row3 = mysqli_fetch_assoc($Cod)) {
        ?>
        <tr>
            <td><?php echo $row3['CodigoBarras']; ?></td>
            <td><?php echo $row3['CodigoProducto']; ?></td>
            <td><?php echo $row3['Descripcion']; ?></td>
            <td><?php echo $row3['Marca']; ?></td>
            <td>$<?php echo $row3['Precio1']; ?></td>
            <td>$<?php echo $row3['Precio2']; ?></td>
            <td><?php echo $row3['UltimaFecha']; ?></td>
            <td><?php echo $row3['Presentacion']; ?></td>
            <td><?php echo $row3['Existencia']; ?></td>
            <td><button onclick="window.location.href='ModProd2.php?id=<?php echo $row3['id_producto']; ?>'" class="bt">Modificar</button></td>
            <td><button onclick="confirmDeletion(<?php echo $row3['id_producto']; ?>)" class="bt">Eliminar</button>   </td>    
        </tr>
        <?php
    }
} else if ($query32 && mysqli_num_rows($query32) > 0) {
    $Cod = $query32;
    while ($row3 = mysqli_fetch_assoc($Cod)) {
        ?>
        <tr>
            <td><?php echo $row3['CodigoBarras']; ?></td>
            <td><?php echo $row3['CodigoProducto']; ?></td>
            <td><?php echo $row3['Descripcion']; ?></td>
            <td><?php echo $row3['Marca']; ?></td>
            <td>$<?php echo $row3['Precio1']; ?></td>
            <td>$<?php echo $row3['Precio2']; ?></td>
            <td><?php echo $row3['UltimaFecha']; ?></td>
            <td><?php echo $row3['Presentacion']; ?></td>
            <td><?php echo $row3['Existencia']; ?></td>
            <td><button onclick="window.location.href='ModProd2.php?id=<?php echo $row3['id_producto']; ?>'" class="bt">Modificar</button></td>
            <td><button onclick="confirmDeletion(<?php echo $row3['id_producto']; ?>)" class="bt">Eliminar</button>   </td>    
        </tr>
        <?php
    }
} ?>


    <tr>
        
    </tr>
    <?php 
    $CodiPro = str_replace("'", "-", $_POST['CodigoPro']); // Primero reemplaza
    $CodiProN = mysqli_real_escape_string($conn, $CodiPro); // Luego escapa los caracteres
    $ConsultaRelacionados = "SELECT * FROM productos WHERE CodigoPrincipal = '$CodiProN'";

    $queryRelacionados = mysqli_query($conn, $ConsultaRelacionados);
    if(mysqli_num_rows($queryRelacionados) > 0){
        ?>
        <td colspan="11">CONVERSIONES:</td>
        <?php
        while ($rowRelacionado = mysqli_fetch_assoc($queryRelacionados)) {
            echo "<tr>
                    <td>{$rowRelacionado['CodigoBarras']}</td>
                    <td>{$rowRelacionado['CodigoProducto']}</td>
                    <td>{$rowRelacionado['Descripcion']}</td>
                    <td>{$rowRelacionado['Marca']}</td>
                    <td>\${$rowRelacionado['Precio1']}</td>
                    <td>\${$rowRelacionado['Precio2']}</td>
                    <td>{$rowRelacionado['UltimaFecha']}</td>
                    <td>{$rowRelacionado['Presentacion']}</td>
                    <td>{$rowRelacionado['Existencia']}</td>
                    <td><button onclick=\"window.location.href='ModProd2.php?id={$rowRelacionado['id_producto']}'\" class='bt'>Modificar</button></td>
                    <td><button onclick=\"window.location.href='Eliminar2.php?id={$rowRelacionado['id_producto']}'\" class='bt'>Eliminar</button></td>
                </tr>";
        }
    } else{
        ?>
        <td colspan="11">SIN CONVERSIONES EXISTENTES</td>
    <?php
    }

}
?>

    </table>
<br> <br>
</body>
</html>

<script>
function confirmDeletion(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        // Si el usuario confirma, redirigir a Eliminar2.php con el ID
        window.location.href = 'Eliminar2.php?id=' + id;
    } 
    // Si no confirma, no hace nada
}
</script>

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
    grid-template-columns: 1fr 1fr;
    margin-top: 20px;
    width: 100%;
}

input{
    margin-top: 10px;
    margin-bottom: 10px;
    height: 25px;
    width: 70%;
    margin-left: 10%;
    padding-left: 5px;
    font-size: 20px;
}

section div{
    width: 100%;
}

h2{
    line-height: 1.5;
}

form >p{
    padding-left: 20px;
    font-size: 25px;
}

button{
    width: 20%;
    margin-left: 10%;
    margin-top: 10px;
    border-radius: 20px;
    cursor: pointer;
    border: 2px solid;
    background-color: #d9d9d9;
    margin-left: 25%;
}

button:hover{
    background-color: white;
}

button:hover> img{
    transform: scale(1.2);
}

button>img{
    width: 30%;
    padding-top: 10px;
    padding-bottom: 10px;
}

button>p{
    padding-bottom: 10px;
    font-size: 25px;
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
table>tr>td>button{
    width: 90%;
}

</style>