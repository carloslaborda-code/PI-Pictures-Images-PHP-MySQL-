<?php
session_start(); // Inicia la sesión

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: /daw/practica_dawpr7/inicio-sesion");
    exit();
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Obtener el nombre de usuario desde la sesión
$username = $_SESSION['username'];

// Consultar las fotos del usuario
try {
    $query_fotos = "
        SELECT f.IdFoto, f.Titulo AS FotoTitulo, f.Fichero, f.Fecha, a.Titulo AS AlbumTitulo, a.IdAlbum
        FROM Fotos f
        JOIN Albumes a ON f.Album = a.IdAlbum
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
        ORDER BY f.Fecha DESC
    ";
    $stmt_fotos = $pdo->prepare($query_fotos);
    $stmt_fotos->execute([':username' => $username]);
    $fotos = $stmt_fotos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar las fotos: " . $e->getMessage());
}

$title = 'Mis Fotos';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>Mis Fotos</h2>
        <?php if (!empty($fotos)): ?>
            <div class="fotos-lista">
                <?php foreach ($fotos as $foto): ?>
                    <div class="foto-item">
                        <!-- Enlace al detalle de la foto -->
                        <a href="/daw/practica_dawpr7/detalle-foto?id=<?php echo htmlspecialchars($foto['IdFoto']); ?>">
                            <img src="/daw/practica_dawpr7/image/<?php echo htmlspecialchars($foto['Fichero']); ?>" 
                                 alt="<?php echo htmlspecialchars($foto['FotoTitulo']); ?>">
                        </a>
                        <p><strong>Título:</strong> 
                            <a href="/daw/practica_dawpr7/detalle-foto?id=<?php echo htmlspecialchars($foto['IdFoto']); ?>">
                                <?php echo htmlspecialchars($foto['FotoTitulo']); ?>
                            </a>
                        </p>
                        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($foto['Fecha']); ?></p>
                        <p><strong>Álbum:</strong> 
                            <a href="/daw/practica_dawpr7/ver-album-privada?id=<?php echo htmlspecialchars($foto['IdAlbum']); ?>">
                                <?php echo htmlspecialchars($foto['AlbumTitulo']); ?>
                            </a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No tienes fotos subidas.</p>
        <?php endif; ?>
    </section>
</main>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
