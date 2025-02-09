<?php
session_start();

$nombre = $_SESSION['nombre'];
if ($nombre =="") {
    header("location:index.php");
    exit;
}
if (isset($_POST["submit"]) && $_POST["submit"] === "Logout") {
    $_SESSION["nombre"] = "";
    header("location:index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <!-- Navigation Buttons -->
    <div>
        <form action="sitio.php" method="post">
            <div >
               <h2>Conectado como  <?=$nombre?>  <input class="btn btn-logout" type="submit" value="Logout" name="submit"></h2>
            </div>
            <hr/>
        </form>
    </div>

    <!-- Placeholder for Future Content -->
    <div id="content">
        <p>Selecciona una opción para gestionar los elementos de la tienda.</p>

        <!-- Botones que ahora redirigen a listado.php con el parámetro correspondiente -->
        <form action="listado.php" method="get">
            <input class="btn btn-create" type="submit" value="producto" name="tabla">
            <input class="btn btn-edit" type="submit" value="tienda" name="tabla">
            <input class="btn btn-delete" type="submit" value="usuarios" name="tabla">
            <input class="btn btn-create" type="submit" value="stock" name="tabla">
        </form>
    </div>
</body>
</html>
