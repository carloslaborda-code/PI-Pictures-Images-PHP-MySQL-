<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// crear_album.php

$title = 'Crear Álbum';
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2>Crear Nuevo Álbum</h2>
    <form action="respuesta_crear_album.php" method="post">
        <label for="titulo">Título del álbum:</label>
        <input type="text" id="titulo" name="titulo" required><br><br>
        
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Crear Álbum">
    </form>
</section>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<!-- Pie de página -->
<?php 
include __DIR__ . '/../templates/footer.php';
?>

</body>
</html>
