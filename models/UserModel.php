<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Clase UserModel para gestionar usuarios
class UserModel {
    private $pdo;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct() {
        require_once __DIR__ . '/../config/db.php'; // Incluye la configuración de la base de datos
        $this->pdo = $pdo; // Usa la conexión $pdo definida en db.php
    }

    // Método que verifica las credenciales en la base de datos
    public function verificarCredenciales($username, $password) {
        try {
            // Primero, obtenemos el usuario según el nombre de usuario
            $query = "SELECT NomUsuario AS username, Clave AS password, Estilo 
                    FROM Usuarios 
                    WHERE NomUsuario = :username";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':username' => $username]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Debug: verificar si se encontró el usuario
            if ($usuario) {
                echo "Usuario encontrado: " . $usuario['username'] . "<br>";
                echo "Contraseña en la BD: " . $usuario['password'] . "<br>";
            } else {
                echo "Usuario no encontrado.<br>";
                return false;
            }

            // Verificar la contraseña ingresada en texto plano
            if ($password === $usuario['password']) {
                echo "Contraseña verificada correctamente.<br>";

                // Obtener el estilo si las credenciales son válidas
                $query_estilo = "SELECT Fichero FROM Estilos WHERE IdEstilo = :idEstilo";
                $stmt_estilo = $this->pdo->prepare($query_estilo);
                $stmt_estilo->execute([':idEstilo' => $usuario['Estilo']]);
                $estilo = $stmt_estilo->fetch(PDO::FETCH_ASSOC);

                if ($estilo) {
                    $usuario['estilo'] = '/daw/practica_dawpr7/css/' . $estilo['Fichero'];
                } else {
                    $usuario['estilo'] = '/daw/practica_dawpr7/css/style.css'; // Estilo por defecto
                }

                return $usuario; // Devolver el usuario si las credenciales son válidas
            } else {
                echo "Contraseña incorrecta.<br>";
                return false;
            }
        } catch (PDOException $e) {
            die("Error al verificar credenciales: " . $e->getMessage());
        }
    }
}
