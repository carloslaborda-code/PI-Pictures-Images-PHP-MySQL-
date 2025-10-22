<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: /daw/practica_dawpr7/inicio-sesion?mensaje=debe_iniciar_sesion");
    exit();
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Recibir datos del formulario
$album_usuario = intval($_POST['album_usuario'] ?? 0);
$nombre = $_POST['nombre'] ?? '';
$titulo_album = $_POST['titulo_album'] ?? '';
$texto_adicional = $_POST['texto_adicional'] ?? '';
$email = $_POST['email'] ?? '';
$calle = $_POST['calle'] ?? '';
$numero = $_POST['numero'] ?? '';
$piso = $_POST['piso'] ?? '';
$puerta = $_POST['puerta'] ?? '';
$codigo_postal = $_POST['codigo_postal'] ?? '';
$localidad = $_POST['localidad'] ?? '';
$provincia = $_POST['provincia'] ?? '';
$pais = $_POST['pais'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$color_portada = $_POST['color_portada'] ?? '#000000';
$copias = intval($_POST['copias'] ?? 1);
$resolucion = intval($_POST['resolucion'] ?? 150);
$fecha_recepcion = $_POST['fecha_recepcion'] ?? null;
$color_impresion = ($_POST['color_impresion'] ?? 'color') === 'color' ? 1 : 0;

// Cálculo de costos
$costo_procesamiento = 10;
$costo_paginas = $costo_fotos = 0;
$numero_paginas = 10; // Número simulado
$numero_fotos = $numero_paginas * 3;

if ($numero_paginas < 5) {
    $costo_paginas = $numero_paginas * 2;
} elseif ($numero_paginas <= 10) {
    $costo_paginas = 4 * 2 + ($numero_paginas - 4) * 1.8;
} else {
    $costo_paginas = 4 * 2 + 6 * 1.8 + ($numero_paginas - 10) * 1.6;
}

$costo_fotos = $numero_fotos * ($color_impresion ? 0.5 : 0) + ($resolucion > 300 ? $numero_fotos * 0.2 : 0);
$costo_total_album = $costo_procesamiento + $costo_paginas + $costo_fotos;
$costo_total = $costo_total_album * $copias;

// Validar si el álbum existe
$query_validar_album = "
    SELECT IdAlbum, Titulo 
    FROM Albumes 
    WHERE IdAlbum = :album_usuario
";
$stmt_validar_album = $pdo->prepare($query_validar_album);
$stmt_validar_album->execute([':album_usuario' => $album_usuario]);

// Depuración para ver si se está obteniendo el álbum de la base de datos
$album_encontrado = $stmt_validar_album->fetch();
if (!$album_encontrado) {
    die("Error: El álbum seleccionado no existe. ID proporcionado: $album_usuario");
} else {
    $titulo_album = $album_encontrado['Titulo'];
}

// Insertar solicitud en la tabla `solicitudes`
try {
    $query = "
        INSERT INTO solicitudes (
            Album, Nombre, Titulo, Descripcion, Email, Direccion, Telefono,
            Color, Copias, Resolucion, Fecha, IColor, Coste
        ) VALUES (
            :album_usuario, :nombre, :titulo_album, :texto_adicional, :email, :direccion, :telefono,
            :color_portada, :copias, :resolucion, :fecha_recepcion, :color_impresion, :costo_total
        )
    ";
    $stmt = $pdo->prepare($query);

    $direccion = "$calle, $numero, Piso: $piso, Puerta: $puerta, $codigo_postal, $localidad, $provincia, $pais";

    $stmt->execute([
        ':album_usuario' => $album_usuario,
        ':nombre' => $nombre,
        ':titulo_album' => $titulo_album,
        ':texto_adicional' => $texto_adicional,
        ':email' => $email,
        ':direccion' => $direccion,
        ':telefono' => $telefono,
        ':color_portada' => $color_portada,
        ':copias' => $copias,
        ':resolucion' => $resolucion,
        ':fecha_recepcion' => $fecha_recepcion,
        ':color_impresion' => $color_impresion,
        ':costo_total' => $costo_total
    ]);
} catch (PDOException $e) {
    die("Error al insertar la solicitud: " . $e->getMessage());
}

// Mostrar detalles de la solicitud
$title = 'Confirmación de Solicitud de Álbum';
include __DIR__ . '/../templates/header.php';
?>


<div class="respuesta-solicitud">
    <h2>¡Gracias por tu solicitud!</h2>
    <p>Tu solicitud ha sido registrada correctamente. A continuación, te mostramos los detalles de tu pedido.</p>
</div>

<section>
    <h3>Detalles del Álbum Solicitado</h3>
    <ul>
        <li><strong>Nombre del destinatario:</strong> <?php echo htmlspecialchars($nombre); ?></li>
        <li><strong>Título del álbum:</strong> <?php echo htmlspecialchars($titulo_album); ?></li>
        <li><strong>Texto adicional:</strong> <?php echo htmlspecialchars($texto_adicional); ?></li>
        <li><strong>Correo electrónico:</strong> <?php echo htmlspecialchars($email); ?></li>
        <li><strong>Dirección de envío:</strong> <?php echo htmlspecialchars($direccion); ?></li>
        <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></li>
        <li><strong>Color de la portada:</strong> <span style="background-color: <?php echo htmlspecialchars($color_portada); ?>;"><?php echo htmlspecialchars($color_portada); ?></span></li>
        <li><strong>Número de copias:</strong> <?php echo htmlspecialchars($copias); ?></li>
        <li><strong>Resolución de las fotos (DPI):</strong> <?php echo htmlspecialchars($resolucion); ?></li>
        <li><strong>Álbum a imprimir:</strong> <?php echo htmlspecialchars($album_usuario); ?></li>
        <li><strong>Fecha de recepción:</strong> <?php echo htmlspecialchars($fecha_recepcion); ?></li>
        <li><strong>Impresión:</strong> <?php echo $color_impresion ? 'Color' : 'Blanco y Negro'; ?></li>
    </ul>
</section>

<section class="coste-album-seccion">
    <h3>Coste del Álbum</h3>
    <table class="coste-album">
        <tr>
            <th>Concepto</th>
            <th>Coste</th>
        </tr>
        <tr>
            <td>Procesamiento y Envío</td>
            <td><?php echo number_format($costo_procesamiento, 2); ?>€</td>
        </tr>
        <tr>
            <td>Coste por Páginas</td>
            <td><?php echo number_format($costo_paginas, 2); ?>€</td>
        </tr>
        <tr>
            <td>Coste por Fotos</td>
            <td><?php echo number_format($costo_fotos, 2); ?>€</td>
        </tr>
        <tr>
            <td><strong>Coste Unitario</strong></td>
            <td><?php echo number_format($costo_total_album, 2); ?>€</td>
        </tr>
        <tr>
            <td><strong>Total (<?php echo $copias; ?> copias)</strong></td>
            <td><strong><?php echo number_format($costo_total, 2); ?>€</strong></td>
        </tr>
    </table>
</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
