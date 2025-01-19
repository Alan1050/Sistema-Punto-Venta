<?php
include 'includes/conn.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mostrarDiv = true; // Cambiar a `false` según la lógica deseada

// Inicializar la variable de sesión para el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}


$formularioVisible = false;

// Lógica para agregar productos al carrito

if (isset($_POST['agregar'])) {

    $cantidad = $_POST['cantidad'];
    $IDD = $_SESSION['IDD'];

    $Consul = "SELECT * FROM productos WHERE id_producto = '$IDD'";
    $query = mysqli_query($conn, $Consul); 

    if ($query) {
        if ($row = mysqli_fetch_assoc($query)) {
            $producto = array(
                'id_producto' => $row['id_producto'],
                'Descripcion' => $row['Descripcion'],
                'Marca' => $row['Marca'],
                'Presentacion' => $row['Presentacion'],
                'Cantidad' => $cantidad
            );

            // Verificar si el producto ya está en el carrito
            $productoExistente = false;
            foreach ($_SESSION['carrito'] as $item) {
                if ($item['id_producto'] === $producto['id_producto']) {
                    $productoExistente = true;
                    break;
                }
            }

            // Solo agregar el producto si no está ya en el carrito

                $_SESSION['carrito'][] = $producto;
            
            $_SESSION['IDD'] = 0;
            echo "
            <script>
            window.location.href='Devoluciones.php';
            </script>";
        } else {
            echo "<pre>No se encontraron resultados para la consulta.</pre>";
        }
    } else {
        echo "<pre>Error en la consulta: " . mysqli_error($conn) . "</pre>";
    }
}

