<?php
namespace App\Crud;

class Plantilla
{

   /**
    * @param $campos
    * @param $filas
    * @return void este método genera una tabla html a partir de un array de filas y un array con los nombres de los campos
    */
   public static function generar_tabla($campos, $filas) {
      $tabla = $_GET['tabla'];
      echo '<table  >';
      echo '<thead><tr>';
      // Imprimir los encabezados de la tabla
      foreach ($campos as $campo) {
         echo '<th>' . htmlspecialchars($campo) . '</th>';
      }
      echo "<th>Eliminar Fila</th>";
      echo '</tr></thead>';

      echo '<tbody>';
      // Imprimir las filas de la tabla
      foreach ($filas as $fila) {
         echo '<tr>';
         foreach ($campos as $campo) {
            // Verificar si el valor es NULL y reemplazarlo con una cadena vacía o algún texto
            $valor = isset($fila[$campo]) ? $fila[$campo] : null;
            echo '<td>' . htmlspecialchars($valor !== null ? $valor : 'NULL') . '</td>';
         }
         // Botón eliminar
         echo '<td>';
         echo '<form action="listado.php?tabla=' . urlencode($tabla) . '" method="POST">';
         echo '<input type="hidden" name="cod" value="' . htmlspecialchars($fila['cod']) . '">'; // Suponiendo que 'cod' es el identificador único
         echo '<input type="submit" name="eliminar" value="Eliminar">';
         echo '</form>';
         echo '</td>';
         echo '</tr>';
      }
      echo '</tbody>';
      echo '</table>';
   }
   public static function add_field(string $tabla, array $campos): string
   {
      $html = "<h1>Agregar una nueva fila a la tabla: " . htmlspecialchars($tabla) . "</h1>";
      $html .= "<form action='listado.php?tabla=" . urlencode($tabla) . "' method='POST'>";

      foreach ($campos as $campo) {
         if ($campo !== "cod") {
            $html .= "<label for='$campo'>" . htmlspecialchars($campo) . ":</label><br>";
            $html .= "<input type='text' id='$campo' name='$campo' required><br><br>";
         }
      }
      $html .= "<input type='submit' name='agregar' value='Agregar Fila'>";
      $html .= "</form>";

      return $html;
   }
   public static function registrar_usuario_listado(string $tabla): string {
      $html = '<h2>Añadir Usuario</h2>';
      $html .= '<form action="listado.php?tabla=' . urlencode($tabla) . '" method="POST">';
      $html .= '<label for="nombre">Nombre:</label><br>';
      $html .= '<input type="text" id="nombre" name="nombre" required><br><br>';

      $html .= '<label for="password">Contraseña:</label><br>';
      $html .= '<input type="password" id="password" name="password" required><br><br>';

      $html .= '<input type="submit" name="agregar" value="Registrar Usuario">';
      $html .= '</form>';
      return $html;
   }
}
?>
