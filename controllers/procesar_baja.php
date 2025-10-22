<?php
session_start();
if (!defined('FROM_INDEX')) { 
    header("HTTP/1.0 403 Forbidden"); 
    exit("Acceso directo no permitido."); 
}
if (!isset($_SESSION['username'])) { 
    header("Location: /daw/practica_dawpr7/inicio-sesion"); 
    exit(); 
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

$username = $_SESSION['username'];
$current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';

try {
    // Obtener las fotos del usuario
    $query_fotos = "
        SELECT f.Fichero
        FROM Fotos f
        JOIN Albumes a ON f.Album = a.IdAlbum
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
    ";
    $stmt_fotos = $pdo->prepare($query_fotos);
    $stmt_fotos->execute([':username' => $username]);
    $fotos = $stmt_fotos->fetchAll(PDO::FETCH_ASSOC);

    // Eliminar físicamente las fotos del servidor
    $upload_dir = __DIR__ . '/../image/';
    foreach ($fotos as $foto) {
        $archivo = $upload_dir . $foto['Fichero'];
        if (file_exists($archivo)) {
            unlink($archivo); // Eliminar el archivo
        }
    }

    // Eliminar registros de Fotos
    $query_delete_fotos = "
        DELETE f
        FROM Fotos f
        JOIN Albumes a ON f.Album = a.IdAlbum
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
    ";
    $stmt = $pdo->prepare($query_delete_fotos);
    $stmt->execute([':username' => $username]);

    // Eliminar los álbumes del usuario
    $query_delete_albumes = "
        DELETE a
        FROM Albumes a
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
    ";
    $stmt = $pdo->prepare($query_delete_albumes);
    $stmt->execute([':username' => $username]);

    // Eliminar el usuario
    $query_delete_usuario = "DELETE FROM Usuarios WHERE NomUsuario = :username";
    $stmt = $pdo->prepare($query_delete_usuario);
    $stmt->execute([':username' => $username]);

    // Destruir la sesión y redirigir al inicio
    session_destroy();
    header("Location: /daw/practica_dawpr7");
    exit();
} catch (PDOException $e) {
    die("Error al eliminar los datos del usuario: " . $e->getMessage());
}

