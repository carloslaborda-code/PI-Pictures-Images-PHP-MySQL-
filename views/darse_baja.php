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
$errores = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

try {
    // Consulta para obtener los álbumes y el número de fotos por álbum del usuario
    $query_albumes = "
        SELECT a.IdAlbum, a.Titulo, COUNT(f.IdFoto) AS num_fotos
        FROM Albumes a
        LEFT JOIN Fotos f ON a.IdAlbum = f.Album
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
        GROUP BY a.IdAlbum, a.Titulo
    ";
    $stmt_albumes = $pdo->prepare($query_albumes);
    $stmt_albumes->execute([':username' => $username]);
    $albumes = $stmt_albumes->fetchAll(PDO::FETCH_ASSOC);

    // Contar el número total de fotos del usuario
    $query_total_fotos = "
        SELECT COUNT(f.IdFoto) AS total_fotos
        FROM Fotos f
        JOIN Albumes a ON f.Album = a.IdAlbum
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
    ";
    $stmt_total_fotos = $pdo->prepare($query_total_fotos);
    $stmt_total_fotos->execute([':username' => $username]);
    $total_fotos = $stmt_total_fotos->fetchColumn();
} catch (PDOException $e) {
    die("Error al cargar los datos del usuario: " . $e->getMessage());
}

$title = "Darse de Baja";
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>¿Estás seguro de que deseas darte de baja?</h2>
        <p>Si te das de baja, todos tus datos, incluyendo tus álbumes y fotos, serán eliminados permanentemente.</p>
        <h3>Resumen de tus datos</h3>
        <ul>
            <?php foreach ($albumes as $album): ?>
                <li><?php echo htmlspecialchars($album['Titulo']); ?> (<?php echo $album['num_fotos']; ?> fotos)</li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Total de fotos:</strong> <?php echo $total_fotos; ?></p>

        <!-- Mostrar errores si existen -->
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger" style="background-color: #ffcccc; color: #b30000; padding: 15px; border-radius: 5px; border: 1px solid #b30000; margin-bottom: 20px;">
                <?php foreach ($errores as $error): ?>
                    <p style="margin: 0; font-weight: bold;"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para confirmar la eliminación -->
        <form action="/daw/practica_dawpr7/procesar-baja" method="post">
            <label for="current_password">Introduce tu contraseña para confirmar:</label>
            <input type="password" id="current_password" name="current_password" required>
            <button type="submit" class="btn-danger" style="background-color: #ff0000; color: #fff; padding: 10px 20px; border: none; cursor: pointer;">Confirmar Baja</button>
            <a href="/daw/practica_dawpr7/perfil-usuario" class="btn-cancel" style="background-color: #ccc; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">Cancelar</a>
        </form>
    </section>
</main>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>
<?php include __DIR__ . '/../templates/footer.php'; ?>
