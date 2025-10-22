<?php
session_start(); // Iniciar la sesión

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: /daw/practica_dawpr7/inicio-sesion");
    exit();
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Obtener el nombre de usuario de la sesión
$username = $_SESSION['username'];

// Consulta para obtener los datos del usuario
try {
    $query = "
        SELECT u.NomUsuario, u.Foto, u.FRegistro 
        FROM Usuarios u
        WHERE u.NomUsuario = :username
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $username]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }

    // Consulta para obtener los álbumes del usuario
    $query_albumes = "
        SELECT a.IdAlbum, a.Titulo
        FROM Albumes a
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
    ";
    $stmt_albumes = $pdo->prepare($query_albumes);
    $stmt_albumes->execute([':username' => $username]);
    $albumes = $stmt_albumes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los datos del usuario: " . $e->getMessage());
}

$title = 'Perfil Usuario';
include __DIR__ . '/../templates/header.php';
?>

<main>
<section>
        <h2>Perfil de Usuario</h2>
        <div class="perfil-usuario">
            <?php
                // Mostrar la imagen de perfil del usuario
                if (!empty($usuario['Foto'])) {
                    echo '<img src="/daw/practica_dawpr7/image/profiles/' . htmlspecialchars($usuario['Foto']) . '" alt="Foto de perfil" width="150">';
                } else {
                    echo '<img src="/daw/practica_dawpr7/image/default-profile.JPG" alt="Foto de perfil por defecto" width="150">';
                }                
            ?>
            <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($usuario['NomUsuario']); ?></p>
            <p><strong>Fecha de Incorporación:</strong> <?php echo htmlspecialchars($usuario['FRegistro']); ?></p>
        </div>
    </section>

    <section>
        <h2>Gestión de Cuenta</h2>
        <ul>
            <li><a href="/daw/practica_dawpr7/mis-albumes">Mis Álbumes</a></li>
            <li><a href="/daw/practica_dawpr7/mis-fotos">Mis Fotos</a></li>
            <li><a href="/daw/practica_dawpr7/crear-album">Crear Album</a></li>
            <li><a href="/daw/practica_dawpr7/solicitar-album">Solicitar álbum</a></li>
            <li><a href="/daw/practica_dawpr7/mis-datos">Modificar mis datos</a></li>
            <li><a href="/daw/practica_dawpr7/darse-baja">Darse de baja</a></li>
        </ul>
    </section>
</main>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
