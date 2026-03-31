document.addEventListener("DOMContentLoaded", function () {
    const formIA = document.getElementById("formAsistenteIA");
    
    if (formIA) {
        formIA.addEventListener("submit", function (e) {
            e.preventDefault(); // Evitamos que la página se recargue

            // 1. Recogemos los datos del formulario
            const altura = document.getElementById("ia_altura").value;
            const peso = document.getElementById("ia_peso").value;
            const complexion = document.getElementById("ia_complexion").value;
            const ajuste = document.getElementById("ia_ajuste").value;
            const prenda = document.getElementById("ia_nombre_prenda").value;

            // 2. Preparamos la interfaz visual (Spinner de carga)
            const btnCalcular = document.getElementById("btnCalcularTalla");
            const resultadoContenedor = document.getElementById("resultadoIA");
            
            btnCalcular.disabled = true;
            btnCalcular.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Conectando con IA...';
            
            resultadoContenedor.classList.remove("d-none");
            resultadoContenedor.innerHTML = `
                <div class="spinner-grow text-dark mb-2" role="status" style="width: 2rem; height: 2rem;"></div>
                <p class="mb-0 fw-bold text-uppercase small" style="letter-spacing: 1px;">Analizando tus medidas...</p>
            `;

            // 3. AQUÍ IRÁ EL FETCH A TU WEBHOOK DE N8N
            // const webhookUrl = "https://tu-n8n.com/webhook/asistente-tallas";
            // const datosIA = { prenda, altura, peso, complexion, ajuste };
            // (El código real lo pondremos en la siguiente fase)

            // SIMULACIÓN (Borraremos este setTimeout cuando conectemos n8n)
            setTimeout(() => {
                // Devolvemos el botón a su estado normal
                btnCalcular.disabled = false;
                btnCalcular.innerHTML = 'Recalcular mi talla';

                // Mostramos el resultado de la IA
                resultadoContenedor.innerHTML = `
                    <i class="bi bi-stars text-warning display-4 d-block mb-2"></i>
                    <h5 class="fw-bold text-uppercase mb-1">Tu talla recomendada es: <span class="text-danger fs-3">M</span></h5>
                    <p class="small text-muted mb-0">Basado en tu altura (${altura}cm) y complexión ${complexion.toLowerCase()}, la talla M te quedará ${ajuste.toLowerCase()} para esta prenda.</p>
                `;
            }, 2500); // Tarda 2.5 segundos simulando "pensar"
        });
    }
});