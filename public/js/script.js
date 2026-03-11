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

    let inputColor = document.getElementById('input_color_id');
    if (inputColor) {
        inputColor.value = colorId;
    }

}

document.addEventListener("DOMContentLoaded", function() {
    
    const urlParams = new URLSearchParams(window.location.search);
    const colorIdUrl = urlParams.get('color');

    let colorInicial = null;

    if (colorIdUrl) {
        colorInicial = document.querySelector('.color-swatch-wrapper[data-color-id="' + colorIdUrl + '"]');
    }

    if (!colorInicial) {
        colorInicial = document.querySelector('.color-swatch-wrapper');
    }

    if (colorInicial) {
        seleccionarColor(colorInicial.getAttribute('data-color-id'), colorInicial);
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

    if (typeof mensajeAlerta !== 'undefined' && mensajeAlerta === 'carrito_ok') {
        Swal.fire({
            icon: 'success',
            title: '¡Añadido!',
            text: 'El producto se ha añadido a tu carrito correctamente.',
            confirmButtonColor: 'var(--color-principal, #000)',
            timer: 2000, 
            showConfirmButton: false,
            toast: true, 
            position: 'top-end'
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

// --- LÓGICA DEL BOTÓN ANIMADO ---
if (typeof gsap !== 'undefined' && typeof MorphSVGPlugin !== 'undefined') {
    gsap.registerPlugin(MorphSVGPlugin);

    document.querySelectorAll('.add-to-cart').forEach(button => {
        let morph = button.querySelector('.morph path'),
            shirt = button.querySelectorAll('.shirt svg > path');

        button.addEventListener('click', e => {
            // 1. Paramos el formulario en seco
            e.preventDefault();

            if (button.classList.contains('active')) {
                return;
            }
            button.classList.add('active');

            // 2. Animación de hundir el botón
            gsap.to(button, {
                keyframes: [
                    { '--background-scale': .97, duration: .15 },
                    { '--background-scale': 1, delay: .125, duration: 1.2, ease: 'elastic.out(1, .6)' }
                ]
            });

            // 3. Animación de la camiseta cayendo
            gsap.to(button, {
                keyframes: [
                    { '--shirt-scale': 1, '--shirt-y': '-42px', '--cart-x': '0px', '--cart-scale': 1, duration: .4, ease: 'power1.in' },
                    { '--shirt-y': '-40px', duration: .3 },
                    { '--shirt-y': '16px', '--shirt-scale': .9, duration: .25, ease: 'none' },
                    { '--shirt-scale': 0, duration: .3, ease: 'none' }
                ]
            });

            gsap.to(button, { '--shirt-second-y': '0px', delay: .835, duration: .12 });

            // 4. Animación del carrito y el check verde
            gsap.to(button, {
                keyframes: [
                    { '--cart-clip': '12px', '--cart-clip-x': '3px', delay: .9, duration: .06 },
                    { '--cart-y': '2px', duration: .1 },
                    { '--cart-tick-offset': '0px', '--cart-y': '0px', duration: .2, onComplete() { button.style.overflow = 'hidden'; } },
                    { '--cart-x': '52px', '--cart-rotate': '-15deg', duration: .2 },
                    { '--cart-x': '104px', '--cart-rotate': '0deg', duration: .2, clearProps: true, onComplete() { 
                        button.style.overflow = 'hidden';
                        button.style.setProperty('--text-o', 0);
                        button.style.setProperty('--text-x', '0px');
                        button.style.setProperty('--cart-x', '-104px');
                    }},
                    { '--text-o': 1, '--text-x': '12px', '--cart-x': '-48px', '--cart-scale': .75, duration: .25, clearProps: true, onComplete() { 
                        button.classList.remove('active');
                    }}
                ]
            });

            // Ocultar texto y deformar línea inferior
            gsap.to(button, { keyframes: [{ '--text-o': 0, duration: .3 }] });
            gsap.to(morph, {
                keyframes: [
                    { morphSVG: 'M0 12C6 12 20 10 32 0C43.9024 9.99999 58 12 64 12V13H0V12Z', duration: .25, ease: 'power1.out' },
                    { morphSVG: 'M0 12C6 12 17 12 32 12C47.9024 12 58 12 64 12V13H0V12Z', duration: .15, ease: 'none' }
                ]
            });

            // 5. ENVIAMOS EL FORMULARIO DESPUÉS DE LA ANIMACIÓN (1.8 seg)
            setTimeout(() => {
                button.closest('form').submit(); 
            }, 1800);
        });
    });
}