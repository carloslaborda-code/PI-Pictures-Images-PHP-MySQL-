<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// solicitar_album.php

$title = 'Solicitar Álbum Impreso';
include __DIR__ . '/../templates/header.php';

function generarTablaCostesPHP() {
    $costo_procesamiento = 10;
    echo '<table class="table-costes">
            <tr>
                <th>Número de páginas</th>
                <th>Número de fotos</th>
                <th>Blanco y negro (150-300 dpi)</th>
                <th>Blanco y negro (450-900 dpi)</th>
                <th>Color (150-300 dpi)</th>
                <th>Color (450-900 dpi)</th>
            </tr>';

    for ($paginas = 1; $paginas <= 15; $paginas++) {
        $fotos = $paginas * 3;
        $costo_paginas = ($paginas <= 4) ? $paginas * 2 : (($paginas <= 10) ? 4 * 2 + ($paginas - 4) * 1.8 : 4 * 2 + 6 * 1.8 + ($paginas - 10) * 1.6);

        $costosBlancoNegro = [
            number_format($costo_procesamiento + $costo_paginas, 2) . ' €',
            number_format($costo_procesamiento + $costo_paginas + 0.2 * $fotos, 2) . ' €'
        ];
        $costosColor = [
            number_format($costo_procesamiento + $costo_paginas + 0.5 * $fotos, 2) . ' €',
            number_format($costo_procesamiento + $costo_paginas + 0.5 * $fotos + 0.2 * $fotos, 2) . ' €'
        ];

        echo '<tr>';
        echo "<td>{$paginas}</td><td>{$fotos}</td>";
        echo "<td>{$costosBlancoNegro[0]}</td><td>{$costosBlancoNegro[1]}</td>";
        echo "<td>{$costosColor[0]}</td><td>{$costosColor[1]}</td>";
        echo '</tr>';
    }

    echo '</table>';
}
?>

<div class="contenedor-tarifas-formulario">
    <section class="section-izquierda">
        <h2>Tarifas</h2>
        <table class="conBorde">
            <tr><th>Concepto</th><th>Coste</th></tr>
            <tr><td>Coste procesamiento y envío</td><td>10€</td></tr>
            <tr><td>&lt; 5 páginas</td><td>2€ por pág.</td></tr>
            <tr><td>Entre 5 y 10 páginas</td><td>1.8€ por pág.</td></tr>
            <tr><td>&gt; 10 páginas</td><td>1.6€ por pág.</td></tr>
            <tr><td>Blanco y negro</td><td>0€</td></tr>
            <tr><td>Color</td><td>0.5€ por foto</td></tr>
            <tr><td>Resolución &le; 300 dpi</td><td>0€ por foto</td></tr>
            <tr><td>Resolución &gt; 300 dpi</td><td>0.2€ por foto</td></tr>
        </table>
        <button id="mostrarTabla" class="boton-tabla">Mostrar/Ocultar Tabla de Costes</button>
        <div id="contenedor-tabla-costes" class="oculto">
            <?php generarTablaCostesPHP(); ?>
        </div>
    </section>

    <section>
        <h2>Formulario de Solicitud</h2>
        <form action="/daw/practica_dawpr7/respuesta-solicitar-album" method="post">
            <label for="nombre">Nombre y Apellidos:</label>
            <input type="text" id="nombre" name="nombre" maxlength="200" required><br><br>

            <label for="titulo_album">Título del Álbum:</label>
            <input type="text" id="titulo_album" name="titulo_album" maxlength="200" required><br><br>

            <label for="texto_adicional">Texto adicional:</label>
            <textarea id="texto_adicional" name="texto_adicional" rows="4" cols="50" maxlength="4000"></textarea><br><br>

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" maxlength="200" required><br><br>

            <!-- Dirección -->
            <fieldset>
                <legend>Dirección de Envío:</legend>
                <label for="calle">Calle:</label>
                <input type="text" id="calle" name="calle" required><br><br>

                <label for="numero">Número:</label>
                <input type="number" id="numero" name="numero" pattern="\d*" required><br><br>

                <label for="piso">Piso:</label>
                <input type="text" id="piso" name="piso"><br><br>

                <label for="puerta">Puerta:</label>
                <input type="number" id="puerta" name="puerta" pattern="\d*"><br><br>

                <label for="codigo_postal">Código Postal:</label>
                <input type="number" id="codigo_postal" name="codigo_postal" pattern="\d*" required><br><br>

                <label for="localidad">Localidad:</label>
                <select id="localidad" name="localidad" required>
                    <option value="Al">Alicante</option>
                    <option value="Sv">San vicente del Raspeig</option>
                    <option value="Torrevieja">Torrevieja</option>
                </select><br><br>

                <label for="provincia">Provincia:</label>
                <select id="provincia" name="provincia" required>
                    <option value="Al">Alicante</option>
                    <option value="Vlc">Valencia</option>
                    <option value="Castellon">Castellón</option>
                </select><br><br>

                <label for="pais">País:</label>
                <select id="pais" name="pais" required>
                    <option value="Es">España</option>
                    <option value="Fr">Francia</option>
                    <option value="Al">Alemania</option>
                </select><br><br>
            </fieldset>

            <label for="telefono">Teléfono (opcional):</label>
            <input type="tel" id="telefono" name="telefono" pattern="\d*"><br><br>

            <label for="color_portada">Color de la Portada:</label>
            <input type="color" id="color_portada" name="color_portada" value="#000000"><br><br>

            <label for="copias">Número de copias:</label>
            <input type="number" id="copias" name="copias" min="1" max="99" value="1"><br><br>

            <label for="resolucion">Resolución de las Fotos (DPI):</label>
            <input type="range" id="resolucion" name="resolucion" min="150" max="900" step="150" value="150">
            <span id="dpiValue">150 DPI</span><br><br>

            <script>
                const resolucionInput = document.getElementById('resolucion');
                const dpiValueSpan = document.getElementById('dpiValue');
                function updateDPIValue() { dpiValueSpan.textContent = `${resolucionInput.value} DPI`; }
                resolucionInput.addEventListener('input', updateDPIValue);
                updateDPIValue();
            </script>

            <label for="album_usuario">Álbum a Imprimir:</label>
            <select id="album_usuario" name="album_usuario" required>
                <option value="vacaciones">Vacaciones</option>
                <option value="familia">Familia</option>
                <option value="paisajes">Paisajes</option>
            </select><br><br>

            <label for="fecha_recepcion">Fecha de Recepción (opcional):</label>
            <input type="date" id="fecha_recepcion" name="fecha_recepcion"><br><br>

            <label for="color_impresion">Impresión a Color:</label>
            <input type="radio" id="color" name="color_impresion" value="color" checked> Color
            <input type="radio" id="blanco_negro" name="color_impresion" value="blanco_negro"> Blanco y Negro<br><br>

            <button type="submit">Solicitar Álbum</button>
        </form>
    </section>
</div>


<script>
//script para ocultar y mostrar la tabla
document.getElementById('mostrarTabla').addEventListener('click', function() {
    const contenedor = document.getElementById('contenedor-tabla-costes');
    contenedor.classList.toggle('oculto');
});
</script>


<?php 
include __DIR__ . '/../templates/footer.php';
?>
