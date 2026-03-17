// --- LÓGICA DEL SLIDER DE PRECIOS DOBLE ---
document.addEventListener("DOMContentLoaded", function () {
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

document.addEventListener("DOMContentLoaded", function () {
    const sliderMin = document.getElementById("slider-min");
    const sliderMax = document.getElementById("slider-max");
    const minVal = document.getElementById("precio-min-val");
    const maxVal = document.getElementById("precio-max-val");
    const sliderTrack = document.querySelector(".slider-track");

    if (!sliderMin || !sliderMax) return;

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
    sliderMin.addEventListener("input", function () {
        if (parseInt(sliderMax.value) - parseInt(sliderMin.value) <= gap) {
            sliderMin.value = parseInt(sliderMax.value) - gap;
        }
        minVal.textContent = sliderMin.value;
        updateSliderTrack();
    });

    // Actualización en tiempo real al mover el Máximo
    sliderMax.addEventListener("input", function () {
        if (parseInt(sliderMax.value) - parseInt(sliderMin.value) <= gap) {
            sliderMax.value = parseInt(sliderMin.value) + gap;
        }
        maxVal.textContent = sliderMax.value;
        updateSliderTrack();
    });

    // Pintar la barra inicial
    updateSliderTrack();
});

