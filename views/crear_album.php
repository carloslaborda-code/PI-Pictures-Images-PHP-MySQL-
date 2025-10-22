<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Inicia la sesión si no se ha iniciado ya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    // Redirige al usuario a la página de inicio de sesión con un mensaje de error
    header("Location: /daw/practica_dawpr7/inicio-sesion?mensaje=debe_iniciar_sesion");
    exit();
}

$title = 'Crear Álbum';
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2>Crear Nuevo Álbum</h2>
    <form action="/daw/practica_dawpr7/procesar-crear-album" method="post">
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
