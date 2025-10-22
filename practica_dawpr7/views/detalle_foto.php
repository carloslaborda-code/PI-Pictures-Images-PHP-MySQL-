<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// detalle_foto.php

// Incluye el título de la página
$title = 'Detalle de Foto';
include __DIR__ . '/../templates/header.php';

// Captura el ID de la foto de los parámetros de la URL
$foto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Datos de ejemplo de dos fotos para mostrar (en el futuro, estos vendrán de una base de datos)
$fotos = [
    1 => [
        'titulo' => 'Anochecer',
        'fecha' => '18/09/2024',
        'pais' => 'España',
        'album' => 'Vacaciones en Europa',
        'usuario' => 'Juan Pérez',
        'src' => '/daw/practica_dawpr7/image/Siluetas_sobre_la_luna.jpg',
        'alt' => 'Una imagen de la luna llena con detalles claros de su superficie y un cielo oscuro de fondo.'
    ],
    2 => [
        'titulo' => 'Campo',
        'fecha' => '19/09/2024',
        'pais' => 'Francia',
        'album' => 'Momentos Especiales',
        'usuario' => 'María López',
        'src' => '/daw/practica_dawpr7/image/campo.jpg',
        'alt' => 'Un campo con colores cálidos en el horizonte.'
    ]
];

// Selecciona la foto en función de si el ID es par o impar
$foto = ($foto_id % 2 === 0) ? $fotos[2] : $fotos[1];
?>

<!-- Sección de detalles de la foto -->
<main>
    <h2 class="titulo-detalle-centro"><?php echo htmlspecialchars($foto['titulo']); ?></h2>
    <div class="detalle-foto-contenedor">
        <img src="<?php echo htmlspecialchars($foto['src']); ?>" alt="<?php echo htmlspecialchars($foto['alt']); ?>" width="100" class="importante foto-detalle">
        <div class="photo-details">
            <p><strong>Título:</strong> <?php echo htmlspecialchars($foto['titulo']); ?></p>
            <p><strong>Fecha:</strong> <?php echo htmlspecialchars($foto['fecha']); ?></p>
            <p><strong>País:</strong> <?php echo htmlspecialchars($foto['pais']); ?></p>
            <p><strong>Álbum:</strong> <?php echo htmlspecialchars($foto['album']); ?></p>
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($foto['usuario']); ?></p>
        </div>
    </div>
</main>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
