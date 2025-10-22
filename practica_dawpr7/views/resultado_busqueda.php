<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Capturamos los datos del formulario de búsqueda
$titulo = isset($_GET['titulo']) ? htmlspecialchars($_GET['titulo']) : 'N/A';
$fecha = isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : 'N/A';
$pais = isset($_GET['pais']) ? htmlspecialchars($_GET['pais']) : 'N/A';
include __DIR__ . '/../templates/header.php';
?>

<!-- Sección para mostrar los criterios de búsqueda del usuario -->
<section>
    <h2>Criterios de Búsqueda</h2>
    <p><strong>Título:</strong> <?php echo $titulo; ?></p>
    <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
    <p><strong>País:</strong> <?php echo $pais; ?></p>
</section>

<!-- Sección con los resultados de la búsqueda -->
<section>
    <h2>Fotos Encontradas</h2>
    <!-- Menú desplegable para ordenar -->
    <div>
        <label for="criterioOrden">Ordenar por:</label>
        <select id="criterioOrden" onchange="aplicarOrden()">
            <option value="titulo-asc">Título (Ascendente)</option>
            <option value="titulo-desc">Título (Descendente)</option>
            <option value="fecha-asc">Fecha (Ascendente)</option>
            <option value="fecha-desc">Fecha (Descendente)</option>
            <option value="autor-asc">Autor (Ascendente)</option>
            <option value="autor-desc">Autor (Descendente)</option>
            <option value="pais-asc">País (Ascendente)</option>
            <option value="pais-desc">País (Descendente)</option>
        </select>
    </div>
    <ul>
        <!-- Ejemplo de foto con enlace a detalle_foto.php con ID 1 -->
        <li>
        <a href="/daw/practica_dawpr7/detalle-foto?id=1">
                <img src="/daw/practica_dawpr7/image/Siluetas_sobre_la_luna.jpg" alt="Una imagen de la luna llena con detalles claros de su superficie y un cielo oscuro de fondo." width="100" class="importante">
            </a>
            <p>Título: Anochecer</p>
            <p>Fecha: 18/09/2024</p>
            <p>Autor: Pablo</p>
            <p>País: España</p>
        </li>
        
        <!-- Más fotos estáticas o dinámicas con enlaces únicos -->
        <li>
        <a href="/daw/practica_dawpr7/detalle-foto?id=2">
                <img src="/daw/practica_dawpr7/image/campo.jpg" alt="Un campo con colores cálidos en el horizonte." width="100" class="importante">
            </a>
            <p>Título: Campo</p>
            <p>Fecha: 19/09/2024</p>
            <p>Autor: María</p>
            <p>País: Francia</p>
        </li>
    </ul>
</section>

<script src="/daw/practica_dawpr7/js/ordenacion.js"></script>
<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
