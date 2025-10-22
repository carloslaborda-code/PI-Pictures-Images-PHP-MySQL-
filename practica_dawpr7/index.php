<?php
// index.php
define('FROM_INDEX', true);

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = trim(str_replace('/daw/practica_dawpr7', '', $request_uri), '/'); // Ajusta esto si es necesario

$routes = [
    '' => 'views/pagina_principal.php',
    'registro' => 'views/registro.php',
    'inicio-sesion' => 'views/formulario_inicio_sesion.php',
    'detalle-foto' => 'views/detalle_foto.php',
    'perfil-usuario' => 'views/perfil_usuario.php',
    'procesar-registro' => 'controllers/procesar_registro.php',
    'control-acceso' => __DIR__ . '/controllers/control_acceso.php',
    'declaracion-accesibilidad' => 'views/declaracion_accesibilidad.php',
    'formulario-busqueda' => 'views/formulario_busqueda.php',
    'resultado-busqueda' => 'views/resultado_busqueda.php',
    'solicitar-album' => 'views/solicitar_album.php',
    'crear-album' => 'views/crear_album.php',
    'respuesta-solicitar-album' => 'views/respuesta_solicitar_album.php',
    'respuesta-creacion-album' => 'views/confirmacion_creacion_album.php',
];

if (array_key_exists($request_uri, $routes)) {
    require $routes[$request_uri];
} else {
    header("HTTP/1.0 404 Not Found");
    include 'views/404.php';
}


