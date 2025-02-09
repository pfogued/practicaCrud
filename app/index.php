<?php
require "./vendor/autoload.php";

use App\Crud\DB;
session_start();

$opcion = $_POST["submit"] ?? "";
$db = new DB();
switch ($opcion) {
    case 'Login':
        //leer valores del formulario
        $nombre = $_POST["nombre"];
        $password = $_POST["password"];
        $resultado = $db->validar_usuario($nombre, $password);
        if ($resultado === true) {
            $_SESSION["nombre"] = $nombre;
            header("Location: sitio.php");
            exit();
        } else {
            $msj = $resultado;
        }
        break;
    case 'Registrar':
        $nombre = $_POST["nombre"];
        $password = $_POST["password"];
        $resultado = $db->registrar_usuario($nombre, $password);
        if ($resultado === true)
            $msj="Se ha insertado al usuario $nombre";
        else
            $msj=$resultado;
        break;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
<div class="w-full max-w-sm bg-white rounded-lg shadow-md p-6">
    <h1 class="text-2xl font-semibold text-gray-700 text-center mb-4">Formulario</h1>
    <h2><?=$msj ?? ""?></h2>
    <form action="index.php" method="POST">
        <!-- Campo Nombre -->
        <div class="mb-4">
            <label for="nombre" class="block text-sm font-medium text-gray-600">Nombre</label>
            <input type="text" id="nombre" name="nombre"
                   class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Introduce tu nombre">
        </div>

        <!-- Campo Contraseña -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-600">Contraseña</label>
            <input type="password" id="password" name="password"
                   class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Introduce tu contraseña">
        </div>

        <!-- Botón Enviar -->
        <div class="flex flex-row justify-around">
            <input type="submit"
                   class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                   value= "Login" name="submit" />
            <input type="submit"
                   class="px-4 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                   value= "Registrar" name="submit" />
            </input>

        </div>
    </form>
</div>
</body>
</html>
