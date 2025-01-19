<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include 'includes/conn.php';

// Escapar datos para evitar SQL Injection
if (isset($_GET['id'])) {
    $ID_Producto = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');

    // Consulta para obtener el producto
    $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $ID_Producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Obtener todas las marcas y líneas
    $ConsultaMarcas = "SELECT * FROM proveedores";
    $QueryMarcas = mysqli_query($conn, $ConsultaMarcas);
    $rowMarcas = [];
    while ($marca = mysqli_fetch_assoc($QueryMarcas)) {
        $rowMarcas[] = $marca;
    }

    $ConsultaLineas = "SELECT * FROM Linea";
    $QueryLineas = mysqli_query($conn, $ConsultaLineas);
    $rowLineas = [];
    while ($linea = mysqli_fetch_assoc($QueryLineas)) {
        $rowLineas[] = $linea;
    }

    // Procesar el formulario si se envía
    if (isset($_POST['Guardar'])) {
        // Recoger y procesar los datos
        $CodigoBarras = $_POST['CodigoBarras'];
        $CodBarrasNew = str_replace("'", "-", $CodigoBarras);

        $Descripcion = $_POST['Descripcion'];
        $Des = strtoupper($Descripcion);

        $Presentacion = $_POST['Presentacion'];
        $Marcas = $_POST['Marca'];
        $Lineas = $_POST['Linea'];
        $CodigoProducto = $_POST['CodigoProducto'];
        $CodPrin = $_POST['CodPrin'];
        $Existencia = $_POST['Exi'];
        $Precio1 = $_POST['Pre1'];
        $Precio2 = $_POST['Pre2'];
        $Precio3 = $_POST['Pre3'];
        $MinExi = $_POST['minExi'];

        // Asegurarse de que todos los campos sean válidos
        if (!empty($CodBarrasNew) && !empty($Des) && !empty($Presentacion) && !empty($Marcas) && !empty($Lineas) && !empty($CodigoProducto)) {
            // Consulta para actualizar el producto
            $stmt = $conn->prepare("UPDATE productos SET CodigoBarras=?, CodigoProducto=?, Descripcion=?, Presentacion=?, Marca=?, Linea=?, CodigoPrincipal=?, Existencia=? ,Precio1=?, Precio2=?, Precio3=?, ExistenciaMinima=? WHERE id_producto=?");
            if ($stmt) {
                $stmt->bind_param("ssssssssssssi", $CodBarrasNew, $CodigoProducto, $Des, $Presentacion, $Marcas, $Lineas, $CodPrin,$Existencia, $Precio1, $Precio2, $Precio3, $MinExi, $ID_Producto);
                $stmt->execute();

                // Verificar si la consulta se ejecutó correctamente
                if ($stmt->affected_rows > 0) {
                    echo '<script>
                        alert("Actualización de Datos Exitosa");
                        window.location.href="Buscar.php";
                    </script>';
                } else {
                    echo '<script>
                        alert("No se realizaron cambios o la actualización falló");
                        window.location.href="Buscar.php";
                    </script>';
                }
            } else {
                echo '<script>
                    alert("Error en la consulta de actualización");
                    window.location.href="Buscar.php";
                </script>';
            }
        } else {
            echo '<script>
                alert("Por favor, complete todos los campos");
            </script>';
        }
    }
} else {
    // Manejar el caso donde no se proporciona 'id'
    echo "ID no proporcionado en la URL.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <title>Modificar Producto</title>

    <style>
        * {
            padding: 0px;
            margin: 0px;
        }

        body {
            background-color: #d9d9d9;
        }

        form {
            width: 80%;
            margin-top: 40px;
            display: grid;
            padding-left: 30px;
        }

        form>label {
            font-size: 25px;
            padding: 5px;
            line-height: 30px;
        }

        form>input[type="text"] {
            width: 50%;
            height: 30px;
        }

        form>input {
            padding-left: 3px;
            height: 20px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        form>input[type="submit"] {
            width: 20%;
            margin-left: 20%;
            height: 40px;
            font-size: 20px;
            border-radius: 20px;
            cursor: pointer;
            background-color: #d9d9d9;
            margin-top: 30px;
        }

        form>input[type="submit"]:hover {
            background-color: white;
        }
    </style></head>
<body>
<header>
    <div class="el">
        <img src="img/Logo.JPG" alt="">
        <h1>Bienvenid@, <span></span> <?php echo htmlspecialchars($_SESSION['nombre']); ?> </h1>
    </div>
    <div class="">
        <ul>
            <li><a href="Buscar.php">Volver</a></li>
            <li><a href="index.php">Cerrar Sesion</a></li>
        </ul>
    </div>
</header>

<section>
    <form method="post" action="" autocomplete="off" >
        <label>Codigo De Barras: </label> 
        <input type="text" name="CodigoBarras" value="<?php echo htmlspecialchars($row['CodigoBarras'] ?? ''); ?>">
        
        <label>Descripcion: </label>
        <input type="text" name="Descripcion" value="<?php echo htmlspecialchars($row['Descripcion'] ?? ''); ?>">
        
        <label>Codigo Del Producto: </label> 
        <input type="text" name="CodigoProducto" value="<?php echo htmlspecialchars($row['CodigoProducto'] ?? ''); ?>">
        
        <label>Presentacion: </label>
        <input type="text" name="Presentacion" list="Presentacion" value="<?php echo htmlspecialchars($row['Presentacion'] ?? ''); ?>">
        
        <label>Marca: </label> 
        <input type="text" name="Marca" list="Marcas" value="<?php echo htmlspecialchars($row['Marca'] ?? ''); ?>">
        
        <label>Linea: </label>
        <input type="text" name="Linea" list="Lineas" value="<?php echo htmlspecialchars($row['Linea'] ?? ''); ?>">

        <label>Codigo Principal: </label>
        <input type="text" name="CodPrin" value="<?php echo htmlspecialchars($row['CodigoPrincipal'] ?? ''); ?>">
        
        <label>Existencia: </label>
        <input type="text" name="Exi" value="<?php echo htmlspecialchars($row['Existencia'] ?? ''); ?>">

        <label>Precio 1: </label>
        <input type="text" name="Pre1" value="<?php echo htmlspecialchars($row['Precio1'] ?? ''); ?>">

        <label>Precio 2: </label>
        <input type="text" name="Pre2" value="<?php echo htmlspecialchars($row['Precio2'] ?? ''); ?>">

        <label>Precio 3: </label>
        <input type="text" name="Pre3" value="<?php echo htmlspecialchars($row['Precio3'] ?? ''); ?>">

        <label>Minimo Existencia: </label>
        <input type="text" name="minExi" value="<?php echo htmlspecialchars($row['ExistenciaMinima'] ?? ''); ?>">


        <input type="submit" value="Guardar" name="Guardar">
    </form>
    <br><br><br>
</section>

<datalist id="Presentacion">
    <option value="GALON">GALON</option>
    <option value="LITRO">LITRO</option>
    <option value="PIEZA">PIEZA</option>
    <option value="CAJA">CAJA</option>
</datalist>

<datalist id="Marcas">
    <?php foreach ($rowMarcas as $marca) { ?>
    <option value="<?php echo htmlspecialchars($marca['Nombre']); ?>"><?php echo htmlspecialchars($marca['Nombre']); ?></option>
    <?php } ?>
</datalist>

<datalist id="Lineas">
    <?php foreach ($rowLineas as $linea) { ?>
    <option value="<?php echo htmlspecialchars($linea['Linea']); ?>"><?php echo htmlspecialchars($linea['Descripcion']); ?></option>
    <?php } ?>
</datalist>
</body>
</html>