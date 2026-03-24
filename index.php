<?php

require_once 'controllers/indexController.php';

include './includes/header.php';
?>

<section class="vip-countdown position-relative text-center py-5 overflow-hidden text-white">
    
    <div class="vip-overlay position-absolute w-100 h-100 top-0 start-0"></div>
    
    <div class="container position-relative z-1">
        
        <h2 class="vip-title display-4 fw-bold text-uppercase mb-3">Acceso Anticipado</h2>
        <p class="fs-5 mb-5 text-light fw-light">Nuestra colección más exclusiva está a punto de salir. Consigue tu acceso anticipado.</p>
        
        <div class="vip-reloj-container mb-5 mx-auto">
            <div id="reloj-vip" class="d-flex justify-content-center align-items-center gap-3 gap-md-5">
                
                <div class="text-center">
                    <span id="dias" class="vip-number fw-bold d-block">00</span>
                    <span class="vip-label text-uppercase fw-bold">Días</span>
                </div>
                <span class="vip-separator fw-bold">:</span>
                
                <div class="text-center">
                    <span id="horas" class="vip-number fw-bold d-block">00</span>
                    <span class="vip-label text-uppercase fw-bold">Horas</span>
                </div>
                <span class="vip-separator fw-bold">:</span>
                
                <div class="text-center">
                    <span id="minutos" class="vip-number fw-bold d-block">00</span>
                    <span class="vip-label text-uppercase fw-bold">Min</span>
                </div>
                <span class="vip-separator fw-bold d-none d-sm-block">:</span>
                
                <div class="text-center d-none d-sm-block">
                    <span id="segundos" class="vip-number fw-bold d-block">00</span>
                    <span class="vip-label text-uppercase fw-bold">Seg</span>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <button class="btn btn-outline-light rounded-0 px-4 py-3 text-uppercase fw-bold vip-btn-espaciado">
                Solicitar Código
            </button>
            <button class="btn btn-principal rounded-0 px-4 py-3 text-uppercase fw-bold vip-btn-espaciado">
                 Ya tengo mi pase
            </button>
        </div>
        
    </div>
</section>

<section class="container my-5 py-5 overflow-hidden">
    <h3 class="text-center fw-bold text-uppercase mb-5" style="letter-spacing: 4px;">Novedades</h3>

    <div id="carruselNovedades" class="carousel carousel-dark slide" data-bs-ride="carousel" data-bs-pause="hover">
        <div class="carousel-inner px-5">

            <?php
            if (!empty($novedades)) {
                $contador = 0;
                foreach ($novedades as $prenda) {

                    if ($contador % 4 == 0) {
                        $claseActive = ($contador == 0) ? 'active' : '';
            ?>
                        <div class="carousel-item <?= $claseActive ?>" data-bs-interval="3000">
                            <div class="row">
                            <?php
                        }
                            ?>
                            <div class="col-6 col-md-3 position-relative d-flex flex-column mb-4">

                                <div class="card product-card border-0 bg-transparent position-relative">

                                    <div class="img-wrapper position-relative overflow-hidden">

                                        <a href="fichaProducto.php?idPrenda=<?= $prenda["id"] ?>&color=<?= $prenda["color_id"] ?>" class="text-decoration-none text-dark d-block">
                                            <img src="<?= $prenda['url_imagen'] ?>" class="card-img-top rounded-0" alt="<?= $prenda['nombre'] ?>" style="height: 380px; object-fit: cover;">
                                        </a>

                                        <div id="overlay-tallas-<?= $prenda['id'] ?>" class="overlay-tallas d-none position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-75 p-3 text-center">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small fw-bold text-uppercase" style="letter-spacing: 1px;">Selecciona Talla</span>
                                                <button type="button" class="btn-close" style="font-size: 0.7rem;" onclick="cerrarOverlayTallas(event, <?= $prenda['id'] ?>)"></button>
                                            </div>

                                            <div id="contenedor-botones-<?= $prenda['id'] ?>" class="d-flex justify-content-center flex-wrap gap-2">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card-body text-center px-0 pb-1 mt-2">
                                        <a href="fichaProducto.php?idPrenda=<?= $prenda["id"] ?>&color=<?= $prenda["color_id"] ?>" class="text-decoration-none text-dark d-block">
                                            <h5 class="card-title text-uppercase fw-bold fs-6 mb-1 text-truncate"><?= $prenda['nombre'] ?></h5>
                                            <p class="card-text mb-0"><?= $prenda['precio'] ?> €</p>
                                        </a>
                                    </div>

                                </div>

                                <div class="d-flex align-items-center justify-content-between gap-2 mt-2 px-1">

                                    <button type="button" class="btn btn-principal rounded-0 flex-grow-1 text-uppercase fw-bold"
                                        style="height: 40px; font-size: 0.75rem; letter-spacing: 1px;"
                                        onclick="abrirOverlayTallas(event, <?= $prenda['id'] ?>, <?= $prenda['color_id'] ?>)">
                                        Añadir <i class="bi bi-plus-lg ms-1"></i>
                                    </button>

                                    <?php
                                    $iconoCorazon = 'bi-heart';
                                    if (isset($arrayFavoritos) && in_array($prenda['id'] . '-' . $prenda['color_id'], $arrayFavoritos)) {
                                        $iconoCorazon = 'bi-heart-fill';
                                    }
                                    ?>
                                    <button type="button" class="btn btn-toggle-favorito btn-favorito-custom btn-favorito-std d-flex justify-content-center align-items-center rounded-0"
                                        data-id="<?= $prenda['id'] ?>"
                                        data-color="<?= $prenda['color_id'] ?>">
                                        <i class="bi <?= $iconoCorazon ?>"></i>
                                    </button>
                                </div>
                            </div>
                            <?php
                            $contador++;
                            if ($contador % 4 == 0 || $contador == count($novedades)) {
                            ?>
                            </div>
                        </div>
                <?php
                            }
                        }
                    } else {
                ?>
                <div class="text-center py-5">
                    <p class="text-muted">Próximamente nuevas prendas...</p>
                </div>
            <?php
                    }
            ?>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carruselNovedades" data-bs-slide="prev" style="width: 5%;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carruselNovedades" data-bs-slide="next" style="width: 5%;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</section>

