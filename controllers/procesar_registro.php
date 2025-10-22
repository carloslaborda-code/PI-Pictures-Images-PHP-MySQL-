<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../partials/validaciones.php';

// Obtener y limpiar los datos del formulario
$username = isset($_POST['username']) ? limpiar_input($_POST['username']) : '';
$email = isset($_POST['email']) ? limpiar_input($_POST['email']) : '';
$password = isset($_POST['password']) ? limpiar_input($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? limpiar_input($_POST['confirm_password']) : '';
$sexo = isset($_POST['sexo']) ? limpiar_input($_POST['sexo']) : '';
$birthdate = isset($_POST['birthdate']) ? limpiar_input($_POST['birthdate']) : '';
$pais = isset($_POST['pais']) ? limpiar_input($_POST['pais']) : '';
$city = isset($_POST['city']) ? limpiar_input($_POST['city']) : '';
$errores = [];

// Validaciones
if ($error_username = validar_username($username)) {
    $errores['username'] = $error_username;
}
if ($error_email = validar_email($email)) {
    $errores['email'] = $error_email;
}
if ($error_password = validar_password($password)) {
    $errores['password'] = $error_password;
}
if ($password !== $confirm_password) {
    $errores['confirm_password'] = 'Las contraseñas no coinciden.';
}
if (empty($sexo)) {
    $errores['sexo'] = 'Debes seleccionar un sexo.';
}
if ($error_birthdate = validar_fecha_nacimiento($birthdate)) {
    $errores['birthdate'] = $error_birthdate;
}

if (!empty($errores)) {
    $_SESSION['form_errors'] = $errores; // Guardar los errores en sesión
    $_SESSION['form_data'] = $_POST; // Guardar los datos ingresados por el usuario
    header("Location: /daw/practica_dawpr7/registro");
    exit();
}

// Manejo de la foto de perfil
$profile_picture = 'default-profile.JPG'; // Foto por defecto
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['profile_picture']['tmp_name'];
    $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $new_name = uniqid("perfil_") . '.' . $extension; // Crear nombre único
    $upload_dir = __DIR__ . '/../image/profiles/';
    $upload_path = $upload_dir . $new_name;

    // Subir la foto
    if (move_uploaded_file($tmp_name, $upload_path)) {
        $profile_picture = $new_name;
    }
}

if (!empty($errores)) {
    $_SESSION['form_errors'] = $errores; // Guardar los errores en sesión
    $_SESSION['form_data'] = $_POST; // Guardar los datos ingresados por el usuario
    header("Location: /daw/practica_dawpr7/registro");
    exit();
}

// Insertar usuario en la base de datos
try {
    $pdo->beginTransaction(); // Iniciar transacción

    // Insertar usuario en la base de datos
    $query_user = "
        INSERT INTO Usuarios (NomUsuario, Email, Clave, Sexo, FNacimiento, Pais, Ciudad, Foto, FRegistro, Estilo)
        VALUES (:username, :email, :password, :sexo, :birthdate, :pais, :city, :photo, NOW(), 6)";
    $stmt_user = $pdo->prepare($query_user);
    $stmt_user->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_BCRYPT),
        ':sexo' => $sexo,
        ':birthdate' => $birthdate,
        ':pais' => $pais,
        ':city' => $city,
        ':photo' => $profile_picture
    ]);

    // Obtener el ID del usuario recién creado
    $usuario_id = $pdo->lastInsertId();

    // Crear un álbum por defecto
    $query_album = "
        INSERT INTO Albumes (Titulo, Usuario)
        VALUES ('Mi Primer Álbum', :usuario_id)";
    $stmt_album = $pdo->prepare($query_album);
    $stmt_album->execute([':usuario_id' => $usuario_id]);

    $pdo->commit(); // Confirmar transacción

    // Iniciar sesión automáticamente después del registro
    $_SESSION['username'] = $username;
    $_SESSION['autenticado'] = true;
    $_SESSION['foto'] = $profile_picture;

    header("Location: /daw/practica_dawpr7/perfil-usuario");
    exit();
} catch (PDOException $e) {
    $pdo->rollBack(); // Revertir transacción en caso de error
    die("Error al registrar al usuario: " . $e->getMessage());
  }

?>
