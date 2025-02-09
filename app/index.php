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
    <style>
        /* Estilos Generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Títulos */
        h1 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #374151;
            margin-bottom: 1rem;
        }

        h2 {
            color: red;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        /* Inputs */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 0.5rem 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 5px rgba(37, 99, 235, 0.3);
        }

        /* Botones */
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        input[type="submit"] {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
            margin: 0 5px;
        }

        input[name="submit"][value="Login"] {
            background-color: #2563eb;
        }

        input[name="submit"][value="Registrar"] {
            background-color: #28a745;
        }

        input[type="submit"]:hover {
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .button-container {
                flex-direction: column;
            }

            input[type="submit"] {
                margin: 5px 0;
            }
        }
    </style>
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
