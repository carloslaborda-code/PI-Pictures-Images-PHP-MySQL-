<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// perfil_usuario.php

$title = 'Pagina de busqueda';
include __DIR__ . '/../templates/header.php';
?>

    <main>
        <section>
            <h2>Compromiso con la Accesibilidad</h2>
            <p>
                Nuestro sitio web se esfuerza por garantizar la accesibilidad para todos los usuarios, incluidas aquellas personas con discapacidades. Nos comprometemos a cumplir con las pautas de accesibilidad web (WCAG) y a realizar mejoras continuas en la usabilidad de nuestra plataforma.
            </p>
        </section>

        <section>
            <h2>Características de los Estilos para Discapacidad</h2>
            <p>A continuación, se describen las características añadidas en cada uno de los estilos CSS diseñados para facilitar la accesibilidad:</p>

            <h3>Modo Noche</h3>
            <ul>
                <li>Colores oscuros en el fondo y texto claro para reducir el brillo.</li>
                <li>Mejor contraste para la lectura en condiciones de poca luz.</li>
                <li>Eliminación de efectos visuales que pueden dificultar la lectura.</li>
            </ul>

            <h3>Alto contraste</h3>
            <ul>
                <li>Colores de alto contraste para maximizar la visibilidad.</li>
            </ul>

            <h3>Letra grande</h3>
            <ul>
                <li>Uso de iconos claros y grandes para facilitar la navegación.</li>
                <li>Tamaño de texto aumentado para mejorar la legibilidad.</li>
                <li>Aumento del espaciado entre líneas y párrafos.</li>
            </ul>

            <h3>Alto Contraste y letra grande</h3>
            <ul>
                <li>Uso de iconos claros y grandes para facilitar la navegación.</li>
                <li>Tamaño de texto aumentado para mejorar la legibilidad.</li>
                <li>Tamaño de texto aumentado para mejorar la legibilidad.</li>
                <li>Colores de alto contraste para maximizar la visibilidad.</li>
                <li>Aumento del espaciado entre líneas y párrafos.</li>
            </ul>

        </section>

        <section>
            <h2>Cómo activar estilos accesibles</h2>
            <p>
                Los usuarios pueden seleccionar estilos alternativos a través de las opciones de accesibilidad de su navegador. Se recomienda ajustar la configuración de su navegador para optimizar la visualización del contenido según sus necesidades.
            </p>
        </section>

        <section>
            <h2>Contacta con Nosotros</h2>
            <p>
                Si tienes alguna pregunta o sugerencia sobre la accesibilidad de nuestro sitio, no dudes en <a href="contacto.html">contactarnos</a>.
            </p>
        </section>
    </main>
    <script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>


<!-- Pie de página -->
<?php 
include __DIR__ . '/../templates/footer.php';
?>

</body>
</html>
