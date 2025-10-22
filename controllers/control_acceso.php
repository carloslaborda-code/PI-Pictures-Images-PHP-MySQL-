<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

require_once __DIR__ . '/../models/UserModel.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($username) || empty($password)) {
    $_SESSION['flashdata'] = 'Por favor, rellena todos los campos.';
    header("Location: /daw/practica_dawpr7/inicio-sesion");
    exit();
}

$userModel = new UserModel();
$usuario = $userModel->verificarCredenciales($username, $password);

if ($usuario) {
    $_SESSION['username'] = $usuario['username'];
    $_SESSION['autenticado'] = true;

    // Validar si se obtiene correctamente el estilo
    if (isset($usuario['estilo'])) {
        $_SESSION['estilo'] = $usuario['estilo'];
    } else {
        $_SESSION['estilo'] = '/daw/practica_dawpr7/css/style.css'; // Estilo por defecto
    }

    // Opcional: Configurar cookies si el usuario selecciona "Recordarme"
    if (isset($_POST['recordarme'])) {
        setcookie('usuario', $usuario['username'], time() + (90 * 24 * 60 * 60), "/");
        setcookie('ultima_visita', date('Y-m-d H:i:s'), time() + (90 * 24 * 60 * 60), "/");
        setcookie('estilo', $_SESSION['estilo'], time() + (90 * 24 * 60 * 60), "/");
    }

    header("Location: /daw/practica_dawpr7/perfil-usuario");
    exit();
} else {
    $_SESSION['flashdata'] = 'Credenciales incorrectas. Intente de nuevo.';
    header("Location: /daw/practica_dawpr7/inicio-sesion");
    exit();
}

