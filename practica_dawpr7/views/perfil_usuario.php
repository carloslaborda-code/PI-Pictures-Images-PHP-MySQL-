<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// perfil_usuario.php

$title = 'Perfil Usuario';
include __DIR__ . '/../templates/header.php';
?>

    <section>
        <h2>Gestión de Cuenta</h2>
        <ul>
            <li><a href="#">Modificar mis datos</a></li>
            <li><a href="#">Darse de baja</a></li>
        </ul>
    </section>

    <section>
        <h2>Álbumes</h2>
        <ul>
            <li><a href="#">Mis Albumes</a></li>
            <li><a href="/daw/practica_dawpr7/crear-album">Crear Album</a></li>
            <li><a href="/daw/practica_dawpr7/solicitar-album">Solicitar álbum</a></li>
        </ul>
    </section>
    <script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<!-- Pie de página -->
<?php 
include __DIR__ . '/../templates/footer.php';
?>

</body>
</html>
