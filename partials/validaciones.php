<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

function limpiar_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function validar_username($username) {
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]{2,14}$/', $username)) {
        return 'El nombre de usuario debe tener entre 3 y 15 caracteres, comenzar con una letra y solo contener letras y números.';
    }
    return '';
}

function validar_email($email) {
    $emailRegex = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]{1,64}@[a-zA-Z0-9-]{1,63}(\.[a-zA-Z0-9-]{1,63}){1,255}$/';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match($emailRegex, $email)) {
        return 'El formato del correo electrónico no es válido.';
    }
    return '';
}

function validar_password($password) {
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d_-]{6,15}$/', $password)) {
        return 'La contraseña debe tener entre 6 y 15 caracteres, contener al menos una mayúscula, una minúscula y un número. Solo se permiten letras, números, guiones y guiones bajos.';
    }
    return '';
}

function validar_fecha_nacimiento($birthdate) {
    if (!empty($birthdate)) {
        $birthdateTimestamp = strtotime($birthdate);
        if ($birthdateTimestamp === false) {
            return 'La fecha de nacimiento no es válida.';
        } else {
            $today = new DateTime();
            $birthDate = new DateTime($birthdate);
            $age = $today->diff($birthDate)->y;
            if ($age < 18) {
                return 'Debes tener al menos 18 años.';
            }
        }
    } else {
        return 'La fecha de nacimiento es obligatoria.';
    }
    return '';
}
?>
