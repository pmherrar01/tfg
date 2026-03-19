<section id="seccion-recientes" class="container my-5 d-none position-relative">
    <h3 class="fw-bold text-uppercase mb-4" style="letter-spacing: 2px;">Visto Recientemente</h3>
    
    <button class="btn position-absolute top-50 start-0 translate-middle-y bg-white rounded-circle shadow-sm d-none d-md-block" 
            style="width: 45px; height: 45px; z-index: 10; margin-left: -20px;" 
            onclick="moverCarruselRecientes(-300)">
        <i class="bi bi-chevron-left fs-5"></i>
    </button>

    <div id="carrusel-recientes" class="row flex-row flex-nowrap overflow-auto pb-3" style="scrollbar-width: none; scroll-behavior: smooth;">
        </div>

    <button class="btn position-absolute top-50 end-0 translate-middle-y bg-white rounded-circle shadow-sm d-none d-md-block" 
            style="width: 45px; height: 45px; z-index: 10; margin-right: -20px;" 
            onclick="moverCarruselRecientes(300)">
        <i class="bi bi-chevron-right fs-5"></i>
    </button>
</section>

<style>
    #carrusel-recientes::-webkit-scrollbar {
        display: none;
    }
</style>