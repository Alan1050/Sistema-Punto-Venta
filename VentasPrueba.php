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
$query = false; // Inicializar la variable $query

if (isset($_POST['agregar'])) {

    $cantidad = $_POST['cantidad'];
    $Precio = $_POST['Precio'];

    $IDC = $_SESSION['IDC'];

    $Consul = "SELECT * FROM productos WHERE id_producto = '$IDC'";
    $query = mysqli_query($conn, $Consul);

    if ($query) {
        $query = mysqli_query($conn, $Consul);
        if ($row = mysqli_fetch_assoc($query)) {
            $producto = array(
                'id' => $row['id_producto'],
                'CodigoBarras' => $row['CodigoBarras'],
                'Descripcion' => $row['Descripcion'],
                'Marca' => $row['Marca'],
                'Presentacion' => $row['Presentacion'],
                'Precio' => $Precio,
                'Cantidad' => $cantidad
            );

            $_SESSION['carrito'][] = $producto;
            $_SESSION['IDC'] = 0;
            $_SESSION['ExistenciaC'] = 0;
            $_SESSION['DescripC'] = '';
            $_SESSION['MarcaC'] = '';
            echo "<script> window.location.href = 'VentasPrueba.php'; </script>";
        } else {
            //echo "<pre>Error en la consulta: " . mysqli_error($conn) . "</pre>";
            //echo "<pre>Consulta ejecutada: " . $Consul . "</pre>";
        }
    } else {
        //echo "<pre>Error: La consulta no se pudo construir. Verifica los datos de entrada.</pre>";
    }
}

// Calcular el total a pagar
$totalAPagar = 0;
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    foreach ($_SESSION['carrito'] as $producto) {
        $totalAPagar += intval($producto['Precio']) * intval($producto['Cantidad']);
    }
}

