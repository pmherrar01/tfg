// --- FUNCIONES DE LA FICHA DE PRODUCTO ---

function cambiarFoto(elementoClicado, urlNuevaFoto) {
    let imgPrincipal = document.getElementById('imagenPrincipal');
    
    // 1. Bajamos la opacidad a 0 (Se hace invisible suavemente)
    imgPrincipal.classList.add('oculto-transicion');
    
    // 2. Esperamos un instante (150ms), cambiamos la foto y volvemos a subir la opacidad
    setTimeout(function() {
        imgPrincipal.src = urlNuevaFoto;
        imgPrincipal.classList.remove('oculto-transicion');
    }, 150);

    // 3. Gestión del borde negro en las miniaturas
    let miniaturas = document.querySelectorAll('.miniatura-galeria');
    miniaturas.forEach(function(miniatura) {
        miniatura.classList.remove('borde-activo');
    });
    
    elementoClicado.classList.add('borde-activo');
}

// Función para mover las flechas
function cambiarConFlechas(direccion) {
    // Buscamos qué miniatura tiene el borde negro ahora mismo
    let miniaturaActual = document.querySelector('.miniatura-galeria.borde-activo');
    let nuevaMiniatura;

    if (direccion === 'next') {
        // Intentamos coger la miniatura de su derecha
        nuevaMiniatura = miniaturaActual.nextElementSibling;
        
        // Si no hay más a la derecha, volvemos a la primera del todo (efecto bucle)
        if (!nuevaMiniatura) {
            nuevaMiniatura = document.querySelector('.miniatura-galeria:first-child');
        }
    } else {
        // Intentamos coger la miniatura de su izquierda
        nuevaMiniatura = miniaturaActual.previousElementSibling;
        
        // Si no hay más a la izquierda, vamos a la última del todo
        if (!nuevaMiniatura) {
            nuevaMiniatura = document.querySelector('.miniatura-galeria:last-child');
        }
    }

    // ¡La magia! Simulamos un clic en la miniatura nueva. 
    // Esto hace que se ejecute la transición y el borde negro solos.
    nuevaMiniatura.click();
}