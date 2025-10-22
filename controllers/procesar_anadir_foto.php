<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

session_start();
require_once __DIR__ . '/../config/db.php';

$errores = [];

// Validar entrada del usuario
$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$pais = isset($_POST['pais']) ? (int)$_POST['pais'] : null;
$alternativo = isset($_POST['texto_alternativo']) ? trim($_POST['texto_alternativo']) : '';
$album = isset($_POST['album']) ? (int)$_POST['album'] : null;

if (empty($titulo)) {
    $errores[] = "El título de la foto es obligatorio.";
}
if (empty($alternativo) || strlen($alternativo) < 10) {
    $errores[] = "El texto alternativo debe tener al menos 10 caracteres.";
}
if (preg_match('/^(foto|imagen)/i', $alternativo)) {
    $errores[] = "El texto alternativo no puede empezar con 'foto' o 'imagen'.";
}
if (!$album) {
    $errores[] = "Debes seleccionar un álbum.";
}

if (!$pais) {
    $errores[] = "Debes seleccionar un país.";
}

if (!isset($_FILES['fichero']) || $_FILES['fichero']['error'] !== UPLOAD_ERR_OK) {
    $errores[] = "Error al subir la imagen.";
} else {
    $archivo_tmp = $_FILES['fichero']['tmp_name'];
    $extension = pathinfo($_FILES['fichero']['name'], PATHINFO_EXTENSION); // Obtener la extensión
    $nombre_archivo = uniqid('foto_', true) . '.' . $extension; // Nombre único con extensión
    
    $tipo_archivo = mime_content_type($archivo_tmp);
    if (!in_array($tipo_archivo, ['image/jpeg', 'image/png', 'image/gif'])) {
        $errores[] = "El archivo debe ser una imagen válida (JPEG, PNG o GIF).";
    } else {
        $destino = __DIR__ . '/../image/' . $nombre_archivo;
        if (!move_uploaded_file($archivo_tmp, $destino)) {
            $errores[] = "Error al guardar la imagen.";
        }
    }
}


if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    header("Location: /daw/practica_dawpr7/anadir-foto");
    exit();
}

// Insertar en la base de datos
try {
    $query = "
        INSERT INTO fotos (Titulo, Descripcion, Fecha, Pais, Album, Fichero, Alternativo)
        VALUES (:titulo, :descripcion, :fecha, :pais, :album, :fichero, :alternativo)
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':titulo' => $titulo,
        ':descripcion' => $descripcion,
        ':fecha' => $fecha,
        ':pais' => $pais,
        ':album' => $album,
        ':fichero' => $nombre_archivo,
        ':alternativo' => $alternativo,
    ]);

    header("Location: /daw/practica_dawpr7/ver-album-privada?id=" . $album);
    exit();
} catch (PDOException $e) {
    die("Error al añadir la foto: " . $e->getMessage());
}
?>
