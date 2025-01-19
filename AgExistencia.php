<?php
include 'includes/conn.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['existencia'])) {

    if (isset($_SESSION['ID'])) {
        // Limpiar y asignar valores de las sesiones y el formulario
        $IDProd = intval($_SESSION['ID']);
        $query = "SELECT Existencia FROM productos WHERE id_producto = '$IDProd'";
        $result = mysqli_query($conn, $query); // Asumiendo que estás usando mysqli

        $row = mysqli_fetch_assoc($result);
        $Existencia = intval($row['Existencia']);

        $ExistenciaAnterior = $_POST['AgExi'];

        $NewExistencia = $ExistenciaAnterior + $Existencia;

        $NePrecio1 = floatval($_POST['NewPre1']);
        $NePrecio2 = floatval($_POST['NewPre2']);
        $NePrecio3 = floatval($_POST['NewPre3']);

        // Consulta SQL para actualizar el producto
        $Modi = "UPDATE productos 
                 SET Existencia = $NewExistencia, 
                     Precio1 = $NePrecio1, 
                     Precio2 = $NePrecio2, 
                     Precio3 = $NePrecio3 
                 WHERE id_producto = $IDProd";

        // Ejecutar la consulta
        $quer = mysqli_query($conn, $Modi);

        // Verificar si la consulta se ejecutó correctamente
        if ($quer) {
            echo "
            <script>
            alert('Cambio Exitoso');
            window.location.href='AgExistencia.php';
            </script>
            "; 
            // Resetear las sesiones después de la actualización
            $_SESSION['Cod2'] = 0;
            $_SESSION['Precio1'] = 0.0;
            $_SESSION['Precio2'] = 0.0;
            $_SESSION['Precio3'] = 0.0;
            $_SESSION['Existencia2'] = 0;
            $_SESSION['ID'];
        } else {
            // Mostrar el error si la consulta falla
            echo "Error al actualizar: " . mysqli_error($conn);
        }
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
                    $Consulta = "SELECT * FROM productos WHERE CodigoBarras = '$CodBarrasNew'";
                    $query = mysqli_query($conn, $Consulta);
                    $Cuenta = mysqli_num_rows($query);
                    if ($Cuenta>1) { 
                            ?>
                            <div class="cont" id="Formulario">
                            <h1><center>SELECCIONE EL PRODUCTO</center></h1>
                            <br>
                            <?php
                            while ($row2 = mysqli_fetch_assoc($query)) {
                                $ID = $row2['id_producto'];
                         ?>
                            <!-- Formulario que se hace visible -->
                            <form action="AgExistencia.php?id=<?php echo $ID; ?>" method="post" id="miFormulario">
                                <h2> <?php $Descr = $row2['Descripcion']; print $Descr; ?> </h2>
                                <h2> <?php print $row2['Marca']; ?> </h2>
                                <h2> <?php print $row2['CodigoProducto']; ?> </h2>
                                <h2> <?php print $row2['Existencia']; ?> </h2>

                                <button type="submit" class="btn3" name="GuardarID">Agregar</button>
                            </form>
                            <br>
                        <?php } ?>
                            </div>
                       <?php


                    } else{
                        if ($row2 = mysqli_fetch_assoc($query)){ ?>
                            <form action="" method="post">
                                <h2>Descripcion: <?php print $row2['Descripcion']; ?></h2>
                                <h2>Marca: <?php print $row2['Marca']; ?> </h2>
                                <h2>Presentacion: <?php print $row2['Presentacion']; ?> </h2>
                                <h2>Precio 1: <?php $_SESSION['Precio1'] = $row2['Precio1']; print $_SESSION['Precio1'] ?> </h2>
                                <h2>Precio 2: <?php $_SESSION['Precio2'] = $row2['Precio2']; print $_SESSION['Precio2'] ?> </h2>
                                <h2>Precio 3: <?php $_SESSION['Precio3'] = $row2['Precio3']; print $_SESSION['Precio3'] ?> </h2>
                                <h2>En Existencia: <?php print $row2['Existencia']; ?></h2>
                                <?php $_SESSION['ID'] = $row2['id_producto']; ?>
                            </form>
                        <?php } else {
                            print "<h2>Producto no encontrado</h2>";
                        }
                    }
                } 



        //Codigo de Producto -----------------------------------

                if (isset($_POST['CodProd'])) {
                    $CodProd = $_POST['CodProd'];
                    $Consulta = "SELECT * FROM productos WHERE CodigoProducto = '$CodProd'";
                    
                    $query = mysqli_query($conn, $Consulta);
                    $Cuenta = mysqli_num_rows($query);
                    if ($Cuenta>1) {
                            ?>
                            <div class="cont" id="Formulario">
                            <h1><center>SELECCIONE EL PRODUCTO</center></h1>
                            <br>
                            <?php
                            while ($row2 = mysqli_fetch_assoc($query)) {
                                $ID = $row2['id_producto'];
                         ?>
                            <!-- Formulario que se hace visible -->
                            <form action="AgExistencia.php?id2=<?php echo $ID; ?>" method="post" id="miFormulario">
                                <h2> <?php $Descr = $row2['Descripcion']; print $Descr; ?> </h2>
                                <h2> <?php print $row2['Marca']; ?> </h2>
                                <h2> <?php print $row2['CodigoProducto']; ?> </h2>
                                <h2> <?php print $row2['Existencia']; ?> </h2>

                                <button type="submit" class="btn3" name="GuardarID">Agregar</button>
                            </form>
                            <br>
                        <?php } ?>
                            </div>
                       <?php
            


                    } else{
                        if ($row2 = mysqli_fetch_assoc($query)){ ?>
                            <form action="" method="post">
                                <h2>Descripcion: <?php print $row2['Descripcion']; ?></h2>
                                <h2>Marca: <?php print $row2['Marca']; ?> </h2>
                                <h2>Presentacion: <?php print $row2['Presentacion']; ?> </h2>
                                <h2>Precio 1: <?php $_SESSION['Precio1'] = $row2['Precio1']; print $_SESSION['Precio1'] ?> </h2>
                                <h2>Precio 2: <?php $_SESSION['Precio2'] = $row2['Precio2']; print $_SESSION['Precio2'] ?> </h2>
                                <h2>Precio 3: <?php $_SESSION['Precio3'] = $row2['Precio3']; print $_SESSION['Precio3'] ?> </h2>
                                <h2>En Existencia: <?php print $row2['Existencia']; ?></h2>
                                <?php $_SESSION['ID'] = $row2['id_producto']; ?>
                            </form>
                        <?php } else {
                            print "<h2>Producto no encontrado</h2>";
                        }
                    }
                }         
            }

