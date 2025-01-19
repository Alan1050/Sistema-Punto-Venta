<?php

include 'includes/conn.php';

session_start();

$Datos2 = "SELECT * FROM proveedores";
$Eje = mysqli_query($conn, $Datos2);

    if (isset($_POST['ingresar'])) {
        $Nombre = $_POST['nombre'];
        $NumTel = $_POST['NumTel'];
        $Ubi = $_POST['Ubi'];

        $Ingreso = "INSERT INTO proveedores (Nombre, NumeroTelefono, Ubicacion) VALUES ('$Nombre','$NumTel','$Ubi')";
        $query = mysqli_query($conn, $Ingreso);
        if ($query) {
            echo "
            <script>
            alert('Registro Guardado con exito');
            window.location.href ='Proveedor.php'
            </script>
            ";
        } else{
            echo "
            <script>
            alert('El registro no se pudo realizar, vuelva a intentarlo');
            window.location.href ='Proveedor.php'
            </script>
            ";
        }
    }

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/Proveedor.css">
    <title></title>
</head>
<body>
    <header>
        <div class="el">
        <img src="img/Logo.JPG" alt="">
            <h1>Bienvenid@, <?php print_r($_SESSION['nombre']) ?> </h1>
        </div>
        <div class="">
            <ul>
                <li><a href="Dashboard.php">Volver</a></li>
                <li><a href="index.php">Cerrar Sesion</a></li>
            </ul>
        </div>
    </header>


    
    <form action="Proveedor.php" method="POST">
        <label>Nombre:</label> <input type="text" name="nombre" style="text-transform:uppercase"><br>
        <label>Numero de Telefono:</label> <input type="text" style="text-transform:uppercase" name="NumTel"><br>
        <label>Ubicacion:</label> <input type="text" style="text-transform:uppercase" name="Ubi"><br>

        <button type="submit" name="ingresar"><img src="img/mas.png" alt=""><br><p>Agregar</p></button>
    </form>
    
    
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Numero de Telefono</th>
            <th>Ubicacion</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row2 = mysqli_fetch_assoc($Eje)){ ?>
        <tr>
            <td><?php echo $row2['Nombre']; ?></td>
            <td><?php echo $row2['NumeroTelefono']; ?></td>
            <td><?php echo $row2['Ubicacion']; ?></td>
            <td>
            <button onclick="window.location.href='ModiProv.php?id=<?php echo $row2['id_proveedor']; ?>'" class="btnG">Modificar</button><br>
            </td>
        </tr>
        <?php }  ?>
        
    </table>

</body>
</html>

<style>
    *{
    padding: 0px;
    margin: 0px;
}

body{
    background-color: #d9d9d9;
}

form{
    width: 50%;
    padding-left: 20px;
    margin-top: 40px;
}

form>label{
    font-size: 25px;
    padding-right: 10px;
    line-height: 30px;
}

form>input[type="text"]{
    width: 60%;
}

form>input{
    padding-left: 3px;
    height: 20px;
}

table {
    margin-top: 20px; 
    width: 98%;
    border-collapse: collapse;
    margin-left: 1%;
}

th {
    width: 25%; /* 100% dividido entre 7 columnas */
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


button{
    width: 15%;
    margin-left: 100%;
    margin-top: -130px;
    border-radius: 20px;
    cursor: pointer;
    border: 2px solid;
    background-color: #d9d9d9;
}

button:hover{
    background-color: white;
}

button:hover> img{
    transform: scale(1.2);
}

button>img{
    width: 50%;
    padding-top: 10px;
    padding-bottom: 10px;
}

button>p{
    padding-bottom: 10px;
    font-size: 20px;
}

.btnG{
    width: 30%;
    margin-left: 0 !important;
    margin-top: -130px;
    border-radius: 20px;
    cursor: pointer;
    border: 2px solid;
    background-color: #d9d9d9;
    height: 20px;
}

</style>