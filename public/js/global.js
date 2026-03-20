// Esperamos a que la página cargue entera
document.addEventListener("DOMContentLoaded", function () {

    let promoModalElement = document.getElementById('promoModal');

    if (promoModalElement) {

        let horaLocalStore = localStorage.getItem("tiempoModal");

        let horaActual = Date.now();

        if (!horaLocalStore || (horaActual - horaLocalStore) > 18000000) {

            setTimeout(function () {
                let miModal = new bootstrap.Modal(document.getElementById('promoModal'));

                miModal.show();

                localStorage.setItem('tiempoModal', Date.now());
            }, 3000);
        }



    }

    inicializarBuscadorEnVivo();



    let graciasCompra = document.getElementById("graciasCompra");

    if (graciasCompra) {
        setTimeout(function () {
            window.location.href = "perfil.php?seccion=pedidos";
        }, 4000);
    }

});

//funcion para el ajax de la lupa 
function inicializarBuscadorEnVivo() {
    let prendaABuscar = document.getElementById("inputBuscador");
    let contenedorResultados = document.getElementById("cajaResultados");

    if (prendaABuscar && contenedorResultados) {
        prendaABuscar.addEventListener("input", function () {
            let texto = this.value.trim();

            if (texto.length >= 2) {
                fetch('controllers/apiBuscarController.php?q=' + texto)
                    .then(respuesta => respuesta.json())
                    .then(datos => {
                        if (datos.length > 0) {
                            contenedorResultados.innerHTML = '';

                            datos.forEach(producto => {
                                // TU HTML ORIGINAL Y PERFECTO CON FOTOS DE 50x50
                                let htmlProducto = `
                                    <a href="fichaProducto.php?idPrenda=${producto.id}&color=${producto.color_id}" class="text-decoration-none text-dark d-block p-3 border-bottom bg-white" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='#fff'">
                                        <div class="d-flex align-items-center">
                                            <img src="${producto.url_imagen}" style="width: 50px; height: 50px; object-fit: cover;" class="me-3 border border-dark border-1">
                                            <div>
                                                <span class="fw-bold d-block text-uppercase" style="font-size: 0.9rem;">${producto.nombre}</span>
                                                <span class="text-muted fw-bold">${producto.precio} €</span>
                                            </div>
                                        </div>
                                    </a>
                                `;
                                contenedorResultados.innerHTML += htmlProducto;
                            });

                            contenedorResultados.classList.remove('d-none');
                        } else {
                            contenedorResultados.innerHTML = '<div class="p-4 text-center text-muted fw-bold text-uppercase">No se encontraron prendas</div>';
                        }
                    });

            } else {
                contenedorResultados.innerHTML = "";
                contenedorResultados.classList.add('d-none');
            }
        });
    }
}
// ==========================================
// ALERTAS SWEETALERT (ULTRALIGERAS Y FLUIDAS)
// ==========================================
document.addEventListener("DOMContentLoaded", function () {

    // Configuración maestra para que TODAS las alertas vuelen y no den lag
    const swalRapido = Swal.mixin({
        confirmButtonColor: 'var(--color-principal, #000)',
        showClass: {
            popup: 'animate__animated animate__fadeIn animate__faster' // Aparece por opacidad (GPU), no por tamaño (CPU)
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut animate__faster'
        }
    });

    // 1. ALERTAS DE ÉXITO O INFORMACIÓN (mensajeAlerta)
    if (typeof mensajeAlerta !== 'undefined') {
        if (mensajeAlerta === 'registro_exito') {
            swalRapido.fire({
                icon: 'success',
                title: '¡Bienvenido a HERROR!',
                text: 'Tu cuenta se ha creado con éxito. Ya puedes iniciar sesión.'
            });
        }
        else if (mensajeAlerta === 'login_requerido') {
            swalRapido.fire({
                icon: 'info',
                title: '¡Inicia Sesión!',
                text: 'Por favor, debes iniciar sesión o registrarte antes de poder tramitar tu pedido.'
            });
        }
    }

    // 2. ALERTAS DE ERRORES (errorAlerta)
    if (typeof errorAlerta !== 'undefined') {
        if (errorAlerta === 'registro_fallo') {
            swalRapido.fire({
                icon: 'error',
                title: 'Ups...',
                text: 'Hubo un problema al registrarte. Es posible que el correo ya esté en uso.'
            });
        }
        else if (errorAlerta === 'password_debil') {
            swalRapido.fire({
                icon: 'warning',
                title: 'Contraseña poco segura',
                text: 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula y un número.'
            });
        }
        else if (errorAlerta === 'falta_talla') {
            swalRapido.fire({
                icon: 'warning',
                title: '¡Falta la talla!',
                text: 'Por favor, selecciona una talla antes de añadir al carrito.'
            });
        }
        else if (errorAlerta === 'no_stock') {
            swalRapido.fire({
                icon: 'error',
                title: 'Límite de stock',
                text: 'No hay más unidades disponibles de este artículo en tu talla y color.'
            });
        }
    }

    // Alerta Especial: Bienvenida Login
    if (typeof bienvenidoAlerta !== 'undefined') {
        if (bienvenidoAlerta === 'true') {
            swalRapido.fire({
                icon: 'success',
                title: '¡Hola, ' + nombreUsuario + '!',
                text: 'Has iniciado sesión correctamente.',
                timer: 3000,
                showConfirmButton: false
            });
        } else if (bienvenidoAlerta === 'false') {
            swalRapido.fire({
                icon: 'error',
                title: 'Error de acceso',
                text: 'El email o la contraseña son incorrectos.'
            });
        }
    }

    // Alerta Especial: Sesión Cerrada
    if (typeof sesionCerradaAlerta !== 'undefined' && sesionCerradaAlerta === 'true') {
        swalRapido.fire({
            icon: 'info',
            title: '¡Hasta pronto!',
            text: 'Has cerrado sesión correctamente de forma segura.',
            timer: 3000,
            showConfirmButton: false
        });
    }
});
document.addEventListener("DOMContentLoaded", function () {
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


document.addEventListener("DOMContentLoaded", function () {

    document.addEventListener('click', function (e) {
        let botonClick = e.target.closest('.btn-toggle-favorito');

        if (botonClick) {
            e.preventDefault();

            let idPrenda = botonClick.getAttribute('data-id');
            let idColor = botonClick.getAttribute('data-color');

            let icono = botonClick.querySelector('i');

            let datos = new FormData();

            datos.append('idPrenda', idPrenda);
            datos.append('idColor', idColor);

            fetch('controllers/apiFavoritosController.php', {
                method: 'POST',
                body: datos
            })
                .then(respuesta => respuesta.json())
                .then(datos => {

                    if (datos.exito === false && datos.mensaje === 'noLogin') {
                        Swal.fire({
                            icon: 'info',
                            title: '¡Inicia Sesión!',
                            text: 'Debes iniciar sesión para guardar tus prendas favoritas.',
                            confirmButtonColor: 'var(--color-principal, #000)'
                        });
                        return;
                    }

                    if (datos.exito === true) {

                        let comboMemoria = idPrenda + '-' + idColor;

                        if (datos.accion === 'agregado') {
                            icono.classList.remove('bi-heart');
                            icono.classList.add('bi-heart-fill');

                            if (typeof listaFavoritosJS !== 'undefined') {
                                listaFavoritosJS.push(comboMemoria);
                            }
                        }
                        else if (datos.accion === 'eliminado') {

                            icono.classList.remove('bi-heart-fill');
                            icono.classList.add('bi-heart');

                            if (typeof listaFavoritosJS !== 'undefined') {
                                let indice = listaFavoritosJS.indexOf(comboMemoria);
                                if (indice !== -1) {
                                    listaFavoritosJS.splice(indice, 1);
                                }
                            }

                            if (window.location.href.includes('seccion=favoritos')) {

                                let columnaContenedora = botonClick.closest('.col-6');

                                if (columnaContenedora) {
                                    columnaContenedora.style.transition = 'all 0.6s ease';
                                    columnaContenedora.style.opacity = '0';
                                    columnaContenedora.style.transform = 'scale(0.5) translateY(50px)';

                                    setTimeout(function () {
                                        columnaContenedora.remove();
                                        let prendasRestantes = document.querySelectorAll('.btn-toggle-favorito').length;

                                        if (prendasRestantes === 0) {
                                            window.location.reload();
                                        }
                                    }, 600);
                                }
                            }
                        }
                    }

                })
                .catch(error => {
                    console.error("Error en la petición:", error);
                });

        }

    });
});


// ==========================================
// PINTAR PRENDAS RECIENTES (Footer)
// ==========================================
function pintarPrendasRecientes() {
    const carruselInner = document.getElementById('carruselRecientesInner');
    const seccionRecientes = document.getElementById('seccionRecientes');

    if (!carruselInner || !seccionRecientes) return;

    let aPrendasRecientes = [];
    let datosLocal = localStorage.getItem('prendasRecientes');

    if (datosLocal) {
        try {
            aPrendasRecientes = JSON.parse(datosLocal);
        } catch (error) {
            aPrendasRecientes = [];
        }
    }

    if (aPrendasRecientes.length > 0) {
        seccionRecientes.classList.remove("d-none");
    } else {
        return;
    }

    carruselInner.innerHTML = "";
    let htmlAcumulado = "";

    aPrendasRecientes.forEach((prenda, index) => {

        if (index % 4 === 0) {
            let activeClass = index === 0 ? "active" : "";
            htmlAcumulado += `<div class="carousel-item ${activeClass}" data-bs-interval="3000"><div class="row">`;
        }

        let iconoCorazon = 'bi-heart';
        if (typeof listaFavoritosJS !== 'undefined' && listaFavoritosJS.includes(prenda.id + '-' + prenda.colorPrenda)) {
            iconoCorazon = 'bi-heart-fill';
        }

        // HTML ORIGINAL LIMPIO (Sin sufijos raros)
        htmlAcumulado += `
            <div class="col-6 col-md-3 position-relative d-flex flex-column mb-4">
                <div class="card product-card border-0 bg-transparent position-relative">
                    
                    <div class="img-wrapper position-relative overflow-hidden">
                        <a href="fichaProducto.php?idPrenda=${prenda.id}&color=${prenda.colorPrenda}" class="text-decoration-none text-dark d-block">
                            <img src="${prenda.imagen}" class="card-img-top rounded-0" alt="${prenda.nombre}" style="height: 380px; object-fit: cover;">
                        </a>
                        
                        <div id="overlay-tallas-${prenda.id}" class="overlay-tallas d-none position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-75 p-3 text-center">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-bold text-uppercase" style="letter-spacing: 1px;">Selecciona Talla</span>
                                <button type="button" class="btn-close" style="font-size: 0.7rem;" onclick="cerrarOverlayTallas(event, ${prenda.id})"></button>
                            </div>
                            <div id="contenedor-botones-${prenda.id}" class="d-flex justify-content-center flex-wrap gap-2">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body text-center px-0 pb-1 mt-2">
                        <a href="fichaProducto.php?idPrenda=${prenda.id}&color=${prenda.colorPrenda}" class="text-decoration-none text-dark d-block">
                            <h5 class="card-title text-uppercase fw-bold fs-6 mb-1 text-truncate">${prenda.nombre}</h5>
                            <p class="card-text mb-0">${prenda.precio} €</p>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-2 mt-2 px-1">
                    <button type="button" class="btn btn-principal rounded-0 flex-grow-1 text-uppercase fw-bold" 
                            style="height: 40px; font-size: 0.75rem; letter-spacing: 1px;"
                            onclick="abrirOverlayTallas(event, ${prenda.id}, ${prenda.colorPrenda})">
                        Añadir <i class="bi bi-plus-lg ms-1"></i>
                    </button>

<button type="button" class="btn btn-toggle-favorito btn-favorito-custom btn-favorito-std d-flex justify-content-center align-items-center rounded-0" 
        data-id="${prenda.id}" 
        data-color="${prenda.colorPrenda}">
    <i class="bi ${iconoCorazon}"></i>
</button>
                </div>
            </div>
        `;

        if (index % 4 === 3 || index === aPrendasRecientes.length - 1) {
            htmlAcumulado += `</div></div>`;
        }
    });

    carruselInner.innerHTML = htmlAcumulado;
}


// ==========================================
// AÑADIR RÁPIDO (QUICK ADD) - MENÚ TALLAS (VERSIÓN LIMPIA)
// ==========================================

function abrirOverlayTallas(event, idPrenda, idColor) {
    event.preventDefault();

    // MAGIA INVISIBLE: Buscamos la tarjeta exacta donde se ha hecho clic
    const tarjeta = event.target.closest('.col-6');
    if (!tarjeta) return;

    // Buscamos el menú oculto DENTRO de esa tarjeta exacta
    const overlay = tarjeta.querySelector('.overlay-tallas');
    const contenedor = tarjeta.querySelector('[id^="contenedor-botones-"]');

    if (!overlay || !contenedor) return;

    overlay.classList.remove('d-none');
    contenedor.innerHTML = '<span class="small fw-bold text-muted mt-2">Cargando tallas...</span>';

    fetch(`controllers/apiTallasController.php?idPrenda=${idPrenda}&idColor=${idColor}`)
        .then(respuesta => respuesta.json())
        .then(tallas => {
            contenedor.innerHTML = '';

            if (tallas.length === 0) {
                contenedor.innerHTML = '<span class="small text-danger fw-bold mt-2">Agotado</span>';
                return;
            }

            tallas.forEach(tallaObj => {
                let btn = document.createElement('button');
                btn.className = 'btn btn-outline-dark rounded-0 px-3 py-1 fw-bold';
                btn.textContent = tallaObj.talla;

                if (tallaObj.stock <= 0) {
                    btn.classList.add('talla-agotada');
                    btn.disabled = true;
                } else {
                    btn.onclick = (e) => anadirDirectoCarrito(e, idPrenda, idColor, tallaObj.talla);
                }

                contenedor.appendChild(btn);
            });
        })
        .catch(error => {
            console.error("Error cargando tallas:", error);
            contenedor.innerHTML = '<span class="small text-danger mt-2">Error al cargar</span>';
        });
}

function cerrarOverlayTallas(event, idPrenda) {
    event.preventDefault();
    const tarjeta = event.target.closest('.col-6');
    if (tarjeta) {
        const overlay = tarjeta.querySelector('.overlay-tallas');
        if (overlay) overlay.classList.add('d-none');
    }
}

function anadirDirectoCarrito(event, idPrenda, idColor, talla) {
    event.preventDefault();

    let datos = new FormData();
    datos.append('accion', 'agregar');
    datos.append('idPrenda', idPrenda);
    datos.append('color_id', idColor);
    datos.append('talla', talla);

    // Cierra el menú de la tarjeta correcta
    cerrarOverlayTallas(event, idPrenda);

    fetch('controllers/carritoController.php', {
        method: 'POST',
        body: datos
    })
        .then(respuesta => {
            Swal.fire({
                icon: 'success',
                title: '¡Añadido al carrito!',
                text: `Talla ${talla} añadida correctamente.`,
                confirmButtonColor: 'var(--color-principal, #000)',
                timer: 2000,
                showConfirmButton: false,
                // --- OPTIMIZACIÓN DE RENDIMIENTO ---
                showClass: {
                    popup: 'animate__animated animate__fadeIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut animate__faster'
                }
            });
        })
        .catch(error => {
            console.error("Error al añadir:", error);
            Swal.fire({
                icon: 'error',
                title: 'Ups...',
                text: 'Hubo un problema al añadir la prenda.',
                confirmButtonColor: 'var(--color-principal, #000)',
                // --- OPTIMIZACIÓN DE RENDIMIENTO ---
                showClass: {
                    popup: 'animate__animated animate__fadeIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut animate__faster'
                }
            });
        });
}

document.addEventListener("DOMContentLoaded", function () {
    // Encendemos el carrusel de prendas recientes
    pintarPrendasRecientes();
});