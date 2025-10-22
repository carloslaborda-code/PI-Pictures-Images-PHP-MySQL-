<?php
session_start();
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

// Obtener álbumes del usuario
try {
    $username = $_SESSION['username'];
    $query_albumes = "
        SELECT a.IdAlbum, a.Titulo
        FROM Albumes a
        JOIN Usuarios u ON a.Usuario = u.IdUsuario
        WHERE u.NomUsuario = :username
    ";
    $stmt = $pdo->prepare($query_albumes);
    $stmt->execute([':username' => $username]);
    $albumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$albumes) {
        throw new Exception("No tienes álbumes creados.");
    }
} catch (PDOException $e) {
    die("Error al cargar los álbumes: " . $e->getMessage());
}

$title = 'Añadir Foto a Álbum';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>Añadir Foto</h2>
        <?php if (isset($_SESSION['errores'])): ?>
            <ul class="errores">
                <?php foreach ($_SESSION['errores'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
                <?php unset($_SESSION['errores']); ?>
            </ul>
        <?php endif; ?>

        <form action="/daw/practica_dawpr7/procesar-anadir-foto" method="post" enctype="multipart/form-data">
            <div>
                <label for="titulo">Título de la foto:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea>
            </div>
            <div>
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            <div>
                <label for="pais">País:</label>
                <select id="pais" name="pais" required>
                    <option value="">Seleccionar país</option>
                    <?php
                    $query_paises = "SELECT IdPais, NomPais FROM Paises";
                    $stmt_paises = $pdo->query($query_paises);
                    while ($pais = $stmt_paises->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($pais['IdPais']) . '">' . htmlspecialchars($pais['NomPais']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="fichero">Subir imagen:</label>
                <input type="file" id="fichero" name="fichero" accept="image/*" required>
            </div>
            <div>
                <label for="texto_alternativo">Texto alternativo:</label>
                <input type="text" id="texto_alternativo" name="texto_alternativo" required minlength="10">
            </div>
            <div>
                <label for="album">Seleccionar álbum:</label>
                <select id="album" name="album" required>
                    <option value="">Seleccionar álbum</option>
                    <?php foreach ($albumes as $album): ?>
                        <option value="<?php echo htmlspecialchars($album['IdAlbum']); ?>">
                            <?php echo htmlspecialchars($album['Titulo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Añadir Foto</button>
        </form>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
