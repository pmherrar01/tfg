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

// --- NUEVA FUNCIÓN: FILTRAR GALERÍA POR COLOR ---
function seleccionarColor(colorId, elementoClicado) {
    // 1. Cambiar el borde negro al color seleccionado
    let envolturas = document.querySelectorAll('.color-swatch-wrapper');
    envolturas.forEach(function(env) {
        env.classList.remove('border-dark', 'p-1');
        env.classList.add('border-secondary');
    });
    elementoClicado.classList.remove('border-secondary');
    elementoClicado.classList.add('border-dark', 'p-1');

    // 2. Filtrar las miniaturas de la galería
    let miniaturas = document.querySelectorAll('.miniatura-color');
    let primeraVisible = null;

    miniaturas.forEach(function(miniatura) {
        // Si la foto tiene el mismo ID de color que hemos clicado, la mostramos
        if (miniatura.getAttribute('data-color-id') == colorId) {
            miniatura.style.display = 'block';
            if (!primeraVisible) {
                primeraVisible = miniatura; // Guardamos la primera que coincida
            }
        } else {
            // Si es de otro color, la ocultamos
            miniatura.style.display = 'none';
        }
    });

    // 3. Autoclic en la primera foto del nuevo color para que cambie la foto grande
    if (primeraVisible) {
        primeraVisible.click();
    }
}

// 4. Autoejecutar al cargar la página para que filtre el primer color
document.addEventListener("DOMContentLoaded", function() {
    let primerColor = document.querySelector('.color-swatch-wrapper');
    if(primerColor) {
        // Simulamos un clic en el primer color al entrar a la ficha
        seleccionarColor(primerColor.getAttribute('data-color-id'), primerColor);
    }
});


document.addEventListener("DOMContentLoaded", function() {
    const sliderMin = document.getElementById("slider-min");
    const sliderMax = document.getElementById("slider-max");
    const minVal = document.getElementById("precio-min-val");
    const maxVal = document.getElementById("precio-max-val");
    const sliderTrack = document.querySelector(".slider-track");

    if(!sliderMin || !sliderMax) return;

    // Diferencia mínima de 1€ para que actúe como un "muro" y no se crucen
    const gap = 1; 

    function updateSliderTrack() {
        const min = parseInt(sliderMin.value);
        const max = parseInt(sliderMax.value);
        const minAttr = parseInt(sliderMin.min);
        const maxAttr = parseInt(sliderMax.max);
        
        const percent1 = ((min - minAttr) / (maxAttr - minAttr)) * 100;
        const percent2 = ((max - minAttr) / (maxAttr - minAttr)) * 100;
        
        sliderTrack.style.left = percent1 + "%";
        sliderTrack.style.width = (percent2 - percent1) + "%";
    }

    // Actualización en tiempo real al mover el Mínimo
    sliderMin.addEventListener("input", function() {
        if (parseInt(sliderMax.value) - parseInt(sliderMin.value) <= gap) {
            sliderMin.value = parseInt(sliderMax.value) - gap;
        }
        minVal.textContent = sliderMin.value;
        updateSliderTrack();
    });

    // Actualización en tiempo real al mover el Máximo
    sliderMax.addEventListener("input", function() {
        if (parseInt(sliderMax.value) - parseInt(sliderMin.value) <= gap) {
            sliderMax.value = parseInt(sliderMin.value) + gap;
        }
        maxVal.textContent = sliderMax.value;
        updateSliderTrack();
    });

    // Pintar la barra inicial
    updateSliderTrack();
});

function aplicarFiltroPrecio() {
    let min = document.getElementById("slider-min").value;
    let max = document.getElementById("slider-max").value;
    
    // Capturar orden actual de la URL para no perderlo
    let urlParams = new URLSearchParams(window.location.search);
    let orden = urlParams.get('orden');
    
    let urlDestino = '?precioMin=' + min + '&precioMax=' + max;
    if (orden) {
        urlDestino += '&orden=' + orden;
    }
    
    window.location.href = urlDestino;
}

// --- LÓGICA DEL SLIDER DE PRECIOS DOBLE ---
document.addEventListener("DOMContentLoaded", function() {
    let sliderMin = document.getElementById("slider-min");
    let sliderMax = document.getElementById("slider-max");
    let displayMin = document.getElementById("precio-min-val");
    let displayMax = document.getElementById("precio-max-val");

    if (sliderMin && sliderMax) {
        let minGap = 1; // Distancia mínima entre ambos selectores (1€)

        function controlarSliders(event) {
            let valorMin = parseInt(sliderMin.value);
            let valorMax = parseInt(sliderMax.value);

            // Si intentan cruzarse, los bloqueamos
            if (valorMax - valorMin <= minGap) {
                if (event.target === sliderMin) {
                    sliderMin.value = valorMax - minGap;
                    valorMin = sliderMin.value;
                } else {
                    sliderMax.value = valorMin + minGap;
                    valorMax = sliderMax.value;
                }
            }

            // Actualizar los numeritos de abajo en tiempo real
            displayMin.textContent = valorMin;
            displayMax.textContent = valorMax;
        }

        // Escuchar cuando el usuario arrastra las barras
        sliderMin.addEventListener("input", controlarSliders);
        sliderMax.addEventListener("input", controlarSliders);
    }
});

document.addEventListener("DOMContentLoaded", function() {
    // Lógica para la animación del Modal de Login/Registro
    const animContainer = document.querySelector('.anim-container');
    const loginLink = document.querySelector('.SignInLink');
    const registerLink = document.querySelector('.SignUpLink');

    if (registerLink && loginLink && animContainer) {
        registerLink.addEventListener('click', (e) => {
            e.preventDefault();
            animContainer.classList.add('active');
        });

        loginLink.addEventListener('click', (e) => {
            e.preventDefault();
            animContainer.classList.remove('active');
        });
    }
});