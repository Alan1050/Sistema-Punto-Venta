<?php

include 'includes/conn.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

        h2{
            line-height: 1.5;
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


                .BPrincipal{
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                }

                .Tabla{
                    width:  100%;
                }

                .btnBus{
                    width: 30%;
                    margin-top: 20px;
                    margin-left: 15%;
                    border-radius: 20px;
                    padding: 10px 0px;
                    cursor: pointer;
                    border: 1px solid black;
                }

                .Busqueda{
                    width: 100%;
                    padding-left: 30px;
                }

                .Busqueda>input[type="text"]{
                    margin-left: 10px;
                    margin-top: 15px;
                    margin-bottom: 15px;
                    width: 60%;
                    font-size: 20px;
                    padding: 5px 0px 5px 10px;
                }

                article{
                    display: grid;
                    grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
                    padding-top: 10px;
                }

                #Cant{
                    width: 30%;
                }
</style>

</head>
<body>

<section class="BPrincipal">

<section>

    <form action="VentasInt.php" method="post" class="Busqueda">
        <h2>Codigo de Barras</h2>
        <input type="text" placeholder="Codigo Barras" name="CodBarras" id="CodBarras">
        <h2>Codigo de Producto</h2>
        <input type="text" placeholder="Codigo Producto" name="CodProd" id="CodProd">
            <br>
        <input type="submit" value="Buscar" class="btnBus" id="btnBus" name="btnBus">
    </form>

    <?php
        if (isset($_GET['ID'])) {
            $ID_ProdBusCodBarra = $_GET['ID'];
            $Conslt = "SELECT * FROM productos WHERE id_producto = $ID_ProdBusCodBarra";
            $queryProdBusCodBarra = mysqli_query($conn, $Conslt);
            $RowCodBarra = mysqli_fetch_assoc($queryProdBusCodBarra);
            ?>
            <form action="VentasInt.php" method="post">
                <p>Descripcion: <?php echo $RowCodBarra['Descripcion']; ?></p>
                <p>Codigo Producto: <?php echo $RowCodBarra['CodigoProducto']; ?></p>
                <p>Marca: <?php echo $RowCodBarra['Marca']; ?></p>
                <p>Existencia Actual: <?php echo $RowCodBarra['Existencia']; ?></p>
                <p>Seleccionar Precio: <input type="text" name="Precio" list="PreciosProdCodBarra"></p>
                <p>Cantidad: <input type="number" name="Cantidad" id="Cant" min="0" max="<?php echo $RowCodBarra['Existencia']; ?>"></p>

                <input type="submit" value="Agregar" name="AgProdCodBarraCarrito" id="AgProdCodBarraCarrito">

                <datalist id="PreciosProdCodBarra">
                    <option value="<?php echo $RowCodBarra['Precio1'] ?>">Precio 1</option>
                    <option value="<?php echo $RowCodBarra['Precio2'] ?>">Precio 2</option>
                    <option value="<?php echo $RowCodBarra['Precio3'] ?>">Precio 3</option>
                </datalist>
            </form>
            <?php

            if (isset($_POST['AgProdCodBarraCarrito'])) {
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = array();
                }
                $Precio = $_POST['Precio'];
                $cantidad = $_POST['Cantidad'];
                $producto = array(
                    'CodigoBarras' => $RowCodBarra['CodigoBarras'],
                    'Descripcion' => $RowCodBarra['Descripcion'],
                    'Marca' => $RowCodBarra['Marca'],
                    'Precio' => $Precio,
                    'Cantidad' => $cantidad
                );
                $_SESSION['carrito'][] = $producto;
                echo "<pre>Error en la consulta: " . mysqli_error($conn) . "</pre>";
                echo "<pre>Consulta ejecutada: " . $Consulta . "</pre>";
            }
        } 
    ?>

</section>

<section>


    <div class="Tabla">
     <table>
      <thead>
        <tr>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
      </thead>
     <tbody>
        <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
            <?php foreach ($_SESSION['carrito'] as $producto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto['Descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($producto['Marca']); ?></td>
                    <td><?php echo htmlspecialchars($producto['Cantidad']); ?></td>
                    <td>$<?php echo htmlspecialchars($producto['Precio']); ?></td>
                    <td>$<?php echo htmlspecialchars($producto['Precio'] * $producto['Cantidad']); ?></td>
                    <td><button>Eliminar</button></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5">Total a Pagar:</td>
                <td>0.00</td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="7">No hay productos en el carrito de Ventas.</td>
            </tr>
        <?php endif; ?>
      </tbody>
     </table>

    </div>
<br>
    <?php
        if (isset($_POST['btnBus'])) {
        if(isset($_POST['CodBarras'])){

            $CodigoBarras = $_POST['CodBarras'];
            $CodigoBarrasNew = str_replace("'", "-", $CodigoBarras);
            $Consulta = "SELECT * FROM productos WHERE CodigoBarras = '$CodigoBarrasNew'";
            $query = mysqli_query($conn, $Consulta);
            $Cuenta = mysqli_num_rows($query);

            if ($Cuenta>1) {
                ?>
                <div>
                    <h1><center>Coincidencia de Producto, seleccione uno</center></h1>
                
                <?php
                while ($row2 = mysqli_fetch_assoc($query)) {
                    $ID = $row2['id_producto'];
                    ?>
                    
                    <article>
                    <p><?php print $row2['Descripcion']; ?></p>
                    <p><?php print $row2['CodigoProducto']; ?></p>
                    <p><?php print $row2['Existencia']; ?></p>
                    <p><?php print $row2['Marca']; ?></p>
                    <button onclick="window.location.href='VentasInt.php?ID=<?php print $ID ?>'">Agregar</button>
                    </article>
                    </div>
                    <?php
                }
            } else if ($Cuenta==1) {
                
            } else{

            }

        } 
        else 
            if (isset($_POST['CodProd'])) {
            
        }
     }

     ?>
</section>

</section>

</body>
</html>