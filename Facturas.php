<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'includes/conn.php'; // Incluye tu archivo de conexión a la base de datos

// Consulta para obtener todas las ventas del día
$query = "SELECT * FROM Facturas";
$result = mysqli_query($conn, $query);

if (isset($_POST['Agregar'])) {
    $Folio = $_POST['Folio'];
    $TotalPagar = $_POST['Total'];
    $FechaLimite = $_POST['Fecha'];

    $Insersion = "INSERT INTO Facturas (Folio, Total_Pagar, Fecha_Limite, Estado) VALUES ('$Folio','$TotalPagar','$FechaLimite', 'Pendiente')";
    $query2 = mysqli_query($conn, $Insersion);

    if ($query) {
        echo '
        <script>
        alert("Registro realizado con exito");
        </script>
        ';
        header('MenuConfiguraciones.php');
    }  else {
        echo '
        <script>
        alert("El registro no se pudo realizar, complete todos los campos");
        </script>
        ';
        header('MenuConfiguraciones.php');
    }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/Corte.css">
    <title>Ventas del Día</title>
    <style>
        section h1{
            margin-top: 20px;
            margin-bottom: 20px;
            padding-left: 20px;
        }
        h2{
            padding-left: 80%;
            margin-top: 20px;
        }
        table {
            width: 95%;
            border-collapse: collapse;
            margin-left: 2.5%;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        section{
            margin-top: 15px;
        }

        section>div>form{
            margin-left: 20px;
        }

        section>div>form>p{
            font-size: 20px;
        }

    section>div>form input{
        margin-top: 10px;
        margin-bottom: 10px;
        height: 25px;
        width: 20%;
        margin-left: 1%;
        padding-left: 5px;
        font-size: 20px;
    }

    button{
        width: 10%;
        margin-left: 10%;
        margin-top: 10px;
        border-radius: 20px;
        cursor: pointer;
        border: 2px solid;
        background-color: #d9d9d9;
    }

    button:hover{
        background-color: white;
    }

    button>img{
        width: 20%;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    button>p{
        padding-bottom: 15px;
        font-size: 25px;
    }
    </style>
</head>
<body>
    <header>
        <div class="el">
        <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <?php print_r($_SESSION['nombre']); ?> </h1>
        </div>
        <div class="">
            <ul>
                <li><a href="MenuConfi.php">Volver</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>

    <section>
        <div>
            <form action="Facturas.php" method="post">
                <p>Folio: <input type="text" name="Folio"></p>
                <p>Total a Pagar: <input type="number" step="0.001" name="Total"></p>
                <p>Fecha Limite: <input type="date" name="Fecha" id=""></p>
                <button type="submit" name="Agregar"><img src="img/mas.png" alt=""><br><p>Agregar</p></button>
            </form>
        </div>

        <div>
        <h1>Ventas del Día</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Total a Pagar</th>
                    <th>Fecha Limite</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?><tr>
                        <td><?php print $row['Folio']; ?></td>
                        <td>$<?php print $row['Total_Pagar']; ?></td>
                        <td><?php print $row['Fecha_Limite']; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontraron Facturas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </section>

</body>
</html>

