<?php
session_start();
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}



// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Consulta para obtener la lista de países
try {
    $query = "SELECT IdPais, NomPais FROM Paises ORDER BY NomPais";
    $stmt = $pdo->query($query);
    $paises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los países: " . $e->getMessage());
}

// Recuperar errores y datos ingresados anteriormente
$errores = $_SESSION['form_errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

// Variables iniciales para el formulario de registro
$username = $form_data['username'] ?? '';
$email = $form_data['email'] ?? '';
$sexo = $form_data['sexo'] ?? '';
$birthdate = $form_data['birthdate'] ?? '';
$pais_id = $form_data['pais'] ?? '';
$city = $form_data['city'] ?? '';
$modo_edicion = false; // Este formulario es para registro, no edición
$action_url = '/daw/practica_dawpr7/procesar-registro'; // URL para procesar el registro

$title = "Registro de Nuevo Usuario";
include __DIR__ . '/../templates/header.php';
?>
<section>
    <h2>Registro de Nuevo Usuario</h2>
    
    <?php
    // Incluye el formulario común
    include __DIR__ . '/../partials/formulario_usuario.php';
    ?>
</section>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>
<script src="/daw/practica_dawpr7/js/validacion_registro.js"></script>


<?php 
include __DIR__ . '/../templates/footer.php';
?>
