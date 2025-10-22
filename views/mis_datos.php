<?php
session_start();
if (!defined('FROM_INDEX')) { header("HTTP/1.0 403 Forbidden"); exit("Acceso directo no permitido."); }
if (!isset($_SESSION['username'])) { header("Location: /daw/practica_dawpr7/inicio-sesion"); exit(); }

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Recuperar errores y datos ingresados anteriormente
$errores = $_SESSION['form_errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

try {
    // Obtener datos del usuario autenticado
    $query = "SELECT NomUsuario, Email, Sexo, FNacimiento, Pais, Ciudad, Foto FROM Usuarios WHERE NomUsuario = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $_SESSION['username']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }

    // Preparar la variable Foto
    $foto_actual = $usuario['Foto'] ?? 'default-profile.JPG';

    // Obtener la lista de países
    $query_paises = "SELECT IdPais, NomPais FROM Paises ORDER BY NomPais";
    $stmt_paises = $pdo->query($query_paises);
    $paises = $stmt_paises->fetchAll(PDO::FETCH_ASSOC);

    // Preparar datos para el formulario
    $username = $usuario['NomUsuario'];
    $email = $form_data['email'] ?? $usuario['Email'];
    $sexo = $form_data['sexo'] ?? $usuario['Sexo'];
    $birthdate = $form_data['birthdate'] ?? $usuario['FNacimiento'];
    $pais_id = $form_data['pais'] ?? $usuario['Pais'];
    $city = $form_data['city'] ?? $usuario['Ciudad'];
    $modo_edicion = true; 
    $action_url = "/daw/practica_dawpr7/procesar-mis-datos"; 
} catch (PDOException $e) {
    die("Error al cargar los datos: " . $e->getMessage());
}

$title = "Mis Datos";
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2>Mis Datos</h2>
    <?php 
        // Pasar la foto actual al formulario
        include __DIR__ . '/../partials/formulario_usuario.php'; 
    ?>
</section>

<script src="/daw/practica_dawpr7/js/cambiarestilo.js"></script>
<?php include __DIR__ . '/../templates/footer.php'; ?>
