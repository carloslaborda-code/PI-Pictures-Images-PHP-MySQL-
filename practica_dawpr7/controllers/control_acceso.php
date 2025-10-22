<?php
// Datos de usuarios permitidos (almacenados directamente en este archivo)
$usuarios_permitidos = [
    'user1' => 'user1',
    'user2' => 'user2',
    'user3' => 'user3',
    'user4' => 'user4'
];

// Verifica que la solicitud sea POST (es decir, que se haya enviado desde el formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Comprobación de usuario y contraseña
    if (isset($usuarios_permitidos[$usuario]) && $usuarios_permitidos[$usuario] === $password) {
        // Redirección a la página principal si el usuario está registrado correctamente
        header("Location: pagina_principal.php");
        exit;
    } else {
        // Redirección a pagina_principal.php con un mensaje de error si las credenciales son incorrectas
        header("Location: pagina_principal.php?error=credenciales_invalidas");
        exit;
    }
} else {
    // Si se intenta acceder directamente a esta página sin un envío POST, redirige al inicio sin mensaje de error
    header("Location: pagina_principal.php");
    exit;
}
