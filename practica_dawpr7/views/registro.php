<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Captura los errores enviados desde procesar_registro.php, si existen
$errores = isset($_GET['errores']) && is_string($_GET['errores']) ? explode(',', $_GET['errores']) : [];
$username = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$password = isset($_GET['password']) ? htmlspecialchars($_GET['password']) : '';
$confirm_password = isset($_GET['confirm_password']) ? htmlspecialchars($_GET['confirm_password']) : '';

$title = "Registro de Nuevo Usuario";
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2 class="h2-registro">Formulario de Registro</h2>
    <form class="formulario-registro" action="/daw/practica_dawpr7/procesar-registro" method="post" novalidate>
        <?php if (in_array('username', $errores)) : ?>
            <p class="error">* El nombre de usuario es obligatorio</p>
        <?php endif; ?>
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>">

        <?php if (in_array('password', $errores)) : ?>
            <p class="error">* La contraseña es obligatoria</p>
        <?php endif; ?>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" value="<?php echo $password; ?>">

        <?php if (in_array('confirm_password', $errores)) : ?>
            <p class="error">* Las contraseñas no coinciden</p>
        <?php endif; ?>
        <label for="confirm_password">Repetir Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>">

        <label for="email">Dirección de Email:</label>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>">

        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo">
            <option value="">Selecciona</option>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
            <option value="otro">Otro</option>
            <option value="prefiere_no_decirlo">Prefiero no decirlo</option>
        </select>

        <label for="birthdate">Fecha de Nacimiento:</label>
        <input type="text" id="birthdate" name="birthdate" placeholder="dd/mm/aaaa">
        
        <button type="submit">Registrarse</button>
    </form>

</section>

<script src="/daw/practica_dawpr7/js/validacion_registro.js"></script>
<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>

<?php 
// Incluye el pie de página
include __DIR__ . '/../templates/footer.php';
?>
