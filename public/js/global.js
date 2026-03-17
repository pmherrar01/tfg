// Esperamos a que la página cargue entera
document.addEventListener("DOMContentLoaded", function () {

    let promoModalElement = document.getElementById('promoModal')

    if (promoModalElement) {
        setTimeout(function () {
            let miModal = new bootstrap.Modal(document.getElementById('promoModal'));

            miModal.show();
        }, 3000);
    }


    let graciasCompra = document.getElementById("graciasCompra");

    if (graciasCompra) {
        setTimeout(function () {
            window.location.href = "perfil.php?seccion=pedidos";
        }, 4000);
    }

});


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
        else if (mensajeAlerta === 'carrito_ok') {
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