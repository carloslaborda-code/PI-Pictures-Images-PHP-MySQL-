<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php'; // Asegúrate de tener acceso a la base de datos

$estilo = '/daw/practica_dawpr7/css/style.css'; // Estilo por defecto

// Verificar si hay una sesión activa o si se debe recordar al usuario
if (isset($_SESSION['username'])) {
    // Usuario autenticado por sesión
    try {
        // Recuperar el estilo del usuario desde la base de datos
        $query = "SELECT Estilo FROM Usuarios WHERE NomUsuario = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $_SESSION['username']]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && !empty($resultado['Estilo'])) {
            $estilo_id = $resultado['Estilo'];
            $_SESSION['estilo'] = $estilo_id;

            // Recuperar la ruta del CSS correspondiente al IdEstilo
            $query = "SELECT Fichero FROM Estilos WHERE IdEstilo = :id_estilo";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_estilo' => $estilo_id]);
            $resultado_estilo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado_estilo && !empty($resultado_estilo['Fichero'])) {
                $estilo = '/daw/practica_dawpr7/css/' . $resultado_estilo['Fichero'];
            }
        }
    } catch (PDOException $e) {
        die("Error al recuperar el estilo del usuario: " . $e->getMessage());
    }
} elseif (isset($_COOKIE['estilo'])) {
    // Leer la cookie "estilo" si no hay sesión ni autenticación automática
    $estilo_id = $_COOKIE['estilo'];

    // Recuperar la ruta del CSS correspondiente al IdEstilo
    try {
        $query = "SELECT Fichero FROM Estilos WHERE IdEstilo = :id_estilo";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_estilo' => $estilo_id]);
        $resultado_estilo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado_estilo && !empty($resultado_estilo['Fichero'])) {
            $estilo = '/daw/practica_dawpr7/css/' . $resultado_estilo['Fichero'];
        }
    } catch (PDOException $e) {
        die("Error al recuperar el estilo de la cookie: " . $e->getMessage());
    }
}

date_default_timezone_set('Europe/Madrid');
$hora_actual = (int) date('H');
$nombre_usuario = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : 'Usuario');

if ($hora_actual >= 6 && $hora_actual < 12) {
    $saludo = "Buenos días, " . htmlspecialchars($nombre_usuario);
} elseif ($hora_actual >= 12 && $hora_actual < 16) {
    $saludo = "Hola, " . htmlspecialchars($nombre_usuario);
} elseif ($hora_actual >= 16 && $hora_actual < 20) {
    $saludo = "Buenas tardes, " . htmlspecialchars($nombre_usuario);
} else {
    $saludo = "Buenas noches, " . htmlspecialchars($nombre_usuario);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'PI - Pictures & Images'; ?></title>
    
    <!-- Siempre cargar el estilo base -->
    <link rel="stylesheet" href="/daw/practica_dawpr7/css/style.css">

    <!-- Cargar el estilo específico si es diferente del estilo base -->
    <?php if ($estilo !== '/daw/practica_dawpr7/css/style.css'): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($estilo); ?>">
    <?php endif; ?>

    <link rel="alternate stylesheet" href="/daw/practica_dawpr7/css/modo_noche.css" title="Modo Noche">
    <link rel="stylesheet" href="/daw/practica_dawpr7/css/modo_impreso.css" media="print">
    <link rel="alternate stylesheet" href="/daw/practica_dawpr7/css/Alto_contraste.css" title="Alto_contraste">
    <link rel="alternate stylesheet" href="/daw/practica_dawpr7/css/letra_grande.css" title="letra_grande">
    <link rel="alternate stylesheet" href="/daw/practica_dawpr7/css/Contraste_Grande.css" title="Contraste y Letra Grande">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/daw/practica_dawpr7/js/script.js" defer></script>
</head>
<body>
    <!-- Encabezado del sitio web -->
    <header>
        <h1>📷 Bienvenido a PI - Pictures & Images</h1>
        <?php if (isset($_COOKIE['usuario'])): ?>
            <p><?php echo $saludo; ?>. Última visita: <?php echo htmlspecialchars($_COOKIE['ultima_visita']); ?></p>
        <?php elseif (isset($_SESSION['username'])): ?>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <?php endif; ?>
        <h3>¡Disfruta de las fotos más recientes!</h3>
        
        <nav>
            <ul>
                <a href="/daw/practica_dawpr7/"><i class="fas fa-home icono-menu"></i>Inicio</a>
                <?php if (!isset($_SESSION['username'])): ?>   
                    <a href="/daw/practica_dawpr7/inicio-sesion"><i class="fas fa-sign-in-alt icono-menu"></i>Inicio de sesión</a>
                    <?php if (!isset($_COOKIE['usuario']))?>
                        <a href="/daw/practica_dawpr7/registro"><i class="fas fa-user-plus icono-menu"></i>Registro</a>
                <?php else: ?>
                    <a href="/daw/practica_dawpr7/perfil-usuario"><i class="fas fa-user icono-menu"></i>Mi Perfil</a>
                    <a href="/daw/practica_dawpr7/logout"><i class="fas fa-sign-out-alt icono-menu"></i>Cerrar sesión</a>
                    <!-- Nuevo enlace a "Añadir Foto a Álbum" -->
                    <a href="/daw/practica_dawpr7/anadir-foto"><i class="fas fa-upload icono-menu"></i>Subir Foto</a>
                <?php endif; ?>
                <a href="/daw/practica_dawpr7/configurar"><i class="fas fa-paint-brush icono-menu"></i>Configurar Estilo</a>
                <a href="/daw/practica_dawpr7/formulario-busqueda"><i class="fas fa-search icono-menu"></i>Búsqueda</a>
                <a href="/daw/practica_dawpr7/declaracion-accesibilidad"><i class="fas fa-universal-access icono-menu"></i>Accesibilidad</a>
                <!-- Formulario de búsqueda rápida -->
                <form class="buscador-rapido" action="/daw/practica_dawpr7/resultado-busqueda" method="get">
                    <input type="text" id="search" name="titulo" placeholder="Buscar fotos..." required>
                    <button type="submit">Buscar</button>
                </form>
            </ul>
        </nav>
    </header>
