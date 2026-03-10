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
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'fechaDesc'); ?>" class="text-muted nav-filtro">Fecha: más reciente - más antiguo </a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'fechaAsc'); ?>" class="text-muted nav-filtro">Fecha: más antiguo - más reciente</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'precioAsc'); ?>" class="text-muted nav-filtro">Precio: Menor a Mayor</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'precioDesc'); ?>" class="text-muted nav-filtro">Precio: Mayor a Menor</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'nombreAsc'); ?>" class="text-muted nav-filtro">Alfabéticamente: A - Z</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('orden', 'nombreDesc'); ?>" class="text-muted nav-filtro">Alfabéticamente: Z - A</a></li>
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
                                    <li class="mb-2"><a href="<?php echo crearUrl('genero', '1'); ?>" class="text-muted nav-filtro">Hombre</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('genero', '2'); ?>" class="text-muted nav-filtro">Mujer</a></li>
                                    <li class="mb-2"><a href="<?php echo crearUrl('genero', '3'); ?>" class="text-muted nav-filtro">Unisex</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item bg-transparent border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroColeccion">
                                Colección
                            </button>
                        </h2>
                        <div id="filtroColeccion" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                            <div class="accordion-body px-0 py-2">
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($listaCategorias as $categoria) { ?>
                                        <li class="mb-2"><a href="<?php echo crearUrl('coleccion', $categoria['id']); ?>" class="text-muted nav-filtro"><?php echo $categoria["nombre"] ?></a></li>
                                    <?php } ?>
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
                                        <li class="mb-2"><a href="<?php echo crearUrl('tipo', $productoLista["id"]); ?>" class="text-muted nav-filtro"><?php echo $productoLista["nombre"] ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item bg-transparent border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroTalla">
                                Talla
                            </button>
                        </h2>
                        <div id="filtroTalla" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                            <div class="accordion-body px-0 py-2">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<?php echo crearUrl('talla', 'S'); ?>" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">S</a>
                                    <a href="<?php echo crearUrl('talla', 'M'); ?>" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">M</a>
                                    <a href="<?php echo crearUrl('talla', 'L'); ?>" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">L</a>
                                    <a href="<?php echo crearUrl('talla', 'XL'); ?>" class="border text-muted text-decoration-none px-3 py-1 nav-filtro">XL</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item bg-transparent border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroColor">
                                Color
                            </button>
                        </h2>
                        <div id="filtroColor" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                            <div class="accordion-body px-0 py-2">
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($listaColores as $color) { ?>
                                        <a href="<?php echo crearUrl('color', $color["nombre"]); ?>" class="color-swatch border border-dark" style="background-color: <?php echo $color["valor_hexadecimal"] ?>;" title="<?php echo $color["nombre"] ?>"></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item bg-transparent border-bottom">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent px-0 fw-bold text-uppercase shadow-none" style="font-size: 0.9rem;" type="button" data-bs-toggle="collapse" data-bs-target="#filtroPrecio">
                                Precio
                            </button>
                        </h2>
                        <div id="filtroPrecio" class="accordion-collapse collapse" data-bs-parent="#acordeonFiltros">
                            <div class="accordion-body px-0 py-3">
                                <div class="range-slider-container position-relative mb-3 mt-4">
                                    <div class="slider-track"></div>
                                    <input type="range" min="<?php echo $precioMin; ?>" max="<?php echo $precioMax; ?>" value="<?php echo $precioMin; ?>" id="slider-min" class="custom-range">
                                    <input type="range" min="<?php echo $precioMin; ?>" max="<?php echo $precioMax; ?>" value="<?php echo $precioMax; ?>" id="slider-max" class="custom-range">
                                </div>
                                <div class="d-flex justify-content-between text-muted small fw-bold mb-3">
                                    <span>Min: <span id="precio-min-val"><?php echo $precioMin ?></span>€</span>
                                    <span>Max: <span id="precio-max-val"><?php echo $precioMax ?></span>€</span>
                                </div>
                                <button class="btn btn-dark w-100 btn-sm text-uppercase" onclick="aplicarFiltroPrecio()">Aplicar Filtro</button>
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
                        // NUEVO: Usamos tu nueva función pasándole el color de la tarjeta
                        $listaImagenesColor = $imagen->listarImagenesPorColor($prenda["id"], $prenda["color_id"]);
                        
                        // Si ese color en concreto tiene más de 1 foto, mostramos la segunda en el Hover
                        $fotoHover = count($listaImagenesColor) > 1 ? $listaImagenesColor[1]["url_imagen"] : $prenda["url_imagen"];
                ?>

                        <div class="col-6 col-md-4">
                            <div class="card product-card border-0 bg-transparent h-100 position-relative">

                                <button type="button" class="btn btn-favorito position-absolute top-0 end-0 m-2" style="z-index: 10;" onclick="this.querySelector('i').classList.toggle('bi-heart'); this.querySelector('i').classList.toggle('bi-heart-fill');">
                                    <i class="bi bi-heart"></i>
                                </button>
                                
                                <a href="fichaProducto.php?idPrenda=<?php echo $prenda["id"] ?>&color=<?php echo $prenda['color_id']; ?>">
                                    <div class="img-wrapper position-relative">
                                        <img src="<?php echo $prenda["url_imagen"]; ?>" class="card-img-top img-principal" alt="Prenda">
                                        <img src="<?php echo $fotoHover; ?>" class="card-img-top img-hover position-absolute top-0 start-0 w-100 h-100" alt="Prenda Hover">
                                    </div>

                                    <div class="card-body text-center px-0">
                                        <h5 class="card-title text-uppercase fw-bold fs-6 mt-2 mb-1"><?php echo $prenda["nombre"] ?></h5>
                                        <p class="card-text"><?php echo $prenda["precio"] ?> €</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                <?php

                    }
                } else {
                    echo "<p class='text-center'>No hay productos disponibles en este momento.</p>";
                }
                ?>

            </div>

        </section>
    </div>
</main>

<?php include './includes/footer.php'; ?>