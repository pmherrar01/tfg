<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'controllers/catalogoController.php'; 

include './includes/header.php';
?>

<main class="container my-5 py-5 mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-uppercase" style="letter-spacing: 4px;">Catálogo</h1>
            <h2><?php echo $mensajeFiltrado ?></h2>
            <p class="text-muted">Descubre todas nuestras colecciones</p>
        </div>
    </div>

    <div class="row">
        <aside class="col-lg-3 d-none d-lg-block mb-4">
            <div class="sticky-top" style="top: 100px; z-index: 1;">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h5 class="fw-bold text-uppercase m-0">Filtros</h5>
                    <a href="catalogo.php" class="text-muted small text-decoration-underline">Limpiar</a>
                </div>

                <div class="accordion accordion-flush" id="acordeonFiltros">
                    <div class="accordion-item bg-transparent border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroOrdenar">
                                Ordenar por
                            </button>
                        </h2>
                        <div id="filtroOrdenar" class="accordion-collapse collapse show" data-bs-parent="#acordeonFiltros">
                            <div class="accordion-body px-0 py-2">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'fechaDesc'); ?>" class="text-muted nav-filtro text-decoration-none small">Fecha: más reciente</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'precioAsc'); ?>" class="text-muted nav-filtro text-decoration-none small">Precio: Menor a Mayor</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'precioDesc'); ?>" class="text-muted nav-filtro text-decoration-none small">Precio: Mayor a Menor</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'nombreAsc'); ?>" class="text-muted nav-filtro text-decoration-none small">A - Z</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item bg-transparent border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroGenero">
                                Género
                            </button>
                        </h2>
                        <div id="filtroGenero" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                            <div class="accordion-body px-0 py-2">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><a href="<?php echo crearUrl('genero', '1'); ?>" class="text-muted nav-filtro text-decoration-none small">Hombre</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('genero', '2'); ?>" class="text-muted nav-filtro text-decoration-none small">Mujer</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('genero', '3'); ?>" class="text-muted nav-filtro text-decoration-none small">Unisex</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item bg-transparent border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroCategoria">
                                Tipo de prenda
                            </button>
                        </h2>
                        <div id="filtroCategoria" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                            <div class="accordion-body px-0 py-2">
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($listaTiposProductos as $productoLista) { ?>
                                        <li class="mb-2"><a href="<?php echo crearUrl('tipo', $productoLista["id"]); ?>" class="text-muted nav-filtro text-decoration-none small"><?php echo $productoLista["nombre"] ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <section class="col-lg-9">
            <div class="row g-4">
                <?php
                if (!empty($listaProductos)) {
                    foreach ($listaProductos as $prenda) {
                        // Usamos $imagen (definida en el controlador)
                        $listaImagenesColor = $imagen->listarImagenesPorColor($prenda["id"], $prenda["color_id"]);
                        $fotoHover = (is_array($listaImagenesColor) && count($listaImagenesColor) > 1) ? $listaImagenesColor["url_imagen"] : $prenda["url_imagen"];
                ?>
                        <div class="col-6 col-md-4 mb-4 d-flex flex-column">
                            <div class="card product-card border-0 bg-transparent h-100 position-relative d-flex flex-column">
                                <?php
                                $iconoCorazon = 'bi-heart'; 
                                if (isset($arrayFavoritos) && in_array($prenda['id'] . '-' . $prenda['color_id'], $arrayFavoritos)) {
                                    $iconoCorazon = 'bi-heart-fill'; 
                                }
                                ?>
                                <button type="button" class="btn btn-toggle-favorito btn-favorito-custom btn-favorito-std position-absolute top-0 end-0 m-2 rounded-0 d-flex justify-content-center align-items-center" style="z-index: 10;" data-id="<?= $prenda['id'] ?>" data-color="<?= $prenda['color_id'] ?>">
                                    <i class="bi <?= $iconoCorazon ?>"></i>
                                </button>
                                
                                <div class="position-relative overflow-hidden group-hover-wrapper">
                                    <a href="fichaProducto.php?idPrenda=<?php echo $prenda["id"] ?>&color=<?php echo $prenda['color_id']; ?>" class="text-decoration-none text-dark d-block">
                                        <div class="img-wrapper position-relative">
                                            <img src="<?php echo $prenda["url_imagen"]; ?>" class="card-img-top img-principal rounded-0" alt="Prenda" style="height: 380px; object-fit: cover; transition: opacity 0.3s ease;">
                                            <img src="<?php echo $fotoHover; ?>" class="card-img-top img-hover position-absolute top-0 start-0 w-100 h-100 rounded-0" alt="Prenda Hover" style="height: 380px; object-fit: cover; opacity: 0; transition: opacity 0.3s ease;">
                                        </div>

                                        <div class="card-body text-center px-0 pb-1 mt-2">
                                            <h5 class="card-title text-uppercase fw-bold fs-6 mb-1 text-truncate"><?php echo $prenda["nombre"] ?></h5>
                                            <p class="card-text mb-2"><?php echo $prenda["precio"] ?> €</p>
                                        </div>
                                    </a>

                                    <div id="overlay-tallas-<?= $prenda['id'] ?>" class="overlay-tallas d-none position-absolute bottom-0 start-0 w-100 bg-white bg-opacity-75 p-3 text-center" style="z-index: 20;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small fw-bold text-uppercase" style="letter-spacing: 1px;">Talla</span>
                                            <button type="button" class="btn-close" style="font-size: 0.7rem;" onclick="cerrarOverlayTallas(event, <?= $prenda['id'] ?>)"></button>
                                        </div>
                                        <div id="contenedor-botones-<?= $prenda['id'] ?>" class="d-flex justify-content-center flex-wrap gap-2"></div>
                                    </div>
                                </div>

                                <div class="mt-auto px-1 pt-2">
                                    <button type="button" class="btn btn-principal rounded-0 w-100 text-uppercase fw-bold" 
                                            style="height: 40px; font-size: 0.75rem; letter-spacing: 1px;"
                                            onclick="abrirOverlayTallas(event, <?= $prenda['id'] ?>, <?= $prenda['color_id'] ?>)">
                                        Añadir <i class="bi bi-plus-lg ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "<div class='col-12 text-center'><p>No hay productos disponibles con estos filtros.</p></div>";
                }
                ?>
            </div>
        </section>
    </div>
</main>

<script src="public/js/catalogo.js"></script>

<?php
include './includes/prendasRecientes.php';
include './includes/footer.php';
?>