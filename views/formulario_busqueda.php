<?php

session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Consulta a la base de datos para obtener los países
try {
    $query = "SELECT IdPais, NomPais FROM Paises ORDER BY NomPais";
    $stmt = $pdo->query($query);
    $paises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los países: " . $e->getMessage());
}

$title = 'Formulario de Búsqueda';
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2>Formulario de Búsqueda</h2>
    
    <form class="formulario-busqueda" action="/daw/practica_dawpr7/resultado-busqueda" method="get">
        <label for="titulo">Título de la Foto:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($_GET['titulo'] ?? ''); ?>">
        <br><br>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?php echo htmlspecialchars($_GET['fecha'] ?? ''); ?>">
        <br><br>

        <label for="pais">País:</label>
        <select id="pais" name="pais">
            <option value="">Selecciona un país</option>
            <?php foreach ($paises as $pais): ?>
                <option value="<?php echo htmlspecialchars($pais['IdPais']); ?>" 
                    <?php echo isset($_GET['pais']) && $_GET['pais'] == $pais['IdPais'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($pais['NomPais']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit">Buscar</button>
    </form>
</section>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
