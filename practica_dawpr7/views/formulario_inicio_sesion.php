<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

$title = 'Acceso de Usuario';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section class="login-container">
        <h2>Acceso de Usuario</h2>

        <!-- Mostrar mensaje de error si existe -->
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form onsubmit="return validarFormulario()" action="/daw/practica_dawpr7/control-acceso" method="post" class="formulario-inicio-sesion">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>

            <br><br>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <br><br>

            <button type="submit">Iniciar Sesión</button>
        </form>
    </section>
</main>

<script>
    function validarFormulario() {
        var usuario = document.getElementById('username').value.trim();
        var contraseña = document.getElementById('password').value.trim();

        if (usuario === "" || contraseña === "") {
            alert("Por favor, rellena ambos campos.");
            return false;
        }
        return true;
    }
</script>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
</body>
</html>
