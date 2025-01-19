<?php
include 'includes/conn.php';

if (isset($_GET['id'])) {
    $ID = intval($_GET['id']);

    // Ejecutar la consulta para eliminar el producto
    $Eliminacion = "DELETE FROM productos WHERE id_producto = '$ID'";
    $Ejecucion = mysqli_query($conn, $Eliminacion);

    // Verificar si la eliminación fue exitosa
    if ($Ejecucion) {
        echo '
        <script>
        alert("Dato eliminado con éxito");
        window.location.href = "ListaProducto.php";
        </script>
        ';
    } else {
        echo '
        <script>
        alert("Hubo un error al eliminar el producto");
        window.location.href = "ListaProducto.php";
        </script>
        ';
    }
} else {
    // Si no hay un ID en la URL, redirigir de nuevo a la lista
    echo '
    <script>
    alert("No se ha especificado un producto para eliminar");
    window.location.href = "ListaProducto.php";
    </script>
    ';
}
?>