<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Conexión a la base de datos y código común
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../partials/album_datos_comun.php';

// Validar ID del álbum
$album_id = isset($_GET['id']) && ctype_digit($_GET['id']) ? intval($_GET['id']) : 0;
if ($album_id === 0) {
    die("ID de álbum no válido.");
}

// Obtener datos del álbum
try {
    $datos_album = obtenerDatosAlbum($pdo, $album_id);
} catch (Exception $e) {
    die($e->getMessage());
}

$title = 'Ver Álbum (Pública Restringida)';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>Álbum: <?php echo htmlspecialchars($datos_album['titulo']); ?></h2>
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($datos_album['descripcion']); ?></p>
        <p><strong>Número total de fotos:</strong> <?php echo $datos_album['numero_fotos']; ?></p>
        <p><strong>Países representados:</strong> 
            <?php echo !empty($datos_album['paises']) ? implode(', ', $datos_album['paises']) : 'Ninguno'; ?>
        </p>
        <p><strong>Intervalo de fechas:</strong> 
            <?php echo ($datos_album['fecha_inicio'] && $datos_album['fecha_fin']) 
                ? "Desde {$datos_album['fecha_inicio']} hasta {$datos_album['fecha_fin']}" 
                : 'No disponible'; ?>
        </p>
    </section>

    <section>
        <h3>Fotos del Álbum</h3>
        <div class="fotos-lista">
            <?php if (!empty($datos_album['fotos'])): ?>
                <?php foreach ($datos_album['fotos'] as $foto): ?>
                    <div class="foto-item">
                        <a href="/daw/practica_dawpr7/detalle-foto?id=<?php echo htmlspecialchars($foto['IdFoto']); ?>">
                            <img src="/daw/practica_dawpr7/image/<?php echo htmlspecialchars($foto['Fichero']); ?>" 
                                alt="<?php echo htmlspecialchars($foto['FotoTitulo']); ?>">
                        </a>
                        <p><strong>Título:</strong> <?php echo htmlspecialchars($foto['FotoTitulo']); ?></p>
                        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($foto['Fecha']); ?></p>
                        <p><strong>País:</strong> <?php echo htmlspecialchars($foto['Pais'] ?? 'Desconocido'); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay fotos en este álbum.</p>
            <?php endif; ?>
        </div>
    </section>


</main>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
