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

document.addEventListener("DOMContentLoaded", function() {
    const sliderMin = document.getElementById("slider-min");
    const sliderMax = document.getElementById("slider-max");
    const minVal = document.getElementById("precio-min-val");
    const maxVal = document.getElementById("precio-max-val");
    const sliderTrack = document.querySelector(".slider-track");

    // Si no estamos en la página del catálogo, no hacemos nada
    if(!sliderMin || !sliderMax) return;

    const gap = 5; // Diferencia mínima en € para que no se choquen los tiradores

    function updateSliderTrack() {
        const min = parseInt(sliderMin.value);
        const max = parseInt(sliderMax.value);
        const minAttr = parseInt(sliderMin.min);
        const maxAttr = parseInt(sliderMax.max);
        
        // Calculamos los porcentajes para pintar la barra a color del medio
        const percent1 = ((min - minAttr) / (maxAttr - minAttr)) * 100;
        const percent2 = ((max - minAttr) / (maxAttr - minAttr)) * 100;
        
        sliderTrack.style.left = percent1 + "%";
        sliderTrack.style.width = (percent2 - percent1) + "%";
    }

    // Cuando movemos el tirador izquierdo (Mínimo)
    sliderMin.addEventListener("input", function() {
        if (parseInt(sliderMax.value) - parseInt(sliderMin.value) <= gap) {
            sliderMin.value = parseInt(sliderMax.value) - gap;
        }
        minVal.textContent = sliderMin.value;
        updateSliderTrack();
    });

    // Cuando movemos el tirador derecho (Máximo)
    sliderMax.addEventListener("input", function() {
        if (parseInt(sliderMax.value) - parseInt(sliderMin.value) <= gap) {
            sliderMax.value = parseInt(sliderMin.value) + gap;
        }
        maxVal.textContent = sliderMax.value;
        updateSliderTrack();
    });

    // Pintar la barra inicial al cargar
    updateSliderTrack();
});

// Esta función se ejecuta al darle al botón "Aplicar Filtro"
function aplicarFiltroPrecio() {
    const min = document.getElementById("slider-min").value;
    const max = document.getElementById("slider-max").value;
    // Recargamos la página pasando los precios por URL
    window.location.href = `catalogo.php?precio_min=${min}&precio_max=${max}`;
}