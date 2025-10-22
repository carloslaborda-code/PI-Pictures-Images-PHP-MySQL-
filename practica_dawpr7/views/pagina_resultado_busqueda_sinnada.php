<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// perfil_usuario.php

$title = 'Pagina no hay resultados';
include __DIR__ . '/../templates/header.php';
?>

    <!-- Sección que muestra que no se encontraron resultados -->
    <section>
        <h2>No se encontraron fotos que coincidan con tu búsqueda</h2>
        <p>Lo sentimos, no hemos encontrado ninguna foto que coincida con los criterios de búsqueda.</p>
        <p>Intenta usar otros términos de <a href="formulario_busqueda.html">busqueda</a> o <a href="pagina_principal_autenticado.html">vuelve a la página principal</a> para ver las fotos disponibles.</p>
    </section>
    <script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<!-- Pie de página -->
<?php 
include __DIR__ . '/../templates/footer.php';
?>
   

</body>
</html>
