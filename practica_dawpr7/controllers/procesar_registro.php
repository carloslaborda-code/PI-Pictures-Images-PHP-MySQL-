<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}
// procesar_registro.php

// Función para limpiar y escapar datos
function limpiar_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Capturamos los datos del formulario
$username = isset($_POST['username']) ? limpiar_input($_POST['username']) : '';
$password = isset($_POST['password']) ? limpiar_input($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? limpiar_input($_POST['confirm_password']) : '';

// Array de errores
$errores = [];

// Validaciones
if (empty($username)) {
    $errores[] = 'username';
}
if (empty($password)) {
    $errores[] = 'password';
}
if ($password !== $confirm_password) {
    $errores[] = 'confirm_password';
}

// Redirección en caso de errores
if (!empty($errores)) {
    // Convierte el array de errores en una cadena separada por comas
    $errores_str = implode(',', $errores);
    $query_string = http_build_query([
        'errores' => $errores_str,
        'username' => $username,
        'email' => $_POST['email'],
        'password' => $password,
        'confirm_password' => $confirm_password
    ]);
    header("Location: /daw/practica_dawpr7/registro?$query_string");
    exit();
}

// Si no hay errores, redirige a la página de inicio (por ejemplo, `index.php`)
header("Location: /daw/practica_dawpr7/?registro=exitoso");
exit();
?>
