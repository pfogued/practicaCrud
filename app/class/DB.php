<?php
namespace App\Crud;
require_once 'vendor/autoload.php';

use mysqli;
use mysqli_sql_exception;
use mysqli_stmt;

class DB {

   private $con;

   public function __construct() {
      putenv("HOST=mysql");
      putenv("DB_USER=alumno");
      putenv("PASSWORD=alumno");
      putenv("DATABASE=tienda");
      $host = getenv('HOST');
      $user = getenv('DB_USER');
      $pass = getenv('PASSWORD');
      $db   = getenv('DATABASE');
      try {
         $this->con=new mysqli($host, $user, $pass, $db);
      } catch (mysqli_sql_exception $e) {
         die ("Error accediendo a la base de datos " . $e->getMessage());
      }
   }

   /**
    * @param string $nombre
    * @param string $pass
    * @return bool
    * //Verifica si un usuario existe en la base de datos
    */
   public function validar_usuario($nombre, $password): bool|string {
      $sentencia = "SELECT password FROM usuarios WHERE nombre = '$nombre'";
      $resultado = $this->con->query($sentencia);
      if (empty($nombre) || empty($password)) {
         return "Debes introducir todos los datos";
      }
      if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        $hashed_password = $fila['password'];
        // Comparar la contraseña ingresada con la contraseña hasheada
        if (password_verify($password, $hashed_password)) {
            return true; // Contraseña correcta
        } else {
            return "La contraseña no coincide.<br>";
        }
    }
    return false;
   }

   /*
    * Este método tendría que investigar en el diccionario de datos
    * Devolverá qué campos de esta tabla son claves foráneas
    * */
   public function get_foraneas(string $tabla): array {
      $foraneas = [];
      if (!$this->con) {
         return [];
      }

      $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL";
      $stmt = $this->con->prepare($query);

      if ($stmt) {
         $stmt->bind_param("s", $tabla);
         if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
               $foraneas[] = $row['COLUMN_NAME'];
            }
         }
      }

      return $foraneas;
   }


   public function get_campos(string $table): array {
      $campos = [];
      if (!$this->con) {
         return [];
      }

      $query = "SHOW COLUMNS FROM $table";
      $result = $this->con->query($query);

      if ($result) {
         while ($row = $result->fetch_assoc()) {
            $campos[] = $row['Field'];
         }
      }

      return $campos;
   }

   // Retorna un array con las filas de una tabla
   public function get_filas(string $sentencia): array {
      $filas = [];
      if (!$this->con) {
         return [];
      }

      $result = $this->con->query($sentencia);
      if ($result) {
         while ($fila = $result->fetch_assoc()) {
            $filas[] = $fila;
         }
      }

      return $filas;
   }

   //Borra una fila de una tabla dada su código
   //Retorna un mensaje diciendo si lo ha podido borrar o no
   public function borrar_fila(string $table, int $cod): string
   {
      if (!$this->con) {
         return "Error en la conexión";
      }

      $query = "DELETE FROM $table WHERE cod = ?";
      $stmt = $this->con->prepare($query);

      if (!$stmt) {
         return "Error al preparar la consulta";
      }

      $stmt->bind_param("i", $cod);

      if ($stmt->execute()) {
         if ($stmt->affected_rows > 0) {
            return "Fila eliminada correctamente";
         } else {
            return "No se encontró la fila con el código especificado";
         }
      } else {
         return "Error al ejecutar la consulta";
      }
   }

   public function close() {
      $this->con->close();
   }

   // Añade una fila cuyos valores se pasan en un array.
   //Tengo el nombre de la tabla y el array ["nombre_Campo"=>"valor"]
   public function add_fila(string $tabla, array $campos) {
      if (!$this->con) {
         return false;
      }

      $columnas = implode(", ", array_keys($campos));
      $placeholders = implode(", ", array_fill(0, count($campos), "?"));

      $query = "INSERT INTO $tabla ($columnas) VALUES ($placeholders)";
      $stmt = $this->con->prepare($query);

      if (!$stmt) {
         return false;
      }

      $tipos = str_repeat("s", count($campos));
      $stmt->bind_param($tipos, ...array_values($campos));

      return $stmt->execute();
   }

   //Registra un usuario en la tabla usuarios y me pasan el nombre y el pass
   //El pass tiene que estar cifrado antes de insertar
   //Retorna un bool = true si ha ido bien o un mensaje si ha ocurrdio algún problema, como que el usuario ya existiese
   public function registrar_usuario($nombre, $password): bool|string {
      if (empty($nombre) || empty($password)) {
         return "No puedes registrar un usuario vacío";
      }
      if ($this->existe_usuario($nombre)) {
         return "El nombre de usuario ya está registrado."; // El nombre ya existe
      }
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $sentencia = $this->con->prepare("INSERT INTO usuarios (nombre, password) VALUES (?, ?)");
      if (!$sentencia) {
         return "Error en la preparación de la consulta: " . $this->con->error;
      }
      $sentencia->bind_param("ss", $nombre, $hashed_password);
      if ($sentencia->execute()) {
         return true;
      } else {
         return "Error al registrar usuario: " . $sentencia->error;
      }
   }

   //Verifica si un usuario existe o no
   private function existe_usuario(string $nombre):bool {
      $sentencia = $this->con->prepare("SELECT cod FROM usuarios WHERE nombre = ?");
      $sentencia->bind_param("s", $nombre);
      $sentencia->execute();
      $sentencia->store_result();
      if ($sentencia->num_rows > 0) {
         return true;
      }
      return false;
   }

   //Ejecuta una sentencia y retorna un mysql_stmt
   //La sentencia hay que paraemtrizarla
   //Recibo la sentencia con parámetros y un array indexado con los valores
   private function ejecuta_sentencia(string $sql, array $datos): mysqli_stmt {
      $stmt = $this->con->prepare($sql);

      if ($stmt && !empty($datos)) {
         $tipos = str_repeat("s", count($datos));
         $stmt->bind_param($tipos, ...$datos);
      }

      return $stmt;
   }
 }

?>
