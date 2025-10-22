<?php
session_start(); // Iniciar la sesión si no está iniciada

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['flashdata'] = 'Debes estar registrado o iniciar sesión para ver los detalles de una foto.';
    header("Location: /daw/practica_dawpr7/inicio-sesion");
    exit();
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Captura y valida el ID de la foto
$foto_id = isset($_GET['id']) && ctype_digit($_GET['id']) ? intval($_GET['id']) : 0;

if ($foto_id === 0) {
    die("ID de la foto no válido.");
}

try {
    // Consulta para obtener los detalles de la foto
    $query = "
        SELECT f.Titulo, f.Descripcion, f.Fichero, f.FRegistro AS Fecha, 
            p.NomPais AS Pais, a.IdAlbum AS AlbumId, a.Titulo AS Album, u.NomUsuario AS Usuario
        FROM Fotos f
        LEFT JOIN Paises p ON f.Pais = p.IdPais
        LEFT JOIN Albumes a ON f.Album = a.IdAlbum
        LEFT JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE f.IdFoto = :foto_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':foto_id' => $foto_id]);
    $foto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$foto) {
        // Si no se encuentra la foto, muestra un error 404
        header("HTTP/1.0 404 Not Found");
        echo "Foto no encontrada.";
        exit();
    }
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

$title = 'Detalle de Foto';
include __DIR__ . '/../templates/header.php';
?>

<!-- Sección de detalles de la foto -->
<main>
    <h2 class="titulo-detalle-centro"><?php echo htmlspecialchars($foto['Titulo']); ?></h2>
    <div class="detalle-foto-contenedor">
        <img src="/daw/practica_dawpr7/image/<?php echo htmlspecialchars($foto['Fichero']); ?>" 
             alt="<?php echo htmlspecialchars($foto['Descripcion']); ?>" 
             width="100" class="importante foto-detalle">
        <div class="photo-details">
            <p><strong>Título:</strong> <?php echo htmlspecialchars($foto['Titulo']); ?></p>
            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($foto['Descripcion']); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($foto['Fecha']); ?></p>
            <p><strong>País:</strong> <?php echo htmlspecialchars($foto['Pais'] ?? 'Desconocido'); ?></p>
            <p><strong>Álbum:</strong> <a href="/daw/practica_dawpr7/ver-album?id=<?php echo htmlspecialchars($foto['AlbumId']); ?>"><?php echo htmlspecialchars($foto['Album']); ?></a></p>
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($foto['Usuario']); ?></p>
        </div>
    </div>
</main>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
