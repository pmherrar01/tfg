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

                            console.log(datos)
                            contenedorResultados.innerHTML = '';
                            datos.forEach(producto => {

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
                            }
                            );

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
// ALERTAS SWEETALERT (LIMPIAS Y UNIFICADAS)
// ==========================================
document.addEventListener("DOMContentLoaded", function () {

    // 1. ALERTAS DE ÉXITO O INFORMACIÓN (mensajeAlerta)
    if (typeof mensajeAlerta !== 'undefined') {

        if (mensajeAlerta === 'registro_exito') {
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido a HERROR!',
                text: 'Tu cuenta se ha creado con éxito. Ya puedes iniciar sesión.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
        else if (mensajeAlerta === 'login_requerido') {
            Swal.fire({
                icon: 'info',
                title: '¡Inicia Sesión!',
                text: 'Por favor, debes iniciar sesión o registrarte antes de poder tramitar tu pedido.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
    }

    // 2. ALERTAS DE ERRORES (errorAlerta)
    if (typeof errorAlerta !== 'undefined') {

        if (errorAlerta === 'registro_fallo') {
            Swal.fire({
                icon: 'error',
                title: 'Ups...',
                text: 'Hubo un problema al registrarte. Es posible que el correo ya esté en uso.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
        else if (errorAlerta === 'password_debil') {
            Swal.fire({
                icon: 'warning',
                title: 'Contraseña poco segura',
                text: 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula y un número.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
        else if (errorAlerta === 'falta_talla') {
            Swal.fire({
                icon: 'warning',
                title: '¡Falta la talla!',
                text: 'Por favor, selecciona una talla antes de añadir al carrito.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
        else if (errorAlerta === 'no_stock') {
            Swal.fire({
                icon: 'error',
                title: 'Límite de stock',
                text: 'No hay más unidades disponibles de este artículo en tu talla y color.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
    }

    // Alerta Especial: Bienvenida Login
    if (typeof bienvenidoAlerta !== 'undefined') {
        if (bienvenidoAlerta === 'true') {
            Swal.fire({
                icon: 'success',
                title: '¡Hola, ' + nombreUsuario + '!',
                text: 'Has iniciado sesión correctamente.',
                confirmButtonColor: 'var(--color-principal, #000)',
                timer: 3000,
                showConfirmButton: false
            });
        } else if (bienvenidoAlerta === 'false') {
            Swal.fire({
                icon: 'error',
                title: 'Error de acceso',
                text: 'El email o la contraseña son incorrectos.',
                confirmButtonColor: 'var(--color-principal, #000)'
            });
        }
    }

    // Alerta Especial: Sesión Cerrada
    if (typeof sesionCerradaAlerta !== 'undefined' && sesionCerradaAlerta === 'true') {
        Swal.fire({
            icon: 'info',
            title: '¡Hasta pronto!',
            text: 'Has cerrado sesión correctamente de forma segura.',
            confirmButtonColor: 'var(--color-principal, #000)',
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


function pintarPrendasRecientes() {
    const carruselInner = document.getElementById('carruselRecientesInner');
    const seccionRecientes = document.getElementById('seccionRecientes');

    if(!carruselInner || !seccionRecientes) return;

    let aPrendasRecientes = [];
    let datosLocal = localStorage.getItem('prendasRecientes');

    if(datosLocal){
        try {
            aPrendasRecientes = JSON.parse(datosLocal);
        } catch (error) {
            aPrendasRecientes = [];
        }
    }

    if(aPrendasRecientes.length > 0){
        seccionRecientes.classList.remove("d-none");
    } else {
        return;
    }

    carruselInner.innerHTML = "";
    let htmlAcumulado = "";

    aPrendasRecientes.forEach((prenda, index) => {
        
        if (index % 4 === 0) {
            let activeClass = index === 0 ? "active" : ""; // Solo el primer grupo lleva la clase 'active'
            htmlAcumulado += `<div class="carousel-item ${activeClass}" data-bs-interval="3000"><div class="row">`;
        }

        htmlAcumulado += `
            <div class="col-6 col-md-3 position-relative">
                <a href="fichaProducto.php?idPrenda=${prenda.id}&color=${prenda.color}" class="text-decoration-none text-dark">
                    <div class="card product-card border-0 bg-transparent">
                        <div class="img-wrapper">
                            <img src="${prenda.imagen}" class="card-img-top" alt="${prenda.nombre}" style="height: 380px; object-fit: cover;">
                        </div>
                        <div class="card-body text-center px-0">
                            <h5 class="card-title text-uppercase fw-bold fs-6 mt-2 mb-1 text-truncate">${prenda.nombre}</h5>
                            <p class="card-text">${prenda.precio} €</p>
                        </div>
                    </div>
                </a>
            </div>
        `;

        if (index % 4 === 3 || index === aPrendasRecientes.length - 1) {
            htmlAcumulado += `</div></div>`;
        }
    });

    carruselInner.innerHTML = htmlAcumulado;
}

    document.addEventListener("DOMContentLoaded", function () {
        pintarPrendasRecientes();
    });

// ==========================================
// AÑADIR RÁPIDO (QUICK ADD) - MENÚ TALLAS
// ==========================================

function abrirOverlayTallas(event, idPrenda, idColor) {
    event.preventDefault(); // Evitamos que la página salte arriba
    
    const overlay = document.getElementById(`overlay-tallas-${idPrenda}`);
    const contenedor = document.getElementById(`contenedor-botones-${idPrenda}`);
    
    if(!overlay || !contenedor) return;

    // Quitamos el d-none y ponemos un mensaje de carga
    overlay.classList.remove('d-none');
    contenedor.innerHTML = '<span class="small fw-bold text-muted mt-2">Cargando tallas...</span>';

    // Preguntamos al servidor por las tallas de ese color
    fetch(`controllers/apiTallasController.php?idPrenda=${idPrenda}&idColor=${idColor}`)
        .then(respuesta => respuesta.json())
        .then(tallas => {
            contenedor.innerHTML = ''; // Limpiamos el "Cargando..."
            
            if(tallas.length === 0) {
                contenedor.innerHTML = '<span class="small text-danger fw-bold mt-2">Agotado</span>';
                return;
            }

            // Dibujamos los botones de talla
            tallas.forEach(tallaObj => {
                let btn = document.createElement('button');
                btn.className = 'btn btn-outline-dark rounded-0 px-3 py-1 fw-bold';
                btn.textContent = tallaObj.talla;

                if(tallaObj.stock <= 0) {
                    // Si no hay stock, le metemos la clase del CSS que la tacha
                    btn.classList.add('talla-agotada');
                    btn.disabled = true;
                } else {
                    // Si hay stock, al hacer clic, al carrito!
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
    event.stopPropagation();
    const overlay = document.getElementById(`overlay-tallas-${idPrenda}`);
    if(overlay) overlay.classList.add('d-none');
}

function anadirDirectoCarrito(event, idPrenda, idColor, talla) {
    event.preventDefault();
    event.stopPropagation();
    
    // Creamos un formulario fantasma igual que el de la fichaProducto
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = 'controllers/carritoController.php'; // Tu controlador de siempre

    form.innerHTML = `
        <input type="hidden" name="accion" value="agregar">
        <input type="hidden" name="idPrenda" value="${idPrenda}">
        <input type="hidden" name="color_id" value="${idColor}">
        <input type="hidden" name="talla" value="${talla}">
    `;

    // Lo adjuntamos al cuerpo de la web y lo enviamos
    document.body.appendChild(form);
    form.submit();
}