// Lógica para realizar la venta
if (isset($_POST['RealizarVenta'])) {
    // Generar un folio único
    $Folio = uniqid();

    // Descripción de los productos
    $Descripcion = '';
    foreach ($_SESSION['carrito'] as $producto) {
        $IAS = $producto['id'];
        $ExiPa = "SELECT * FROM productos WHERE id_producto = '$IAS'";
        $qu = mysqli_query($conn, $ExiPa);
        $rowa = mysqli_fetch_assoc($qu);
        $ExiVerAnt = intval($rowa['Existencia']);
        $CanNe = intval($producto['Cantidad']);
        $De = $producto['Descripcion'];
        if ($ExiVerAnt >= $CanNe) {
            $ExiNe = intval($rowa['Existencia']) - intval($producto['Cantidad']);
        } else{
        $ExiNe = 0;
        echo '<script>
        alert("Se realizara la venta, pero la cantidad del producto '.$De.' supera a la existencia y quedara en 0");
        </script>';
        }
        $CamExi = "UPDATE `productos` SET `Existencia`='$ExiNe' WHERE id_producto = '$IAS'";
        $queryCam = mysqli_query($conn, $CamExi);
        $Descripcion .= "{$producto['Descripcion']} Marca: {$producto['Marca']}, Cantidad: {$producto['Cantidad']}, Precio: {$producto['Precio']}\n";
    }
    $Descripcion = rtrim($Descripcion, "\n"); // Eliminar el salto de línea final

    // Insertar la venta en la base de datos
    $Nom = $_SESSION['nombre'];
    $insertarVenta = "INSERT INTO ventas (NombreEmpleado, Descripcion, Folio, TotalPagar) VALUES ('$Nom', '$Descripcion', '$Folio', '$totalAPagar')";
    $resultado = mysqli_query($conn, $insertarVenta);



    // Vaciar el carrito después de la venta
    $_SESSION['carrito'] = array();

    echo '<script>
        alert("Venta Realizada con Éxito con el Folio: ' . $Folio . '");
        window.location.href = "VentasPrueba.php";
        </script>';
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
    <title>Ventas</title>
    <style>
        * {
            padding: 0px;
            margin: 0px;
        }

        body {
            background-color: #d9d9d9;
        }

        section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin-top: 20px;
            width: 100%;
        }

        section>div>form {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        section>div>form input {
            margin-top: 10px;
            margin-bottom: 10px;
            height: 25px;
            width: 80%;
            margin-left: 10%;
            padding-left: 5px;
            font-size: 20px;
        }

        section>div>div input {
            height: 25px;
            padding-left: 10px;
            font-size: 25px;
            width: 20%;
        }

        section>div>div {
            margin-left: 20px;
        }

        h2 {
            line-height: 1.5;
        }

        section>div>form p {
            padding-left: 20px;
            font-size: 25px;
        }

        button {
            width: 50%;
            margin-left: 10%;
            margin-top: 10px;
            border-radius: 20px;
            cursor: pointer;
            border: 2px solid;
            background-color: #d9d9d9;
            margin-left: 25%;
        }

        button:hover {
            background-color: white;
        }

        .btn2 {
            width: 20%;
            margin-left: 2%;
        }

        button:hover>img {
            transform: scale(1.2);
        }

        button>img {
            width: 30%;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        button>p {
            padding-bottom: 15px;
            font-size: 25px;
        }

        table {
            margin-top: 20px;
            width: 90%;
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

        input[type=number] {
            -moz-appearance: textfield;
        }

        .For1 {
            margin-top: 30px;
            margin-left: 50px;
            background-color: rgb(255, 255, 255);
            width: 60%;
            border-radius: 20px;
            padding-left: 20px;
            padding-top: 10px;
            padding-bottom: 20px;
        }

        .For1>h1 {
            font-size: 30px;
            font-weight: 100;
        }

        .For1>h3 {
            font-size: 25px;
            font-weight: 300;

        }

        .For1>p {
            padding-left: 10px;
            padding-top: 5px;
        }

        .For1>input {
            margin-top: 5px;
            margin-left: 10px;
            width: 50%;
        }

        .bt-Ag {
            margin-left: 30%;
        }

        .btnEliminarTable {
            width: 90%;
            margin-left: 0px;
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
                    while ($row = mysqli_fetch_assoc($Datos)) { ?>
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
                    $CodiPro = mysqli_real_escape_string($conn, $_POST['Prod']);
                    $CodiProN = str_replace("'", "-", $CodiPro);

                    // Consulta para obtener el producto basado en CodigoProducto
                    $Consulta30 = "SELECT * FROM productos WHERE CodigoProducto = '$CodiPro'";
                    $query30 = mysqli_query($conn, $Consulta30);

                    $Consulta31 = "SELECT * FROM productos WHERE CodigoBarras = '$CodiProN'";
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
                                                    <form action="VentasPrueba.php" method="get">
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
                                $rowDatos = mysqli_fetch_assoc($queryDatos);
                                $_SESSION['ExistenciaC'] = $rowDatos['Existencia'];
                                $_SESSION['IDC'] = $rowDatos['id_producto'];
                                $_SESSION['DescripC'] = $rowDatos['Descripcion'];
                                $_SESSION['MarcaC'] = $rowDatos['Marca'];
                                ?>
                                <h3>Descripcion: </h3>
                                <p><?php print_r($_SESSION['DescripC']) ?></p>
                                <h3>Marca: </h3>
                                <p><?php print_r($_SESSION['MarcaC']) ?></p>
                                <h3>Cantidad: </h3> <input type="number" name="cantidad"><br>
                                <h3>Precio: </h3> <input type="number" name="Precio" list="Prec"><br>
                                <datalist id="Prec">
                                    <option value="<?php echo $rowDatos['Precio1']; ?>"><?php echo $rowDatos['Precio1']; ?></option>
                                    <option value="<?php echo $rowDatos['Precio2']; ?>"><?php echo $rowDatos['Precio2']; ?></option>
                                    <option value="<?php echo $rowDatos['Precio3']; ?>"><?php echo $rowDatos['Precio3']; ?></option>
                                </datalist>
                                <h3>En Existencia: </h3>
                                <p><?php print_r($_SESSION['ExistenciaC']) ?></p>
                                <h3>Ultima Vez Agregado: </h3>
                                <p><?php print_r($rowDatos['UltimaFecha']) ?></p>
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
                                                    <form action="VentasPrueba.php" method="get">
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
                                $ConsultaDatos = "SELECT * FROM `productos` WHERE CodigoBarras = '$CodiProN'";
                                $queryDatos = mysqli_query($conn, $ConsultaDatos);
                                $rowDatos = mysqli_fetch_assoc($queryDatos);
                                $_SESSION['ExistenciaC'] = $rowDatos['Existencia'];
                                $_SESSION['IDC'] = $rowDatos['id_producto'];
                                $_SESSION['DescripC'] = $rowDatos['Descripcion'];
                                $_SESSION['MarcaC'] = $rowDatos['Marca'];
                                ?>
                                <h3>Descripcion: </h3>
                                <p><?php print_r($_SESSION['DescripC']) ?></p>
                                <h3>Marca: </h3>
                                <p><?php print_r($_SESSION['MarcaC']) ?></p>
                                <h3>Cantidad: </h3> <input type="number" name="cantidad"><br>
                                <h3>Precio: </h3> <input type="number" name="Precio" list="Prec"><br>
                                <datalist id="Prec">
                                    <option value="<?php echo $rowDatos['Precio1']; ?>"><?php echo $rowDatos['Precio1']; ?></option>
                                    <option value="<?php echo $rowDatos['Precio2']; ?>"><?php echo $rowDatos['Precio2']; ?></option>
                                    <option value="<?php echo $rowDatos['Precio3']; ?>"><?php echo $rowDatos['Precio3']; ?></option>
                                </datalist>
                                <h3>En Existencia: </h3>
                                <p><?php print_r($_SESSION['ExistenciaC']) ?></p>
                                <h3>Ultima Vez Agregado: </h3>
                                <p><?php print_r($rowDatos['UltimaFecha']) ?></p>
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
                                                    <form action="VentasPrueba.php" method="get">
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
                                $ConsultaDatos = "SELECT * FROM `productos` WHERE Descripcion = '$CodiPro'";
                                $queryDatos = mysqli_query($conn, $ConsultaDatos);
                                $rowDatos = mysqli_fetch_assoc($queryDatos);
                                $_SESSION['ExistenciaC'] = $rowDatos['Existencia'];
                                $_SESSION['IDC'] = $rowDatos['id_producto'];
                                $_SESSION['DescripC'] = $rowDatos['Descripcion'];
                                $_SESSION['MarcaC'] = $rowDatos['Marca'];
                                ?>
                                <h3>Descripcion: </h3>
                                <p><?php print_r($_SESSION['DescripC']) ?></p>
                                <h3>Marca: </h3>
                                <p><?php print_r($_SESSION['MarcaC']) ?></p>
                                <h3>Cantidad: </h3> <input type="number" name="cantidad"><br>
                                <h3>Precio: </h3> <input type="number" name="Precio" list="Prec"><br>
                                <datalist id="Prec">
                                    <option value="<?php echo $rowDatos['Precio1']; ?>"><?php echo $rowDatos['Precio1']; ?></option>
                                    <option value="<?php echo $rowDatos['Precio2']; ?>"><?php echo $rowDatos['Precio2']; ?></option>
                                    <option value="<?php echo $rowDatos['Precio3']; ?>"><?php echo $rowDatos['Precio3']; ?></option>
                                </datalist>
                                <h3>En Existencia: </h3>
                                <p><?php print_r($_SESSION['ExistenciaC']) ?></p>
                                <h3>Ultima Vez Agregado: </h3>
                                <p><?php print_r($rowDatos['UltimaFecha']) ?></p>
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
                    $_SESSION['IDC'] = $ID;
                    $Cons = "SELECT * FROM productos WHERE id_producto = '$ID'";
                    $query3 = mysqli_query($conn, $Cons);
                    if ($rowDatos = mysqli_fetch_assoc($query3)) {
                ?>
                        <form action="VentasPrueba.php" method="post" class="For1">
                            <h1>Informacion del Producto:</h1>
                            <?php
                            $ConsultaDatos = "SELECT * FROM `productos` WHERE id_producto = '$ID'";
                            $queryDatos = mysqli_query($conn, $ConsultaDatos);
                            $rowDatos = mysqli_fetch_assoc($queryDatos);
                            $_SESSION['ExistenciaC'] = $rowDatos['Existencia'];
                            $_SESSION['IDC'] = $rowDatos['id_producto'];
                            $_SESSION['DescripC'] = $rowDatos['Descripcion'];
                            $_SESSION['MarcaC'] = $rowDatos['Marca'];
                            ?>
                            <h3>Descripcion: </h3>
                            <p><?php print_r($_SESSION['DescripC']) ?></p>
                            <h3>Marca: </h3>
                            <p><?php print_r($_SESSION['MarcaC']) ?></p>
                            <h3>Cantidad: </h3> <input type="number" name="cantidad"><br>
                            <h3>Precio: </h3> <input type="number" name="Precio" list="Prec"><br>
                            <datalist id="Prec">
                                <option value="<?php echo $rowDatos['Precio1']; ?>"><?php echo $rowDatos['Precio1']; ?></option>
                                <option value="<?php echo $rowDatos['Precio2']; ?>"><?php echo $rowDatos['Precio2']; ?></option>
                                <option value="<?php echo $rowDatos['Precio3']; ?>"><?php echo $rowDatos['Precio3']; ?></option>
                            </datalist>
                            <h3>En Existencia: </h3>
                            <p><?php print_r($_SESSION['ExistenciaC']) ?></p>
                            <h3>Ultima Vez Agregado: </h3>
                            <p><?php print_r($rowDatos['UltimaFecha']) ?></p>
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
                        <th>Precio U.</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                        <?php foreach ($_SESSION['carrito'] as $producto): $IP = $producto['id']; ?>
                            <tr>
                                <form action="VentasPrueba.php?ID=<?php echo $IP; ?>" method="post">
                                    <td><?php echo htmlspecialchars($producto['Descripcion']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Marca']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Presentacion']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['Cantidad']); ?></td>
                                    <td>$<?php echo htmlspecialchars($producto['Precio']); ?> C/U</td>
                                    <td>$<?php echo htmlspecialchars(intval($producto['Cantidad']) * intval($producto['Precio'])); ?></td>
                                    <td><button class="btnEliminarTable" type="submit" name="btnEliminarTable">Eliminar</button></td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No hay productos en el carrito de Cotzizaciones.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table><br><br>
            <h2>Total a Pagar: $<?php echo $totalAPagar; ?></h2>
            <form action="" method="post">
                <button type="submit" name="RealizarVenta"><img src="img/caja-registradora.png" alt=""><br>Realizar Venta</button>
            </form>
        </div>
    </section>
</body>

</html>

<?php
if (isset($_POST['btnEliminarTable'])) {
    $idAEliminar = $_GET['ID'];
    // Recorrer el array para encontrar el producto con el ID a eliminar



    foreach ($_SESSION['carrito'] as $indice => $producto) {
        if ($producto['id'] == $idAEliminar) {
            unset($_SESSION['carrito'][$indice]);
            echo "<script> window.location.href = 'VentasPrueba.php'; </script>";
            // Romper el bucle si solo deseas eliminar un producto
            break;
        }
    }
}
?>

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