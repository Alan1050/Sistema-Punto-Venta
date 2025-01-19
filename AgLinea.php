<?php
include 'includes/conn.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['Ingresar'])) {
    $Numeracion = $_POST['Numeracion'];
    $Descripcion = $_POST['Descripcion'];

    $Insersion = "INSERT INTO Linea (Linea, Descripcion) VALUES ('$Numeracion','$Descripcion')";
    $query = mysqli_query($conn, $Insersion);

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
                    <p>Numeracion</p>
                    <input type="text" name="Numeracion"><br>
                    <p>Descripcion</p>
                    <input type="text" name="Descripcion">
                </div>
                <div>
                    <button type="submit" name="Ingresar"><img src="img/mas.png" alt=""><br>Ingresar</button>
                </div>
            </form>

        </div>
        <div>
            <table>
                <tr>
                    <th>Numeracion</th>
                    <th>Descripcion</th>
                </tr>

                <?php
                $Dat = "SELECT * FROM Linea";
                $Datos = mysqli_query($conn,$Dat);
                while ($row = mysqli_fetch_assoc($Datos)){ ?>
                <tr>
                    <td><?php echo $row['Linea']; ?></td>
                    <td><?php echo $row['Descripcion']; ?></td>
                </tr>
                <?php }  ?>
            </table>

        </div>
    </section>

</body>
</html>
