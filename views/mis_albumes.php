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

// Consultar los álbumes del usuario
try {
    $query_albumes = "
        SELECT a.IdAlbum, a.Titulo, a.Descripcion
        FROM Albumes a
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
    ";
    $stmt_albumes = $pdo->prepare($query_albumes);
    $stmt_albumes->execute([':username' => $username]);
    $albumes = $stmt_albumes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los álbumes: " . $e->getMessage());
}

$title = 'Mis Álbumes';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>Mis Álbumes</h2>
        <?php if (!empty($albumes)): ?>
            <ul>
                <?php foreach ($albumes as $album): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($album['Titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($album['Descripcion']); ?></p>
                        <a href="/daw/practica_dawpr7/ver-album-privada?id=<?php echo htmlspecialchars($album['IdAlbum']); ?>">Ver álbum</a>
                        </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No tienes álbumes creados.</p>
        <?php endif; ?>
    </section>
</main>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
