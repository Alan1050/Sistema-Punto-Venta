<?php
include 'includes/conn.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Inicializar la variable de sesión para el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}
$_SESSION['carrito'] = array();

$formularioVisible = false;

// Lógica para agregar productos al carrito
if (isset($_POST['agregar'])) {
    
    if ($_SESSION['iD'] > 0) {
        $CodBarra = $_POST['CodBarra'];
        $I = $_SESSION['iD'];
        $CodBarrasNew = str_replace("'", "-", $CodBarra);
        $cantidad = $_POST['cantidad'];
    
        // Consulta para obtener los detalles del producto
        $Consulta = "SELECT * FROM productos WHERE CodigoBarras = '$CodBarrasNew' AND id_producto = '$I'";
        $query = mysqli_query($conn, $Consulta);
    
        if ($row2 = mysqli_fetch_assoc($query)) {
            $producto = array(
                'CodigoBarras' => $CodBarrasNew,
                'Descripcion' => $row2['Descripcion'],
                'Marca' => $row2['Marca'],
                'Presentacion' => $row2['Presentacion'],
                'Precio' => $_POST['Precio'],
                'Cantidad' => $cantidad
            );
    
            // Agregar el producto al carrito
            $_SESSION['carrito'][] = $producto;
            $_SESSION['iD'] =0;
            header('Ventas.php');
        }
    } else if ($_SESSION['iD'] === 0) {

    $Barras = $_SESSION['CodigoBarras'];
    $cantidad = $_POST['cantidad'];

    // Consulta para obtener los detalles del producto
    $Consulta = "SELECT * FROM productos WHERE CodigoBarras = '$Barras'";
    $query = mysqli_query($conn, $Consulta);

    if ($row3 = mysqli_fetch_assoc($query)) {
        $producto2 = array(
            'CodigoBarras' => $CodBarrasNew,
            'Descripcion' => $row3['Descripcion'],
            'Marca' => $row3['Marca'],
            'Presentacion' => $row3['Presentacion'],
            'Precio' => $_POST['Precio'],
            'Cantidad' => $cantidad
        );

        // Agregar el producto al carrito
        $_SESSION['carrito'][] = $producto2;
        $_SESSION['iD'] =0;
        header('Ventas.php');
    }
} 

if ($_SESSION['iD2'] > 0) {
    $Codpo = $_POST['CodProd'];
    $I = $_SESSION['iD2'];
    $cantidad = $_POST['cantidad'];

    // Consulta para obtener los detalles del producto
    $Consulta = "SELECT * FROM productos WHERE CodigoProducto = '$Codpo' AND id_producto = '$I'";
    $query = mysqli_query($conn, $Consulta);

    if ($row2 = mysqli_fetch_assoc($query)) {
        $producto = array(
            'CodigoBarras' => $CodBarrasNew,
            'Descripcion' => $row2['Descripcion'],
            'Marca' => $row2['Marca'],
            'Presentacion' => $row2['Presentacion'],
            'Precio' => $_POST['Precio'],
            'Cantidad' => $cantidad
        );

        // Agregar el producto al carrito
        $_SESSION['carrito'][] = $producto;
        $_SESSION['iD'] =0;
        header('Ventas.php');
    }
} else if ($_SESSION['iD2']) {

$Prod = $_SESSION['CodigoProducto'];
$cantidad = $_POST['cantidad'];

// Consulta para obtener los detalles del producto
$Consulta = "SELECT * FROM productos WHERE CodigoProducto = '$Prod'";
$query = mysqli_query($conn, $Consulta);

if ($row3 = mysqli_fetch_assoc($query)) {
    $producto2 = array(
        'CodigoBarras' => $CodBarrasNew,
        'Descripcion' => $row3['Descripcion'],
        'Marca' => $row3['Marca'],
        'Presentacion' => $row3['Presentacion'],
        'Precio' => $_POST['Precio'],
        'Cantidad' => $cantidad
    );

    // Agregar el producto al carrito
    $_SESSION['carrito'][] = $producto2;
    $_SESSION['iD'] =0;
    header('Ventas.php');
}
}

}

// Calcular el total a pagar
$totalAPagar = 0;
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    foreach ($_SESSION['carrito'] as $producto) {
        $totalAPagar += $producto['Precio'] * $producto['Cantidad'];
    }
}

