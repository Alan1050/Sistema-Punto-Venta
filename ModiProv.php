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
    $stmt = $conn->prepare("SELECT * FROM proveedores WHERE id_proveedor = ?");
    $stmt->bind_param("i", $ID_Producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Procesar el formulario si se envía
    if (isset($_POST['Guardar'])) {

        $Nombre = $_POST['Nombre'];
        $NumTel = $_POST['NumeroTelefono'];
        $Ubi = $_POST['Ubicacion'];

        // Asegurarse de que todos los campos sean válidos
        if (!empty($Nombre) && !empty($NumTel) && !empty($Ubi)) {
            // Consulta para actualizar el producto
            $stmt = $conn->prepare("UPDATE proveedores SET Nombre=?, NumeroTelefono=?, Ubicacion=? WHERE id_proveedor=?");
            if ($stmt) {
                $stmt->bind_param("sssi", $Nombre, $NumTel, $Ubi, $ID_Producto);
                $stmt->execute();

                // Verificar si la consulta se ejecutó correctamente
                if ($stmt->affected_rows > 0) {
                    echo '<script>
                        alert("Actualización de Datos Exitosa");
                        window.location.href="Proveedor.php";
                    </script>';
                } else {
                    echo '<script>
                        alert("No se realizaron cambios o la actualización falló");
                        window.location.href="Proveedor.php";
                    </script>';
                }
            } else {
                echo '<script>
                    alert("Error en la consulta de actualización");
                    window.location.href="Proveedor.php";
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
    <title>Modificar Proveedor</title>

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
            <li><a href="Proveedor.php">Volver</a></li>
            <li><a href="index.php">Cerrar Sesion</a></li>
        </ul>
    </div>
</header>

<section>
    <form method="post" action="" autocomplete="off" >
        <label>Nombre: </label> 
        <input type="text" name="Nombre" value="<?php echo htmlspecialchars($row['Nombre'] ?? ''); ?>">
        
        <label>Numero Telefefono: </label>
        <input type="text" name="NumeroTelefono" value="<?php echo htmlspecialchars($row['NumeroTelefono'] ?? ''); ?>">
        
        <label>Ubicacion: </label> 
        <input type="text" name="Ubicacion" value="<?php echo htmlspecialchars($row['Ubicacion'] ?? ''); ?>">

        <input type="submit" value="Guardar" name="Guardar" class="btnG">
    </form>
    <br><br><br>
</section>

</body>
</html>