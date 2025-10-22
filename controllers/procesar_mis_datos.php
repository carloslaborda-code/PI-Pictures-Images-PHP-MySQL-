<?php
session_start();
if (!defined('FROM_INDEX')) { 
    header("HTTP/1.0 403 Forbidden"); 
    exit("Acceso directo no permitido."); 
}
if (!isset($_SESSION['username'])) { 
    header("Location: /daw/practica_dawpr7/inicio-sesion"); 
    exit(); 
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../partials/validaciones.php';

$username = $_SESSION['username'];
$email = isset($_POST['email']) ? limpiar_input($_POST['email']) : '';
$password = isset($_POST['password']) ? limpiar_input($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? limpiar_input($_POST['confirm_password']) : '';
$sexo = isset($_POST['sexo']) ? limpiar_input($_POST['sexo']) : '';
$birthdate = isset($_POST['birthdate']) ? limpiar_input($_POST['birthdate']) : '';
$pais = isset($_POST['pais']) ? limpiar_input($_POST['pais']) : '';
$current_password = isset($_POST['current_password']) ? limpiar_input($_POST['current_password']) : '';
$city = isset($_POST['city']) ? limpiar_input($_POST['city']) : '';

$errores = [];

// Verificar la contraseña actual
if (empty($current_password)) {
    $errores['current_password'] = 'Debes introducir tu contraseña actual para confirmar los cambios.';
} else {
    try {
        $query = "SELECT Clave FROM Usuarios WHERE NomUsuario = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || $current_password !== $usuario['Clave']) {
            $errores['current_password'] = 'La contraseña actual no es correcta.';
        }        
    } catch (PDOException $e) {
        die("Error al verificar la contraseña: " . $e->getMessage());
    }
}

// Validaciones de los otros campos
if ($error_email = validar_email($email)) {
    $errores['email'] = $error_email;
}
if (!empty($password)) {
    if ($error_password = validar_password($password)) {
        $errores['password'] = $error_password;
    }
    if ($password !== $confirm_password) {
        $errores['confirm_password'] = 'Las contraseñas no coinciden.';
    }
}

// Si hay errores, redirigir al formulario con mensajes de error
if (!empty($errores)) {
    $_SESSION['form_errors'] = $errores;
    $_SESSION['form_data'] = $_POST; // Guardar los datos ingresados para mostrar de nuevo en el formulario
    header("Location: /daw/practica_dawpr7/mis-datos");
    exit();
}

// Manejo de la foto de perfil
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['profile_picture']['tmp_name'];
    $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $new_name = uniqid("perfil_") . '.' . $extension; // Crear nombre único
    $upload_dir = __DIR__ . '/../image/profiles/';
    $upload_path = $upload_dir . $new_name;

    // Subir la nueva foto
    if (move_uploaded_file($tmp_name, $upload_path)) {
        // Eliminar la foto anterior si no es la foto por defecto
        if ($usuario['Foto'] && $usuario['Foto'] !== 'default-profile.JPG') {
            unlink($upload_dir . $usuario['Foto']);
        }
        $query_update_foto = "UPDATE Usuarios SET Foto = :foto WHERE NomUsuario = :username";
        $stmt_update_foto = $pdo->prepare($query_update_foto);
        $stmt_update_foto->execute([':foto' => $new_name, ':username' => $username]);
    }
}

// Eliminar foto (si se selecciona la opción)
if (isset($_POST['eliminar_foto']) && $foto_actual !== 'default-profile.JPG') {
    $upload_dir = __DIR__ . '/../image/profiles/';
    if (file_exists($upload_dir . $foto_actual)) {
        unlink($upload_dir . $foto_actual);
    }
    $query_update_foto = "UPDATE Usuarios SET Foto = 'default-profile.JPG' WHERE NomUsuario = :username";
    $stmt_update_foto = $pdo->prepare($query_update_foto);
    $stmt_update_foto->execute([':username' => $username]);
}

// Actualizar los datos del usuario si no hay errores
try {
    $query = "UPDATE Usuarios SET Email = :email, Sexo = :sexo, FNacimiento = :birthdate, Pais = :pais, Ciudad = :city";
    if (!empty($password)) {
        $query .= ", Clave = :password";
    }
    $query .= " WHERE NomUsuario = :username";

    $params = [
        ':email' => $email,
        ':sexo' => $sexo,
        ':birthdate' => $birthdate,
        ':pais' => $pais,
        ':city' => $city, // Añadido el campo ciudad
        ':username' => $username
    ];
    if (!empty($password)) {
        $params[':password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $_SESSION['flashdata'] = 'Datos actualizados con éxito.';
    header("Location: /daw/practica_dawpr7/mis-datos");
    exit();
} catch (PDOException $e) {
    die("Error al actualizar los datos: " . $e->getMessage());
}
?>