// Lógica para realizar la venta
if (isset($_POST['RealizarVenta'])) {

    foreach ($_SESSION['carrito'] as $producto) {
            $Iden = $producto['id_producto'];
            $cantidad = $producto['Cantidad'];
            $Descrip = $producto['Descripcion'];
    
            // Consultar cantidad actual
            $consultaCantidad = "SELECT Existencia FROM productos WHERE id_producto = '$Iden'";
            $resultadoCantidad = mysqli_query($conn, $consultaCantidad);
            $row = mysqli_fetch_assoc($resultadoCantidad);
    
            if ($row) {
                $nuevaExistencia = $row['Existencia'] + $cantidad;
    
                // Actualizar la existencia en el inventario
                $actualizarInventario = "UPDATE productos SET Existencia = '$nuevaExistencia' WHERE id_producto = '$Iden'";
                $Acc = mysqli_query($conn, $actualizarInventario);
                    if ($Acc) {
                        echo '<script>
                        alert("Devolucion Exitosa, Presione Enter o Cerrar para regresar");
                        window.location.href = "Devoluciones.php";
                        </script>';
                    } else {
                        echo '<script>
                        alert("No se pudo realizar la Devolucion vuelva a intentar");
                        window.location.href = "Devoluciones.php";
                        </script>'; 
                    }
                
            }
    }

    // Vaciar el carrito después de la venta
    $_SESSION['carrito'] = array();

    // Redirigir a la página del ticket
    //header("Location: generar_ticket.php?folio=$Folio");


} 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/Ventas.css">
    <link rel="stylesheet" href="css/ventana.css">
    <title>Ventas P</title>
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



            .btn3{
                width: 70%;
                font-size: 20px;
                padding: 5px;
                margin-top: -2.5px;
            }

            .For1{
                margin-top: 30px;
                margin-left: 50px;
                background-color:rgb(255, 255, 255);
                width: 60%;
                border-radius: 20px;
                padding-left: 20px;
                padding-top: 10px;
                padding-bottom: 20px;
            }

            .For1> h1{
                font-size: 30px;
                font-weight: 100;
            }

            .For1> h3{
                font-size: 25px;
                font-weight: 300;
                padding-top: 10px;
            }

            .For1> p{
                padding-left: 10px;
                padding-top: 5px;
            }

            .For1>input{
                margin-top: 5px;
                margin-left: 10px;
                width: 50%;
            }

            .bt-Ag{
                margin-left: 30%;
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
                <li><a href="Inventario.php">Volver</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>

    <section>
        <div>

            <form action="" method="post">
            <div>
                    <p>Buscar Producto</p> <br>
                    <input type="text" name="Prod" placeholder="Buscar por Codigo de Barras o de Producto" list="listProds">
            </div>
            <div>
                <button type="submit" name="Buscar"><img src="img/buscar.png" alt=""><br>Buscar</button>
            </div>
            <datalist id="listProds">
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

            <div>
                
            <?php
            if (isset($_POST['Buscar'])) {
                $_SESSION['ID'] = 0;
                $CodiPro = $_POST['Prod'];

                // Consulta para obtener el producto basado en CodigoProducto
                $Consulta30 = "SELECT * FROM productos WHERE CodigoProducto = '$CodiPro'";
                $query30 = mysqli_query($conn, $Consulta30);
            
                $Consulta31 = "SELECT * FROM productos WHERE CodigoBarras = '$CodiPro'";
                $query31 = mysqli_query($conn, $Consulta31);
            
                $Consulta32 = "SELECT * FROM productos WHERE Descripcion = '$CodiPro'";
                $query32 = mysqli_query($conn, $Consulta32);
                
            if ($query30 && mysqli_num_rows($query30) > 0) {
                if (mysqli_num_rows($query30) > 1) {
                
                    if ($mostrarDiv): ?>
                            <!-- Div oculto -->
                            <div id="centeredDiv">
                                    <h1>Seleccione el Producto:</h1>
                                    <table>
                                        <tr>
                                            <th>Codigo de Producto</th>
                                            <th>Descripcion</th>
                                            <th>Marca</th>
                                            <th>Precio 1</th>
                                            <th>Ultima Fecha</th>
                                            <th>Presentacion</th>
                                            <th>Existencia</th>
                                            <th colspan="2">Acciones:</th>
                                        </tr>

                                        <?php while ($row3 = mysqli_fetch_assoc($query30)) { 
                                            $ID = $row3['id_producto']; ?>
                                                <tr>
                                                    <td><?php echo $row3['CodigoProducto']; ?></td>
                                                    <td><?php echo $row3['Descripcion']; ?></td>
                                                    <td><?php echo $row3['Marca']; ?></td>
                                                    <td>$<?php echo $row3['Precio1']; ?></td>
                                                    <td><?php echo $row3['UltimaFecha']; ?></td>
                                                    <td><?php echo $row3['Presentacion']; ?></td>
                                                    <td><?php echo $row3['Existencia']; ?></td>
                                                    <td>
                                                        <form action="Devoluciones.php" method="get">
                                                            <input type="hidden" name="id" value="<?php echo $row3['id_producto']; ?>">
                                                            <button type="submit" class="bt" name="btnid">Seleccionar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php } ?>
                                    </table>
                                <button id="cancelBtn">Cancelar</button>
                            </div>
                    <?php endif;

                } else { ?>
                    <form action="" method="post" class="For1">
                        <h1>Informacion del Producto:</h1>
                        <?php
                            $ConsultaDatos = "SELECT * FROM `productos` WHERE CodigoProducto = '$CodiPro'";
                            $queryDatos = mysqli_query($conn, $ConsultaDatos);
                            $rowDatos = mysqli_fetch_assoc( $queryDatos);
                            $_SESSION['ExistenciaD'] = $rowDatos['Existencia'];
                            $_SESSION['IDD'] = $rowDatos['id_producto'];
                            $_SESSION['DescripD'] = $rowDatos['Descripcion'];
                            $_SESSION['MarcaD'] = $rowDatos['Marca'];
                        ?>
                        <h3>Descripcion: </h3> <p><?php print_r($_SESSION['DescripD']) ?></p>
                        <h3>Marca: </h3> <p><?php print_r($_SESSION['MarcaD']) ?></p>
                        <h3>Cuantos Se Devolveran: </h3> <input type="number" name="cantidad"><br>
                        <button type="submit" class="btn2 bt-Ag" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
                    </form>
                    <?php
                }
            } else if ($query31 && mysqli_num_rows($query31) > 0) {

                if (mysqli_num_rows($query31) > 1) {
                
                    if ($mostrarDiv): ?>
                            <!-- Div oculto -->
                            <div id="centeredDiv">
                                    <h1>Seleccione el Producto:</h1>
                                    <table>
                                        <tr>
                                            <th>Codigo de Producto</th>
                                            <th>Descripcion</th>
                                            <th>Marca</th>
                                            <th>Precio 1</th>
                                            <th>Ultima Fecha</th>
                                            <th>Presentacion</th>
                                            <th>Existencia</th>
                                            <th colspan="2">Acciones:</th>
                                        </tr>

                                        <?php while ($row3 = mysqli_fetch_assoc($query31)) { 
                                            $ID = $row3['id_producto']; ?>
                                                <tr>
                                                    <td><?php echo $row3['CodigoProducto']; ?></td>
                                                    <td><?php echo $row3['Descripcion']; ?></td>
                                                    <td><?php echo $row3['Marca']; ?></td>
                                                    <td>$<?php echo $row3['Precio1']; ?></td>
                                                    <td><?php echo $row3['UltimaFecha']; ?></td>
                                                    <td><?php echo $row3['Presentacion']; ?></td>
                                                    <td><?php echo $row3['Existencia']; ?></td>
                                                    <td>
                                                        <form action="Devoluciones.php" method="get">
                                                            <input type="hidden" name="id" value="<?php echo $row3['id_producto']; ?>">
                                                            <button type="submit" class="bt" name="btnid">Seleccionar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php } ?>
                                    </table>
                                <button id="cancelBtn">Cancelar</button>
                            </div>
                    <?php endif;
                } else {?>
                    <form action="" method="post" class="For1">
                        <h1>Informacion del Producto:</h1>
                        <?php
                            $ConsultaDatos = "SELECT * FROM `productos` WHERE CodigoBarras = '$CodiPro'";
                            $queryDatos = mysqli_query($conn, $ConsultaDatos);
                            $rowDatos = mysqli_fetch_assoc( $queryDatos);
                            $_SESSION['ExistenciaD'] = $rowDatos['Existencia'];
                            $_SESSION['IDD'] = $rowDatos['id_producto'];
                            $_SESSION['DescripD'] = $rowDatos['Descripcion'];
                            $_SESSION['MarcaD'] = $rowDatos['Marca'];
                        ?>
                        <h3>Descripcion: </h3> <p><?php print_r($_SESSION['DescripD']) ?></p>
                        <h3>Marca: </h3> <p><?php print_r($_SESSION['MarcaD']) ?></p>
                        <h3>Cuantos Se Devolveran: </h3> <input type="number" name="cantidad"><br>
                        <button type="submit" class="btn2 bt-Ag" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
                    </form>
                    <?php
                }
            } else if ($query32 && mysqli_num_rows($query32) > 0) {
                if (mysqli_num_rows($query32) > 1) {
                
                    if ($mostrarDiv): ?>
                            <!-- Div oculto -->
                            <div id="centeredDiv">
                                    <h1>Seleccione el Producto:</h1>
                                    <table>
                                        <tr>
                                            <th>Codigo de Producto</th>
                                            <th>Descripcion</th>
                                            <th>Marca</th>
                                            <th>Precio 1</th>
                                            <th>Ultima Fecha</th>
                                            <th>Presentacion</th>
                                            <th>Existencia</th>
                                            <th colspan="2">Acciones:</th>
                                        </tr>

                                        <?php while ($row3 = mysqli_fetch_assoc($query32)) { 
                                            $ID = $row3['id_producto']; ?>
                                                <tr>
                                                    <td><?php echo $row3['CodigoProducto']; ?></td>
                                                    <td><?php echo $row3['Descripcion']; ?></td>
                                                    <td><?php echo $row3['Marca']; ?></td>
                                                    <td>$<?php echo $row3['Precio1']; ?></td>
                                                    <td><?php echo $row3['UltimaFecha']; ?></td>
                                                    <td><?php echo $row3['Presentacion']; ?></td>
                                                    <td><?php echo $row3['Existencia']; ?></td>
                                                    <td>
                                                        <form action="Devoluciones.php" method="get">
                                                            <input type="hidden" name="id" value="<?php echo $row3['id_producto']; ?>">
                                                            <button type="submit" class="bt" name="btnid">Seleccionar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                        <?php } ?>
                                    </table>
                                <button id="cancelBtn">Cancelar</button>
                            </div>
                    <?php endif;
                } else {?>
                    <form action="" method="post" class="For1">
                        <h1>Informacion del Producto:</h1>
                        <?php
                            $ConsultaDatos = "SELECT * FROM `productos` WHERE Descripcion = '$CodiPro'";
                            $queryDatos = mysqli_query($conn, $ConsultaDatos);
                            $rowDatos = mysqli_fetch_assoc( $queryDatos);
                            $_SESSION['ExistenciaD'] = $rowDatos['Existencia'];
                            $_SESSION['IDD'] = $rowDatos['id_producto'];
                            $_SESSION['DescripD'] = $rowDatos['Descripcion'];
                            $_SESSION['MarcaD'] = $rowDatos['Marca'];
                        ?>
                        <h3>Descripcion: </h3> <p><?php print_r($_SESSION['DescripD']) ?></p>
                        <h3>Marca: </h3> <p><?php print_r($_SESSION['MarcaD']) ?></p>
                        <h3>Cuantos Se Devolveran: </h3> <input type="number" name="cantidad"><br>
                        <button type="submit" class="btn2 bt-Ag" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
                    </form>
                    <?php
                }
            }
            }
            ?>
            <?php
            if (isset(($_GET['id']))) {
                $ID = $_GET['id'];
                $_SESSION['ID'] = $ID;
                $Cons = "SELECT * FROM productos WHERE id_producto = '$ID'";
                $query3 = mysqli_query($conn, $Cons);
                if ($rowDatos = mysqli_fetch_assoc($query3)) {
                ?>
                <form action="" method="post" class="For1">
                <h1>Informacion del Producto:</h1> <br>
                <?php
                    $ConsultaDatos = "SELECT * FROM `productos` WHERE id_producto = '$ID'";
                    $queryDatos = mysqli_query($conn, $ConsultaDatos);
                    $rowDatos = mysqli_fetch_assoc( $queryDatos);
                    $_SESSION['ExistenciaD'] = $rowDatos['Existencia'];
                    $_SESSION['IDD'] = $rowDatos['id_producto'];
                    $_SESSION['DescripD'] = $rowDatos['Descripcion'];
                    $_SESSION['MarcaD'] = $rowDatos['Marca'];
                ?>
                <h3>Descripcion: </h3> <br> <p><?php print_r($_SESSION['DescripD']) ?></p> <br>
                <h3>Marca: </h3> <br> <p><?php print_r($_SESSION['MarcaD']) ?></p> <br>
                <h3>Cuantos Se Devolveran: </h3> <br> <input type="number" name="cantidad"><br>
                <button type="submit" class="btn2 bt-Ag" name="agregar"><img src="img/mas.png" alt=""><br>Agregar</button>
            </form>
            <?php
            }
        }
            ?>
            </div>
        </div>
        <div>

        <table>
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Presentación</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
            <?php foreach ($_SESSION['carrito'] as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['Descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($producto['Marca']); ?></td>
                    <td><?php echo htmlspecialchars($producto['Presentacion']); ?></td>
                    <td><?php echo htmlspecialchars($producto['Cantidad']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No hay productos en el carrito de Devoluciones.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table><br><br>
            <form action="" method="post">
                <button type="submit" name="RealizarVenta"><img src="img/devoluciones-faciles.png" alt=""><br>Realizar Devolucion</button>
            </form>
        </div>
    </section>
</body>
</html>

<script>
    // Obtener referencias al botón de cancelar y al div
document.addEventListener("DOMContentLoaded", () => {
    const cancelBtn = document.getElementById('cancelBtn');
    const centeredDiv = document.getElementById('centeredDiv');

    // Verificar si los elementos existen antes de agregar el evento
    if (cancelBtn && centeredDiv) {
        cancelBtn.addEventListener('click', () => {
            // Ocultar el div estableciendo `display` a `none`
            centeredDiv.style.display = 'none';
        });
    }
});
</script>