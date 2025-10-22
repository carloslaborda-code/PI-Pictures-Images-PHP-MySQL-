<?php

session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Guarda datos del formulario para restaurarlos después
    if (!empty($_GET)) {
        $_SESSION['form_data'] = $_GET;
    }
    header("Location: /daw/practica_dawpr7/inicio-sesion?mensaje=debe_iniciar_sesion");
    exit();
}



// Recuperar datos del formulario si fueron guardados
if (isset($_SESSION['form_data'])) {
    $_GET = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Captura y validación de los datos del formulario
$titulo = isset($_GET['titulo']) ? trim($_GET['titulo']) : '';
$fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
$pais = isset($_GET['pais']) ? intval($_GET['pais']) : 0;

try {
    $query = "SELECT f.IdFoto, f.Fichero, f.Titulo, f.FRegistro, p.NomPais 
              FROM Fotos f
              LEFT JOIN Paises p ON f.Pais = p.IdPais
              WHERE 1=1";

    $params = [];

    if (!empty($titulo)) {
        $query .= " AND LOWER(f.Titulo) LIKE :titulo";
        $params[':titulo'] = '%' . strtolower($titulo) . '%';
    }

    if (!empty($fecha) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        $query .= " AND f.FRegistro = :fecha";
        $params[':fecha'] = $fecha;
    }

    if (!empty($pais)) {
        $query .= " AND f.Pais = :pais";
        $params[':pais'] = $pais;
    }

    $query .= " ORDER BY f.Titulo ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($fotos)) {
        header("Location: /daw/practica_dawpr7/formulario-busqueda-error");
        exit();
    }
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

$title = 'Resultados de Búsqueda';
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2>Resultados de Búsqueda</h2>
    <ul>
        <?php foreach ($fotos as $foto): ?>
            <li>
                <a href="/daw/practica_dawpr7/detalle-foto?id=<?php echo htmlspecialchars($foto['IdFoto']); ?>">
                    <img src="/daw/practica_dawpr7/image/<?php echo htmlspecialchars($foto['Fichero']); ?>" 
                         alt="<?php echo htmlspecialchars($foto['Titulo']); ?>" 
                         width="100">
                </a>
                <p><strong>Título:</strong> <?php echo htmlspecialchars($foto['Titulo']); ?></p>
                <p><strong>Fecha:</strong> <?php echo htmlspecialchars($foto['FRegistro']); ?></p>
                <p><strong>País:</strong> <?php echo htmlspecialchars($foto['NomPais'] ?? 'Desconocido'); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</section>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
