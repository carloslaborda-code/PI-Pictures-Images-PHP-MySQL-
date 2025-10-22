<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

$title = 'Página Principal';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <!-- Mostrar mensaje de error si existe el parámetro 'error' en la URL -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'credenciales_invalidas'): ?>
        <p class="error" style="color: red; font-weight: bold;">Error: Las credenciales proporcionadas no son válidas. Inténtalo de nuevo.</p>
    <?php endif; ?>

    <!-- Sección de las últimas cinco fotos añadidas -->
    <section>
        <h2>Últimas 5 fotos añadidas</h2>
        <ul>
            <li>
                <a href="/daw/practica_dawpr7/detalle-foto?id=1">
                    <img src="/daw/practica_dawpr7/image/Siluetas_sobre_la_luna.jpg" alt="Una imagen de la luna llena con detalles claros de su superficie y un cielo oscuro de fondo." width="100" class="importante">
                </a>
                <p>Título: Anochecer</p>
                <p>Fecha: 18/09/2024</p>
                <p>País: España</p>
            </li>

            <li>
                <a href="/daw/practica_dawpr7/detalle-foto?id=2">
                    <img src="/daw/practica_dawpr7/image/acampar.jpg" alt="Tienda de campaña en un entorno natural con un atardecer en el horizonte." width="100" class="importante">
                </a>
                <p>Título: Camping</p>
                <p>Fecha: 15/09/2024</p>
                <p>País: España</p>
            </li>

            <li>
                <a href="detalle_foto.php?id=3">
                    <img src="/daw/practica_dawpr7/image/Rio_Santa.jpg" alt="Un río que fluye entre montañas verdes bajo un cielo parcialmente nublado." width="100" class="importante">
                </a>
                <p>Título: Río</p>
                <p>Fecha: 12/09/2024</p>
                <p>País: Suiza</p>
            </li>

            <li>
                <a href="detalle_foto.php?id=4">
                    <img src="/daw/practica_dawpr7/image/rio.jpeg" alt="Un lago tranquilo rodeado de árboles con reflejos en el agua bajo un cielo despejado." width="100" class="importante">
                </a>
                <p>Título: Lago</p>
                <p>Fecha: 10/09/2024</p>
                <p>País: Japón</p>
            </li>

            <li>
                <a href="detalle_foto.php?id=5">
                    <img src="/daw/practica_dawpr7/image/campo.jpg" alt="Un campo abierto de hierba verde iluminado por el sol al atardecer." width="100" class="importante">
                </a>
                <p>Título: Prado Verde</p>
                <p>Fecha: 08/09/2024</p>
                <p>País: Canadá</p>
            </li>
        </ul>
    </section>
</main>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
</body>
</html>
