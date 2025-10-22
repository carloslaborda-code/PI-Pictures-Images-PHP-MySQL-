<?php
function obtenerDatosAlbum($pdo, $album_id) {
    // Información del álbum
    $query_album = "
        SELECT a.Titulo, a.Descripcion
        FROM Albumes a
        WHERE a.IdAlbum = :album_id
    ";
    $stmt_album = $pdo->prepare($query_album);
    $stmt_album->execute([':album_id' => $album_id]);
    $album = $stmt_album->fetch(PDO::FETCH_ASSOC);

    if (!$album) {
        throw new Exception("Álbum no encontrado.");
    }

    // Fotos del álbum
    $query_fotos = "
        SELECT f.IdFoto, f.Titulo AS FotoTitulo, f.Fichero, f.Fecha, p.NomPais AS Pais
        FROM Fotos f
        LEFT JOIN Paises p ON f.Pais = p.IdPais
        WHERE f.Album = :album_id
    ";
    $stmt_fotos = $pdo->prepare($query_fotos);
    $stmt_fotos->execute([':album_id' => $album_id]);
    $fotos = $stmt_fotos->fetchAll(PDO::FETCH_ASSOC);

    // Intervalo de fechas
    $fechas = array_column($fotos, 'Fecha');
    $paises = array_unique(array_filter(array_column($fotos, 'Pais')));

    return [
        'titulo' => $album['Titulo'],
        'descripcion' => $album['Descripcion'],
        'fotos' => $fotos,
        'numero_fotos' => count($fotos),
        'paises' => $paises,
        'fecha_inicio' => $fechas ? min($fechas) : null,
        'fecha_fin' => $fechas ? max($fechas) : null
    ];
}