if (isset($_GET['id'])) {

            $ID = $_GET['id'];
            $_SESSION['ID'] = $ID;
            $Cons = "SELECT * FROM productos WHERE id_producto = '$ID'";
            $query3 = mysqli_query($conn, $Cons);
            if ($rowProd = mysqli_fetch_assoc($query3)) {
                 ?>
                 <form action="AgExistencia.php" method="post">
                     <h2>Descripcion: <?php print $rowProd['Descripcion']; ?></h2>
                     <h2>Marca: <?php print $rowProd['Marca']; ?> </h2>
                     <h2>Presentacion: <?php print $rowProd['Presentacion']; ?> </h2>
                     <h2>Precio 1: <?php $_SESSION['Precio1'] = $rowProd['Precio1']; print $_SESSION['Precio1'] ?> </h2>
                     <h2>Precio 2: <?php $_SESSION['Precio2'] = $rowProd['Precio2']; print $_SESSION['Precio2'] ?> </h2>
                     <h2>Precio 3: <?php $_SESSION['Precio3'] = $rowProd['Precio3']; print $_SESSION['Precio3'] ?> </h2>
                     <h2>En Existencia: <?php print $rowProd['Existencia']; ?></h2>
                     <input type="hidden" name="CodBarra" value="<?php print $rowProd['CodigoBarras']; ?>">
                 </form>
                 <?php
            }
        }
    




//Codigo de Producto -----------------------------------

        if (isset($_GET['id2'])) {


            $ID2 = $_GET['id2'];
            $_SESSION['ID'] = $ID2;
            $Cons2 = "SELECT * FROM productos WHERE id_producto = '$ID2'";
            $query3 = mysqli_query($conn, $Cons2);
            if ($rowProd = mysqli_fetch_assoc($query3)) {
                 ?>
                 <form action="AgExistencia.php" method="post">
                     <h2>Descripcion: <?php print $rowProd['Descripcion']; ?></h2>
                     <h2>Marca: <?php print $rowProd['Marca']; ?> </h2>
                     <h2>Presentacion: <?php print $rowProd['Presentacion']; ?> </h2>
                     <h2>Precio 1: <?php $_SESSION['Precio1'] = $rowProd['Precio1']; print $_SESSION['Precio1'] ?> </h2>
                     <h2>Precio 2: <?php $_SESSION['Precio2'] = $rowProd['Precio2']; print $_SESSION['Precio2'] ?> </h2>
                     <h2>Precio 3: <?php $_SESSION['Precio3'] = $rowProd['Precio3']; print $_SESSION['Precio3'] ?> </h2>
                     <h2>En Existencia: <?php print $rowProd['Existencia'];  ?></h2>
                     <input type="hidden" name="CodBarra" value="<?php print $rowProd['CodigoBarras']; ?>">
                 </form>
                 <?php
            }
        }

       
            ?>
            </div>
        </div>
        <div>

        <form action="AgExistencia.php" method="post">
            <p>Cuantos Agregara</p>
            <input type="number" name="AgExi" id="" value="0"> 
                <p>Nuevo Precio 1:</p> 
                <input type="number" name="NewPre1" value="<?php print_r($_SESSION['Precio1']); ?>" step="0.01">
                <p>Nuevo Precio 2:</p> 
                <input type="number" name="NewPre2" value="<?php print_r($_SESSION['Precio2']); ?>" step="0.01">
                <p>Nuevo Precio 3:</p> 
                <input type="number" name="NewPre3" value="<?php print_r($_SESSION['Precio3']); ?>" step="0.01">

            <button type="submit" name="existencia"><img src="img/mas.png" alt=""><br><p>Agregar</p></button>
        </form>
        </div>
    </section>

</body>
</html>