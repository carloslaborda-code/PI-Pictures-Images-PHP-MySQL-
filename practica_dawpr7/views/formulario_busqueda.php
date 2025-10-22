<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// perfil_usuario.php

$title = 'Pagina de busqueda';
include __DIR__ . '/../templates/header.php';
?>

    <!-- Sección del formulario de búsqueda -->
    <section>
        <!-- Título de la sección que indica que el contenido es un formulario de búsqueda -->
        <h2>Formulario de Búsqueda</h2>
        
        <!-- Formulario para realizar la búsqueda. El método GET envía los datos a la URL especificada -->
        <form class="formulario-busqueda" action="/daw/practica_dawpr7/resultado-busqueda" method="get">
            <!-- Campo de búsqueda por título -->
            <label for="titulo">Título de la Foto:</label>
            <!-- Campo de texto donde el usuario puede ingresar el título de la foto -->
            <input type="text" id="titulo" name="titulo">
            <br><br>

            <!-- Campo de búsqueda por fecha -->
            <label for="fecha">Fecha:</label>
            <!-- Campo de tipo "date" para que el usuario seleccione una fecha en formato AAAA-MM-DD -->
            <input type="date" id="fecha" name="fecha">
            <br><br>

            <!-- Campo de búsqueda por país -->
            <label for="pais">País:</label>
            <!-- Campo de texto para ingresar el país en el que se tomó la foto -->
            <input type="text" id="pais" name="pais">
            <br><br>

            <!-- Botón para enviar el formulario de búsqueda -->
            <button type="submit">Buscar</button>
        </form>
    </section>
    <script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<!-- Pie de página -->
<?php 
include __DIR__ . '/../templates/footer.php';
?>

</body>
</html>
