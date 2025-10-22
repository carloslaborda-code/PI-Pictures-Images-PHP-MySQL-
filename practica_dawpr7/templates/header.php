<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'PI - Pictures & Images'; ?></title>
    <link rel="stylesheet" href="/daw/practica_dawpr7/css/style.css">
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
        <h3>¡Disfruta de las fotos más recientes!</h3>
        
        <!-- Menú de navegación principal -->
        <nav>
            <ul>
                <a href="/daw/practica_dawpr7/"><i class="fas fa-home icono-menu"></i>Inicio</a>
                <a href="/daw/practica_dawpr7/registro"><i class="fas fa-user-plus icono-menu"></i>Registro</a>
                <a href="/daw/practica_dawpr7/formulario-busqueda"><i class="fas fa-search icono-menu"></i>Búsqueda</a>
                <a href="/daw/practica_dawpr7/inicio-sesion"><i class="fas fa-sign-in-alt icono-menu"></i>Inicio de sesión</a>
                <a href="/daw/practica_dawpr7/perfil-usuario"><i class="fas fa-user icono-menu"></i>Mi Perfil</a>
                <a href="/daw/practica_dawpr7/declaracion-accesibilidad"><i class="fas fa-universal-access icono-menu"></i>Accesibilidad</a> <!-- Enlace a la página de accesibilidad -->
                
                <!-- Formulario de búsqueda rápida -->
                <form class="buscador-rapido" action="/daw/practica_dawpr7/resultado-busqueda" method="get">
                    <input type="text" id="search" name="search" placeholder="Buscar fotos..." required>
                    <button type="submit">Buscar</button>
                </form>
            </ul>
            <div>
                <label for="styleSwitcher">Selecciona un estilo:</label>
                <select id="styleSwitcher" onchange="cambiarEstilo()">
                    <option value="/daw/practica_dawpr7/css/style.css">Estilo Predeterminado</option>
                    <option value="/daw/practica_dawpr7/css/modo_noche.css">Modo Noche</option>
                    <option value="/daw/practica_dawpr7/css/Alto_contraste.css">Alto Contraste</option>
                    <option value="/daw/practica_dawpr7/css/letra_grande.css">Letra Grande</option>
                    <option value="/daw/practica_dawpr7/css/Contraste_Grande.css">Contraste y Letra Grande</option>
                </select>
            </div>
        </nav>
    </header>
