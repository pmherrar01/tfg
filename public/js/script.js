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

function cambiarConFlechas(direccion) {
    let todasLasMiniaturas = Array.from(document.querySelectorAll('.miniatura-galeria'));
    
    let miniaturasVisibles = todasLasMiniaturas.filter(min => min.style.display !== 'none');
    
    if (miniaturasVisibles.length === 0) return;

    let indexActual = miniaturasVisibles.findIndex(min => min.classList.contains('borde-activo'));
    let nuevoIndex;

    if (direccion === 'next') {
        nuevoIndex = indexActual + 1;
        if (nuevoIndex >= miniaturasVisibles.length) {
            nuevoIndex = 0;
        }
    } else {
        nuevoIndex = indexActual - 1;
        if (nuevoIndex < 0) {
            nuevoIndex = miniaturasVisibles.length - 1;
        }
    }

    miniaturasVisibles[nuevoIndex].click();
}

function seleccionarColor(colorId, elementoClicado) {
    let envolturas = document.querySelectorAll('.color-swatch-wrapper');
    envolturas.forEach(function(env) {
        env.classList.remove('border-dark', 'p-1');
        env.classList.add('border-light');
    });
    elementoClicado.classList.remove('border-light');
    elementoClicado.classList.add('border-dark', 'p-1');

    let miniaturas = document.querySelectorAll('.miniatura-color');
    let primeraVisible = null;

    miniaturas.forEach(function(miniatura) {
        if (miniatura.getAttribute('data-color-id') == colorId) {
            miniatura.style.display = 'block';
            if (!primeraVisible) {
                primeraVisible = miniatura;
            }
        } else {
            miniatura.style.display = 'none';
        }
    });

    // Cambiamos automáticamente la foto grande a la primera del nuevo color
    if (primeraVisible) {
        primeraVisible.click();
    }

    // 3. ACTUALIZAR EL STOCK Y LAS TALLAS DINÁMICAMENTE
    const selectTalla = document.getElementById('talla');
    if(selectTalla && typeof tallasProducto !== 'undefined') {
        
        // Vaciamos el desplegable
        selectTalla.innerHTML = '<option value="" selected disabled>Selecciona tu talla</option>';

        // Filtramos solo las tallas que coincidan con el color clicado
        const tallasDelColor = tallasProducto.filter(t => t.color_id == colorId);

        // Creamos las opciones nuevas según el stock de la base de datos
        tallasDelColor.forEach(tallaObj => {
            let opcion = document.createElement('option');
            opcion.value = tallaObj.talla;
            
            if (tallaObj.stock == 0) {
                opcion.disabled = true;
                opcion.textContent = tallaObj.talla + ' (Agotado)';
            } else if (tallaObj.stock <= 10) {
                opcion.textContent = tallaObj.talla + ' - ¡Rápido, quedan ' + tallaObj.stock + '!';
            } else {
                opcion.textContent = tallaObj.talla;
            }
            
            selectTalla.appendChild(opcion);
        });
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

document.addEventListener("DOMContentLoaded", function() {
    
    if (typeof bienvenidoAlerta !== 'undefined') {
        if (bienvenidoAlerta === 'true') {
            Swal.fire({
                icon: 'success',
                title: '¡Hola, ' + nombreUsuario + '!',
                text: 'Has iniciado sesión correctamente.',
                confirmButtonColor: '#000', 
                timer: 3000,
                showConfirmButton: false
            });
        } else if (bienvenidoAlerta === 'false') {
            Swal.fire({
                icon: 'error',
                title: 'Error de acceso',
                text: 'El email o la contraseña son incorrectos.',
                confirmButtonColor: '#000'
            });
        }
    }

    if (typeof mensajeAlerta !== 'undefined' && mensajeAlerta === 'registro_exito') {
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido a HERROR!',
            text: 'Tu cuenta se ha creado con éxito. Ya puedes iniciar sesión.',
            confirmButtonColor: 'var(--color-principal, #000)'
        });
    }

    if (typeof errorAlerta !== 'undefined') {
        if (errorAlerta === 'registro_fallo') {
            Swal.fire({
                icon: 'error',
                title: 'Ups...',
                text: 'Hubo un problema al registrarte. Es posible que el correo ya esté en uso.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        } else if (errorAlerta === 'password_debil') {
            Swal.fire({
                icon: 'warning',
                title: 'Contraseña poco segura',
                text: 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula y un número.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
    }

    if (typeof errorAlerta !== 'undefined' && errorAlerta === 'registro_fallo') {
        Swal.fire({
            icon: 'error',
            title: 'Ups...',
            text: 'Hubo un problema al registrarte. Es posible que el correo ya esté en uso.',
            confirmButtonColor: '#000'
        });
    }
    if (typeof sesionCerradaAlerta !== 'undefined' && sesionCerradaAlerta === 'true') {
        Swal.fire({
            icon: 'info', // Queda muy elegante para despedidas
            title: '¡Hasta pronto!',
            text: 'Has cerrado sesión correctamente de forma segura.',
            confirmButtonColor: '#000', // Acuérdate de poner tu color
            timer: 3000, // Se cierra sola a los 3 segundos
            showConfirmButton: false
        });
    }

});