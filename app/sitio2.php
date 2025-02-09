<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF - 8">
    <meta name="viewport" content="width=device - width, initial - scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<h1>Admin Panel</h1>

<!-- Navigation Buttons -->
<div>
    <form action="sitio.php" method="post">
        <div >
            Conectado como XXXX  <input class="btn btn - logout" type="submit" value="Logout" name="submit">
        </div>
        <hr/>
        <input class="btn btn - create" type="submit" value="Productos" name="submit">
        <input class="btn btn - edit" type="submit" value="Tiendas" name="submit">
        <input class="btn btn - delete" type="submit" value="Usuarios" name="submit">
        <input class="btn btn - create" type="submit" value="Stock" name="submit">

    </form>

</div>

<!-- Placeholder for Future Content -->
<div id="content">
    <p>Selecciona una opción para gestionar los elementos de la tienda.</p>
</div>
</body>
</html>
