<?php
session_start(); // Asegúrate de que esta línea esté al principio

include 'includes/conn.php';

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapa las entradas del usuario para prevenir SQL Injection
    $Clave = mysqli_real_escape_string($conn, $_POST['Usuario']);
    $Contraseña = mysqli_real_escape_string($conn, $_POST['Contraseña']);

    // Consulta SQL
    $Consulta = "SELECT Tipo_Cuenta, Nombre FROM usuarios WHERE Clave = '$Clave' AND Contraseña = '$Contraseña'";
    $query = mysqli_query($conn, $Consulta);

    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            $Rol = mysqli_fetch_array($query);

            // Establece las variables de sesión
            $_SESSION['nombre'] = $Rol['Nombre'];
            $_SESSION['Rol'] = $Rol['Tipo_Cuenta'];

            // Redirige a Dashboard.php
            echo '<script>
            window.location.href = "Dashboard.php";
            </script>';
            $_SESSION['facturas_consultadas'] = 1;
        } else {
            // Usuario o contraseña incorrectos
            echo '<script>
            alert("Usuario y/o Contraseña Equivocada");
            window.location.href = "index.php";
            </script>';
        }
    } else {
        // Error en la consulta
        echo '<script>
        alert("Error en la consulta: ' . mysqli_error($conn) . '");
        window.location.href = "index.php";
        </script>';
    }

    mysqli_close($conn);
} else {
    // Si el formulario no ha sido enviado, redirige al formulario
    header("Location: index.php");
    exit();
}
?>