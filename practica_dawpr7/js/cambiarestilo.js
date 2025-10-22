// Función para cambiar el estilo seleccionado y guardar la preferencia en una cookie
function cambiarEstilo() {
    // Obtiene el elemento <select> donde el usuario selecciona el estilo
    const styleSwitcher = document.getElementById("styleSwitcher");
    // Obtiene el valor del estilo seleccionado en el <select>
    const selectedStyle = styleSwitcher.value;
    
    // Llama a la función aplicarEstilo para aplicar el estilo seleccionado
    aplicarEstilo(selectedStyle);
    
    // Crea una cookie llamada "estilo" con el valor del estilo seleccionado
    // La cookie tendrá una duración de 45 días y estará disponible en todas las rutas del sitio
    document.cookie = `estilo=${selectedStyle}; max-age=${45 * 24 * 60 * 60}; path=/`; // 45 días de duración
}

// Función para aplicar un estilo alternativo sin deshabilitar el estilo principal
function aplicarEstilo(archivoEstilo) {
    // Selecciona todos los enlaces <link> que tienen el atributo 'rel="alternate stylesheet"',
    // es decir, los estilos alternativos que pueden ser seleccionados
    const enlacesEstilos = document.querySelectorAll('link[rel="alternate stylesheet"]');
    
    // Recorre todos los enlaces de estilos alternativos
    enlacesEstilos.forEach(link => {
        // Deshabilita temporalmente todos los estilos alternativos
        link.disabled = true;
        
        // Comprueba si el href del enlace contiene el nombre del archivo de estilo seleccionado
        if (link.href.includes(archivoEstilo)) {
            // Si es el archivo de estilo que se seleccionó, lo habilita
            link.disabled = false;
        }
    });
}

// Función para obtener el valor de una cookie por su nombre
function obtenerCookie(nombre) {
    // Añade un "; " al principio del documento de cookies para facilitar la búsqueda de la cookie específica
    const valor = `; ${document.cookie}`;
    // Divide el string de cookies en partes utilizando el nombre de la cookie para encontrar el valor deseado
    const partes = valor.split(`; ${nombre}=`);
    // Si la cookie existe, la devuelve; de lo contrario, devuelve undefined
    if (partes.length === 2) return partes.pop().split(';').shift();
}

// Al cargar la página, aplicar el estilo guardado en la cookie (si existe)
document.addEventListener("DOMContentLoaded", () => {
    // Obtiene el valor de la cookie "estilo" si existe
    const estiloGuardado = obtenerCookie("estilo");
    
    // Si la cookie existe y tiene un valor, aplica ese estilo
    if (estiloGuardado) {
        aplicarEstilo(estiloGuardado);
        
        // Actualiza el valor del selector <select> para mostrar el estilo guardado como seleccionado
        document.getElementById("styleSwitcher").value = estiloGuardado;
    } else {
        // Si no existe la cookie, aplica el estilo predeterminado
        aplicarEstilo("style.css"); // Aplica el estilo predeterminado si no hay cookie
    }
});
