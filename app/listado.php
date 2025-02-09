<?php
session_start();
require "./vendor/autoload.php";

use App\Crud\DB;
use App\Crud\Plantilla;
$opcion = $_POST["submit"] ?? "";
$nombre = $_SESSION['nombre'];
if ($nombre =="") {
    header("location:index.php");
    exit;
}
switch ($opcion) {
    case 'Logout':
        $_SESSION["nombre"] = "";
        header("location:index.php");
        exit;
    case 'Volver':
        header("location:sitio.php");
        break;
}

if (!isset($_GET['tabla'])) {
    die("No se ha seleccionado ninguna tabla.");
}

$tabla = $_GET['tabla'];  // El nombre de la tabla que se pasa desde 'sitio.php'
$db = new DB();

// Obtener los campos de la tabla
$campos = $db->get_campos($tabla);

// Obtener datos de la tabla
$sentencia = "SELECT * FROM `$tabla`";
$filas = $db->get_filas($sentencia);

if (isset($_POST['eliminar'])) {
    $cod = $_POST['cod'];
    if ($cod) {
        $msj = $db->borrar_fila($tabla, $cod);
        // Redirigir para refrescar la página y mostrar la tabla actualizada
        header("Location: listado.php?tabla=" . urlencode($tabla));
        exit;
    }
}
if (isset($_POST['agregar'])) {
    if ($tabla == "usuarios") {
        $nombre_usuario = $_POST['nombre'];
        $password_usuario = $_POST['password'];

        $msj = $db->registrar_usuario($nombre_usuario, $password_usuario);
        // Redirigir para refrescar la página y mostrar la tabla actualizada
        header("Location: listado.php?tabla=" . urlencode($tabla));
    } else {
        $camposFormulario = [];
        foreach ($campos as $campo) {
            if ($campo !== "cod") {
                $camposFormulario[$campo] = $_POST[$campo];
            }
        }
        $msj = $db->add_fila($tabla, $camposFormulario);
        // Redirigir para refrescar la página y mostrar la tabla actualizada
        header("Location: listado.php?tabla=" . urlencode($tabla));
    }
}
$mostrarFormularioAgregar = isset($_POST['tabla']) && $_POST['tabla'] === "Añadir Fila";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de <?php echo htmlspecialchars($tabla); ?></title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<!-- Navigation Buttons -->
<div>
    <form action="listado.php" method="post">
        <div >
            <h2>Conectado como  <?=$nombre?>
                <input class="btn btn-logout" type="submit" value="Logout" name="submit">
                <input class="btn btn-logout" type="submit" value="Volver" name="submit">
            </h2>
        </div>
        <hr/>
    </form>
</div>
<h1>Listado del contenido de la tabla: <?php echo htmlspecialchars($tabla); ?></h1>
<?=$msj ?? ""?>
<form action="listado.php" method="POST">
    <input class="btn btn-create" type="submit" value="Añadir Fila" name="tabla" formaction="listado.php?tabla=<?php echo urlencode($tabla); ?>">
</form>
<!-- Formulario para Añadir Fila -->
<?php if ($mostrarFormularioAgregar): ?>
    <?php if ($tabla == "usuarios"): ?>
        <?=Plantilla::registrar_usuario_listado($tabla)?>
    <?php else: ?>
        <?=Plantilla::add_field($tabla, $campos)?>
    <?php endif; ?>
<?php endif; ?>
<!-- Aquí se genera la tabla utilizando la clase Plantilla -->
<?php Plantilla::generar_tabla($campos, $filas); ?>

</body>
</html>