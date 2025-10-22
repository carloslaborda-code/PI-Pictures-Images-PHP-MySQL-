<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

$title = 'Página Principal';
include __DIR__ . '/../templates/header.php';

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';


// Consulta para obtener las últimas 5 fotos ordenadas por fecha de registro
try {
    $query = "SELECT f.IdFoto, f.Fichero, f.Titulo, f.FRegistro, p.NomPais 
          FROM Fotos f 
          LEFT JOIN Paises p ON f.Pais = p.IdPais 
          ORDER BY f.FRegistro DESC 
          LIMIT 5";
    $stmt = $pdo->query($query);
    $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

// --- Funcionalidad foto escogida ---
$archivo_fotos = __DIR__ . '/../config/fotos_seleccionadas.txt';

if (file_exists($archivo_fotos)) {
    // Leer el archivo y obtener una línea aleatoria
    $lineas = file($archivo_fotos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $foto_aleatoria = $lineas[array_rand($lineas)];

    // Extraer los datos de la línea
    list($idFoto, $critico, $comentario) = explode('|', $foto_aleatoria);

    // Obtener información de la foto desde la base de datos
    try {
        $query_foto = "SELECT Titulo, Fichero, Descripcion FROM Fotos WHERE IdFoto = ?";
        $stmt_foto = $pdo->prepare($query_foto);
        $stmt_foto->execute([$idFoto]);
        $foto_seleccionada = $stmt_foto->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $foto_seleccionada = null;
    }
} else {
    $foto_seleccionada = null;
}

// --- Funcionalidad consejo fotográfico ---
$archivo_consejos = __DIR__ . '/../config/consejos_fotograficos.json';

$consejo = null; // Variable para almacenar el consejo seleccionado

if (file_exists($archivo_consejos)) {
    // Leer y decodificar el archivo JSON
    $contenido_json = file_get_contents($archivo_consejos);
    $consejos = json_decode($contenido_json, true);

    // Verificar si hay consejos disponibles
    if (!empty($consejos)) {
        // Seleccionar un consejo aleatorio
        $consejo = $consejos[array_rand($consejos)];
    }
} else {
    $consejo = null; // Manejo si el archivo no existe
}

?>

    <!-- Sección del consejo fotográfico -->
    <?php if (!empty($consejo)): ?>
        <section class="centrado consejo">
            <h2>Consejo Fotográfico</h2>
            <div class="consejo-fotografico">
                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($consejo['categoria']); ?></p>
                <p><strong>Dificultad:</strong> <?php echo htmlspecialchars($consejo['dificultad']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($consejo['descripcion']); ?></p>
            </div>
        </section>
    <?php else: ?>
        <p>No hay consejos disponibles en este momento.</p>
    <?php endif; ?>

    <!-- Sección de la foto seleccionada -->
    <?php if (!empty($foto_seleccionada)): ?>
        <section class="centrado">
            <h2>Foto Seleccionada</h2>
            <div class="foto-seleccionada">
                <img src="/daw/practica_dawpr7/image/<?php echo htmlspecialchars($foto_seleccionada['Fichero']); ?>" 
                    alt="<?php echo htmlspecialchars($foto_seleccionada['Titulo']); ?>" width="300">
                <p><strong>Título:</strong> <?php echo htmlspecialchars($foto_seleccionada['Titulo']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($foto_seleccionada['Descripcion']); ?></p>
                <p><strong>Seleccionada por:</strong> <?php echo htmlspecialchars($critico); ?></p>
                <p><em>Comentario:</em> <?php echo htmlspecialchars($comentario); ?></p>
            </div>
        </section>
    <?php else: ?>
        <p>No hay fotos seleccionadas disponibles.</p>
    <?php endif; ?>


    <!-- Sección de las últimas cinco fotos añadidas -->
    <section>
        <h2>Últimas 5 fotos añadidas</h2>
        <ul>
            <?php foreach ($fotos as $foto): ?>
                <li>
                    <a href="/daw/practica_dawpr7/detalle-foto?id=<?php echo htmlspecialchars($foto['IdFoto']); ?>">
                        <img src="/daw/practica_dawpr7/image/<?php echo htmlspecialchars($foto['Fichero']); ?>" 
                            alt="<?php echo htmlspecialchars($foto['Titulo']); ?>" 
                            width="100" class="importante">
                    </a>
                    <p>Título: <?php echo htmlspecialchars($foto['Titulo']); ?></p>
                    <p>Fecha: <?php echo htmlspecialchars($foto['FRegistro']); ?></p>
                    <p>País: <?php echo htmlspecialchars($foto['NomPais'] ?? 'Desconocido'); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    </main>

    <script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

    <?php 
        include __DIR__ . '/../templates/footer.php';
    ?>
</body>
</html>