<section class="container-fluid px-0 my-5 overflow-hidden">
    <div class="row g-0">

        <div class="col-md-6 collection-box position-relative">
            <img src="public/img/hombreColeccion.jpg" class="w-100 object-fit-cover collection-img" alt="Colección Hombre" style="height: 65vh; object-position: top;">

            <div class="collection-overlay d-flex flex-column align-items-center justify-content-center text-center">
                <h3 class="display-4 fw-bold text-white text-uppercase collection-title">Hombre</h3>
                <a href="catalogo.php?genero=1" class="btn btn-outline-light collection-btn mt-3">Ver Colección</a>
            </div>
        </div>

        <div class="col-md-6 collection-box position-relative">
            <img src="public/img/mujer.png" class="w-100 object-fit-cover collection-img" alt="Colección Mujer" style="height: 65vh; object-position: top;">

            <div class="collection-overlay d-flex flex-column align-items-center justify-content-center text-center">
                <h3 class="display-4 fw-bold text-white text-uppercase collection-title">Mujer</h3>
                <a href="catalogo.php?genero=2" class="btn btn-outline-light collection-btn mt-3">Ver Colección</a>
            </div>
        </div>

    </div>
</section>

<?php

include './includes/prendasRecientes.php';

?>


<section class="newsletter-section py-5">
    <div class="container text-center py-4">
        <h3 class="display-6 fw-bold text-uppercase mb-3 newsletter-title">Subscribete y consigue descuentos. </h3>
        <p class="mb-4 newsletter-subtitle">Suscríbete para tener acceso anticipado a nuevas colecciones y descuentos exclusivos.</p>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="#" method="POST" class="newsletter-form d-flex align-items-center justify-content-center flex-wrap gap-3">
                    <input type="email" class="form-control newsletter-input flex-grow-1" placeholder="TU CORREO ELECTRÓNICO" required>
                    <button type="submit" class="btn btn-newsletter">Subcribete</button>
                </form>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-0 border-dark border-3 shadow-lg">

            <div class="modal-header border-bottom border-dark border-2 bg-white">
                <h5 class="modal-title fw-bold text-uppercase" id="promoModalLabel">
                    HERROR
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-5 text-center bg-light">
                <h1 class="display-4 fw-bold text-uppercase mb-3">¡Consigue un <span class="text-decoration-underline">10%</span> de descuento!</h1>
                <p class="text-muted fs-5 mb-4">Suscríbete a nuestra newsletter y recibe un código de descuento instantáneo para tu primera compra. ¡Únete a la familia!</p>

                <form id="formSuscripcion" class="mx-auto" style="max-width: 500px;">
                    <div class="input-group input-group-lg mb-2">
                        <input type="email" class="form-control rounded-0 border-dark border-2" placeholder="tu@email.com" required>
                        <button class="btn btn-dark rounded-0 fw-bold text-uppercase px-4 border-2 border-dark" type="submit">¡Lo quiero!</button>
                    </div>
                    <small class="text-muted fw-bold small text-uppercase">No enviamos spam. Solo puro estilo.</small>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="public/js/index.js"></script>

<?php


include './includes/footer.php';

?>