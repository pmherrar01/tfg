document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Lógica para alternar entre Tarjeta y Bizum
    const radiosPago = document.querySelectorAll('input[name="metodo_pago"]');
    if (radiosPago.length > 0) {
        radiosPago.forEach(radio => {
            radio.addEventListener('change', cambiarMetodoPago);
        });
        cambiarMetodoPago(); // Estado inicial
    }

    // ==========================================
    // 2. LÓGICA DE LA TARJETA INTERACTIVA (Vanilla JS)
    // ==========================================
    
    // Capturamos los elementos visuales de la tarjeta
    const visualNumero = document.getElementById('tarjeta-numero-visual');
    const visualNombre = document.getElementById('tarjeta-nombre-visual');
    const visualMes = document.getElementById('tarjeta-mes-visual');
    const visualAnio = document.getElementById('tarjeta-anio-visual');
    const visualCvv = document.getElementById('tarjeta-cvv-visual');
    const tarjetaAnimada = document.getElementById('tarjeta-credito');

    // Capturamos los inputs del formulario
    const inputNumero = document.getElementById('input-numero');
    const inputNombre = document.getElementById('input-nombre');
    const inputMes = document.getElementById('input-mes');
    const inputAnio = document.getElementById('input-anio');
    const inputCvv = document.getElementById('input-cvv');

    if(inputNumero) { // Solo ejecutar si estamos en el checkout
        
        // A) Actualizar el Número de la tarjeta en tiempo real
        inputNumero.addEventListener('input', function(e) {
            // Solo permitir números y borrar espacios
            let valor = e.target.value.replace(/\D/g, ''); 
            // Formatear en bloques de 4 (ej: 1234 5678)
            let formateado = valor.match(/.{1,4}/g)?.join(' ') || ''; 
            e.target.value = formateado; // Se actualiza el input con espacios
            
            // Pintar en la tarjeta visual
            visualNumero.textContent = formateado !== '' ? formateado : '#### #### #### ####';
        });

        // B) Actualizar el Nombre en tiempo real
        inputNombre.addEventListener('input', function(e) {
            let nombre = e.target.value.toUpperCase();
            visualNombre.textContent = nombre !== '' ? nombre : 'NOMBRE APELLIDOS';
        });

        // C) Actualizar Mes y Año
        inputMes.addEventListener('change', function(e) {
            visualMes.textContent = e.target.value;
        });

        inputAnio.addEventListener('change', function(e) {
            visualAnio.textContent = e.target.value;
        });

        // D) Escribir CVV
        inputCvv.addEventListener('input', function(e) {
            let valor = e.target.value.replace(/\D/g, ''); // Solo números
            e.target.value = valor;
            visualCvv.textContent = valor;
        });

        // E) EL EFECTO 3D: Girar la tarjeta al entrar en el input CVV
        inputCvv.addEventListener('focus', function() {
            tarjetaAnimada.classList.add('girada');
        });

        // Volver a girar la tarjeta al salir del input CVV
        inputCvv.addEventListener('blur', function() {
            tarjetaAnimada.classList.remove('girada');
        });
    }
});

// Función para cambiar el estilo visual entre Tarjeta y Bizum
function cambiarMetodoPago() {
    const radioTarjeta = document.getElementById('pago_tarjeta');
    const cajaTarjeta = document.getElementById('caja_tarjeta');
    const cajaBizum = document.getElementById('caja_bizum');
    const formTarjeta = document.getElementById('form_tarjeta');
    const formBizum = document.getElementById('form_bizum');

    if (!cajaTarjeta || !cajaBizum || !formTarjeta || !formBizum) return;

    if (radioTarjeta.checked) {
        cajaTarjeta.classList.replace('border-secondary', 'border-dark');
        cajaTarjeta.classList.replace('border-1', 'border-2');
        cajaTarjeta.classList.replace('bg-light', 'bg-white');
        cajaTarjeta.style.opacity = '1';
        formTarjeta.style.display = 'block';

        cajaBizum.classList.replace('border-dark', 'border-secondary');
        cajaBizum.classList.replace('border-2', 'border-1');
        cajaBizum.classList.replace('bg-white', 'bg-light');
        cajaBizum.style.opacity = '0.7';
        formBizum.style.display = 'none';
    } else {
        cajaBizum.classList.replace('border-secondary', 'border-dark');
        cajaBizum.classList.replace('border-1', 'border-2');
        cajaBizum.classList.replace('bg-light', 'bg-white');
        cajaBizum.style.opacity = '1';
        formBizum.style.display = 'block';

        cajaTarjeta.classList.replace('border-dark', 'border-secondary');
        cajaTarjeta.classList.replace('border-2', 'border-1');
        cajaTarjeta.classList.replace('bg-white', 'bg-light');
        cajaTarjeta.style.opacity = '0.7';
        formTarjeta.style.display = 'none';
    }
}