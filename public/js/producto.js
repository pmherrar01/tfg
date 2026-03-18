// --- FUNCIONES DE LA FICHA DE PRODUCTO ---

function cambiarFoto(elementoClicado, urlNuevaFoto) {
    let imgPrincipal = document.getElementById('imagenPrincipal');

    // 1. Bajamos la opacidad a 0 (Se hace invisible suavemente)
    imgPrincipal.classList.add('oculto-transicion');

    // 2. Esperamos un instante (150ms), cambiamos la foto y volvemos a subir la opacidad
    setTimeout(function () {
        imgPrincipal.src = urlNuevaFoto;
        imgPrincipal.classList.remove('oculto-transicion');
    }, 150);

    // 3. Gestión del borde negro en las miniaturas
    let miniaturas = document.querySelectorAll('.miniatura-galeria');
    miniaturas.forEach(function (miniatura) {
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
    envolturas.forEach(function (env) {
        env.classList.remove('border-dark', 'p-1');
        env.classList.add('border-light');
    });
    elementoClicado.classList.remove('border-light');
    elementoClicado.classList.add('border-dark', 'p-1');

    let miniaturas = document.querySelectorAll('.miniatura-color');
    let primeraVisible = null;

    miniaturas.forEach(function (miniatura) {
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
    if (selectTalla && typeof tallasProducto !== 'undefined') {

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

    // ==========================================
    // MAGIA PARA EL BOTÓN DE FAVORITOS
    // ==========================================
    
    // 1. Buscamos el botón de favoritos en el HTML
    let btnFav = document.getElementById('btn-favorito-ficha');
    
    if (btnFav) {
        // 2. Le cambiamos el "data-color" fantasma por el color que acabas de clicar
        btnFav.setAttribute('data-color', colorId);

        // 3. Comprobamos si este nuevo color está en la memoria de favoritos
        let idPrenda = btnFav.getAttribute('data-id');
        let comboActual = idPrenda + '-' + colorId; // Ej: "1-3"
        let iconoCorazon = btnFav.querySelector('i');
        
        if (typeof listaFavoritosJS !== 'undefined') {
            if (listaFavoritosJS.includes(comboActual)) {
                // Si es favorito, rellenamos el corazón
                iconoCorazon.classList.remove('bi-heart');
                iconoCorazon.classList.add('bi-heart-fill');
            } else {
                // Si no es favorito, vaciamos el corazón
                iconoCorazon.classList.remove('bi-heart-fill');
                iconoCorazon.classList.add('bi-heart');
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {

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

// --- LÓGICA DEL BOTÓN ANIMADO ---
if (typeof gsap !== 'undefined' && typeof MorphSVGPlugin !== 'undefined') {
    gsap.registerPlugin(MorphSVGPlugin);

    document.querySelectorAll('.add-to-cart').forEach(button => {
        let morph = button.querySelector('.morph path'),
            shirt = button.querySelectorAll('.shirt svg > path');

        button.addEventListener('pointerdown', e => {
            if (button.classList.contains('active')) { return; }
            gsap.to(button, { '--background-scale': .97, duration: .15 });
        });

        button.addEventListener('click', e => {
            e.preventDefault();

            let selectTalla = button.closest('form').querySelector('#talla');
            if (selectTalla && (!selectTalla.value || selectTalla.value === "")) {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Falta la talla!',
                    text: 'Por favor, selecciona una talla antes de añadir al carrito.',
                    confirmButtonColor: 'var(--color-principal, #000)'
                });
                return; // Cortamos la ejecución de la función, la animación NO empieza
            }

            if (button.classList.contains('active')) { return; }
            button.classList.add('active');

            gsap.to(button, {
                keyframes: [{
                    '--background-scale': .97, duration: .15
                }, {
                    '--background-scale': 1, delay: .125, duration: 1.2, ease: 'elastic.out(1, .6)'
                }]
            });

            gsap.to(button, {
                keyframes: [{
                    '--shirt-scale': 1, '--shirt-y': '-42px', '--cart-x': '0px', '--cart-scale': 1, duration: .4, ease: 'power1.in'
                }, {
                    '--shirt-y': '-40px', duration: .3
                }, {
                    '--shirt-y': '16px', '--shirt-scale': .9, duration: .25, ease: 'none'
                }, {
                    '--shirt-scale': 0, duration: .3, ease: 'none'
                }]
            });

            gsap.to(button, { '--shirt-second-y': '0px', delay: .835, duration: .12 });

            gsap.to(button, {
                keyframes: [{
                    '--cart-clip': '12px', '--cart-clip-x': '3px', delay: .9, duration: .06
                }, {
                    '--cart-y': '2px', duration: .1
                }, {
                    '--cart-tick-offset': '0px', '--cart-y': '0px', duration: .2, onComplete() { button.style.overflow = 'hidden'; }
                }, {
                    '--cart-x': '52px', '--cart-rotate': '-15deg', duration: .2
                }, {
                    // AQUÍ ESTÁ EL TRUCO PARA EL BOTÓN ANCHO: movemos el carrito 200px para que desaparezca seguro
                    '--cart-x': '200px', '--cart-rotate': '0deg', duration: .3, clearProps: true, onComplete() {
                        button.style.overflow = 'hidden';
                        button.style.setProperty('--text-o', 0);
                        button.style.setProperty('--text-x', '0px');
                        button.style.setProperty('--cart-x', '-160px');
                    }
                }, {
                    '--text-o': 1, '--text-x': '20px', '--cart-x': '-100px', '--cart-scale': .75, duration: .25, clearProps: true, onComplete() {
                        button.classList.remove('active');
                    }
                }]
            });

            gsap.to(button, { keyframes: [{ '--text-o': 0, duration: .3 }] });

            gsap.to(morph, {
                keyframes: [{
                    morphSVG: 'M0 12C6 12 20 10 32 0C43.9024 9.99999 58 12 64 12V13H0V12Z', duration: .25, ease: 'power1.out'
                }, {
                    morphSVG: 'M0 12C6 12 17 12 32 12C47.9024 12 58 12 64 12V13H0V12Z', duration: .15, ease: 'none'
                }]
            });

            gsap.to(shirt, {
                keyframes: [{
                    morphSVG: 'M4.99997 3L8.99997 1.5C8.99997 1.5 10.6901 3 12 3C13.3098 3 15 1.5 15 1.5L19 3L23.5 8L20.5 11L19 9.5L18 22.5C18 22.5 14 21.5 12 21.5C10 21.5 5.99997 22.5 5.99997 22.5L4.99997 9.5L3.5 11L0.5 8L4.99997 3Z', duration: .25, delay: .25
                }, {
                    morphSVG: 'M4.99997 3L8.99997 1.5C8.99997 1.5 10.6901 3 12 3C13.3098 3 15 1.5 15 1.5L19 3L23.5 8L20.5 11L19 9.5L18.5 22.5C18.5 22.5 13.5 22.5 12 22.5C10.5 22.5 5.5 22.5 5.5 22.5L4.99997 9.5L3.5 11L0.5 8L4.99997 3Z', duration: .85, ease: 'elastic.out(1, .5)'
                }, {
                    morphSVG: 'M4.99997 3L8.99997 1.5C8.99997 1.5 10.6901 3 12 3C13.3098 3 15 1.5 15 1.5L19 3L22.5 8L19.5 10.5L19 9.5L17.1781 18.6093C17.062 19.1901 16.778 19.7249 16.3351 20.1181C15.4265 20.925 13.7133 22.3147 12 23C10.2868 22.3147 8.57355 20.925 7.66487 20.1181C7.22198 19.7249 6.93798 19.1901 6.82183 18.6093L4.99997 9.5L4.5 10.5L1.5 8L4.99997 3Z', duration: 0, delay: 1.25
                }]
            });

            // Enviar formulario tras la magia
            setTimeout(() => {
                button.closest('form').submit();
            }, 1900);
        });
    });
}