// Lógica para realizar la venta
if (isset($_POST['RealizarVenta'])) {
    // Generar un folio único
    $Folio = uniqid();

    // Descripción de los productos
$Descripcion = '';
foreach ($_SESSION['carrito'] as $producto) {
    $Descripcion .= "{$producto['Descripcion']} Marca: {$producto['Marca']}, Cantidad: {$producto['Cantidad']}, Precio: {$producto['Precio']}\n";
}
$Descripcion = rtrim($Descripcion, "\n"); // Eliminar el salto de línea final

// Insertar la venta en la base de datos
$Nom = $_SESSION['nombre'];
$insertarVenta = "INSERT INTO ventas (NombreEmpleado, Descripcion, Folio, TotalPagar) VALUES ('$Nom', '$Descripcion', '$Folio', '$totalAPagar')";
$resultado = mysqli_query($conn, $insertarVenta);

if ($resultado) {
    // Actualizar el inventario
    foreach ($_SESSION['carrito'] as $producto) {
        if ($_SESSION['iD'] > 0) {
            $CodBarra = $producto['CodigoBarras'];
            $cantidad = $producto['Cantidad'];
            $Descrip = $producto['Descripcion'];
            $IDProd = $_SESSION['iD'];
    
            // Consultar cantidad actual
            $consultaCantidad = "SELECT Existencia FROM productos WHERE CodigoBarras = '$CodBarra' AND id_producto = '$IDProd'";
            $resultadoCantidad = mysqli_query($conn, $consultaCantidad);
            $row = mysqli_fetch_assoc($resultadoCantidad);
    
            if ($row) {
                $nuevaExistencia = $row['Existencia'] - $cantidad;
    
                // Actualizar la existencia en el inventario
                $actualizarInventario = "UPDATE productos SET Existencia = '$nuevaExistencia' WHERE CodigoBarras = '$CodBarra' AND id_producto = '$IDProd'";
                mysqli_query($conn, $actualizarInventario);
            }
        } else if ($_SESSION['iD'] === 0) {
            
            $CodBarra = $producto['CodigoBarras'];
            $cantidad = $producto['Cantidad'];
            $Descrip = $producto['Descripcion'];
            $Marca = $producto['Marca'];
    
            // Consultar cantidad actual
            $consultaCantidad = "SELECT Existencia FROM productos WHERE CodigoBarras = '$CodBarra' AND Descripcion = '$Descrip' AND Marca = '$Marca'";
            $resultadoCantidad = mysqli_query($conn, $consultaCantidad);
            $row = mysqli_fetch_assoc($resultadoCantidad);
    
            if ($row) {
                $nuevaExistencia = $row['Existencia'] - $cantidad;
    
                // Actualizar la existencia en el inventario
                $actualizarInventario = "UPDATE productos SET Existencia = '$nuevaExistencia' WHERE CodigoBarras = '$CodBarra' AND Descripcion = '$Descrip' AND Marca = '$Marca'";
                mysqli_query($conn, $actualizarInventario);
            } 
        }

        if ($_SESSION['iD2'] > 0) {
            $CodBarra = $producto['CodigoProducto'];
            $cantidad = $producto['Cantidad'];
            $Descrip = $producto['Descripcion'];
            $IDProd = $_SESSION['iD'];
    
            // Consultar cantidad actual
            $consultaCantidad = "SELECT Existencia FROM productos WHERE CodigoProducto = '$CodBarra' AND id_producto = '$IDProd'";
            $resultadoCantidad = mysqli_query($conn, $consultaCantidad);
            $row = mysqli_fetch_assoc($resultadoCantidad);
    
            if ($row) {
                $nuevaExistencia = $row['Existencia'] - $cantidad;
    
                // Actualizar la existencia en el inventario
                $actualizarInventario = "UPDATE productos SET Existencia = '$nuevaExistencia' WHERE CodigoBarras = '$CodBarra' AND id_producto = '$IDProd'";
                mysqli_query($conn, $actualizarInventario);
            }
        } else if ($_SESSION['iD2'] === 0) {
            
            $CodBarra = $producto['CodigoProducto'];
            $cantidad = $producto['Cantidad'];
            $Descrip = $producto['Descripcion'];
            $Marca = $producto['Marca'];
    
            // Consultar cantidad actual
            $consultaCantidad = "SELECT Existencia FROM productos WHERE CodigoBarras = '$CodBarra' AND Descripcion = '$Descrip' AND Marca = '$Marca'";
            $resultadoCantidad = mysqli_query($conn, $consultaCantidad);
            $row = mysqli_fetch_assoc($resultadoCantidad);
    
            if ($row) {
                $nuevaExistencia = $row['Existencia'] - $cantidad;
    
                // Actualizar la existencia en el inventario
                $actualizarInventario = "UPDATE productos SET Existencia = '$nuevaExistencia' WHERE CodigoBarras = '$CodBarra' AND Descripcion = '$Descrip' AND Marca = '$Marca'";
                mysqli_query($conn, $actualizarInventario);
            } 
        }
       
    }

    // Vaciar el carrito después de la venta
    $_SESSION['carrito'] = array();

    // Redirigir a la página del ticket
    // header("Location: generar_ticket.php?folio=$Folio");

    // Redirigir a la página principal
    exit();
} else {
    echo "<script>alert('Error al realizar la venta.');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/Ventas.css">
    <title>Ventas</title>
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

    section>div>form{
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    section>div>form input{
        margin-top: 10px;
        margin-bottom: 10px;
        height: 25px;
        width: 80%;
        margin-left: 10%;
        padding-left: 5px;
        font-size: 20px;
    }
    section>div>div input{
        height: 25px;
        padding-left: 10px;
        font-size: 25px;
        width: 20%;
    }

    section>div>div{
        margin-left: 20px;
    }

    h2{
        line-height: 1.5;
    }

    section>div>form p{
        padding-left: 20px;
        font-size: 25px;
    }

    button{
        width: 50%;
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

    .btn2{
        width: 20%;
        margin-left: 2%;
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
        padding-bottom: 15px;
        font-size: 25px;
    }

    table {
        margin-top: 20px; 
        width: 88%;
        border-collapse: collapse;
    }

    th {
        width: 12%;
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

            /* Estilo del formulario */
            .cont {
            position: fixed; /* Fijo en la pantalla */
            top: 50%; /* Centrado verticalmente */
            left: 50%; /* Centrado horizontalmente */
            transform: translate(-50%, -50%); /* Ajuste para centrar */
            background-color: white;
            padding: 20px;
            border: 2px solid black;
            z-index: 9999; /* Asegura que esté por delante de todo */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Sombra para efecto flotante */
            }
            .cont>#miFormulario{
                display: grid;
                grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
                text-align: center;
            }

            .btn3{
                width: 70%;
                font-size: 20px;
                padding: 5px;
                margin-top: -2.5px;
            }
</style>
</head>
<body>
    <header>
        <div class="el">
            <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <?php print $_SESSION['nombre']; ?> </h1>
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

            <form action="" method="post">
                <div>
                    <p>Codigo de Barras</p> <br>
                    <input type="text" name="CodBarra">
                    <p>Codigo de Producto</p> <br>
                    <input type="text" name="CodProd">
                </div>
                <div>
                    <button type="submit" name="Buscar"><img src="img/buscar.png" alt=""><br>Buscar</button>
                </div>
            </form>

            <div>
                
            <?php

            if (isset($_POST['Buscar'])) {

                if (isset($_POST['CodBarra'])) {

                    $CodBarra = $_POST['CodBarra'];
                    $CodBarrasNew = str_replace("'", "-", $CodBarra);
                    $_SESSION['CodBarr'] = $CodBarrasNew;
                    $Consulta = "SELECT * FROM productos WHERE CodigoBarras = '$CodBarrasNew'";
                    $query = mysqli_query($conn, $Consulta);
                    $Cuenta = mysqli_num_rows($query);

                    if ($Cuenta>1) {

                        $formularioVisible = true; // Hacer visible el formulario si se encuentran más de un producto
                         if ($formularioVisible):
                            ?>

                            <div class="cont" id="Formulario">
                            <h1><center>SELECCIONE EL PRODUCTO</center></h1>
                            <br>
                            <?php
                            while ($row2 = mysqli_fetch_assoc($query)) {
                                $ID = $row2['id_producto'];
                         ?>
                            <!-- Formulario que se hace visible -->
                            <form action="Ventas.php?id=<?php echo $ID; ?>&&Cod=<?php echo $CodBarrasNew; ?>" method="post" id="miFormulario">
                                <h2> <?php print $row2['Descripcion']; ?> </h2>
                                <h2> <?php print $row2['Marca']; ?> </h2>
                                <h2> <?php print $row2['CodigoProducto']; ?> </h2>
                                <h2> <?php print $row2['Existencia']; ?> </h2>

                                <button type="submit" class="btn3" name="GuardarID">Agregar</button>
                            </form>
                            <br>
                        <?php } ?>
                        <button type="button" onclick="ocultarFormulario()">Cancelar</button>
                            </div>
                       <?php
            
                    endif; 


                    } else{
                        if ($row2 = mysqli_fetch_assoc($query)){ ?>
                            <form action="" method="post">
                                <h2>Descripcion: <?php print $row2['Descripcion']; ?></h2>
                                <h2>Marca: <?php print $row2['Marca']; ?> </h2>
                                <h2>Presentacion: <?php print $row2['Presentacion']; ?> </h2>
                                <h2>Seleccione el Precio: <input type="number" list="list" name="Precio" id="" step="0.001"> </h2>
                                <h2>En Existencia: <?php print $row2['Existencia']; ?></h2>
                                <h2>Cantidad: <input type="number" name="cantidad" min="1"></h2>
                                <?php $_SESSION['CodigoBarra'] = $row2['CodigoBarras']; ?>
                                <button type="submit" class="btn2" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
                            </form>
                        <?php } else {
                            print "<h2>Producto no encontrado</h2>";
                        }
                    }
                } 

                if (isset($_POST['CodProd'])) {
                    $CodProd = $_POST['CodProd'];
                    $_SESSION['CodProd'] = $CodProd;
                    $Consulta = "SELECT * FROM productos WHERE CodigoProducto = '$CodProd'";
                    $query = mysqli_query($conn, $Consulta);
                    $Cuenta = mysqli_num_rows($query);
                    if ($Cuenta>1) {
                        $formularioVisible = true; // Hacer visible el formulario si se encuentran más de un producto
                         if ($formularioVisible):
                            ?>
                            <div class="cont" id="Formulario">
                            <h1><center>SELECCIONE EL PRODUCTO</center></h1>
                            <br>
                            <?php
                            while ($row2 = mysqli_fetch_assoc($query)) {
                                $ID = $row2['id_producto'];
                         ?>
                            <!-- Formulario que se hace visible -->
                            <form action="Ventas.php?id2=<?php echo $ID; ?>&&Cod2=<?php echo $CodProd; ?>" method="post" id="miFormulario">
                                <h2> <?php $Descr = $row2['Descripcion']; print $Descr; ?> </h2>
                                <h2> <?php print $row2['Marca']; ?> </h2>
                                <h2> <?php print $row2['CodigoProducto']; ?> </h2>
                                <h2> <?php print $row2['Existencia']; ?> </h2>

                                <button type="submit" class="btn3" name="GuardarID">Agregar</button>
                            </form>
                            <br>
                        <?php } ?>
                        <button type="button" onclick="ocultarFormulario()">Cancelar</button>
                            </div>
                       <?php
            
                    endif; 


                    } else{
                        if ($row2 = mysqli_fetch_assoc($query)){ ?>
                            <form action="" method="post">
                                <h2>Descripcion: <?php print $row2['Descripcion']; ?></h2>
                                <h2>Marca: <?php print $row2['Marca']; ?> </h2>
                                <h2>Presentacion: <?php print $row2['Presentacion']; ?> </h2>
                                <h2>Seleccione el Precio: <input type="number" list="list" name="Precio" id="" step="0.001"> </h2>
                                <h2>En Existencia: <?php print $row2['Existencia']; ?></h2>
                                <h2>Cantidad: <input type="number" name="cantidad" min="1"></h2>
                                <?php $_SESSION['CodigoProducto'] = $row2['CodigoProducto']; ?>
                                <button type="submit" class="btn2" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
                            </form>
                        <?php } else {
                            print "<h2>Producto no encontrado</h2>";
                        }
                    }
                }         
            }
if (isset($_GET['id']) && isset($_GET['Cod'])) {


            $ID = $_GET['id'];
            $_SESSION['iD'] = $ID;
            $Cod = $_GET['Cod'];
            $Cons = "SELECT * FROM productos WHERE id_producto = '$ID' AND CodigoBarras = '$Cod'";
            $query3 = mysqli_query($conn, $Cons);
            if ($rowProd = mysqli_fetch_assoc($query3)) {
                 ?>
                 <form action="Ventas.php" method="post">
                     <h2>Descripcion: <?php print $rowProd['Descripcion']; ?></h2>
                     <h2>Marca: <?php print $rowProd['Marca']; ?> </h2>
                     <h2>Presentacion: <?php print $rowProd['Presentacion']; ?> </h2>
                     <h2>Seleccione el Precio: <input type="number" list="list2" name="Precio" id="" step="0.001"> </h2>
                     <h2>En Existencia: <?php print $rowProd['Existencia']; ?></h2>
                     <h2>Cantidad: <input type="number" name="cantidad" min="1"></h2>
                     <input type="hidden" name="CodBarra" value="<?php print $rowProd['CodigoBarras']; ?>">
                     <button type="submit" class="btn2" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
                 </form>

                 <datalist id="list2">          
                <?php
 
 $ID2 = $_GET['id'];
 $Cod2 = $_GET['Cod'];
 $Cons2 = "SELECT * FROM productos WHERE id_producto = '$ID2' AND CodigoBarras = '$Cod2'";
 $query4 = mysqli_query($conn, $Cons2);
                $rowPrecios2 = mysqli_fetch_assoc($query4);
                 ?>
             <option value="<?php echo $rowPrecios2['Precio1']; ?>">Precio 1</option>
             <option value="<?php echo $rowPrecios2['Precio2']; ?>">Precio 2</option>
             <option value="<?php echo $rowPrecios2['Precio3']; ?>">Precio 3</option>
        </datalist>

                 <?php
            }
        }



        if (isset($_GET['id2']) && isset($_GET['Cod2'])) {


            $ID = $_GET['id2'];
            $_SESSION['iD'] = $ID;
            $Cod = $_GET['Cod2'];
            $Cons2 = "SELECT * FROM productos WHERE id_producto = '$ID' AND CodigoProducto = '$Cod'";
            $query3 = mysqli_query($conn, $Cons2);
            if ($rowProd = mysqli_fetch_assoc($query3)) {
                 ?>
                 <form action="Ventas.php" method="post">
                     <h2>Descripcion: <?php print $rowProd['Descripcion']; ?></h2>
                     <h2>Marca: <?php print $rowProd['Marca']; ?> </h2>
                     <h2>Presentacion: <?php print $rowProd['Presentacion']; ?> </h2>
                     <h2>Seleccione el Precio: <input type="number" list="list3" name="Precio" id="" step="0.001"> </h2>
                     <h2>En Existencia: <?php print $rowProd['Existencia']; ?></h2>
                     <h2>Cantidad: <input type="number" name="cantidad" min="1"></h2>
                     <input type="hidden" name="CodBarra" value="<?php print $rowProd['CodigoBarras']; ?>">
                     <button type="submit" class="btn2" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
                 </form>

                 <datalist id="list3">          
                <?php
 
 $ID3 = $_GET['id2'];
 $Cod3 = $_GET['Cod2'];
 $Cons3 = "SELECT * FROM productos WHERE id_producto = '$ID3' AND CodigoProducto = '$Cod3'";
 $query5 = mysqli_query($conn, $Cons3);
                $rowPrecios3 = mysqli_fetch_assoc($query5);
                 ?>
             <option value="<?php echo $rowPrecios3['Precio1']; ?>">Precio 1</option>
             <option value="<?php echo $rowPrecios3['Precio2']; ?>">Precio 2</option>
             <option value="<?php echo $rowPrecios3['Precio3']; ?>">Precio 3</option>
        </datalist>

                 <?php
            }
        }
            ?>
            </div>
            <datalist id="list">
                
            <?php
                $CodBarrasNew = str_replace("'", "-", $CodBarra);
            $ConsultaPrecios = "SELECT * FROM productos WHERE CodigoBarras = '$CodBarrasNew'";
            $quer = mysqli_query($conn, $ConsultaPrecios);
            $rowPrecios = mysqli_fetch_assoc($quer);
             ?>
         <option value="<?php echo $rowPrecios['Precio1']; ?>">Precio 1</option>
         <option value="<?php echo $rowPrecios['Precio2']; ?>">Precio 2</option>
         <option value="<?php echo $rowPrecios['Precio3']; ?>">Precio 3</option>
    </datalist>
        </div>
        <div>

            <table>
                <tr>
                    <th>Descripcion</th>
                    <th>Marca</th>
                    <th>Presentacion</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>

                <?php
                if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
                    foreach ($_SESSION['carrito'] as $producto) {
                        $total = $producto['Precio'] * $producto['Cantidad'];
                        print "<tr>";
                        print "<td>{$producto['Descripcion']}</td>";
                        print "<td>{$producto['Marca']}</td>";
                        print "<td>{$producto['Presentacion']}</td>";
                        print "<td>{$producto['Cantidad']}</td>";
                        print "<td>$ {$producto['Precio']}</td>";
                        print "<td>$ {$total}</td>";
                        print "</tr>";
                    }
                }
                ?>
            </table><br><br>
            <h3>Total a Pagar: $ <span><?php print $totalAPagar; ?></span></h3>
            <form action="" method="post">
                <button type="submit" name="RealizarVenta"><img src="img/caja-registradora.png" alt=""><br>Realizar Venta</button>
            </form>
        </div>
    </section>

</body>
</html>

<script>
function ocultarFormulario() {
    document.getElementById('Formulario').style.display = 'none';
}
</script>