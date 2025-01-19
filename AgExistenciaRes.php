<?php
include 'includes/conn.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$mostrarDiv = true; // Cambiar a `false` según la lógica deseada

if (isset($_GET['id'])) {

    $ID = $_GET['id'];
    $_SESSION['ID'] = $ID;
    $Cons = "SELECT * FROM productos WHERE id_producto = '$ID'";
    $query3 = mysqli_query($conn, $Cons);
    if ($rowProd = mysqli_fetch_assoc($query3)) {
        $_SESSION['Precio1'] = $rowProd['Precio1'];
        $_SESSION['Precio2'] = $rowProd['Precio2'];
        $_SESSION['Precio3'] = $rowProd['Precio3'];
        $_SESSION['Descrip'] = $rowProd['Descripcion'];
        $_SESSION['Existencia'] = $rowProd['Existencia'];

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
    <link rel="stylesheet" href="css/ventana.css">
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
                                                        <form action="AgExistenciaRes.php" method="get">
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

                } else {
                    $ConsultaDatos = "SELECT * FROM `productos` WHERE CodigoProducto = '$CodiPro'";
                    $queryDatos = mysqli_query($conn, $ConsultaDatos);
                    $rowDatos = mysqli_fetch_assoc( $queryDatos);
                    $_SESSION['Precio1'] = $rowDatos['Precio1'];
                    $_SESSION['Precio2'] = $rowDatos['Precio2'];
                    $_SESSION['Precio3'] = $rowDatos['Precio3'];
                    $_SESSION['Existencia'] = $rowDatos['Existencia'];
                    $_SESSION['ID'] = $rowDatos['id_producto'];
                    $_SESSION['Descrip'] = $rowDatos['Descripcion'];
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
                                                        <form action="AgExistenciaRes.php" method="get">
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
                } else {
                    $ConsultaDatos = "SELECT * FROM `productos` WHERE CodigoBarraS = '$CodiPro'";
                    $queryDatos = mysqli_query($conn, $ConsultaDatos);
                    $rowDatos = mysqli_fetch_assoc( $queryDatos);
                    $_SESSION['Precio1'] = $rowDatos['Precio1'];
                    $_SESSION['Precio2'] = $rowDatos['Precio2'];
                    $_SESSION['Precio3'] = $rowDatos['Precio3'];
                    $_SESSION['Existencia'] = $rowDatos['Existencia'];
                    $_SESSION['ID'] = $rowDatos['id_producto'];
                    $_SESSION['Descrip'] = $rowDatos['Descripcion'];
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
                                                        <form action="AgExistenciaRes.php" method="get">
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
                } else {
                    $ConsultaDatos = "SELECT * FROM `productos` WHERE Descripcion = '$CodiPro'";
                    $queryDatos = mysqli_query($conn, $ConsultaDatos);
                    $rowDatos = mysqli_fetch_assoc( $queryDatos);
                    $_SESSION['Precio1'] = $rowDatos['Precio1'];
                    $_SESSION['Precio2'] = $rowDatos['Precio2'];
                    $_SESSION['Precio3'] = $rowDatos['Precio3'];
                    $_SESSION['Existencia'] = $rowDatos['Existencia'];
                    $_SESSION['ID'] = $rowDatos['id_producto'];
                    $_SESSION['Descrip'] = $rowDatos['Descripcion'];
                }
            }
            }
            ?>
            </div>
        </div>
        <div>

        <form action="AgExistenciaRes.php" method="post">
            <p>Nombre del Producto:</p> <p><?php echo $_SESSION['Descrip']; ?></p>
            <p>Cuantos Agregara</p>
            <input type="number" name="AgExi" id="" value="0"> 
                <p>Nuevo Precio 1:</p> 
                <input type="number" name="NewPre1" value="<?php print_r($_SESSION['Precio1']); ?>" step="0.01" class="prec">
                <p>Nuevo Precio 2:</p> 
                <input type="number" name="NewPre2" value="<?php print_r($_SESSION['Precio2']); ?>" step="0.01" class="prec">
                <p>Nuevo Precio 3:</p> 
                <input type="number" name="NewPre3" value="<?php print_r($_SESSION['Precio3']); ?>" step="0.01" class="prec">

            <button type="submit" name="existencia"><img src="img/mas.png" alt=""><br><p>Agregar</p></button>
        </form>
        </div>
    </section>

</body>
</html>

<?php


# if (isset($_POST['btnid'])) {
# 
#     $_SESSION['ID'] = $_POST['id_producto'];
#     echo $_SESSION['ID'];
#     $IDN= $_SESSION['ID'];
#     $ConsultaDatosN = "SELECT * FROM `productos` WHERE id_producto = '$IDN'";
#     $queryDatosN = mysqli_query($conn, $ConsultaDatosN);
#     $rowDatosN = mysqli_fetch_assoc( $queryDatosN);
#     $_SESSION['Precio1'] = $rowDatosN['Precio1'];
#     $_SESSION['Precio2'] = $rowDatosN['Precio2'];
#     $_SESSION['Precio3'] = $rowDatosN['Precio3'];
#     $_SESSION['Existencia'] = $rowDatosN['Existencia'];
#     $_SESSION['Descrip'] = $rowDatosN['Descripcion'];
# }

if (isset($_POST['existencia'])) {

    if (isset($_SESSION['ID']) && $_SESSION['ID'] > 0) {

        $Ag = intval($_POST['AgExi']);
        $ExiPas = intval($_SESSION['Existencia']);
        $Precio1N = $_POST['NewPre1'];
        $Precio2N = $_POST['NewPre2'];
        $Precio3N = $_POST['NewPre3'];
        $IDPr = $_SESSION['ID'];
    
        $ExiNew1 = $Ag + $ExiPas;
        date_default_timezone_set('America/Mexico_City');

        // Obtener el mes y el año
        $mes = date('m'); // Formato numérico del mes (01-12)
        $anio = date('Y'); // Año completo (por ejemplo, 2024)
        $MesAño = $mes ."-". $anio;;
    
        $Modificacion = "UPDATE `productos` SET `Precio1`='$Precio1N',`Precio2`='$Precio2N',`Precio3`='$Precio3N', `Existencia`='$ExiNew1', `UltimaFecha`='$MesAño' WHERE id_producto = '$IDPr'";
        $queryCambio = mysqli_query($conn, $Modificacion);
    
        if ($queryCambio) {
            echo "
            <script>
            alert('Se ah agreado los cambios con exito');
            window.location.href='AgExistenciaRes.php';
            </script>";
            $_SESSION['Precio1'] = 0;
            $_SESSION['Precio2'] = 0;
            $_SESSION['Precio3'] = 0;
            $_SESSION['ID'] = 0;
            $_SESSION['Descrip'] = "";
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

