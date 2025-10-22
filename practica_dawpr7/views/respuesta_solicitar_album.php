<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// respuesta_solicitar_album.php

// Definir el título de la página
$title = 'Confirmación de Solicitud de Álbum';
include __DIR__ . '/../templates/header.php';

// Recibir los datos enviados desde el formulario
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
$resolucion = $_POST['resolucion'] ?? 150;
$album_usuario = $_POST['album_usuario'] ?? '';
$fecha_recepcion = $_POST['fecha_recepcion'] ?? '';
$color_impresion = $_POST['color_impresion'] ?? 'color';

// Costos y tarifas
$costo_procesamiento = 10; // Coste fijo de procesamiento y envío
$costo_paginas = $costo_fotos = 0;

// Cálculo del coste basado en número de páginas y resolución de fotos
$numero_paginas = 10;  // Valor simulado como ejemplo, ajustable en base a práctica
$numero_fotos = $numero_paginas * 3;

// Determinar el coste por número de páginas
if ($numero_paginas < 5) {
    $costo_paginas = $numero_paginas * 2;
} elseif ($numero_paginas <= 10) {
    $costo_paginas = 4 * 2 + ($numero_paginas - 4) * 1.8;
} else {
    $costo_paginas = 4 * 2 + 6 * 1.8 + ($numero_paginas - 10) * 1.6;
}

// Determinar el coste por fotos en función de resolución y color
if ($color_impresion == 'color') {
    $costo_fotos = $numero_fotos * 0.5;
}
if ($resolucion > 300) {
    $costo_fotos += $numero_fotos * 0.2;
}

// Calcular el coste total por álbum y multiplicar por el número de copias
$costo_total_album = $costo_procesamiento + $costo_paginas + $costo_fotos;
$costo_total = $costo_total_album * $copias;
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
        <li><strong>Dirección de envío:</strong> 
            <?php echo htmlspecialchars("$calle, $numero, Piso: $piso, Puerta: $puerta, $codigo_postal, $localidad, $provincia, $pais"); ?>
        </li>
        <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></li>
        <li><strong>Color de la portada:</strong> <span style="background-color: <?php echo htmlspecialchars($color_portada); ?>;"><?php echo htmlspecialchars($color_portada); ?></span></li>
        <li><strong>Número de copias:</strong> <?php echo htmlspecialchars($copias); ?></li>
        <li><strong>Resolución de las fotos (DPI):</strong> <?php echo htmlspecialchars($resolucion); ?></li>
        <li><strong>Álbum a imprimir:</strong> <?php echo htmlspecialchars($album_usuario); ?></li>
        <li><strong>Fecha de recepción:</strong> <?php echo htmlspecialchars($fecha_recepcion); ?></li>
        <li><strong>Impresión:</strong> <?php echo ($color_impresion == 'color') ? 'Color' : 'Blanco y Negro'; ?></li>
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

<?php 
// Incluir el pie de página
include __DIR__ . '/../templates/footer.php';
?>
