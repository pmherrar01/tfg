document.addEventListener("DOMContentLoaded", function () {
    const sliderMin = document.getElementById("slider-min");
    const sliderMax = document.getElementById("slider-max");
    const minVal = document.getElementById("precio-min-val");
    const maxVal = document.getElementById("precio-max-val");
    const sliderTrack = document.querySelector(".slider-track");
    
    if (!sliderMin || !sliderMax) return;
    
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

    function controlarSliders(event) {
        let valorMin = parseInt(sliderMin.value);
        let valorMax = parseInt(sliderMax.value);
                     
        if (valorMax - valorMin <= gap) {
            if (event.target === sliderMin) {
                sliderMin.value = valorMax - gap;
            } else {
                sliderMax.value = valorMin + gap;
            }
        }
        
        minVal.textContent = sliderMin.value;
        maxVal.textContent = sliderMax.value;
        updateSliderTrack();
    }

    sliderMin.addEventListener("input", controlarSliders);
    sliderMax.addEventListener("input", controlarSliders);
    
    // Inicializar visualmente la barra al cargar la página
    updateSliderTrack();
});

// Función global para aplicar el filtro (fuera del DOMContentLoaded para que el botón la encuentre)
function aplicarFiltroPrecio() {
    let min = document.getElementById("slider-min").value;
    let max = document.getElementById("slider-max").value;
    
    // Capturamos la URL actual con todos sus parámetros
    let urlParams = new URLSearchParams(window.location.search);
    
    // Le inyectamos los nuevos valores del precio
    urlParams.set('precioMin', min);
    urlParams.set('precioMax', max);
    
    // Si cambian el precio, quitamos la paginación para volver a la página 1
    urlParams.delete('pagina');
    
    // Redirigimos conservando absolutamente TODO lo demás (incluido ?especial=herror)
    window.location.href = 'catalogo.php?' + urlParams.toString();
}