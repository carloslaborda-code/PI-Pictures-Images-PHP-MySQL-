<?php
// procesar_crear_album.php

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Inicia la sesión si no se ha iniciado ya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Redirige al usuario a la página de inicio de sesión con un mensaje de error
    header("Location: /daw/practica_dawpr7/inicio-sesion?mensaje=debe_iniciar_sesion");
    exit();
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Obtiene los datos enviados por el formulario
$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

// Verificar si los campos obligatorios están completos
if (empty($titulo) || empty($descripcion)) {
    die("Por favor, completa todos los campos.");
}

// Obtener el ID del usuario actual para asociarlo al álbum
try {
    $username = $_SESSION['username'];

    // Obtener el ID del usuario basado en el nombre de usuario
    $query_usuario = "
        SELECT IdUsuario
        FROM Usuarios
        WHERE NomUsuario = :username
    ";
    $stmt_usuario = $pdo->prepare($query_usuario);
    $stmt_usuario->execute([':username' => $username]);
    $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        throw new Exception("Usuario no encontrado.");
    }

    $id_usuario = $usuario['IdUsuario'];

    // Insertar el nuevo álbum en la base de datos
    $query_insert_album = "
        INSERT INTO Albumes (Titulo, Descripcion, Usuario)
        VALUES (:titulo, :descripcion, :id_usuario)
    ";
    $stmt_insert_album = $pdo->prepare($query_insert_album);
    $stmt_insert_album->execute([
        ':titulo' => $titulo,
        ':descripcion' => $descripcion,
        ':id_usuario' => $id_usuario
    ]);

    // Guardar los datos en la sesión para mostrarlos en la confirmación
    $_SESSION['album_creado'] = [
        'titulo' => $titulo,
        'descripcion' => $descripcion
    ];

    // Redirigir al usuario a la página de confirmación de creación del álbum
    header("Location: /daw/practica_dawpr7/respuesta-creacion-album");
    exit();
} catch (PDOException $e) {
    die("Error al crear el álbum: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
